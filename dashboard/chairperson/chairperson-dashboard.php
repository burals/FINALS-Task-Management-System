<?php
// Include necessary files
include_once '../../database/dbconnection.php';  // Ensure the path is correct
include_once '../admin/authentication/admin-class.php';
include_once '../../config/settings-configuration.php';


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) { 
    die("Access denied! Please log in first.");
}

// Check if the role is 'chairperson'
if ($_SESSION['role'] !== 'chairperson') {
    die("Access denied! You are not authorized to access this page.");
}
if (isset($_GET['message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
}
// Ensure fullname is set
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'chairperson';

// Instantiate Database class and get the connection
$database = new Database();
$conn = $database->dbConnection(); // PDO connection

// Fetch task data with assigned employees from the database
$query = "
    SELECT t.id, 
           t.title, 
           t.description, 
           t.due_date, 
           GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees, 
           t.status, 
           t.created_at
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    GROUP BY t.id
    ORDER BY t.created_at DESC
";
$stmt = $conn->prepare($query); // Use PDO prepare
$stmt->execute(); // Execute the statement

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairperson Dashboard</title>
 <link rel="stylesheet" href="../../src/css/chair-dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="nav-container">
    <nav class="navbar">
        <p>Logged in as: <strong><?php echo htmlspecialchars($fullname); ?></strong></p>
        <ul>
            <a href="../admin/authentication/admin-class.php?admin_signout" class="signout">Sign Out</a>
        </ul>
    </nav>
</div>

<h1>Welcome, <?php echo htmlspecialchars($fullname); ?></h1>

<h2>Dashboard</h2>

<div class="task-container">
    <?php if ($stmt->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Assigned Employees</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['id'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($task['title'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($task['description'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($task['due_date'] ?? ''); ?></td>
                        <td><?php echo !empty($task['assigned_employees']) ? htmlspecialchars($task['assigned_employees']) : 'No employees assigned'; ?></td>
                        <td><?php echo htmlspecialchars($task['status'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($task['created_at'] ?? ''); ?></td>
                        <td>
    <?php if (!empty($task['id'])): ?>
        <a href="edit-task.php?id=<?php echo htmlspecialchars($task['id']); ?>" class="btn btn-primary">Edit</a>
        <a href="view-reports.php?task_id=<?php echo htmlspecialchars($task['id']); ?>" class="btn btn-secondary">View Reports</a>
    <?php else: ?>
        No actions available
    <?php endif; ?>
</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tasks assigned yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
