<?php
// Include database connection
require_once '../../database/dbconnection.php';

$database = new Database();
$conn = $database->dbConnection();

// Get report_id from URL
$report_id = $_GET['report_id'] ?? null;
$task_id = $_GET['task_id'] ?? null;  // Ensure task_id is also retrieved

if (!$report_id || !$task_id) {
    echo "No report or task ID provided.";
    exit;
}

// Prepare the SQL query to delete the report
$stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
$stmt->bindParam(1, $report_id, PDO::PARAM_INT);

// Execute the query and check if successful
if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Report deleted successfully!</div>";
} else {
    echo "<div class='alert alert-danger'>Failed to delete report. Please try again.</div>";
}

// Redirect back to the reports page with the task_id
header("Location: view-reports.php?task_id=" . $task_id); // Ensure task_id is included
exit;
?>
