<?php
// Include database connection
require_once 'C:\wamp64\www\College-erp-main\includes\db.php'; 

// Check if user is logged in and is a teacher
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: ../login.php");
    exit;
}

try {
    // Fetch teacher's user_id based on username
    $query = "SELECT id FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $_SESSION['username']]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        header("Location: ../login.php");
        exit;
    }

    $teacher_id = $teacher['id'];

    // Fetch subjects taught by the teacher
    $query = "SELECT subject_id, name, total_classes FROM subjects WHERE instructor_id = :instructor_id ORDER BY name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':instructor_id' => $teacher_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle attendance form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance'])) {
        $subject_id = $_POST['subject_id'];

        // Fetch and update total_classes for the selected subject
        $query = "SELECT total_classes FROM subjects WHERE subject_id = :subject_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':subject_id' => $subject_id]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subject) {
            $total_classes = $subject['total_classes'] + 1;

            // Update total_classes for the subject
            $query = "UPDATE subjects SET total_classes = :total_classes WHERE subject_id = :subject_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':total_classes' => $total_classes, ':subject_id' => $subject_id]);

            // Process each student's attendance
            foreach ($_POST['attendance'] as $student_username => $status) {
                $query = "SELECT id FROM users WHERE username = :username AND role = 'Student'";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':username' => $student_username]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($student) {
                    $student_id = $student['id'];

                    // Check and update attendance
                    $query = "SELECT attended_classes FROM attendance WHERE student_id = :student_id AND subject_id = :subject_id";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':student_id' => $student_id, ':subject_id' => $subject_id]);
                    $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

                    $attended_classes = ($status == '1') ? 1 : 0;

                    if ($attendance) {
                        // Update existing attendance
                        $new_attended_classes = $attendance['attended_classes'] + $attended_classes;

                        $query = "UPDATE attendance SET attended_classes = :attended_classes, total_classes = :total_classes WHERE student_id = :student_id AND subject_id = :subject_id";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([
                            ':attended_classes' => $new_attended_classes,
                            ':total_classes' => $total_classes,
                            ':student_id' => $student_id,
                            ':subject_id' => $subject_id
                        ]);
                    } else {
                        // Insert new attendance record
                        $query = "INSERT INTO attendance (student_id, subject_id, total_classes, attended_classes) 
                                  VALUES (:student_id, :subject_id, :total_classes, :attended_classes)";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([
                            ':student_id' => $student_id,
                            ':subject_id' => $subject_id,
                            ':total_classes' => $total_classes,
                            ':attended_classes' => $attended_classes
                        ]);
                    }
                }
            }
            $_SESSION['attendance_submitted'] = true;
            header("Location: ?page=attendance");
            exit;
        }
    }
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <link rel="stylesheet" href="modules/teacher/styles/dashboard.css">
    <script>
        window.onload = function() {
            if (<?php echo isset($_SESSION['attendance_submitted']) ? 'true' : 'false'; ?>) {
                alert('Attendance Submitted Successfully!');
                <?php unset($_SESSION['attendance_submitted']); ?>
            }
        }
    </script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=attendance">Manage Attendance</a></li>
            <li><a href="?page=marks">Manage Marks</a></li>
            <li><a href="?page=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Manage Attendance</h1>

        <form method="POST">
            <label for="subject_id">Select Subject:</label>
            <select name="subject_id" id="subject_id" required>
                <option value="">Select a subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= $subject['subject_id'] ?>"><?= $subject['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT id, name, username FROM users WHERE role = 'Student'";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <input type="checkbox" name="attendance[<?= htmlspecialchars($student['username']) ?>]" value="1"> Present
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Submit Attendance</button>
        </form>
    </div>
</body>
</html>
