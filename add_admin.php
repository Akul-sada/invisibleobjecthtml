<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';


// ===============================================================
// Run this in phpMyAdmin to make your first admin
// ============================================================
// UPDATE users SET is_admin = TRUE WHERE email = 'sadananda.sherigara@gmail.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Update user to admin
        $stmt = mysqli_prepare($conn, "UPDATE users SET is_admin = TRUE WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error updating user: " . mysqli_stmt_error($stmt));
        }

        if (mysqli_stmt_affected_rows($stmt) === 0) {
            throw new Exception("No user found with this email");
        }

        $success = "User $email has been made an admin";
        mysqli_stmt_close($stmt);
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .admin-form {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input[type="email"] {
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
            margin-bottom: 20px;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="admin-form">
        <h2>Add Admin</h2>
        
        <?php if (isset($error)): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter user email" required>
            <button type="submit">Make Admin</button>
        </form>
    </div>
</body>
</html>