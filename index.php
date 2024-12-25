<?php
// index.php
// Main entry point for the College ERP system

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) && $_GET['page'] !== 'login' && $_GET['page'] !== 'home') {
    header('Location: ?page=login'); // Redirect to login if not logged in
    exit;
}

// Get the page value from the URL or set to 'home' by default
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Routing based on the page request
switch ($page) {

    case 'login':
        include 'modules/login.php';
        break;

    case 'logout':
        include 'modules/logout.php';
        break;

    case 'leaves' :
        if ($_SESSION['role'] === 'Admin') {
        include 'modules/admin/leaves.php';
        }
        elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/leaves.php';
        }
        break;

    case 'dashboard':
        // Role-based access control for dashboards
        if ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/dashboard.php';
        } elseif ($_SESSION['role'] === 'Teacher') {
            include 'modules/teacher/dashboard.php';
        } elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/dashboard.php';
        } else {
            include 'templates/403.php'; // Forbidden access page
        }
        break;

    case 'create_user':
        if ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/create_user.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'attendance':
        if ($_SESSION['role'] === 'Teacher') {
            include 'modules/teacher/attendance.php';
        } elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/attendance.php';
        } elseif ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/attendance.php';
        }else {
            include 'templates/403.php';
        }
        break;

    case 'marks':
        if ($_SESSION['role'] === 'Teacher') {
            include 'modules/teacher/marks.php';
        } elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/overallmarks.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'subjects':
        if ($_SESSION['role'] === 'Teacher' || $_SESSION['role'] === 'Student') {
            include 'modules/subjects.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'feedback':
        if ($_SESSION['role'] === 'Teacher' || $_SESSION['role'] === 'Student') {
            include 'modules/feedback.php';
        } elseif ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/feedback.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'manage_user':
        if ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/manage_users.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'profile':
        if ($_SESSION['role'] === 'Teacher') {
            include 'modules/teacher/profile.php';
        } elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/profile.php';
        } else {
            include 'templates/403.php';
        }
        break;

    case 'home':
        if ($_SESSION['role'] === 'Admin') {
            include 'modules/admin/dashboard.php';
        } elseif ($_SESSION['role'] === 'Teacher') {
            include 'modules/teacher/dashboard.php';
        } elseif ($_SESSION['role'] === 'Student') {
            include 'modules/student/dashboard.php';
        } else {
            include 'templates/403.php'; // Forbidden access page
        }
        break;

    default:
        include 'templates/404.php'; // Page not found
}
?>
