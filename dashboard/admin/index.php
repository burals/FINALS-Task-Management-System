<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if (!$admin->isUserLoggedIn()) {
        $admin->redirect('../../');
    }

    $smtm = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $smtm->execute(array(":id" => $_SESSION['adminSession']));
    $user_data = $smtm->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../src/css/index.css">
    <link rel="stylesheet" href="../../src/css/popup.css">
    <!-- Include Font Awesome locally instead of CDN -->
    <link rel="stylesheet" href="../../src/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <div class="profile-image">
                    <i class="fas fa-user"></i>
                </div>
                <div class="admin-text">@<?php echo isset($user_data['username']) ? $user_data['username'] : 'Admin'; ?></div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage-users.php">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="createtask.php" id="createTaskBtn">
                        <i class="fas fa-plus"></i> Create Task
                    </a>
                </li>
                <li class="nav-item">
                    <a href="all-tasks.php">
                        <i class="fas fa-tasks"></i> All Tasks
                    </a>
                </li>
                <li class="nav-item">
                    <a href="authentication/admin-class.php?admin_signout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Tasks</span>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-clock"></i>
                    <span>Time</span>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-calendar"></i>
                    <span>Calendar</span>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading</span>
                </div>
            </div>

            <!-- Create Task Form (Hidden by default) -->
            <div class="create-task-container" style="display: none;">
                <!-- Your existing form content -->
            </div>
        </div>
    </div>

    <!-- Popup and Scripts -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <h2>Welcome!</h2>
        <p>Logged in successfully</p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Welcome popup
        window.onload = function() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }

        // Create Task form toggle
        document.getElementById('createTaskBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const taskContainer = document.querySelector('.create-task-container');
            const dashboardGrid = document.querySelector('.dashboard-grid');
            
            if (taskContainer.style.display === 'none') {
                dashboardGrid.style.display = 'none';
                taskContainer.style.display = 'block';
            } else {
                dashboardGrid.style.display = 'grid';
                taskContainer.style.display = 'none';
            }
        });

        // Mobile menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>