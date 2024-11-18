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
</head>
<body>
<nav class="navbar">
    <!-- Left Side -->
    <div class="left">
        <a href="settings.php" class="settings">Settings</a>
    </div>

    <!-- Right Side -->
    <ul>
        <li><a href="authentication/admin-class.php?admin_signout" class="signout">Sign Out</a></li>
    </ul>
</nav>


    <div class="container">
        <h1 class="WC">WELCOME</h1>
    </div>

    <!-- Overlay and Popup elements -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <h2>Welcome!</h2>
        <p>Logged in successfully</p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Display the popup when the page loads
        window.onload = function () {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>
</html>
