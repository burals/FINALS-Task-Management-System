<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    // Fetch user data
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $_SESSION['adminSession']));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch task data and assigned employees
    $tasks = $admin->runQuery("
    SELECT t.*, 
           GROUP_CONCAT(u.fullname SEPARATOR ', ') AS assigned_employees
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.id = ta.task_id
    LEFT JOIN user u ON ta.employee_id = u.id
    GROUP BY t.id
    ");
    $tasks->execute();

    $user_list = $admin->runQuery("SELECT id, fullname, role FROM user");
$user_list->execute();
    // Fetch all employees for the task creation form
    $employees = $admin->runQuery("SELECT * FROM user");
    $employees->execute();

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../src/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar">
    <div class="left">
        <span class="user-indicator">Logged in as: ADMIN <?= htmlspecialchars($user_data['fullname']); ?></span>
    </div>
    <ul>
        <li><a href="authentication/admin-class.php?admin_signout" class="signout">Sign Out</a></li>
    </ul>
</nav>

<div class="container">
    <!-- Left Column: Task Creation Form -->
    <div class="task-form">
        <h2>Create a New Task</h2>
        <form action="process-task.php" method="POST">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" placeholder="Enter task title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter task description" required></textarea>
            </div>
            <div class="form-group">
    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" required>
    <label for="due_time">Time</label>
    <input type="time" id="due_time" name="due_time" required>
</div>
            <div class="form-group">
                <label for="assign_employee">Assign to Employees</label>
                <div id="assign_employee" class="checkbox-group">
                    <?php while ($employee = $employees->fetch(PDO::FETCH_ASSOC)): ?>
                        <div>
                            <input type="checkbox" id="employee_<?= $employee['id']; ?>" name="employee_ids[]" value="<?= $employee['id']; ?>">
                            <label for="employee_<?= $employee['id']; ?>"><?= htmlspecialchars($employee['fullname']); ?></label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <button type="submit" class="btn-submit">Create Task</button>
        </form>
    </div>

    <!-- Right Column: Task List -->
    <div class="task-list">
        <h2>Task List</h2>
        <?php if ($tasks->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Assigned Employee</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $tasks->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $task['id']; ?></td>
                        <td><?= htmlspecialchars($task['title']); ?></td>
                        <td><?= htmlspecialchars($task['description']); ?></td>
                        <td><?= $task['due_date']; ?></td>
                        <td><?= !empty($task['assigned_employees']) ? htmlspecialchars($task['assigned_employees']) : 'No employees assigned'; ?></td>
                        <td><?= htmlspecialchars($task['status']); ?></td>
                        <td><?= $task['created_at']; ?></td>
                        <td>
                            <a href="edit-task.php?id=<?= $task['id']; ?>" class="edit-btn">Edit</a>
                           
                            <a href="view-reports.php?task_id=<?= $task['id']; ?>"class="report-btn">Reports</a>
                            <?php endwhile; ?>
            <?php else: ?>
               
                    <td  style="text-align: center;">No task added yet</td>
                
            <?php endif; ?>
                            </td>
                    </tr>
           
        </tbody>
    </table>
    </div>
    
</div>

<div class="user-list-wrapper">
    <div class="user-list">
        <h2>User List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all users
                while ($user = $user_list->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="feedback">
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'task_updated'): ?>
            <div class="alert success">Task updated successfully!</div>
        <?php elseif ($_GET['success'] == 'task_deleted'): ?>
            <div class="alert success">Task deleted successfully!</div>
        <?php elseif ($_GET['success'] == 'task_created'): ?>
            <div class="alert success">Task created successfully!</div>
        <?php endif; ?>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
</div>

</body>
</html>
