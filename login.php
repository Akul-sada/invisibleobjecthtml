<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }
    h1 {
        text-align: center;
    }
    form {
        max-width: 400px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    label {
        display: block;
        margin-bottom: 10px;
    }
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
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
</style>
<body>
    <h1>Login Page</h1>
    <form action="login.php" method="POST">
        <label for="username">Email:</label>
        <input type="email" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once 'db_connect.php';
        require_once 'config.php';
        
        $email = $_POST['username'];
        $password = $_POST['password'];
        
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['email_verified'] == 1) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    
                    // Generate JWT token
                    $jwt_secret = $_ENV['JWT_SECRET'];
                    $payload = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'exp' => time() + (60 * 60) // 1 hour expiration
                    ];
                    
                    $token = JWT::encode($payload, $jwt_secret, 'HS256');
                    $_SESSION['token'] = $token;
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>Please verify your email before logging in.</p>";
                }
            } else {
                echo "<p style='color: red;'>Invalid credentials.</p>";
            }
        } catch(PDOException $e) {
            echo "<p style='color: red;'>An error occurred. Please try again.</p>";
        }
    }
    ?>
</body>
</html>