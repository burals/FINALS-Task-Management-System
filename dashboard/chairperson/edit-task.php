<?php
require_once '../admin/authentication/admin-class.php';

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

    // Fetch active employees
    $employees = $admin->getActiveUsers();

    // Fetch currently assigned employees
    $currentAssignedEmployees = $admin->getAssignedUsers($taskId);
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
    $newAssignedEmployees = isset($_POST['assigned_employee']) ? $_POST['assigned_employee'] : [];
    $status = $_POST['status'];

    if (empty($title) || empty($description) || empty($dueDate) || empty($dueTime)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'edit-task.php?id=$taskId';</script>";
        exit;
    }

    // Update task information
    $admin->updateTask($taskId, $title, $description, $dueDatetime, $status);

    // Update employee assignments
    $admin->updateTaskAssignments($taskId, $newAssignedEmployees, $currentAssignedEmployees);

    header('Location: task-list.php?success=task_updated');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../src/css/img/CCS-LOGO.png" type="image/x-icon">
    <link rel="stylesheet" href="../../src/css/edit.css">
    <title>Edit Task</title>
</head>
<body>
<div class="form-container">
    <h2>Edit Task: <?= htmlspecialchars($task['title'] ?? 'Unknown Task') ?></h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($task['title']); ?>" required>
        
        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($task['description']); ?></textarea>
        
        <label>Due Date:</label>
        <input type="date" name="due_date" value="<?= htmlspecialchars(explode(' ', $task['due_date'])[0]); ?>" required>
        
        <label>Due Time:</label>
        <input type="time" name="due_time" value="<?= htmlspecialchars(explode(' ', $task['due_date'])[1]); ?>" required>
        
        <label>Assign to Employees:</label>
        <select name="assigned_employee[]" multiple size="5" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['id']; ?>" 
                    <?= in_array($employee['id'], array_column($currentAssignedEmployees, 'id')) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($employee['fullname']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
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
        <a href="task-list.php" class="back-btn">Back</a>
    </form>
</div>
</body>
</html>
