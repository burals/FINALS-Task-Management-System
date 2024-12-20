<?php
    include_once '../../database/dbconnection.php';
    include_once '../admin/authentication/admin-class.php';
    include_once '../../config/settings-configuration.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Fetch user data
$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize filters
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_date = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

// Build the SQL query based on filters
$sql = "
SELECT t.*, 
       GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
FROM tasks t
LEFT JOIN task_assignments ta ON t.id = ta.task_id
LEFT JOIN user u ON ta.employee_id = u.id
";

$conditions = [];
$params = [];

// Add filters to SQL
if (!empty($filter_status)) {
    $conditions[] = "t.status = :status";
    $params[':status'] = $filter_status;
}

if ($filter_date === 'overdue') {
    $conditions[] = "t.due_date < CURDATE() AND t.status != 'Completed'";
} elseif ($filter_date === 'today') {
    $conditions[] = "DATE(t.created_at) = CURDATE()";
} elseif ($filter_date === 'this_week') {
    $conditions[] = "WEEK(t.created_at) = WEEK(CURDATE()) AND YEAR(t.created_at) = YEAR(CURDATE())";
} elseif ($filter_date === 'completed') {
    $conditions[] = "t.status = 'Completed'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY t.id";

$tasks = $admin->runQuery($sql);
$tasks->execute($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="../../src/css/task-list.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="side-bar">
    <img class="profile-pic" src="profile-picture.jpg" alt="Profile Picture">
    <span class="user-indicator">ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    <h3><a href="chairperson-dashboard.php">DASHBOARD</a></h3>
    <h3><a href="add-task.php">ADD TASK</a></h3>
    <h3><a href="task-list.php" class="active">TASK LIST</a></h3>
    <h3><a href="profile.php">PROFILE</a></h3>
    <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
</div>

<!-- Task List with Filters -->
<div class="task-list">
    <h2>Task List</h2>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">All</option>
            <option value="Pending" <?= $filter_status === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $filter_status === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Completed" <?= $filter_status === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <label for="date_filter">Date Filter:</label>
        <select name="date_filter" id="date_filter">
            <option value="">All</option>
            <option value="today" <?= $filter_date === 'today' ? 'selected' : '' ?>>Today</option>
            <option value="this_week" <?= $filter_date === 'this_week' ? 'selected' : '' ?>>This Week</option>
            <option value="overdue" <?= $filter_date === 'overdue' ? 'selected' : '' ?>>Overdue</option>
            <option value="completed" <?= $filter_date === 'completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <button type="submit">Apply Filters</button>
    </form>

    <?php if ($tasks->rowCount() > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Assigned Employee</th>
                <th>Status</th>
                <th>Created At</th>
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
                    <td><?= !empty($task['assigned_employees']) ? htmlspecialchars($task['assigned_employees']) : 'No employees assigned'; ?></td>
                    <td><?= htmlspecialchars($task['status']); ?></td>
                    <td><?= $task['created_at']; ?></td>
                    <td>
                        <a href="edit-task.php?id=<?= $task['id']; ?>" class="edit-btn">Edit</a>
                        <a href="view-reports.php?task_id=<?= $task['id']; ?>" class="report-btn">Reports</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No tasks found based on the selected filters.</p>
    <?php endif; ?>
</div>
</body>
</html>
