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

// Fetch attendance records along with subject names
$stmt_attendance = $pdo->prepare("SELECT attendance.*, subjects.name AS subject_name, subjects.total_classes FROM attendance
                                  JOIN subjects ON attendance.subject_id = subjects.subject_id
                                  WHERE student_id = ?");
$stmt_attendance->execute([$_SESSION['user_id']]);
$attendance_records = $stmt_attendance->fetchAll();

$current_total_percentage = 0;
$total_classes = 0;
$total_attended = 0;

// Calculate overall attendance
foreach ($attendance_records as $record) {
    $total_classes += $record['total_classes'];
    $total_attended += $record['attended_classes'];
}
$current_total_percentage = ($total_classes > 0) ? ($total_attended / $total_classes) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - College ERP</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    font-size: 28px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

p {
    font-size: 18px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 16px;
}

table th, table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

table th {
    background-color: #f4f4f9;
    color: #333;
}

table td {
    background-color: #fff;
}

table td:nth-child(4) {
    color: #fff;
    font-weight: bold;
    padding: 10px;
}

table td.red {
    background-color: #ff6384;
}

table td.green {
    background-color: #36a2eb;
}

.chart-container {
    margin: 20px 0;
    height: 300px;
}

#calculator {
    margin-top: 20px;
    text-align: center;
}

#calculator input, #calculator button {
    padding: 10px;
    font-size: 16px;
    margin: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

#calculator input {
    width: 200px;
}

button {
    background-color: #36a2eb;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #2980b9;
}

#result {
    margin-top: 20px;
    font-size: 18px;
    color: #333;
    background-color: #e0ffe0;
    padding: 10px;
    border-radius: 4px;
    display: inline-block;
}

.footer {
    text-align: center;
    margin-top: 30px;
    font-size: 14px;
    color: #666;
}

</style>
<body>
    <div class="container">
        <h2>Attendance Overview</h2>
        <p>Total Attendance: <?php echo round($current_total_percentage, 2); ?>%</p>
        <p>Total Classes Attended: <?php echo $total_attended; ?> out of <?php echo $total_classes; ?></p>
        <input id="total" value="<?php echo $total_classes; ?>" style="display:none">
        <input id="attended" value="<?php echo $total_attended; ?>" style="display:none">
        
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Total Classes</th>
                    <th>Attended Classes</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_records as $record): 
                    $percentage = round(($record['attended_classes'] / $record['total_classes']) * 100, 2); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['total_classes']); ?></td>
                        <td><?php echo htmlspecialchars($record['attended_classes']); ?></td>
                        <td style="color: <?php echo $percentage >= 75 ? 'green' : 'red'; ?>;">
                            <?php echo $percentage . '%'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
        </div>

        <h3>Attendance Calculator</h3>
        <form id="calculator">
            <input type="number" id="currentAttendance" placeholder="Current %" required disabled min="0" max="100" value="<?php echo round($current_total_percentage); ?>">
            <input type="number" id="remainingClasses" placeholder="Remaining Classes" required min="1">
            <button type="submit">Calculate</button>
        </form>
        <p id="result"></p>
    </div>

    <div class="footer">
        &copy; 2024 College ERP
    </div>

    <script>
// Chart.js: Attendance Chart
const ctx = document.getElementById('attendanceChart').getContext('2d');

// Function to generate a random color
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// PHP: Generate labels and data
const labels = [
    <?php foreach ($attendance_records as $record) {
        echo '"' . htmlspecialchars($record['subject_name']) . '",';
    } ?>
];

const data = [
    <?php foreach ($attendance_records as $record) {
        echo round(($record['attended_classes'] / $record['total_classes']) * 100, 2) . ',';
    } ?>
];

// Generate random colors for each data point
const backgroundColors = data.map(() => getRandomColor());

const attendanceData = {
    labels: labels,
    datasets: [{
        label: 'Attendance Percentage',
        data: data,
        backgroundColor: backgroundColors, // Set random colors here
        hoverOffset: 4
    }]
};

new Chart(ctx, {
    type: 'pie',
    data: attendanceData,
    options: {
        responsive: true,
        maintainAspectRatio: false, // Maintain chart aspect ratio when resizing
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                    }
                }
            }
        }
    }
});
</script>


</script>
<script>
// Attendance Calculator
document.getElementById('calculator').addEventListener('submit', function (e) {
    e.preventDefault();

    const currentAttendance = getCurrentAttendance();
    const remainingClasses = getRemainingClasses();
    const total_attended = getTotalAttended();
    const total_classes = getTotalClasses();

    const requiredClasses = calculateRequiredClasses(total_attended, total_classes, remainingClasses);
    displayResult(requiredClasses, remainingClasses);
});

function getCurrentAttendance() {
    return parseFloat(document.getElementById('currentAttendance').value);
}

function getRemainingClasses() {
    return parseInt(document.getElementById('remainingClasses').value);
}

function getTotalAttended() {
    return parseInt(document.getElementById('attended').value);
}

function getTotalClasses() {
    return parseInt(document.getElementById('total').value);
}

function calculateRequiredClasses(total_attended, total_classes, remainingClasses) {
    return Math.ceil(((total_classes + remainingClasses) * 0.75) - total_attended);
}

function displayResult(requiredClasses, remainingClasses) {
    // Check if required classes exceed remaining classes
    if (requiredClasses > remainingClasses) {
        document.getElementById('result').textContent = 'Please contact the counselor or HOD. You need more classes than are remaining to reach 75%.';
    } else {
        document.getElementById('result').textContent = `You need to attend ${requiredClasses} out of the next ${remainingClasses} classes to reach 75%.`;
    }
}
</script>
</body>
</html>
