<?php
require_once 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1 WHERE verification_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<h2>Email Verified Successfully!</h2>";
            echo "<p>You can now <a href='login.php'>login</a> to your account.</p>";
        } else {
            echo "<h2>Invalid Verification Link</h2>";
            echo "<p>The verification link is invalid or has expired.</p>";
        }
    } catch(PDOException $e) {
        echo "<p style='color: red;'>An error occurred. Please try again.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .verification-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h2>Email Verification</h2>
        <p class="message">Please enter the verification code sent to your email</p>
        
        <form method="POST">
            <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            <input type="text" name="verification_code" placeholder="Enter 6-digit code" pattern="[0-9]{6}" required>
            <button type="submit">Verify Email</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once 'db_connect.php';
            
            $email = $_POST['email'];
            $code = $_POST['verification_code'];
            
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :code");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':code', $code);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    // Update verification status
                    $updateStmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_code = NULL WHERE email = :email");
                    $updateStmt->bindParam(':email', $email);
                    $updateStmt->execute();
                    
                    echo "<p class='message' style='color: green;'>Email verified successfully!</p>";
                    echo "<p class='message'><a href='login.php'>Click here to login</a></p>";
                } else {
                    echo "<p class='message' style='color: red;'>Invalid verification code.</p>";
                }
            } catch(PDOException $e) {
                echo "<p class='message' style='color: red;'>An error occurred. Please try again.</p>";
            }
        }
        ?>
    </div>
</body>
</html>