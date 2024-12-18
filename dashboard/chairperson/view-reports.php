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
    SELECT r.id, r.title, r.description, r.created_at, e.fullname AS employee_name
    FROM reports r
    JOIN user e ON r.employee_id = e.id
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
    <title>View and Submit Reports</title>
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
                        <label for="title">Report Title:</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Report Description:</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Submit Report</button>
                    <a href="chairperson-dashboard.php" class="btn btn-primary mb-4">Back</a>
                </form>
            </div>
        </div>

        <!-- List of Submitted Reports -->
        <h3>Submitted Reports</h3>
        <?php if (!empty($reports)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Submitted By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['id']) ?></td>
                            <td><?= htmlspecialchars($report['title']) ?></td>
                            <td><?= htmlspecialchars($report['description']) ?></td>
                            <td><?= htmlspecialchars($report['employee_name']) ?></td>
                            <td><?= htmlspecialchars($report['created_at']) ?></td>
                            <td><a href="delete-report.php?report_id=<?= $report['id'] ?>&task_id=<?= $task_id ?>" 
                            class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this report?')">
                            Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No reports have been submitted for this task yet.</p>
        <?php endif; ?>
    </div>

</body>
</html>
