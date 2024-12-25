<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - College ERP</title>
    <link rel="stylesheet" href="modules/student/styles/dashboard.css">
</head>
<style>.feedback-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.2s;
}

.feedback-form h1 {
    font-size: 1.5em;
    color: #007bff;
    text-align: center;
    margin-bottom: 20px;
}

.feedback-form label {
    font-size: 0.9em;
    color: #333;
    display: block;
    margin-bottom: 8px;
}

.feedback-form input,
.feedback-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    margin-bottom: 15px;
    transition: border-color 0.2s;
}

.feedback-form input:focus,
.feedback-form textarea:focus {
    border-color: #007bff;
    outline: none;
}

.feedback-form textarea {
    resize: vertical;
    min-height: 100px;
}

.feedback-form .btn {
    display: inline-block;
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    text-align: center;
    font-size: 1em;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.feedback-form .btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.alert {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}</style>
<?php
require_once 'C:\wamp64\www\college-erp-main\includes\db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insert feedback into database
    $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt->execute([$name, $email, $message])) {
        $successMessage = "Thank you for your feedback!";
    } else {
        $errorMessage = "Error submitting feedback. Please try again.";
    }
}
?>



<body>
    <div class="container">
        <header>
            <h1>Submit Feedback</h1>
        </header>

        <div class="feedback-form">
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success">
                    <?= $successMessage ?>
                </div>
            <?php elseif (isset($errorMessage)): ?>
                <div class="alert alert-danger">
                    <?= $errorMessage ?>
                </div>
            <?php endif; ?>
            <form action="modules/feedback.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required></textarea>

                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>
    </div>
</body>
</html>
