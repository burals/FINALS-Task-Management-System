<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

// Check if the task ID is provided
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    try {
        // Delete related task assignments first
        $stmt = $admin->runQuery("DELETE FROM task_assignments WHERE task_id = :task_id");
        $stmt->execute([':task_id' => $task_id]);

        // Then delete the task
        $stmt = $admin->runQuery("DELETE FROM tasks WHERE id = :task_id");
        $stmt->execute([':task_id' => $task_id]);
        header('Location: index.php?success=task_deleted');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    // If no task ID is provided, redirect to the dashboard
    header("Location: index.php");
    exit;
}

?>
