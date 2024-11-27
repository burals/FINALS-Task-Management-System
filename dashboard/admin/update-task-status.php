<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    // Update the status of the task
    try {
        $stmt = $admin->runQuery("UPDATE tasks SET status = :status WHERE id = :task_id");
        $stmt->execute([
            ':status' => $status,
            ':task_id' => $task_id
        ]);

        // Redirect back to the dashboard
        header('Location: user-dashboard.php?success=status_updated');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
