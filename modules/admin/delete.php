<?php
require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; // Path to your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user_id is provided
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // SQL DELETE query
        $query = "DELETE FROM users WHERE id = :id";  // Adjust the table name if needed

        try {
            // Prepare and execute the query
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);  // Binding the user_id

            if ($stmt->execute()) {
                // Success: Respond with a JSON object indicating success
                echo json_encode(['success' => true]);
            } else {
                // Failure: Respond with an error message
                echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (PDOException $e) {
            // Catch any database-related errors
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // No user_id provided in the request
        echo json_encode(['success' => false, 'message' => 'No user ID provided']);
    }
} else {
    // Request method is not POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
