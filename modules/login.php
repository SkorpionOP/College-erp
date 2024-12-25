<?php
// login.php

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
                $_SESSION['role'] = $user['role']; // Store the role in the session

                // Redirect based on role
                switch ($user['role']) {
                    case 'Admin':
                        header('Location: ?page=dashboard');
                        break;
                    case 'Teacher':
                        header('Location: ?page=dashboard');
                        break;
                    case 'Student':
                        header('Location: ?page=dashboard');
                        break;
                    default:
                        $error = "Unknown role. Please contact support.";
                        session_destroy();
                        break;
                }
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "Username not found.";
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
    <title>Login - College ERP</title>
    <link rel="stylesheet" href="modules/styles/login.css">
</head>
<style>
    /* General styles */
body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    background: #f5f6fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

/* Container styles */
.container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 400px;
    width: 100%;
}

.container h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #444;
}

/* Error message */
.error {
    color: #e74c3c;
    font-size: 14px;
    margin-bottom: 15px;
    background: #fdecea;
    padding: 10px;
    border: 1px solid #e74c3c;
    border-radius: 4px;
}

/* Input box styles */
.input-box {
    margin-bottom: 20px;
    text-align: left;
}

.input-box .details {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
    display: inline-block;
}

.input-box input {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    transition: border 0.3s ease;
}

.input-box input:focus {
    border-color: #007bff;
}

/* Button styles */
.button {
    margin-top: 10px;
}

.button button {
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.button button:hover {
    background: #0056b3;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 20px 30px;
    }

    .container h1 {
        font-size: 20px;
    }

    .button button {
        font-size: 14px;
    }
}
/* Login button styles */
.button button {
    width: 100%;
    padding: 12px 16px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background: linear-gradient(90deg, #007bff, #0056b3);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.button button:hover {
    background: linear-gradient(90deg, #0056b3, #003a73);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.button button:active {
    background: linear-gradient(90deg, #003a73, #00254f);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(1px);
}

.button button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
}

</style>
<body>
    <div class="container">
        <h1>Login into your Account</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="?page=login">
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
