<?php
require_once 'C:\wamp64\www\College-erp-main\includes\db.php';

$user = null;
$subjects = [];
$counsellors = [];

// Fetch counsellors (teachers) for students
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE role = 'Teacher'");
$stmt->execute();
$counsellors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subjects for teachers
$stmt = $pdo->prepare("SELECT subject_id, name FROM subjects");
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user details if user_id is provided in GET request
if (isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $stmt = $pdo->prepare("SELECT id, username, name, email, phone, gender, role, counsellor_id, subject_id FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<p>User not found.</p>";
        exit;  // Stop further execution if the user isn't found
    }
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $password = $_POST['password'];  // Password field
    $subject_id = $_POST['subject_id'] ?? null; // Selected subject for teacher (nullable)
    $counsellor_id = $_POST['counsellor_id'] ?? null; // Selected counsellor for student (nullable)

    // If password is not empty, hash it before updating
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $passwordHash = null;  // Do not update password if it's empty
    }

    // Prepare the update query, including the password if it's provided
    $query = "UPDATE users SET name = :name, email = :email, phone = :phone, gender = :gender, role = :role";
    
    // Only include subject_id if role is Teacher
    if ($role === 'Teacher' && $subject_id !== null) {
        $query .= ", subject_id = :subject_id";
    }

    // Only include counsellor_id if role is Student
    if ($role === 'Student' && $counsellor_id !== null) {
        $query .= ", counsellor_id = :counsellor_id";
    }

    if ($passwordHash) {
        $query .= ", password = :password";  // Add password update if provided
    }

    $query .= " WHERE id = :id";

    // Prepare parameters for the query
    $params = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'gender' => $gender,
        'role' => $role,
        'id' => $userId
    ];

    // Add subject_id if it's for Teacher
    if ($role === 'Teacher' && $subject_id !== null) {
        $params['subject_id'] = $subject_id;
    }

    // Add counsellor_id if it's for Student
    if ($role === 'Student' && $counsellor_id !== null) {
        $params['counsellor_id'] = $counsellor_id;
    }

    // Add password if it's provided
    if ($passwordHash) {
        $params['password'] = $passwordHash;
    }

    // Execute the update query
    $stmt = $pdo->prepare($query);
    if ($stmt->execute($params)) {
        // If the user is a Teacher and a subject is selected, update the instructor_id in the subjects table
        if ($role === 'Teacher' && $subject_id !== null) {
            $updateSubjectQuery = "UPDATE subjects SET instructor_id = :instructor_id WHERE subject_id = :subject_id";
            $subjectStmt = $pdo->prepare($updateSubjectQuery);
            $subjectStmt->execute([
                'instructor_id' => $userId, // Set the instructor_id to the teacher's user_id
                'subject_id' => $subject_id
            ]);
        }

        header("Location: /college-erp-main/?page=manage_user&status=success");
        exit;
    } else {
        header("Location: /college-erp-main/?page=manage_user&status=error");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 30px;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
            display: block;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        input[readonly] {
            background-color: #e9ecef;
        }

        @media (max-width: 600px) {
            form {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <h1>Edit User Details</h1>
    <?php if ($user): ?>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" readonly required><br>
            
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
            
            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"><br>
            
            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select><br>
            
            <label>Role:</label>
            <select name="role" required>
                <option value="Teacher" <?= $user['role'] == 'Teacher' ? 'selected' : '' ?>>Teacher</option>
                <option value="Student" <?= $user['role'] == 'Student' ? 'selected' : '' ?>>Student</option>
            </select><br>

            <!-- Show Subject selection only if user is a Teacher -->
            <?php if ($user['role'] === 'Teacher'): ?>
                <label>Subject:</label>
                <select name="subject_id" required>
                    <option value="">Select a subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['subject_id'] ?>" <?= $user['subject_id'] == $subject['subject_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
            <?php endif; ?>

            <!-- Show Counsellor selection only if user is a Student -->
            <?php if ($user['role'] === 'Student'): ?>
                <label>Counsellor:</label>
                <select name="counsellor_id" required>
                    <option value="">Select a counsellor</option>
                    <?php foreach ($counsellors as $counsellor): ?>
                        <option value="<?= $counsellor['id'] ?>" <?= $user['counsellor_id'] == $counsellor['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($counsellor['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
            <?php endif; ?>
            
            <label>Password:</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password"><br>
            
            <button type="submit">Update</button>
        </form>
    <?php endif; ?>
</body>
</html>
