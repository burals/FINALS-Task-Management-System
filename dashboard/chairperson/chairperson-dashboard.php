<?php
    include_once '../../database/dbconnection.php';
    include_once '../admin/authentication/admin-class.php';
    include_once '../../config/settings-configuration.php';

    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    // Fetch user data
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $_SESSION['adminSession']));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch key metrics
$total_users_stmt = $admin->runQuery("SELECT COUNT(*) AS total_users FROM user WHERE status = 'active'");
$total_users_stmt->execute();
$total_users = $total_users_stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$total_tasks_stmt = $admin->runQuery("SELECT COUNT(*) AS total_tasks FROM tasks");
$total_tasks_stmt->execute();
$total_tasks = $total_tasks_stmt->fetch(PDO::FETCH_ASSOC)['total_tasks'];

$completed_tasks_stmt = $admin->runQuery("SELECT COUNT(*) AS completed_tasks FROM tasks WHERE status = 'completed'");
$completed_tasks_stmt->execute();
$completed_tasks = $completed_tasks_stmt->fetch(PDO::FETCH_ASSOC)['completed_tasks'];

// Fetch recent tasks based on updated_at
$recent_tasks_stmt = $admin->runQuery("
    SELECT title, description, status, updated_at 
    FROM tasks 
    ORDER BY updated_at DESC 
    LIMIT 5
");
$recent_tasks_stmt->execute();
$recent_tasks = $recent_tasks_stmt->fetchAll(PDO::FETCH_ASSOC);

$pending_tasks_stmt = $admin->runQuery("
SELECT COUNT(*) AS pending_count 
FROM tasks 
WHERE status = 'pending'
");
$pending_tasks_stmt->execute();
$pending_count = $pending_tasks_stmt->fetch(PDO::FETCH_ASSOC)['pending_count'];

    // Fetch task data and assigned employees
    $tasks = $admin->runQuery("
        SELECT t.*, 
               GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
        FROM tasks t
        LEFT JOIN task_assignments ta ON t.id = ta.task_id
        LEFT JOIN user u ON ta.employee_id = u.id
        GROUP BY t.id
    ");
    $tasks->execute();
    
    // Fetch all employees for the task creation form
    $employees = $admin->runQuery("SELECT id, fullname FROM user WHERE role = 'employee'");
    $employees->execute();
    ?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairperson Dashboard</title>
    <link rel="stylesheet" href="../../src/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="side-bar">
<img class="profile-pic" src="profile-picture.jpg" alt="Profile Picture">
<span class="user-indicator">ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    <h3><a href="index.php" class="active">DASHBOARD</a></h3>
    <h3><a href="add-task.php">ADD TASK</a></h3>
    <h3><a href="task-list.php">TASK LIST</a></h3>
    <h3><a href="profile.php">PROFILE</a></h3>
    <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
    </div>
   
    <div class="content">
    <h1>Welcome, <?= htmlspecialchars($user_data['fullname']); ?>!</h1>
    <p>Today's Date: <?= date("F j, Y"); ?></p>

    <!-- Key Metrics Section -->
    <div class="metrics">
        <div class="metric-card">
            <h2>Total Users</h2>
            <p><?= $total_users; ?></p>
        </div>
        <div class="metric-card">
            <h2>Total Tasks</h2>
            <p><?= $total_tasks; ?></p>
        </div>
        <div class="metric-card">
            <h2>Completed Tasks</h2>
            <p><?= $completed_tasks; ?></p>
        </div>
        <div class="metric-cards">
    <div class="metric-card">
        <h2>Pending Tasks</h2>
        <p><?= $pending_count; ?></p>
    </div>
</div>

    </div>

    <!-- Recent Activities Section -->
    <h2>Recent Activities</h2>
    <div class="recent-activities">

<ul>
    <?php foreach ($recent_tasks as $task): ?>
        <li>
            <strong>Task:</strong> <?= htmlspecialchars($task['title']); ?> - 
            <strong>Status:</strong> <?= htmlspecialchars($task['status']); ?> <br>
            <strong>Updated:</strong> <?= date("F j, Y, g:i a", strtotime($task['updated_at'])); ?>
        </li>
    <?php endforeach; ?>
</ul></div>
</div>

</body>
</html>