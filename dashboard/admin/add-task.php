<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    // Fetch user data
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $_SESSION['adminSession']));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch task data and assigned employees
    $tasks = $admin->runQuery("
    SELECT t.*, 
           GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    GROUP BY t.id
    ");
    $tasks->execute();

    $user_list = $admin->runQuery("SELECT id, fullname, role FROM user");
$user_list->execute();
    // Fetch all employees for the task creation form
    $employees = $admin->runQuery("SELECT * FROM user");
    $employees->execute();

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../src/css/add-task.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="side-bar">
<img class="profile-pic" src="profile-picture.jpg" alt="Profile Picture">
<span class="user-indicator">ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    <h3><a href="index.php">DASHBOARD</a></h3>
    <h3><a href="add-task.php" class="active">ADD TASK</a></h3>
    <h3><a href="task-list.php">TASK LIST</a></h3>
    <h3><a href="user-list.php">USER LIST</a></h3>
    <h3><a href="profile.php">PROFILE</a></h3>
    <h3><a href=".php">SIGN OUT</a></h3>
</div>
<div class="container">
    <!-- Left Column: Task Creation Form -->
    <div class="task-form">
        <h2>Create a New Task</h2>
        <form action="process-task.php" method="POST">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" placeholder="Enter task title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter task description" required></textarea>
            </div>
            <div class="form-group">
    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" required>
    <label for="due_time">Time</label>
    <input type="time" id="due_time" name="due_time" required>
</div>

<div class="form-group">
                <label for="assign_employee">Assign to Employees</label>
                <div id="assign_employee" class="checkbox-group">
                    <?php while ($employee = $employees->fetch(PDO::FETCH_ASSOC)): ?>
                        <div>
                            <input type="checkbox" id="employee_<?= $employee['id']; ?>" name="employee_ids[]" value="<?= $employee['id']; ?>">
                            <label for="employee_<?= $employee['id']; ?>"><?= htmlspecialchars($employee['fullname']); ?></label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <button type="submit" class="btn-submit">Create Task</button>
        </form>
    </div>

<div class="feedback">
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'task_updated'): ?>
            <div class="alert success">Task updated successfully!</div>
        <?php elseif ($_GET['success'] == 'task_deleted'): ?>
            <div class="alert success">Task deleted successfully!</div>
        <?php elseif ($_GET['success'] == 'task_created'): ?>
            <div class="alert success">Task created successfully!</div>
        <?php endif; ?>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
</div>

</body>
</html>