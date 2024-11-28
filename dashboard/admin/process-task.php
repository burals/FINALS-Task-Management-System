<?php
// Database credentials - update these according to your setup
$host = "localhost";
$dbname = "itelec3";
$username = "root";
$password = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/autoload copy.php';

// Create a new PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode for debugging
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$smtp_email = 'wrenchnerbangit@gmail.com';
$smtp_password = 'zapq uiqd mdjn axss';  

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time']; // Added due time input
    $due_datetime = $due_date . ' ' . $due_time; // Combine date and time
    $employee_ids = $_POST['employee_ids']; // Array of selected employee IDs from checkboxes

    // Validate inputs
    if (empty($title) || empty($description) || empty($due_date) || empty($due_time) || empty($employee_ids)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Ensure the due date and time are valid
    $current_datetime = date('Y-m-d H:i:s');
    if ($due_datetime < $current_datetime) {
        echo "<script>alert('Due date and time cannot be in the past!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Insert the task into the database
    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Insert task into the `tasks` table
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date) VALUES (:title, :description, :due_date)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':due_date' => $due_datetime
        ]);

        // Get the ID of the newly inserted task
        $task_id = $pdo->lastInsertId();

        // Insert into `task_assignments` table for each selected employee
        $assignmentStmt = $pdo->prepare("INSERT INTO task_assignments (task_id, employee_id) VALUES (:task_id, :employee_id)");
        foreach ($employee_ids as $employee_id) {
            $assignmentStmt->execute([
                ':task_id' => $task_id,
                ':employee_id' => $employee_id
            ]);

            // Send an email notification to the assigned employee
            $stmtEmployee = $pdo->prepare("SELECT email, fullname FROM user WHERE id = :employee_id");
            $stmtEmployee->execute([':employee_id' => $employee_id]);
            $employee = $stmtEmployee->fetch();

            if ($employee) {
                $email = $employee['email'];
                $employee_name = $employee['fullname'];

                // Set up PHPMailer to send the email
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->SMTPDebug = 0;
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = $smtp_email;
                    $mail->Password = $smtp_password;
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom($smtp_email, "CSS Task Management System");
                    $mail->addAddress($email, $employee_name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Task Assigned: ' . $title;
                    $mail->Body = 'You have been assigned a new task:<br><br>' .
                                  'Task: ' . $title . '<br>' .
                                  'Description: ' . $description . '<br>' .
                                  'Due Date: ' . $due_datetime . '<br>' .
                                  'Please complete the task on time.';

                    // Send the email
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect to the dashboard with a success message
        header('Location: index.php?success=task_created');
        exit;

    } catch (PDOException $e) {
        // Roll back the transaction on error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // If accessed directly, redirect to the dashboard
    header("Location: index.php");
    exit;
}
