<?php
require_once 'authentication/user-class.php';

$user = new USER();
if (!$user->isUserLoggedIn()) {
    $user->redirect('../../');
}

// Fetch user data
$stmt = $user->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle task creation
if (isset($_POST['submit_task'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $user->runQuery("INSERT INTO tasks (title, description, due_date, status) VALUES (:title, :description, :due_date, :status)");
    $stmt->execute([':title' => $title, ':description' => $description, ':due_date' => $due_date, ':status' => $status]);

    // Redirect after creating the task
    $user->redirect("task-management.php?success=task_created");
}

// Fetch tasks created by the user (optional)
$tasks = $user->runQuery("SELECT * FROM tasks WHERE created_by = :created_by");
$tasks->execute([':created_by' => $user_data['id']]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link rel="stylesheet" href="../../src/css/task-management.css">
</head>
<body>
<nav class="navbar">
    <div class="left">
        <span class="user-indicator">Logged in as: <?= htmlspecialchars($user_data['fullname']); ?></span>
    </div>
    <ul>
        <li><a href="authentication/user-class.php?user_signout" class="signout">Sign Out</a></li>
    </ul>
</nav>

<div class="container">
    <!-- Task Creation Form -->
    <div class="task-creation">
        <h2>Create New Task</h2>
        <form method="POST" action="task-management.php">
            <label for="title">Task Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Task Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date" required>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>

            <button type="submit" name="submit_task">Create Task</button>
        </form>
    </div>

    <!-- Tasks List -->
    <div class="task-list">
        <h2>Your Tasks</h2>
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
                <?php while ($task = $tasks->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $task['id']; ?></td>
                        <td><?= htmlspecialchars($task['title']); ?></td>
                        <td><?= htmlspecialchars($task['description']); ?></td>
                        <td><?= $task['due_date']; ?></td>
                        <td><?= htmlspecialchars($task['status']); ?></td>
                        <td>
                            <a href="edit-task.php?task_id=<?= $task['id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete-task.php?task_id=<?= $task['id']; ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Success Message -->
<?php if (isset($_GET['success']) && $_GET['success'] == 'task_created'): ?>
    <div class="alert success">Task created successfully!</div>
<?php endif; ?>

</body>
</html>
