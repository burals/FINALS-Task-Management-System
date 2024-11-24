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
    <link rel="stylesheet" href="../../src/css/dashboard.css">
    <link rel="stylesheet" href="../../src/css/popup.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Styling the popup */
        .popup {
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f9f9f9;
            border: 2px solid #ccc;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 1000;
        }

        /* Background overlay */
        .overlay {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <header class="header">
        <h2 class="u-name">TMS <b>Admin</b></h2>
        <i class="fa fa-bell" aria-hidden="true"></i>
    </header>

    <div class="body">
        <!-- Sidebar -->
        <nav class="side-bar">
            <div class="user-p">
                <img src="../../src/css/img/user.png">
             
            </div>

                <!-- Admin -->
                <ul id="navList">
                    <li>
                        <a href="index.php">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="user.php">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="create_task.php">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <span>Create Task</span>
                        </a>
                    </li>
                    <li>
                        <a href="tasks.php">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span>All Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
        </nav>

        <!-- Popup -->
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
            <h2>Welcome!</h2>
            <p>Logged in successfully</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        // Display the popup when the page loads
        window.onload = function () {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        };

        // Function to close the popup
        function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>

</html>
