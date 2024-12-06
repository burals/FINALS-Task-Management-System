<?php
include_once '../../database/dbconnection.php';
include_once '../admin/authentication/admin-class.php';
include_once '../../config/settings-configuration.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) { 
    die("Access denied! Please log in first.");
}

// Check if the role is users
if ($_SESSION['role'] !== 'users') {
    die("Access denied! You are not authorized to access this page.");
}

// Ensure username is set
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'USER';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to Your Dashboard</h1>
    <p>Logged in as : USER <strong><?php echo htmlspecialchars($fullname); ?></strong></p>

    <h2>Your Tasks</h2>
   
        <p>No tasks assigned yet.</p>

        <a href="../admin/authentication/admin-class.php?admin_signout" class="signout">Sign Out</a>
</html>
