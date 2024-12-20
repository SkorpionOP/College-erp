<?php
// registration.php

require_once 'includes/db.php';
require_once 'includes/functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT);
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $gender = isset($_POST['gender']) ? sanitize($_POST['gender']) : null;

    // Basic validation
    if (empty($username) || empty($password) || empty($name) || empty($email) || empty($phone)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            // Adjust the SQL query to include new fields
            $stmt = $pdo->prepare("INSERT INTO users (username, password, name, email, phone, gender) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $password, $name, $email, $phone, $gender])) {
                $success = "User created successfully.";
                header('Location: ?page=login');
            } else {
                $error = "Error creating user.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            // Log error to a file or monitoring system here
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create User - College ERP</title>
  <link rel="stylesheet" href="modules/styles/style1.css"> <!-- Adjusted path to your CSS file -->
</head>
<body>
  <div class="container">
    <!-- Title section -->
    <div class="title">Create New User</div>
    <div class="content">
      <!-- Registration form -->
      <form method="POST">
        <div class="user-details">
          <!-- Input for Full Name -->
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
          </div>
          <!-- Input for Username -->
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
          </div>
          <!-- Input for Email -->
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <!-- Input for Phone Number -->
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
          </div>
          <!-- Input for Password -->
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <!-- Input for Confirm Password -->
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
          </div>
        </div>
        <div class="gender-details">
          <!-- Radio buttons for gender selection -->
          <span class="gender-title">Gender</span>
          <div class="category">
            <label for="gender-male">
              <span class="dot one"></span>
              <span class="gender">Male</span>
              <input type="radio" id="gender-male" name="gender" value="male">
            </label>
            <label for="gender-female">
              <span class="dot two"></span>
              <span class="gender">Female</span>
              <input type="radio" id="gender-female" name="gender" value="female">
            </label>
            <label for="gender-other">
              <span class="dot three"></span>
              <span class="gender">Prefer not to say</span>
              <input type="radio" id="gender-other" name="gender" value="other">
            </label>
          </div>
        </div>
        <!-- Submit button -->
        <div class="button">
          <input type="submit" value="Create User">
        </div>
      </form>
    </div>
  </div>
</body>
</html>
