<?php
// Include database connection
require_once '../../database/dbconnection.php';

$database = new Database();
$conn = $database->dbConnection();

// Get task_id from URL
$task_id = $_GET['task_id'] ?? null;

// Validate input
if (!$task_id) {
    echo "No task ID provided.";
    exit;
}
try {
    // Begin transaction
    $conn->beginTransaction();

    // Delete all reports associated with the task
    $stmt = $conn->prepare("DELETE FROM reports WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $task_id]);

    // Delete the task itself
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :task_id");
    $stmt->execute([':task_id' => $task_id]);

    // Commit transaction
    $conn->commit();

    // Redirect with success message
    header("Location: task-list.php?message=Task and all associated reports deleted successfully!");
    exit;
} catch (Exception $e) {
    // Rollback on error
    $conn->rollBack();
    echo "Failed to delete task and associated reports: " . $e->getMessage();
    exit;
}
?>
