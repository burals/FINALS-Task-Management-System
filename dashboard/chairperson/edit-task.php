<?php
include_once '../../database/dbconnection.php';

// Check if task ID is provided
if (!isset($_GET['id'])) {
    die("Task ID is required.");
}

// Get the task ID
$taskId = intval($_GET['id']);

// Fetch task details
$database = new Database();
$conn = $database->dbConnection();
$query = "SELECT * FROM tasks WHERE id = :task_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    die("Task not found.");
}

// Process the form submission to update task
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    // Update the task details (only due date and status)
    $updateQuery = "UPDATE tasks SET due_date = :due_date, status = :status WHERE id = :task_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':due_date', $due_date);
    $updateStmt->bindParam(':status', $status);
    $updateStmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "Task updated successfully.";
        header("Location: chairperson-dashboard.php"); // Redirect after update
    } else {
        echo "Failed to update task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #95a5a6;
        }
        .cancel-button {
            background-color: #95a5a6;
            margin-top: 10px;
        }
        .cancel-button:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Task</h2>
    <form method="POST">
        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required><br>

        <label for="status">Status:</label>
        <select name="status">
            <option value="pending" <?php echo ($task['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo ($task['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
        </select><br>

        <button type="submit">Update Task</button>
        <button type="button" class="cancel-button" onclick="window.location.href='chairperson-dashboard.php';">Cancel</button>
    </form>
</div>

</body>
</html>
