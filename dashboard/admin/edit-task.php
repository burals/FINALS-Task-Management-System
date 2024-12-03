<?php

require_once 'authentication/admin-class.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    $stmt = $admin->runQuery("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute([':id' => $taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        die('Task not found.');
    }
}

// Fetch the list of employees
$employeesStmt = $admin->runQuery("SELECT id, fullname, email FROM user");
$employeesStmt->execute();
$employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get currently assigned employees
$assignedStmt = $admin->runQuery("
    SELECT u.id, u.fullname, u.email 
    FROM user u
    JOIN task_assignments ta ON ta.employee_id = u.id
    WHERE ta.task_id = :task_id
");
$assignedStmt->execute([':task_id' => $taskId]);
$currentAssignedEmployees = $assignedStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['due_date'];
    $dueTime = $_POST['due_time'];
    $dueDatetime = $dueDate . ' ' . $dueTime;
    $newAssignedEmployees = $_POST['assigned_employee'] ?? []; // Array of selected employee IDs
    $status = $_POST['status'];

    if (empty($title) || empty($description) || empty($dueDate) || empty($dueTime)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'edit-task.php?id=$taskId';</script>";
        exit;
    }

    // Update task information
    $updateStmt = $admin->runQuery("
        UPDATE tasks 
        SET title = :title, description = :description, due_date = :due_date, status = :status 
        WHERE id = :id
    ");

    $updateStmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':due_date' => $dueDatetime,
        ':status' => $status,
        ':id' => $taskId
    ]);

    // Handle task assignments
    $deleteStmt = $admin->runQuery("DELETE FROM task_assignments WHERE task_id = :task_id");
    $deleteStmt->execute([':task_id' => $taskId]);

    if (!empty($newAssignedEmployees)) {
        $assignStmt = $admin->runQuery("INSERT INTO task_assignments (task_id, employee_id) VALUES (:task_id, :employee_id)");
        foreach ($newAssignedEmployees as $employeeId) {
            $assignStmt->execute([
                ':task_id' => $taskId,
                ':employee_id' => $employeeId
            ]);
        }
    }

    header('Location: index.php?success=task_updated');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../../src/css/edit.css">
</head>
<body>
   
</body>
</html>
