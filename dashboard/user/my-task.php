    <?php
    require_once 'Task.php';

    // Ensure user is logged in
    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    $userId = $_SESSION['adminSession'];
    $taskObj = new Task($userId);

    // Fetch user data
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $userId));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Define the profile picture path (assuming it's stored in the 'uploads/{user_id}/profile.jpg')
    $profilePicturePath = "../uploads/" . $user_data['id'] . "/profile.jpg";

    // Check if the profile picture exists, otherwise use a default image
    if (!file_exists($profilePicturePath)) {
        $profilePicturePath = "default-profile.jpg"; // Set your default profile picture
    }

    // Fetch assigned tasks
    $tasks = $taskObj->getAssignedTasks();

    if (isset($_POST['submit_task_action'])) {
        $taskId = $_POST['task_id'];
        $taskObj->updateTaskStatus($taskId, 'completed');  // Set task status to 'completed'

        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            // Handle file upload for task-related documents
            $file = $_FILES['document'];
            $uploadSuccess = $taskObj->uploadDocument($taskId, $file);

            if ($uploadSuccess) {
                echo "File uploaded successfully and task marked as completed!";
            } else {
                echo "Error uploading the file.";
            }
        }

        if (isset($_POST['report_content']) && !empty($_POST['report_content'])) {
            // Handle report generation
            $reportContent = $_POST['report_content'];
            $reportSuccess = $taskObj->generateReport($taskId, $reportContent);

            if ($reportSuccess) {
                echo "Report generated and saved successfully! Task marked as completed!";
            } else {
                echo "Error generating the report.";
            }
        }

        // Redirect to refresh the page and show updated task list
        header("Location: my-task.php");
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Tasks</title>
        <link rel="stylesheet" href="../../src/css/user-dashboard.css">
        <link rel="stylesheet" href="../../src/css/index.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <!-- Sidebar -->
        <div class="side-bar">
            <img class="profile-pic" src="<?= htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture">
            <span class="user-indicator"><?= strtoupper($user_data['role']) ?> <?= htmlspecialchars($user_data['fullname']); ?></span>
            <h3><a href="user-dashboard.php">DASHBOARD</a></h3>
            <h3><a href="my-task.php" class="active">MY TASKS</a></h3>
            <h3><a href="profile.php">PROFILE</a></h3>
            <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1>Welcome, <?= htmlspecialchars($user_data['fullname']); ?></h1>

            <h2>Your Assigned Tasks</h2>
            <?php if (!empty($tasks)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Assigned Employees</th>
                            <th>Upload Document / Generate Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['id']); ?></td>
                                <td><?= htmlspecialchars($task['title']); ?></td>
                                <td><?= htmlspecialchars($task['description']); ?></td>
                                <td><?= htmlspecialchars($task['due_date']); ?></td>
                                <td><?= htmlspecialchars($task['status']); ?></td>
                                <td><?= htmlspecialchars($task['assigned_employees']); ?></td>
                                <td>
                                    <form method="POST" enctype="multipart/form-data" action="my-task.php">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>"> <!-- Hidden task ID -->
                                        <input type="file" name="document">
                                        <textarea name="report_content" placeholder="Write your report here..."></textarea>
                                        <button type="submit" name="submit_task_action">Upload Document / Generate Report</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no tasks assigned to you.</p>
            <?php endif; ?>
        </div>

    </body>

    </html>