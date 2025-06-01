<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];    // Changed from DB_USER
$password = $_ENV['DB_PASSWORD'];    // Changed from DB_PASS
$dbname = $_ENV['DB_NAME'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add verification_code column if it doesn't exist
    $sql = "ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS verification_code VARCHAR(6) AFTER email_verified";
    $conn->exec($sql);
    
    // Verify full table structure
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email_verified TINYINT(1) DEFAULT 0,
        verification_code VARCHAR(6),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($to, $verificationCode) {
    require 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "
            <h2>Thank you for registering!</h2>
            <p>Your verification code is: <strong>{$verificationCode}</strong></p>
            <p>If you didn't register on our site, please ignore this email.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
