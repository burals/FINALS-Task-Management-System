<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
require_once 'authentication/admin-class.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once '../../src/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize admin and check login
$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Initialize variables
$taskId = null;
$task = null;
$employees = [];
$currentAssignedEmployees = [];

// Validate and fetch task
if (isset($_GET['id'])) {
    $taskId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if ($taskId) {
        try {
            // Fetch task details
            $stmt = $admin->runQuery("SELECT * FROM tasks WHERE id = :id");
            $stmt->execute([':id' => $taskId]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) {
                die('Task not found.');
            }

            // Fetch all employees
            $employeesStmt = $admin->runQuery("SELECT id, fullname, email FROM user");
            $employeesStmt->execute();
            $employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch currently assigned employees
            $assignedStmt = $admin->runQuery("
                SELECT u.id, u.fullname, u.email 
                FROM user u
                JOIN task_assignments ta ON ta.employee_id = u.id
                WHERE ta.task_id = :task_id
            ");
            $assignedStmt->execute([':task_id' => $taskId]);
            $currentAssignedEmployees = $assignedStmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        die('Invalid task ID.');
    }
} else {
    die('No task ID provided.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $dueDate = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_STRING);
    $dueTime = filter_input(INPUT_POST, 'due_time', FILTER_SANITIZE_STRING);
    $dueDatetime = $dueDate . ' ' . $dueTime;
    $newAssignedEmployees = $_POST['assigned_employee'] ?? [];
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($title) || empty($description) || empty($dueDate) || empty($dueTime)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'edit-task.php?id=$taskId';</script>";
        exit;
    }

    try {
        // Update task information
        $updateStmt = $admin->runQuery("
            UPDATE tasks SET title = :title, description = :description, due_date = :due_date, status = :status 
            WHERE id = :id
        ");
        $updateStmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':due_date' => $dueDatetime,
            ':status' => $status,
            ':id' => $taskId
        ]);

        // Identify removed employees
        $removedEmployees = [];
        foreach ($currentAssignedEmployees as $employee) {
            if (!in_array($employee['id'], $newAssignedEmployees)) {
                $removedEmployees[] = $employee;
            }
        }

        // Send email to removed employees
        foreach ($removedEmployees as $employee) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@gmail.com'; // Replace with your email
                $mail->Password = 'your-app-password'; // Use an app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email content
                $mail->setFrom('your-email@gmail.com', 'Task Management System');
                $mail->addAddress($employee['email'], $employee['fullname']);
                $mail->Subject = 'Task Update Notification';
                $mail->Body = "Dear {$employee['fullname']},\n\nYou have been removed from the task: {$task['title']}.\n\nRegards,\nTask Management Team";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mail Error: {$mail->ErrorInfo}");
            }
        }

        // Remove old assignments
        $deleteStmt = $admin->runQuery("DELETE FROM task_assignments WHERE task_id = :task_id");
        $deleteStmt->execute([':task_id' => $taskId]);

        // Add new assignments
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

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "<script>alert('An error occurred while updating the task.'); window.location.href = 'edit-task.php?id=$taskId';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <h2>Edit Task</h2>
    
    <?php if ($task): ?>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']); ?>">
        <link rel="stylesheet" href="/src/css/edit.css">
        
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" 
                   value="<?= htmlspecialchars($task['title']); ?>" required>
        </div>
        
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= 
                htmlspecialchars($task['description']); 
            ?></textarea>
        </div>
        
        <div>
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" 
                   value="<?= date('Y-m-d', strtotime($task['due_date'])); ?>" required>
        </div>
        
        <div>
            <label for="due_time">Due Time:</label>
            <input type="time" id="due_time" name="due_time" 
                   value="<?= date('H:i', strtotime($task['due_date'])); ?>" required>
        </div>
        
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : ''; ?>>
                    Pending
                </option>
                <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : ''; ?>>
                    In Progress
                </option>
                <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : ''; ?>>
                    Completed
                </option>
            </select>
        </div>
        
        <div>
            <label>Assign Employees:</label>
            <?php foreach ($employees as $employee): ?>
                <div>
                    <input type="checkbox" 
                           name="assigned_employee[]" 
                           value="<?= $employee['id']; ?>"
                           <?php 
                           $isAssigned = in_array(
                               $employee['id'], 
                               array_column($currentAssignedEmployees, 'id')
                           );
                           echo $isAssigned ? 'checked' : ''; 
                           ?>>
                    <?= htmlspecialchars($employee['fullname']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div>
            <input type="submit" value="Update Task">
        </div>
    </form>
    <?php else: ?>
        <p>No task found.</p>
    <?php endif; ?>
</body>
</html>