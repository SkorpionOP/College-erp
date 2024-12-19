<?php
// login.php


require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    try {
        // Check if the username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // Verify the password hash
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ?page=dashboard');
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "Username not found.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
        // Log error to a file or monitoring system here
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College ERP</title>
    <link rel="stylesheet" href="modules/styles/login.css">
</head>
<body>
    <div class="container">
        <h1>Login to College ERP</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="input-box">
                <span class="details">Username:</span>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-box">
                <span class="details">Password:</span>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="button">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
