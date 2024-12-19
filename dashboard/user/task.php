<?php
require_once '../admin/authentication/admin-class.php';

class Task
{
    private $admin;
    private $userId;

    public function __construct($userId)
    {
        $this->admin = new ADMIN();
        $this->userId = $userId;
    }

    // Fetch assigned tasks for the user
    public function getAssignedTasks()
    {
        $stmt = $this->admin->runQuery("
            SELECT t.*, 
                   GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
            FROM tasks t
            LEFT JOIN task_assignments ta ON t.id = ta.task_id
            LEFT JOIN user u ON ta.employee_id = u.id
            WHERE ta.employee_id = :id
            GROUP BY t.id
        ");
        $stmt->execute(array(":id" => $this->userId));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update task status
    public function updateTaskStatus($taskId, $status)
    {
        $update = $this->admin->runQuery("UPDATE tasks SET status = :status WHERE id = :task_id");
        $update->execute(array(':status' => $status, ':task_id' => $taskId));
    }

    // Handle file upload for task-related documents
    public function uploadDocument($taskId, $file)
    {
        if ($file['error'] == 0) {
            $uploadDir = "../uploads/" . $this->userId . "/documents/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($file['name']);
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Optionally, you can store the file path in the database
                $stmt = $this->admin->runQuery("INSERT INTO task_documents (task_id, user_id, file_path) VALUES (:task_id, :user_id, :file_path)");
                $stmt->execute(array(':task_id' => $taskId, ':user_id' => $this->userId, ':file_path' => $filePath));
                return true;  // File uploaded successfully
            }
        }
        return false; // File upload failed
    }

    // Generate and save a report
    public function generateReport($taskId, $reportContent)
    {
        $stmt = $this->admin->runQuery("INSERT INTO reports (user_id, task_id, content, created_at) VALUES (:user_id, :task_id, :content, NOW())");
        $stmt->execute(array(':user_id' => $this->userId, ':task_id' => $taskId, ':content' => $reportContent));
        return true; // Report generated and saved
    }
}
