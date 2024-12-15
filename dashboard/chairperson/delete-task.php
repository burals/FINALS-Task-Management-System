<?php
// Include the database connection file
include_once '../../database/dbconnection.php';

// Check if the `id` parameter exists in the URL
if (!isset($_GET['id'])) {
    die("Task ID is required.");
}

$taskId = intval($_GET['id']); // Sanitize task ID
$database = new Database();
$conn = $database->dbConnection();

// Fetch task details along with assigned employees
$query = "
    SELECT t.title, 
           t.description, 
           t.due_date, 
           GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    WHERE t.id = :task_id
    GROUP BY t.id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    $taskTitle = htmlspecialchars($task['title']);
    $taskDescription = htmlspecialchars($task['description']);
    $taskDueDate = htmlspecialchars($task['due_date']);
    $assignedEmployees = htmlspecialchars($task['assigned_employees'] ?: 'No employees assigned');
} else {
    die("Task not found.");
}

// Handle task deletion when the delete button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "DELETE FROM tasks WHERE id = :task_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the dashboard after deletion
        header("Location: chairperson-dashboard.php");
        exit();
    } else {
        echo "Failed to delete the task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Task</title>
    <style>
        /* Your existing CSS */
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
            max-width: 600px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .task-detail {
            margin-bottom: 15px;
        }
        .task-detail strong {
            font-weight: bold;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        button, .cancel-btn {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button {
            background-color: #2c3e50;
            color: white;
        }
        button:hover {
            background-color: #34495e;
        }
        .cancel-btn {
            background-color: #bdc3c7;
            text-align: center;
            line-height: 38px; /* Adjusted to center text */
            color: #2c3e50;
            text-decoration: none;
        }
        .cancel-btn:hover {
            background-color: #95a5a6;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Delete Task</h1>
    <!-- Display task details -->
    <div class="task-detail">
        <p><strong>Title:</strong> <?php echo $taskTitle; ?></p>
        <p><strong>Description:</strong> <?php echo $taskDescription; ?></p>
        <p><strong>Due Date:</strong> <?php echo $taskDueDate; ?></p>
        <p><strong>Assigned Employees:</strong> <?php echo $assignedEmployees; ?></p>
    </div>
    <form method="POST">
        <button type="submit">Delete Task</button>
        <a href="chairperson-dashboard.php" class="cancel-btn">Cancel</a>
    </form>
</div>
</body>
</html>
