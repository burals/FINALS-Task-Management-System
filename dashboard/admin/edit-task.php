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

    // Fetch task details
    $stmt = $admin->runQuery("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute([':id' => $taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        die('Task not found.');
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
} else {
    die("Task ID not provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['due_date'];
    $dueTime = $_POST['due_time'];
    $dueDatetime = $dueDate . ' ' . $dueTime;
    $newAssignedEmployees = isset($_POST['assigned_employee']) ? $_POST['assigned_employee'] : []; // Array of selected employee IDs
    $status = $_POST['status'];

    if (empty($title) || empty($description) || empty($dueDate) || empty($dueTime)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'edit-task.php?id=$taskId';</script>";
        exit;
    }

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

    // Identify newly assigned employees using array_diff
    $newlyAssignedEmployees = array_diff($newAssignedEmployees, array_column($currentAssignedEmployees, 'id'));

    // Send email to removed employees
    foreach ($removedEmployees as $employee) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'wrenchnerbangit@gmail.com'; // Replace with your email
            $mail->Password = 'zapq uiqd mdjn axss'; // Replace with your email password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            // Email content
            $mail->setFrom('wrenchnerbangit@gmail.com', 'CCS Task Management System');
            $mail->addAddress($employee['email'], $employee['fullname']);
            $mail->Subject = 'Task Update Notification';
            $mail->Body = "Dear {$employee['fullname']},\n\nYou have been removed from the task: {$task['title']}.\n\nRegards,\n CCS Task Management Team";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Send email to newly assigned employees
    foreach ($newlyAssignedEmployees as $employeeId) {
        $stmtEmployee = $admin->runQuery("SELECT email, fullname FROM user WHERE id = :id");
        $stmtEmployee->execute([':id' => $employeeId]);
        $employee = $stmtEmployee->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'wrenchnerbangit@gmail.com'; // Replace with your email
                $mail->Password = 'zapq uiqd mdjn axss'; // Replace with your email password
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;

                // Email content
                $mail->setFrom('wrenchnerbangit@gmail.com', 'CCS Task Management System');
                $mail->addAddress($employee['email'], $employee['fullname']);
                $mail->Subject = 'Task Update Notification';
                $mail->Body = "Dear {$employee['fullname']},\n\nYou have been assigned a new task: {$task['title']}.\n\nRegards,\n CCS Task Management Team";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
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
<div class="form-container">
    <h2>Edit Task: <?= htmlspecialchars($task['title'] ?? 'Unknown Task') ?></h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        
        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
        
        <label>Due Date:</label>
        <input type="date" name="due_date" value="<?php echo htmlspecialchars(explode(' ', $task['due_date'])[0]); ?>" required>
        
        <label>Due Time:</label>
        <input type="time" name="due_time" value="<?php echo htmlspecialchars(explode(' ', $task['due_date'])[1]); ?>" required>
        
        <label>Assign to Employees:</label>
        <div class="checkbox-group">
            <?php foreach ($employees as $employee): ?>
                <div>
                    <input 
                        type="checkbox" 
                        id="employee_<?= $employee['id']; ?>" 
                        name="assigned_employee[]" 
                        value="<?= $employee['id']; ?>" 
                        <?= in_array($employee['id'], array_column($currentAssignedEmployees, 'id')) ? 'checked' : ''; ?>
                    >
                    <label for="employee_<?= $employee['id']; ?>"><?= htmlspecialchars($employee['fullname']); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        
        <label>Status:</label>
        <select name="status" required>
            <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
            <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
        </select>
        
        <button type="submit">Update Task</button>
        <a href="delete-task.php?task_id=<?= htmlspecialchars($task['id']); ?>" 
   class="delete-btn" 
   onclick="return confirm('Are you sure you want to delete this task and all associated reports?');">
   Delete</a>
    <a href="index.php" class="back-btn">Back</a>
    </form>
    
</div>
</body>

</html>
