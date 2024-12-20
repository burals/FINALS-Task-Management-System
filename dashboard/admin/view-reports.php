<?php
// Start the session
session_start();

// Get the employee_id from the session
$user_id = $_SESSION['user_id']; // Use the session's employee ID

// Check if employee_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>You need to log in first.</div>";
    exit;
}
require_once '../../database/dbconnection.php';
$database = new Database();
$conn = $database->dbConnection();

// Get task_id from URL
$task_id = $_GET['task_id'] ?? null;

if (!$task_id) {
    echo "No task ID provided.";
    exit;
}

// Fetch reports for the current task
$stmt = $conn->prepare("
    SELECT r.id, r.user_id, r.task_id, r.content, r.created_at
    FROM reports r
    JOIN user e ON r.user_id = e.id
    WHERE r.task_id = ?
");
$stmt->execute([$task_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <link rel="stylesheet" href="../../src/css/reports.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Reports for Task ID: <span><?= htmlspecialchars($task_id) ?></span></h2>

        <!-- Display Reports -->
        <?php if ($reports): ?>
            <div class="card">
                <div class="card-header">Submitted Reports</div>
                <div class="card-body">
                    <?php foreach ($reports as $report): ?>
                        <div class="report">
                            <h4>Report ID: <?= htmlspecialchars($report['id']) ?></h4>
                            <p><strong>User ID:</strong> <?= htmlspecialchars($report['user_id']) ?></p>
                            <p><strong>Task ID:</strong> <?= htmlspecialchars($report['task_id']) ?></p>
                            <p><strong>Content:</strong> <?= htmlspecialchars($report['content']) ?></p>
                            <p><strong>Created At:</strong> <?= htmlspecialchars($report['created_at']) ?></p>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No reports found for this task.</div>
        <?php endif; ?>

        <a href="task-list.php" class="btn btn-primary mt-4">Back</a>
    </div>
</body>
</html>
