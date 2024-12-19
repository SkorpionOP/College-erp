<?php
// create_user.php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT);
    $name = sanitize($_POST['name']);

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, name) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $name])) {
            $success = "User created successfully.";
            header("Location: login.php"); // Redirect to login page
        exit;
        } else {
            $error = "Error creating user.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - College ERP</title>
    <link rel="stylesheet" type="text/css" href='modules\styles\style.css'>
</head>
<body>
    <div class="container">
        <h1>Create New User</h1>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <button type="submit">Create User</button>
        </form>
    </div>
</body>
</html>
