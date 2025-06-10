<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup</title>
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
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    label {
      display: block;
      margin-bottom: 10px;
    }
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="number"] {
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
    .form-group {
        margin-bottom: 15px;
    }
    select#country_code {
        width: 30%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-right: 10px;
    }
    input[type="tel"] {
        width: 65%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    small {
        display: block;
        color: #666;
        margin-top: 5px;
    }
    .input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .verify-btn {
        width: auto;
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .verify-btn:hover {
        background-color: #0056b3;
    }
    
    input[type="email"],
    input[type="tel"] {
        flex: 1;
    }

    #verification-section {
        margin-top: 10px;
        padding: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    input:read-only,
    select:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    button:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
  </style>
  <body>
    <h1>Signup Page</h1>
    <!-- Add this before the form -->
    <div id="error-messages" style="color: red; text-align: center; margin-bottom: 20px;">
        <?php
        if (isset($error)) {
            echo $error;
        }
        ?>
    </div>
    <div id="success-message" style="color: green; text-align: center; margin-bottom: 20px;">
        <?php
        session_start();
        if (isset($_SESSION['verification_code']) && isset($_SESSION['whatsapp_link'])) {
            echo "<p>Registration successful! Check your email for verification code.</p>";
            echo "<p style='text-align: center; margin-top: 15px;'>";
            echo "<a href='" . $_SESSION['whatsapp_link'] . "' target='_blank' style='color: #25d366; text-decoration: none;'>";
            echo "<img src='https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg' style='width: 20px; vertical-align: middle;'> ";
            echo "Get verification code via WhatsApp</a></p>";
            // Clear the session variables
            unset($_SESSION['verification_code']);
            unset($_SESSION['whatsapp_link']);
        }
        ?>
    </div>
    <form action="signup.php" method="POST">
      <div class="form-group">
        <label for="firstname">First Name:</label>
        <input 
          type="text" 
          id="firstname" 
          name="firstname" 
          pattern="[A-Za-z]{2,50}" 
          title="First name should only contain letters and be 2-50 characters long"
          required 
        />
      </div>
      <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input 
          type="text" 
          id="lastname" 
          name="lastname" 
          pattern="[A-Za-z]{2,50}" 
          title="Last name should only contain letters and be 2-50 characters long"
          required 
        />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <div class="input-group">
            <input type="email" id="email" name="email" required />
            <!-- <button type="button" id="verifyEmail" class="verify-btn">Verify Email</button> -->
        </div>
      </div>

      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <div class="input-group">
            <select name="country_code" id="country_code" required>
                <option value="">Select Country Code</option>
                <option value="+1">United States (+1)</option>
                <option value="+91">India (+91)</option>
            </select>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required maxlength="10" />
            <button type="button" id="sendCode" class="verify-btn">Send Code</button>
            <br/>
            <input placeholder="enter varification code here" type="tel" id="varification_code" name="varification_code" pattern="[0-9]{6}" required maxlength="10" />
        </div>
        <small>Enter 10-digit number without country code</small>
      </div>

      <div id="verification-section" style="display: none;" class="form-group">
        <label for="verification_code">Enter Verification Code:</label>
        <div class="input-group">
            <input type="text" id="verification_code" name="verification_code" pattern="[0-9]{6}" maxlength="6" />
            <button type="button" id="verifyCode" class="verify-btn">Verify Code</button>
        </div>
        <small>Enter the 6-digit code sent to your WhatsApp</small>
      </div>
      
      <br />
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required 
    pattern="(?=.*\d)(?=.*[A-Z]).{8,}" 
    title="Must contain at least one number and one uppercase letter, and at least 8 or more characters"
/>
      <br />
      <label for="repeatpassword">Confirm Password:</label>
      <input
        type="password"
        id="repeatpassword"
        name="repeatpassword"
        required
      />
      <br />
      <button type="submit">Register</button>
      <p style="text-align: center; margin-top: 15px;">
        Have account already? 
        <a href="login.php" style="color: #28a745; text-decoration: none;">Login</a>
      </p>
      <?php
      if (isset($verification_code) && isset($whatsapp_link)) {
          echo "<p style='text-align: center; margin-top: 15px;'>";
          echo "<a href='$whatsapp_link' target='_blank' style='color: #25d366; text-decoration: none;'>";
          echo "<img src='https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg' style='width: 20px; vertical-align: middle;'> ";
          echo "Get verification code via WhatsApp</a></p>";
      }
      ?>
    </form>
  </body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate all required fields are present
        if (!isset($_POST['country_code']) || empty($_POST['country_code'])) {
            throw new Exception("Please select a country code");
        }

        // Get the phone number details
        $country_code = trim($_POST['country_code']);
        $phone_number = trim($_POST['phone']);
        
        // Basic phone validation
        if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
            throw new Exception("Invalid phone number format. Please enter 10 digits.");
        }
        
        // Format the full number
        $formatted_number = $country_code . $phone_number;
        
        // Generate verification code once
        $verification_code = sprintf("%06d", mt_rand(1, 999999));
        
        // Format phone number for WhatsApp (remove + and any spaces)
        $whatsapp_number = str_replace(['+', ' '], '', $formatted_number);

        // Create connection
        $conn = mysqli_connect("localhost", "root", "");
        if (!$conn) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }

        // Create database if not exists
        if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS invisiblephoenix")) {
            throw new Exception("Error creating database: " . mysqli_error($conn));
        }

        // Select the database
        if (!mysqli_select_db($conn, "invisiblephoenix")) {
            throw new Exception("Error selecting database: " . mysqli_error($conn));
        }

        // Create table if not exists
        $createTableQuery = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            country_code VARCHAR(10) NOT NULL,
            phone VARCHAR(15) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            verification_code VARCHAR(6) NOT NULL,
            is_verified BOOLEAN DEFAULT FALSE
        )";
        
        if (!mysqli_query($conn, $createTableQuery)) {
            throw new Exception("Error creating table: " . mysqli_error($conn));
        }

        // Hash password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Check if passwords match
        if ($_POST['password'] !== $_POST['repeatpassword']) {
            throw new Exception("Passwords do not match");
        }

        // Validate password strength
        if (strlen($_POST['password']) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }
        if (!preg_match('/[A-Z]/', $_POST['password'])) {
            throw new Exception("Password must contain at least one uppercase letter");
        }
        if (!preg_match('/[0-9]/', $_POST['password'])) {
            throw new Exception("Password must contain at least one number");
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Add after phone validation
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $fullname = $firstname . ' ' . $lastname;

        // Prepare and execute insert statement
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, country_code, phone, email, password, verification_code) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
        
        mysqli_stmt_bind_param($stmt, "ssssss", 
            $fullname,  // Changed from $_POST['name'] to $fullname
            $country_code,
            $formatted_number,
            $_POST['email'],
            $hashed_password,
            $verification_code
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting user data: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

        // Generate WhatsApp link only after successful registration
        $whatsapp_message = urlencode("Your verification code is: " . $verification_code);
        $whatsapp_link = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";

        // Store these in session to display after redirect
        session_start();
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['whatsapp_link'] = $whatsapp_link;

        // Send verification email
        $to = $_POST['email'];
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

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Invisible Phoenix <sadananda.sherigara@sriramtechnologies.com>" . "\r\n";

        if (!mail($to, $subject, $message, $headers)) {
            throw new Exception("Failed to send verification email");
        }

        // Close connection
        mysqli_close($conn);

        // Redirect to verification page
        header("Location: verify.php?email=" . urlencode($_POST['email']));
        exit();

    } catch(Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "<p style='color: red;'>This email or phone number is already registered.</p>";
        } else {
            echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<script>
document.getElementById('verifyEmail').addEventListener('click', function() {
    const email = document.getElementById('email').value;
    if (!email) {
        alert('Please enter an email address');
        return;
    }
    
    // Send verification code
    fetch('send_verification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(data => {
        alert('Verification code sent to your email');
    })
    .catch(error => {
        alert('Error sending verification code');
    });
});

document.getElementById('verifyPhone').addEventListener('click', function() {
    const countryCode = document.getElementById('country_code').value;
    const phone = document.getElementById('phone').value;
    
    if (!countryCode || !phone) {
        alert('Please enter complete phone number');
        return;
    }
    
    // Send verification code via WhatsApp
    const whatsappNumber = countryCode.replace('+', '') + phone;
    const verificationCode = Math.floor(100000 + Math.random() * 900000);
    const whatsappLink = `https://wa.me/${whatsappNumber}?text=Your verification code is: ${verificationCode}`;
    
    window.open(whatsappLink, '_blank');
});

document.getElementById('sendCode').addEventListener('click', function() {
    const countryCode = document.getElementById('country_code').value;
    const phone = document.getElementById('phone').value;
    
    if (!countryCode || !phone) {
        alert('Please enter complete phone number');
        return;
    }

    // Generate and store verification code
    const verificationCode = Math.floor(100000 + Math.random() * 900000);
    sessionStorage.setItem('whatsapp_verification_code', verificationCode);
    
    // Format WhatsApp message
    const whatsappNumber = countryCode.replace('+', '') + phone;
    const message = `Your verification code is: ${verificationCode}`;
    const whatsappLink = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
    
    // Open WhatsApp
    window.open(whatsappLink, '_blank');
    
    // Show verification input
    document.getElementById('verification-section').style.display = 'block';
});

document.getElementById('verifyCode').addEventListener('click', function() {
    const inputCode = document.getElementById('verification_code').value;
    const storedCode = sessionStorage.getItem('whatsapp_verification_code');
    
    if (inputCode === storedCode) {
        alert('Phone number verified successfully!');
        document.getElementById('phone').readOnly = true;
        document.getElementById('country_code').disabled = true;
        document.getElementById('sendCode').disabled = true;
        document.getElementById('verification-section').style.display = 'none';
        sessionStorage.removeItem('whatsapp_verification_code');
    } else {
        alert('Invalid verification code. Please try again.');
    }
});
</script>