<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Fetch user data
$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Define the profile picture path (assuming it's stored in the 'uploads/{user_id}/profile.jpg')
$profilePicturePath = "../uploads/" . $user_data['id'] . "/profile.jpg";

// Check if the profile picture exists, otherwise use a default image
if (!file_exists($profilePicturePath)) {
    $profilePicturePath = "default-profile.jpg"; // Set your default profile picture
}

// Fetch assigned tasks for the user
$tasks = $admin->runQuery("
    SELECT t.*, 
           GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    WHERE ta.employee_id = :id
    GROUP BY t.id
");
$tasks->execute(array(":id" => $user_data['id']));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../../src/css/user-dashboard.css">
    <link rel="stylesheet" href="../../src/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <div class="side-bar">
        <img class="profile-pic" src="<?= htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture">
        <span class="user-indicator"><?= strtoupper($user_data['role']) ?> <?= htmlspecialchars($user_data['fullname']); ?></span>
        <h3><a href="user-dashboard.php" class="active">DASHBOARD</a></h3>
        <h3><a href="my-task.php">MY TASKS</a></h3>
        <h3><a href="profile.php">PROFILE</a></h3>
        <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome, <?= htmlspecialchars($user_data['fullname']); ?></h1>

        <h2>Your Assigned Tasks</h2>
        <?php if ($tasks->rowCount() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Assigned Employees</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $tasks->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['id']); ?></td>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td><?= htmlspecialchars($task['due_date']); ?></td>
                            <td><?= htmlspecialchars($task['status']); ?></td>
                            <td><?= htmlspecialchars($task['assigned_employees']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks assigned to you.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript to Toggle Sidebar on Mobile -->
    <script>
        function toggleSidebar() {
            var sidebar = document.querySelector('.side-bar');
            sidebar.classList.toggle('active');
        }
    </script>

</body>

</html>