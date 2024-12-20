<!-- Dashboard HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - College ERP</title>
    <link rel="stylesheet" href="modules/styles/dashboard.css">
</head>
<body>
    <?php
    require_once 'includes/db.php';

    // Redirect if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ?page=login");
        exit;
    }

    // Fetch user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Fetch attendance records
    $stmt_attendance = $pdo->prepare("
        SELECT 
            SUM(attended_classes) AS attended,
            SUM(total_classes) AS total_classes 
        FROM attendance 
        WHERE student_id = ?");
    $stmt_attendance->execute([$_SESSION['user_id']]);
    $attendance = $stmt_attendance->fetch();
    $attendance_percentage = $attendance['total_classes'] > 0 
        ? round(($attendance['attended'] / $attendance['total_classes']) * 100, 2) 
        : 0;

    // Fetch notifications
    $stmt_notifications = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
    $stmt_notifications->execute();
    $notifications = $stmt_notifications->fetchAll();

    // Fetch marks summary with subject names and grades
    $stmt_marks = $pdo->prepare("
        SELECT 
            s.name AS subject_name,
            m.marks_obtained,
            m.total_marks,
            (m.marks_obtained / m.total_marks) * 100 AS percentage,
            m.subject_id
        FROM marks m
        JOIN subjects s ON m.subject_id = s.subject_id
        WHERE m.user_id = ?
        LIMIT 5
    ");
    $stmt_marks->execute([$_SESSION['user_id']]);
    $marks_records = $stmt_marks->fetchAll();

    // Fetch today's timetable
    $day_of_week = date('l'); // Get the current day of the week
    $stmt_timetable = $pdo->prepare("SELECT * FROM timetable WHERE day_of_week = ?");
    $stmt_timetable->execute([$day_of_week]);
    $today_timetable = $stmt_timetable->fetch();
    

    $hour = date('G');
        if ($hour < 12) {
            $greeting = "Good Morning";
    } elseif ($hour < 18) {
        $greeting = "Good Afternoon";
    } else {
        $greeting = "Good Evening";
    }
    ?>

    <div class="container">
        <div class="welcome-banner">
            <h1><?php echo $greeting . ', ' . htmlspecialchars($user['name']); ?>!</h1>
            <p>Your academic performance overview is below.</p>
            <?php if ($attendance_percentage < 75): ?>
                <p class="warning">Warning: Your attendance is below 75%!</p>
            <?php endif; ?>
            <a href="feedback.php" class="feedback-button">Feedback</a>
        </div>
        <div class="shortcuts">
            <a href="?page=attendance">Attendance</a>
            <a href="?page=marks">Marks</a>
            <a href="?page=subjects">Subjects</a>
            <a href="?page=edit-user">Edit Profile</a>
        </div>
        <div class="dashboard-grid">
            <div class="card">
                <h3>Overall Attendance</h3>
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="card">
                <h3>Recent Notifications</h3>
                <ul class="notifications">
                    <?php foreach ($notifications as $notification): ?>
                        <li><a href="modules/notifications.php?id=<?= htmlspecialchars($notification['id']) ?>">
                            <?= htmlspecialchars($notification['title']) ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card">
                <h3>Marks Summary</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marks_records as $record):
                            $percentage = $record['marks_obtained'] / $record['total_marks'] * 100;
                            $grade = '';
                            $class = '';

                            if ($percentage >= 90) {
                                $grade = 'A+';
                                $class = 'grade-A-plus';
                            } elseif ($percentage >= 85) {
                                $grade = 'A';
                                $class = 'grade-A';
                            } elseif ($percentage >= 70) {
                                $grade = 'B';
                                $class = 'grade-B';
                            } elseif ($percentage >= 50) {
                                $grade = 'C';
                                $class = 'grade-C';
                            } else {
                                $grade = 'F';
                                $class = 'grade-F';
                            }
                        ?>
                            <tr class="<?= $class ?>">
                                <td onclick="window.location.href='modules/instructor.php?subject_id=<?= htmlspecialchars($record['subject_id']) ?>'"><?= htmlspecialchars($record['subject_name']) ?></td>
                                <td><?= htmlspecialchars($grade) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card">
                <h3>Today's Timetable (<?= $day_of_week ?>)</h3>
                <table class="today-timetable">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>09:00 - 10:00</td>
                            <td><?= htmlspecialchars($today_timetable['period_1_subject']) ?></td>
                        </tr>
                        <tr>
                            <td>10:00 - 11:00</td>
                            <td><?= htmlspecialchars($today_timetable['period_2_subject']) ?></td>
                        </tr>
                        <tr>
                            <td>11:00 - 12:00</td>
                            <td><?= htmlspecialchars($today_timetable['period_3_subject']) ?></td>
                        </tr>
                        <tr>
                            <td>12:00 - 12:45</td>
                            <td><?= htmlspecialchars($today_timetable['lunch_start'] . ' - ' . $today_timetable['lunch_end']) ?></td>
                        </tr>
                        <tr>
                            <td>12:45 - 1:45</td>
                            <td><?= htmlspecialchars($today_timetable['period_5_subject']) ?></td>
                        </tr>
                        <tr>
                            <td>1:45 - 2:45</td>
                            <td><?= htmlspecialchars($today_timetable['period_6_subject']) ?></td>
                        </tr>
                        <tr>
                            <td>2:45 - 3:45</td>
                            <td><?= htmlspecialchars($today_timetable['period_7_subject']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Attended', 'Missed'],
                datasets: [{
                    data: [<?= $attendance['attended'] ?>, <?= $attendance['total_classes'] - $attendance['attended'] ?>],
                    backgroundColor: ['#4caf50', '#f44336'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
