<?php

    include_once 'config/settings-configuration.php';

    // Check if login is successful by checking the URL parameter
    $login_successful = isset($_GET['status']) && $_GET['status'] == 'success';
?><!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body class="image">
    <div class="wrapper"> <!-- New wrapper div -->
        <div class="logo"></div> <!-- Logo goes here -->
        <div class="form-container">
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <h1 class="Tittle">SIGN IN</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?>">
                <input type="email" name="email" placeholder="Enter Email" required> <br> <br>
                <input type="password" name="password" placeholder="Enter Password" required> <br> <br>

                <div class="reset-container">
                    <a href="reset-password.php" class="reset">Reset password</a>
                </div>

                <button type="submit" name="btn-signin">SIGN IN</button> <br>
                <a href="signup-page.php" class="signup-link">Don't have an account?</a>
            </form>
        </div>
    </div>
</body>
</html>

