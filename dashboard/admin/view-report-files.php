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

// Get the task_id from the URL
$task_id = $_GET['task_id'] ?? null;

if (!$task_id) {
    echo "<div class='alert alert-danger'>No task ID provided.</div>";
    exit;
}

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
    <title>View Report Files</title>
    <link rel="stylesheet" href="../../src/css/reports.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Attached Documents for Task ID: <span><?= htmlspecialchars($task_id) ?></span></h2>

        <!-- Display Attached Documents -->
        <?php if (!empty($documents)): ?>
            <ul class="file-list">
                <?php foreach ($documents as $doc): ?>
                    <?php
                    // Get the relative file path
                    $file_path = "../uploads/" . $doc['file_path'];
                    $server_file_path = $_SERVER['DOCUMENT_ROOT'] . "../uploads/" . $doc['file_path']; // Corrected path

                    // Debugging: Log the server root and file path
                    error_log("Server Root: " . $_SERVER['DOCUMENT_ROOT']);
                    error_log("Full File Path: " . $server_file_path);
                    ?>
                    <li>
                        <span><?= htmlspecialchars($doc['file_path']) ?></span>
                        <?php 
                        // Check if the file exists on the server
                        if (file_exists($server_file_path)): ?>
                            <a href="<?= $file_path ?>" download class="btn btn-success">Download</a>
                        <?php else: ?>
                            <span style="color: red;">(File not found on server)</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">No documents attached for this task.</div>
        <?php endif; ?>

        <a href="view-reports.php?task_id=<?= htmlspecialchars($task_id) ?>" class="btn btn-primary mt-4">Back to Reports</a>
    </div>
</body>
</html>
