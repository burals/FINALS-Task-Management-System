<?php 
// Include necessary files
require_once '../../database/dbconnection.php';

// Start session (ensure it exists)
session_start();

// Check if user is logged in and authorized as 'chairperson'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'chairperson') {
    header("Location: ../../login.php");
    exit;
}

// Check if task ID is provided
$task_id = $_GET['id'] ?? null;
if (!$task_id) {
    die("No task ID provided.");
}

// Instantiate the Database class
$database = new Database();
$conn = $database->dbConnection();

// Fetch task details
$query = "
    SELECT t.*, GROUP_CONCAT(u.id) AS assigned_employee_ids
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    WHERE t.id = ?
    GROUP BY t.id
";
$stmt = $conn->prepare($query);
$stmt->execute([$task_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission to update task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_task'])) {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $due_date = $_POST['due_date'] ?? '';
        $due_time = $_POST['due_time'] ?? '00:00:00';
        $status = $_POST['status'] ?? 'Pending';
        $employee_ids = $_POST['employee_ids'] ?? [];

        // Combine due date and due time
        $full_due_date = "$due_date $due_time";

        // Update the task
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $description, $full_due_date, $status, $task_id]);

        // Update task assignments
        $conn->prepare("DELETE FROM task_assignments WHERE task_id = ?")->execute([$task_id]);
        foreach ($employee_ids as $employee_id) {
            $conn->prepare("INSERT INTO task_assignments (task_id, employee_id) VALUES (?, ?)")->execute([$task_id, $employee_id]);
        }

        // Redirect with success message
        header("Location: chairperson-dashboard.php?message=Task updated successfully!");
        exit;
    }

    if (isset($_POST['delete_task'])) {
        // Delete the task
        $conn->prepare("DELETE FROM tasks WHERE id = ?")->execute([$task_id]);
        $conn->prepare("DELETE FROM task_assignments WHERE task_id = ?")->execute([$task_id]);

        // Redirect after deletion
        header("Location: chairperson-dashboard.php?message=Task deleted successfully!");
        exit;
    }
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
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title'] ?? '') ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($task['description'] ?? '') ?></textarea>

            <label for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date" value="<?= htmlspecialchars(explode(' ', $task['due_date'])[0] ?? '') ?>" required>

            <label for="due_time">Due Time</label>
            <input type="time" id="due_time" name="due_time" value="<?= htmlspecialchars(explode(' ', $task['due_date'])[1] ?? '00:00:00') ?>" required>


            <label for="employee_ids">Assign Employees</label>
            <select id="employee_ids" name="employee_ids[]" multiple>
                <?php
                $employees = $conn->query("SELECT id, fullname FROM user")->fetchAll(PDO::FETCH_ASSOC);
                $assigned_employee_ids = explode(',', $task['assigned_employee_ids'] ?? '');
                foreach ($employees as $employee) {
                    $selected = in_array($employee['id'], $assigned_employee_ids) ? 'selected' : '';
                    echo "<option value=\"{$employee['id']}\" $selected>{$employee['fullname']}</option>";
                }
                ?>
            
            </select>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Pending" <?= ($task['status'] === 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="In Progress" <?= ($task['status'] === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= ($task['status'] === 'Completed') ? 'selected' : '' ?>>Completed</option>
            </select>

            <button type="submit" name="update_task" class="btn btn-success">Update Task</button>
            <a href="delete-task.php?task_id=<?= htmlspecialchars($task['id']); ?>" 
   class="delete-btn" 
   onclick="return confirm('Are you sure you want to delete this task and all associated reports?');">
   Delete</a>
            <a href="chairperson-dashboard.php" class="back-btn">Back</a>
        </form>
        
     
    </div>
</body>
</html>
