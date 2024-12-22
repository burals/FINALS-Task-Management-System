<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Fetch user data
$user_data = $admin->getUserById($_SESSION['adminSession']);

// Fetch active employees for the dropdown
$activeEmployees = $admin->getActiveEmployees();


// Set profile picture path (check if a profile picture exists)
$profilePicturePath = "../uploads/" . $_SESSION['adminSession'] . "/profile.jpg";
if (!file_exists($profilePicturePath)) {
    $profilePicturePath = "profile-picture.jpg"; // Fallback if no profile picture is set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="../../src/css/add-task.css">
    <link rel="icon" href="../../src/css/img/CCS-LOGO.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="side-bar">
<img class="profile-pic" src="<?= $profilePicturePath; ?>" alt="Profile Picture">
    <span class="user-indicator">ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    <h3><a href="chairperson-dashboard.php">DASHBOARD</a></h3>
    <h3><a href="add-task.php" class="active">ADD TASK</a></h3>
    <h3><a href="task-list.php">TASK LIST</a></h3>
    <h3><a href="profile.php">PROFILE</a></h3>
    <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
</div>
<div class="container">
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
                <select id="assign_employee" name="employee_ids[]" multiple required>
                    <?php foreach ($activeEmployees as $employee): ?>
                        <option value="<?= $employee['id']; ?>"><?= htmlspecialchars($employee['fullname']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Create Task</button>
        </form>
    </div>
</div>

</body>
</html>
