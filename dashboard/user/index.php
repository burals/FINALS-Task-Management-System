<?php
// Start session
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied! Please log in first.");
}

// Check if the role is user
if ($_SESSION['role'] !== 'user') {
    die("Access denied! You are not authorized to access this page.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database_name');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to Your Dashboard</h1>
    <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

    <h2>Your Tasks</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($task = $result->fetch_assoc()): ?>
                <li>
                    <strong>Task:</strong> <?php echo htmlspecialchars($task['task_description']); ?> 
                    - <strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No tasks assigned yet.</p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
<?php
// Close database connection
$stmt->close();
$conn->close();
?>
