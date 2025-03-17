<?php
// registration.php
require_once 'includes/db.php';
require_once 'includes/functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT);
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $gender = isset($_POST['gender']) ? sanitize($_POST['gender']) : null;
    $role = sanitize($_POST['role']);
    $counsellor_id = isset($_POST['counsellor']) ? sanitize($_POST['counsellor']) : null;
    $subject_id = isset($_POST['subject']) ? sanitize($_POST['subject']) : null;

    // Basic validation
    if (empty($username) || empty($password) || empty($name) || empty($email) || empty($phone) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            // Check if the username already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error = "Username already exists. Please choose a different username.";
            } else {
                // Insert user into users table
                $stmt = $pdo->prepare("INSERT INTO users (username, password, name, email, phone, gender, role, counsellor_id, subject_id) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$username, $password, $name, $email, $phone, $gender, $role, $counsellor_id, $subject_id])) {
                    $user_id = $pdo->lastInsertId(); // Get the last inserted user_id

                    if ($role == 'teacher' && !empty($subject_id)) {
                        // Check if the subject already has a teacher assigned
                        $stmt = $pdo->prepare("SELECT instructor_id FROM subjects WHERE subject_id = ?");
                        $stmt->execute([$subject_id]);
                        $subject = $stmt->fetch();

                        if (!$subject['instructor_id']) {
                            // If no instructor is assigned to the subject, assign the teacher
                            $stmt = $pdo->prepare("UPDATE subjects SET instructor_id = ? WHERE subject_id = ?");
                            if ($stmt->execute([$user_id, $subject_id])) {
                                // Update the user's subject_id in the users table
                                $stmt = $pdo->prepare("UPDATE users SET subject_id = ? WHERE id = ?");
                                if ($stmt->execute([$subject_id, $user_id])) {
                                    $success = "Teacher created and assigned to subject successfully.";
                                } else {
                                    $error = "Error updating teacher's subject.";
                                }
                            } else {
                                $error = "Error assigning teacher to subject.";
                            }
                        } else {
                            // If instructor_id already exists, do nothing
                            $success = "Teacher created successfully, but subject already has a teacher.";
                        }
                    } elseif ($role == 'student' && !empty($counsellor_id)) {
                        // Assign counsellor to student using counsellor's ID
                        $stmt = $pdo->prepare("UPDATE users SET counsellor_id = ? WHERE id = ?");
                        if ($stmt->execute([$counsellor_id, $user_id])) {
                            $success = "Student created and assigned to counsellor successfully.";
                        } else {
                            $error = "Error assigning counsellor to student.";
                        }
                    } else {
                        $success = "User created successfully.";
                    }
                } else {
                    $error = "Error creating user.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
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
    <link rel="stylesheet" href="modules/admin/styles/admin.css">
</head>
<body>
    <div class="container">
        <div class="title">Create New User</div>
        <div class="content">
            <form method="POST">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="input-box">
                    <span class="details">Role</span>
                    <select name="role" id="role" required>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>

                <!-- Gender Selection -->
                <div class="gender-details">
                    <span class="gender-title">Gender</span>
                    <div class="category">
                        <label for="gender-male">
                            <input type="radio" id="gender-male" name="gender" value="male" required>
                            <span class="dot one"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="gender-female">
                            <input type="radio" id="gender-female" name="gender" value="female" required>
                            <span class="dot two"></span>
                            <span class="gender">Female</span>
                        </label>
                        <label for="gender-other">
                            <input type="radio" id="gender-other" name="gender" value="other" required>
                            <span class="dot three"></span>
                            <span class="gender">Prefer not to say</span>
                        </label>
                    </div>
                </div>

                <!-- Counsellor Selection (for students only) -->
                <div class="input-box" id="counsellor-selection" style="display: none;">
                    <span class="details">Counsellor</span>
                    <select name="counsellor" id="counsellor">
                        <?php
                        // Fetch teachers (users with 'teacher' role)
                        $stmt = $pdo->query("SELECT id, name FROM users WHERE role = 'teacher'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Subject Selection (for teachers only) -->
                <div class="input-box" id="subject-selection" style="display: none;">
                    <span class="details">Subject</span>
                    <select name="subject" id="subject">
                        <?php
                        // Fetch subjects
                        $stmt = $pdo->query("SELECT subject_id, name FROM subjects");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['subject_id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="button">
                    <input type="submit" value="Create User">
                </div>
            </form>

            <?php if (!empty($success)) { ?>
                <script>
                    alert('<?php echo $success; ?>');
                </script>
            <?php } elseif (!empty($error)) { ?>
                <script>
                    alert('<?php echo $error; ?>');
                </script>
            <?php } ?>
        </div>
    </div>

    <script>
        // Show/hide form elements based on selected role
        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            document.getElementById('subject-selection').style.display = (role === 'teacher') ? 'block' : 'none';
            document.getElementById('counsellor-selection').style.display = (role === 'student') ? 'block' : 'none';
        });

        // Trigger role change on page load to ensure correct form elements are displayed
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
