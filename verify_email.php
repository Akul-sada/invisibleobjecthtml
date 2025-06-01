<?php

require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "your_secret_key"; // Store this securely in production

if (isset($_GET['token'])) {
    try {
        $decoded = JWT::decode($_GET['token'], new Key($key, 'HS256'));
        
        // Connect to database
        require_once 'db_connect.php';
        
        // Update user's email verification status
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1 WHERE email = :email");
        $stmt->bindParam(':email', $decoded->email);
        $stmt->execute();
        
        echo "Email verified successfully!";
    } catch (Exception $e) {
        echo "Invalid or expired verification link.";
    }
}
?>