<?php
// Include database connection
require_once 'C:\\wamp64\\www\\College-erp-main\\includes\\db.php'; 

// Start session and secure the page
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: modules/teacher/login.php");
    exit;
}

$query = "SELECT id FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->execute([':username' => $_SESSION['username']]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    header("Location: modules/teacher/login.php");
    exit;
}

$teacher_id = $teacher['id'];

$query = "SELECT subject_id, name FROM subjects WHERE instructor_id = :instructor_id ORDER BY name ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([':instructor_id' => $teacher_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$marksSubmitted = false;

// Handle marks form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['marks'])) {
    $subject_id = $_POST['subject_id'];
    $exam_date = $_POST['exam_date'];
    $total_marks = $_POST['total_marks'];
    $exam_type = $_POST['exam_type']; // New exam type field

    foreach ($_POST['marks'] as $student_id => $marks) {
        $query = "INSERT INTO marks (user_id, subject_id, marks_obtained, total_marks, exam_type, exam_date) 
                  VALUES (:user_id, :subject_id, :marks_obtained, :total_marks, :exam_type, :exam_date)
                  ON DUPLICATE KEY UPDATE 
                      marks_obtained = :marks_obtained, 
                      total_marks = :total_marks, 
                      exam_type = :exam_type,
                      exam_date = :exam_date";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':user_id' => $student_id,
            ':subject_id' => $subject_id,
            ':marks_obtained' => $marks,
            ':total_marks' => $total_marks,
            ':exam_type' => $exam_type,
            ':exam_date' => $exam_date
        ]);
    }

    $marksSubmitted = true; // Set flag for success
    header("Location: ?page=marks");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Marks</title>
    <link rel="stylesheet" href="modules/teacher/styles/dashboard.css">
    <script>
        <?php if ($marksSubmitted): ?>
        alert("Marks have been successfully submitted!");
        <?php endif; ?>
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
        <h1>Manage Marks</h1>

        <form method="POST">
            <label for="subject_id">Select Subject:</label>
            <select name="subject_id" id="subject_id" required>
                <option value="">Select a subject</option>
                <?php
                foreach ($subjects as $subject) {
                    echo "<option value='{$subject['subject_id']}'>{$subject['name']}</option>";
                }
                ?>
            </select>

            <label for="exam_date">Exam Date:</label>
            <input type="date" name="exam_date" id="exam_date" required>

            <label for="total_marks">Total Marks:</label>
            <input type="number" name="total_marks" id="total_marks" min="0" required>

            <label for="exam_type">Exam Type:</label>
            <select name="exam_type" id="exam_type" required>
                <option value="Quiz">Quiz</option>
                <option value="Midterm">Midterm</option>
                <option value="Final">Final</option>
                <option value="Assignment">Assignment</option>
                <option value="Other">Other</option>
            </select>

            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT id, name FROM users WHERE role = 'Student'";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($students as $student) {
                        echo "<tr>
                                <td>{$student['id']}</td>
                                <td>{$student['name']}</td>
                                <td>
                                    <input type='number' name='marks[{$student['id']}]' value='' min='0'>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit">Submit Marks</button>
        </form>
    </div>
</body>
</html>
