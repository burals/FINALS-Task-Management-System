<?php
// Include the Database and Authentication Classes
include_once '../../database/dbconnection.php';
include_once '../admin/authentication/admin-class.php';
include_once '../../config/settings-configuration.php';

// Create an instance of the Database class and establish a connection
$db = new Database();
$conn = $db->dbConnection();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) { 
    die("Access denied! Please log in first.");
}

// Get the logged-in user's full name
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User';

// Fetch tasks assigned to the user
$tasks = $conn->prepare("SELECT * FROM tasks WHERE assigned_employee = :assigned_employee");
$tasks->execute([':assigned_employee' => $fullname]);

// Handle task status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    // Update task status
    $updateTask = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :task_id");
    $updateTask->execute([':status' => $status, ':task_id' => $task_id]);

    // Redirect to refresh the page
    header('Location: task-dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    // Update task status in the database
    $updateTask = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :task_id");
    $updateTask->execute([':status' => $status, ':task_id' => $task_id]);

    // Redirect to the same page to refresh the task list
    header('Location: task-dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fullname); ?> Dashboard</title>
    <link rel="stylesheet" href="../../src/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="nav-container">
    <nav class="navbar">
        <p>Logged in as: user <strong><?php echo htmlspecialchars($fullname); ?></strong></p>
        <ul>
            <a href="../admin/authentication/admin-class.php?admin_signout" class="signout">Sign Out</a>
        </ul>
    </nav>
</div>

<h1>Welcome to Your Dashboard</h1>
<h2>Your Tasks</h2>

<div class="container">
    <div class="task-list">
        <h2>Your Assigned Tasks</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tasks->rowCount() > 0): ?>
                    <?php while ($task = $tasks->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $task['id']; ?></td>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td><?= $task['due_date']; ?></td>
                            <td><?= htmlspecialchars($task['status']); ?></td>
                            <td>
                                <?php if ($task['status'] != 'Completed'): ?>
                                    <form method="POST" action="task-dashboard.php">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <select name="status" required>
                                            <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                        <button type="submit" name="update_task_status">Update Status</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No tasks assigned yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div>
    
</div>
</body>
</html>
