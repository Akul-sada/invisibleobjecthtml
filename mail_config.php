<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
            <p>Enter this code on the verification page to complete your registration.</p>
            <p>If you didn't register on our site, please ignore this email.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Database connection
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Add verification_code column if it doesn't exist
    $sql = "ALTER TABLE users ADD COLUMN verification_code VARCHAR(6) AFTER email_verified";
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>