<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if(!$admin->isUserLoggedIn())
    {
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
    <title>ADMIN DASHBOARD</title>
    <link rel="stylesheet" href="../../src/css/index.css">
    <link rel = "stylesheet" href="../../src/css/popup.css">

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

        /* Add your own styling for the sign out button and user section */
    </style>
</head>
<body>

    <button class="signout"><a href="authentication/admin-class.php?admin_signout">SIGN OUT</a></button>

    <h1 class="WC">WELCOME <br>
        <div class="user_n">
            <?php echo $user_data['username']; ?> 
        </div>
    </h1>

    <!-- Overlay and Popup elements -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <h2>Welcome!</h2>
        <?php  echo $user_data['email']; ?>
        <p>Logged in successfully</p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Display the popup when the page loads
        window.onload = function() {
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