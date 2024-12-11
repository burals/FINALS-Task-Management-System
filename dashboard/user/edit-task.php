<?php
require_once '../user/user-class.php';

$user = new USER();
if (!$user->isUserLoggedIn()) {
    $user->redirect('../../');
}

// Fetch user data
$stmt = $user->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the task to edit
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $user->runQuery("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute([':id' => $task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle task update
if (isset($_POST['update_task'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $user->runQuery("UPDATE tasks SET title = :title, description = :description, due_date = :due_date, status = :status WHERE id = :id");
    $stmt->execute([':title' => $title, ':description' => $description, ':due_date' => $due_date, ':status' => $status, ':id' => $task_id]);

    // Redirect after updating the task
    $user->redirect("task-management.php?success=task_updated");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
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
    <!-- Task Edit Form -->
    <div class="task-creation">
        <h2>Edit Task</h2>
        <form method="POST" action="edit-task.php?task_id=<?= $task['id']; ?>">
            <label for="title">Task Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($task['title']); ?>" required>

            <label for="description">Task Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($task['description']); ?></textarea>

            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date" value="<?= $task['due_date']; ?>" required>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            </select>

            <button type="submit" name="update_task">Update Task</button>
        </form>
    </div>
</div>

</body>
</html>
