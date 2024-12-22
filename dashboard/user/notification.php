<?php
require_once '../admin/authentication/admin-class.php';
require_once '../../src/vendor/autoload.php';
require_once '../admin/authentication/admin-class.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Notification
{
    private $admin;

    public function __construct()
    {
        $this->admin = new ADMIN();
    }

    /**
     * Send email notification
     * @param string $recipientEmail
     * @param string $subject
     * @param string $body
     * @return bool
     */
   

    /**
     * Notify users of a new task
     * @param int $taskId
     * @return void
     */
    public function notifyNewTask($taskId)
    {
        $stmt = $this->admin->runQuery("
            SELECT t.title, t.description, u.email 
            FROM tasks t
            LEFT JOIN task_assignments ta ON t.id = ta.task_id
            LEFT JOIN user u ON ta.employee_id = u.id
            WHERE t.id = :task_id
        ");
        $stmt->execute(array(':task_id' => $taskId));

        $taskDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($taskDetails) {
            $subject = "New Task Assigned: " . $taskDetails['title'];
            $body = "
                <h1>New Task Notification</h1>
                <p>You have been assigned a new task.</p>
                <p><strong>Title:</strong> {$taskDetails['title']}</p>
                <p><strong>Description:</strong> {$taskDetails['description']}</p>
                <p>Log in to the system to view and update the task.</p>
            ";

            // Send notification email to the user
            $this->sendEmail($taskDetails['email'], $subject, $body);
        }
    }
}
