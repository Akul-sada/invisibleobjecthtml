<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $verification_code = sprintf("%06d", mt_rand(1, 999999));
    
    // Store verification code in session
    session_start();
    $_SESSION['email_verification_code'] = $verification_code;
    
    // Send email
    $subject = "Email Verification Code";
    $message = "
    <html>
    <head><title>Email Verification</title></head>
    <body>
        <h2>Email Verification</h2>
        <p>Your verification code is: <strong>$verification_code</strong></p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Invisible Phoenix <your-email@domain.com>" . "\r\n";
    
    mail($email, $subject, $message, $headers);
    echo "success";
}
?>