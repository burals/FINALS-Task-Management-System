<?php
include_once '../../database/dbconnection.php';
include_once '../admin/authentication/admin-class.php';
include_once '../../config/settings-configuration.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) { 
    die("Access denied! Please log in first.");
}

// Check if the role is 'users'
if ($_SESSION['role'] !== 'chairperson') {
    die("Access denied! You are not authorized to access this page.");
}

// Ensure fullname is set
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'chairperson';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairperson Dashboard</title>
    <link rel="stylesheet" href="../../src/css/chair-dashboard.css">
     
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="nav-container">
<nav class="navbar">

            <p>Logged in as: <strong><?php echo htmlspecialchars($fullname); ?></strong></p>
        <ul>
        <a href="../admin/authentication/admin-class.php?admin_signout" class="signout">Sign Out</a>
    </ul>

</nav>
</div>
    <h1>Welcome to Your Dashboard</h1>


    <h2>Your Tasks</h2>

<div class="task-container">
    <p>No tasks assigned yet.</p>
</div>
</body>