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
  </style>
  <body>
    <h1>Signup Page</h1>
    <form action="signup.php" method="POST">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required />
      <br />
      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <select name="country_code" id="country_code" required>
          <option value="">Select Country Code</option>
          <option value="+1">United States (+1)</option>
          <option value="+91">India (+91)</option>
        </select>
        <input
          type="tel"
          id="phone"
          name="phone"
          pattern="[0-9]{10}"
          required
          maxlength="10"
        />
        <small>Enter 10-digit number without country code</small>
      </div>
      <br />
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required />
      <br />
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required />
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
        
        // Generate verification code
        $verification_code = sprintf("%06d", mt_rand(1, 999999));
        
        require_once 'db_connect.php';
        
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password, email_verified, verification_code) 
                              VALUES (:name, :phone, :email, :password, 0, :code)");
        
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':phone', $formatted_number);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
        $stmt->bindParam(':code', $verification_code);
        
        $stmt->execute();

        // Send verification email
        require_once 'mail_config.php';
        $emailResult = sendVerificationEmail($_POST['email'], $verification_code);
        
        if ($emailResult === true) {
            header("Location: verify.php?email=" . urlencode($_POST['email']));
            exit();
        } else {
            echo "<p style='color: red;'>Email Error: " . $emailResult . "</p>";
        }
        
    } catch(Exception $e) {
        if($e->getCode() == '23000') {
            echo "<p style='color: red;'>This email is already registered.</p>";
        } else {
            echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
        }
        exit;
    }
}
?>