<?php
// delete_feedback.php

require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; // Ensure the path is correct

// Start session and secure the page
if (!isset($_SESSION['user_id'])) {
    header("Location: modules/login.php"); // Redirect to login if not authenticated
    exit;
}

// Check if a POST request is made with a feedback ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
    $feedback_id = intval($_POST['feedback_id']); // Sanitize feedback ID

    try {
        // Prepare and execute the deletion query
        $query = "DELETE FROM feedback WHERE id = :feedback_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':feedback_id', $feedback_id, PDO::PARAM_INT);

        // Execute the deletion query
        if ($stmt->execute()) {
            // Redirect back to the feedback page with a success message
            header("Location: feedback.php?message=Feedback deleted successfully.");
            exit;
        } else {
            // Redirect back with an error message if deletion failed
            header("Location: feedback.php?message=Error deleting feedback.");
            exit;
        }
    } catch (PDOException $e) {
        // Log the error (optional) and redirect with an error message
        error_log("Error deleting feedback: " . $e->getMessage());
        header("Location: feedback.php?message=An error occurred while deleting feedback.");
        exit;
    }
} else {
    // Redirect if accessed without a valid POST request
    header("Location: feedback.php?message=Invalid request.");
    exit;
}
?>
