<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Fetch task details
    $stmt = $admin->runQuery("SELECT * FROM tasks WHERE id = :task_id");
    $stmt->execute([':task_id' => $task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        die('Task not found.');
    }

    // Fetch assigned employees
    $assignedStmt = $admin->runQuery("
        SELECT u.fullname 
        FROM user u
        JOIN task_assignments ta ON ta.employee_id = u.id
        WHERE ta.task_id = :task_id
    ");
    $assignedStmt->execute([':task_id' => $task_id]);
    $assignedEmployees = $assignedStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link rel="stylesheet" href="../../src/css/task-details.css">
</head>
<body>
<nav class="navbar">
    <div class="left">
        <span class="user-indicator">User: <?= htmlspecialchars($user_data['fullname']); ?></span>
    </div>
    <ul>
        <li><a href="authentication/admin-class.php?user_signout" class="signout">Sign Out</a></li>
    </ul>
</nav>

<div class="task-details">
    <h2>Task: <?= htmlspecialchars($task['title']); ?></h2>
    <p><strong>Description:</strong> <?= htmlspecialchars($task['description']); ?></p>
    <p><strong>Due Date:</strong> <?= $task['due_date']; ?></p>
    <p><strong>Status:</strong> <?= $task['status']; ?></p>
    <p><strong>Assigned Employees:</strong> 
        <?php foreach ($assignedEmployees as $employee): ?>
            <?= htmlspecialchars($employee['fullname']); ?><br>
        <?php endforeach; ?>
    </p>
</div>

</body>
</html>
