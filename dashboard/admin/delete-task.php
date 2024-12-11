<?php
// Include database connection
require_once '../../database/dbconnection.php';

$database = new Database();
$conn = $database->dbConnection();

// Get task_id and report_id from URL
$task_id = $_GET['task_id'] ?? null;
$report_id = $_GET['report_id'] ?? null;

if (!$task_id || !$report_id) {
    echo "No task ID or report ID provided.";
    exit;
}

// Handle report deletion
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ? AND task_id = ?");
    $stmt->execute([$report_id, $task_id]);

    // Set a success message to be displayed
    $successMessage = "Report deleted successfully!";
}

// Fetch reports for the current task
$stmt = $conn->prepare("
    SELECT r.id, r.title, r.description, r.created_at, e.fullname AS employee_name
    FROM reports r
    JOIN user e ON r.employee_id = e.id
    WHERE r.task_id = ?
");
$stmt->execute([$task_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
