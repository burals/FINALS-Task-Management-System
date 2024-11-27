<?php
session_start();

// Include the Database class file
require '../../database/dbconnection.php';

// Create an instance of the Database class and establish a connection
$dbInstance = new Database();
$pdo = $dbInstance->dbConnection();

// Ensure the user is logged in
if (!isset($_SESSION['adminSession']) || $_SESSION['adminSession'] !== 56) {
    echo "<script>alert('Unauthorized access.'); window.location.href = '../../index.php';</script>";
    exit;
}

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../../index.php");
    exit;
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'] ?? 0;
$query = $pdo->prepare("SELECT fullname, email FROM user WHERE id = :id LIMIT 1");
$query->execute(['id' => $user_id]);
$userData = $query->fetch(PDO::FETCH_ASSOC);

// Fetch tasks assigned to the user
$tasksQuery = $pdo->prepare("
    SELECT t.id, t.title, t.description, t.due_date, t.status
    FROM tasks t
    JOIN task_assignments ta ON t.id = ta.task_id
    WHERE ta.employee_id = :employee_id
");
$tasksQuery->execute(['employee_id' => $user_id]);
$tasks = $tasksQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch notifications (logs for the user)
$notificationsQuery = $pdo->prepare("
    SELECT activity, created_at 
    FROM logs 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC
");
$notificationsQuery->execute(['user_id' => $user_id]);
$notifications = $notificationsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../../../src/css/user-dashboard.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="#tasks">Tasks</a></li>
                <li><a href="#notifications">Notifications</a></li>
                <li><a href="#profile">Profile</a></li>
            </ul>
            <form method="post">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </div>

        <div class="content">
            <!-- Tasks Section -->
            <section id="tasks">
                <h3>Your Tasks</h3>
                <?php if (count($tasks) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                                    <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                                    <td><?php echo htmlspecialchars($task['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No tasks assigned.</p>
                <?php endif; ?>
            </section>

            <!-- Notifications Section -->
            <section id="notifications">
                <h3>Notifications</h3>
                <ul>
                    <?php if (count($notifications) > 0): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <li><?php echo htmlspecialchars($notification['activity']); ?> (<?php echo htmlspecialchars($notification['created_at']); ?>)</li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No notifications available.</p>
                    <?php endif; ?>
                </ul>
            </section>

            <!-- Profile Section -->
            <section id="profile">
                <h3>Your Profile</h3>
                <div class="profile-info">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($userData['fullname'] ?? 'Unknown'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email'] ?? 'Unknown'); ?></p>
                    <a href="edit-profile.php">Edit Profile</a>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
