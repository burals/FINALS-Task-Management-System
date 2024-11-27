<?php
// Database credentials - update these according to your setup
$host = "localhost";
$dbname = "itelec3";
$username = "root";
$password = "";

// Create a new PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode for debugging
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];
    $employee_ids = $_POST['employee_ids']; // Array of selected employee IDs from checkboxes

    // Validate inputs
    if (empty($title) || empty($description) || empty($due_date) || empty($employee_ids)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Ensure the due date is valid
    $current_date = date('Y-m-d');
    if ($due_date < $current_date) {
        echo "<script>alert('Due date cannot be in the past!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Insert the task into the database
    try {
        // Insert task into the `tasks` table
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date) VALUES (:title, :description, :due_date)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':due_date' => $due_date
        ]);

        // Get the ID of the newly inserted task
        $task_id = $pdo->lastInsertId(); // Directly using PDO's `lastInsertId()`

        // Insert into `task_assignments` table for each selected employee
        $assignmentStmt = $pdo->prepare("INSERT INTO task_assignments (task_id, employee_id) VALUES (:task_id, :employee_id)");
        foreach ($employee_ids as $employee_id) {
            $assignmentStmt->execute([
                ':task_id' => $task_id,
                ':employee_id' => $employee_id
            ]);
        }

        // Redirect to the dashboard with a success message
        header('Location: index.php?success=task_created');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If accessed directly, redirect to the dashboard
    header("Location: index.php");
    exit;
}
?>
