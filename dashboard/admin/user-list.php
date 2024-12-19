<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    // Fetch user data
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $_SESSION['adminSession']));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

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

    $user_list = $admin->runQuery("SELECT id, fullname, role FROM user");
$user_list->execute();
    // Fetch all employees for the task creation form
    $employees = $admin->runQuery("SELECT * FROM user");
    $employees->execute();

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../src/css/task-list.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="side-bar">
<img class="profile-pic" src="profile-picture.jpg" alt="Profile Picture">
<span class="user-indicator">ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    <h3><a href="index.php">DASHBOARD</a></h3>
    <h3><a href="add-task.php">ADD TASK</a></h3>
    <h3><a href="task-list.php">TASK LIST</a></h3>
    <h3><a href="user-list.php" class="active">USER LIST</a></h3>
    <h3><a href="profile.php">PROFILE</a></h3>
    <h3><a href=".php">SIGN OUT</a></h3>
</div>


</body>
</html>