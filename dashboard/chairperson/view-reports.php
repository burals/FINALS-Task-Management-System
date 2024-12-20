<?php


// Start the session
session_start();

// Get the employee_id from the session
$user_id = $_SESSION['user_id']; // Use the session's employee ID


// Check if employee_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>You need to log in first.</div>";
    exit;
}
require_once '../../database/dbconnection.php';
$database = new Database();
$conn = $database->dbConnection();


// Get task_id from URL
$task_id = $_GET['task_id'] ?? null;

if (!$task_id) {
    echo "No task ID provided.";
    exit;
}

// Handle form submission for submitting a report
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($title) && !empty($description)) {
        // Insert the report into the reports table using the logged-in user's employee_id
        $stmt = $conn->prepare("INSERT INTO reports (task_id, employee_id, title, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$task_id, $user_id, $title, $description]);

        echo "<div class='alert alert-success'>Report submitted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Please fill in all fields.</div>";
    }
}



// Fetch reports for the current task
$stmt = $conn->prepare("
    SELECT r.id, r.user_id, r.task_id, r.content, r.created_at
    FROM reports r
    JOIN user e ON r.user_id = e.id
    WHERE r.task_id = ?
");
$stmt->execute([$task_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../../src/css/reports.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h2>Reports for Task ID: <span><?= htmlspecialchars($task_id) ?></span></h2>

        <!-- Report Submission Form -->
        <div class="card mb-4">
            <div class="card-header">Submit a Report</div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                    <form method="POST" enctype="multipart/form-data" action="my-task.php">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>"> <!-- Hidden task ID -->
                                        <input type="file" name="document">
                                        <textarea name="report_content" placeholder="Write your report here..."></textarea>
                                        
                                    </form>
                                    <button type="submit" name="submit_task_action">Upload Document / Generate Report</button>
                    <a href="task-list.php" class="btn btn-primary mb-4">Back</a>
                </form>
            </div>
        </div>



</body>
</html>
