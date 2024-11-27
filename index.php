<?php
include_once 'config/settings-configuration.php';

$login_successful = isset($_GET['status']) && $_GET['status'] == 'success';
$login_error = isset($_GET['status']) && $_GET['status'] == 'error';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body class="image">
    <div class="wrapper">
        <div class="logo"></div> <!-- Add the logo above the form -->
        <div class="form-container">
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <h1 class="Tittle">SIGN IN</h1>
                
                <!-- CSRF token (for security) -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <input type="email" name="email" placeholder="Enter Email" required> <br><br>
                <input type="password" name="password" placeholder="Enter Password" required> <br><br>

                <!-- Reset Password Link -->
                <div class="reset-container">
                    <a href="forgot-password.php" class="reset">Reset password</a>
                </div>

                <!-- Error or Success message -->
                <?php if ($login_error): ?>
                    <div class="error-message">Invalid email or password.</div>
                <?php endif; ?>

                <?php if ($login_successful): ?>
                    <div class="success-message">You are successfully logged in!</div>
                <?php endif; ?>

                <button type="submit" name="btn-signin">SIGN IN</button> <br>
                <a href="signup-page.php" class="signup-link">Don't have an account?</a>
            </form>
        </div>
    </div>
</body>
</html>
