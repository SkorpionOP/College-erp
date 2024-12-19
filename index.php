<?php
// index.php
// Main entry point for the College ERP system
session_start();

require_once 'includes/db.php';
require_once 'includes/functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        include 'templates/home.php';
        break;

    case 'login':
        include 'modules/login.php';
        break;

    case 'logout':
        include 'logout.php';
        break;
    case 'dashboard':
        include 'modules/dashboard.php';
        break;

    case 'create_user':
        // Form for creating a new user
        include 'modules/create_user1.php';
        break;
    

    case 'test':
        echo "Test page works!";
        break;
    
    case 'hash_password' :
        echo password_hash('123', PASSWORD_BCRYPT);
        break;
    case 'attendance':
        include 'modules/attendance.php';
        break;
    case 'subjects':
        include 'modules/subjects.php';
        break;
    default:
        include 'templates/404.php';
}
?>