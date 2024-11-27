<?php
require_once 'authentication/admin-class.php';

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

// Fetch the list of employees for the checkbox options
$employeesStmt = $admin->runQuery("SELECT id, fullname FROM user");
$employeesStmt->execute();
$employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get currently assigned employees for this task
$assignedStmt = $admin->runQuery("
    SELECT u.id, u.fullname 
    FROM user u
    JOIN task_assignments ta ON ta.employee_id = u.id
    WHERE ta.task_id = :task_id
");
$assignedStmt->execute([':task_id' => $taskId]);
$assignedEmployees = $assignedStmt->fetchAll(PDO::FETCH_ASSOC);

// If the form is submitted, update the task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['due_date'];
    $assignedEmployees = $_POST['assigned_employee']; // Array of selected employee IDs
    $status = $_POST['status'];

    // Update task information
    $updateStmt = $admin->runQuery("
        UPDATE tasks SET title = :title, description = :description, due_date = :due_date, status = :status 
        WHERE id = :id
    ");
    $updateStmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':due_date' => $dueDate,
        ':status' => $status,
        ':id' => $taskId
    ]);

    // Remove old assignments
    $deleteStmt = $admin->runQuery("DELETE FROM task_assignments WHERE task_id = :task_id");
    $deleteStmt->execute([':task_id' => $taskId]);

    // Add new assignments
    if (!empty($assignedEmployees)) {
        $assignStmt = $admin->runQuery("INSERT INTO task_assignments (task_id, employee_id) VALUES (:task_id, :employee_id)");
        foreach ($assignedEmployees as $employeeId) {
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
    <h2>Edit Task</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        
        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
        
        <label>Due Date:</label>
        <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
        
        <label>Assign to Employees:</label>
        <div class="checkbox-group">
            <?php foreach ($employees as $employee): ?>
                <div>
                    <input type="checkbox" name="assigned_employee[]" value="<?= $employee['id']; ?>" 
                    <?php echo in_array($employee['id'], array_column($assignedEmployees, 'id')) ? 'checked' : ''; ?>>
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
    </form>
</div>
</body>
</html>
