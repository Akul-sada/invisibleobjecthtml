<?php
require 'db_connect.php';

$send_email_to = $_POST['email'];
$verification_code = sprintf("%06d", mt_rand(1, 999999));

// Update database with verification code
$stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
$stmt->bind_param("ss", $verification_code, $send_email_to);
$stmt->execute();
$stmt->close();

// Email details
$to = $send_email_to;
$subject = "Email Verification Code";
$message = "
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Email Verification</h2>
    <p>Your verification code is:</p>
    <h3 style='color: #28a745;'>$verification_code</h3>
    <p>This code will expire in 15 minutes.</p>
</body>
</html>
";

// Headers to send HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: YourApp <sadananda.sherigara@gmail.com>" . "\r\n";

// Send email
if(mail($to, $subject, $message, $headers)) {
    echo "Verification email sent!";
} else {
    echo "Failed to send verification email.";
}
?>