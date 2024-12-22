<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>You need to log in first.</div>";
    exit;
}

require_once '../../database/dbconnection.php';
$database = new Database();
$conn = $database->dbConnection();

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get task_id from URL
$task_id = $_GET['task_id'] ?? null;

if (!$task_id) {
    echo "<div class='alert alert-danger'>No task ID provided.</div>";
    exit;
}

// Handle delete request
if (isset($_GET['delete_report_id'])) {
    $delete_report_id = $_GET['delete_report_id'];

    // Prepare the delete statement
    $delete_stmt = $conn->prepare("DELETE FROM reports WHERE id = ? AND task_id = ?");
    if ($delete_stmt->execute([$delete_report_id, $task_id])) {
        echo "<div class='alert alert-success'>Report deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting report.</div>";
    }
}

// Fetch reports for the task
$stmt = $conn->prepare("
    SELECT r.id, r.user_id, r.task_id, r.content, r.created_at
    FROM reports r
    WHERE r.task_id = ?
");
if (!$stmt->execute([$task_id])) {
    echo "<div class='alert alert-danger'>Error fetching reports:</div>";
    exit;
}
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch attached documents for the task
$doc_stmt = $conn->prepare("
    SELECT id, file_path
    FROM task_documents
    WHERE task_id = ?
");

if (!$doc_stmt->execute([$task_id])) {
    echo "<div class='alert alert-danger'>Error fetching documents:</div>";
    exit;
}
$documents = $doc_stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <?php if (!empty($reports)): ?>
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
                            <!-- Delete Report Button -->
                            <a href="view-reports.php?task_id=<?= htmlspecialchars($task_id) ?>&delete_report_id=<?= htmlspecialchars($report['id']) ?>" class="btn btn-danger">Delete Report</a>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No reports found for this task.</div>
        <?php endif; ?>

        <a href="view-report-files.php?task_id=<?= htmlspecialchars($task_id) ?>" class="btn btn-primary mt-4">See Report Files</a>
        <a href="task-list.php" class="btn btn-primary mt-4">Back</a>
    </div>
</body>
</html>
