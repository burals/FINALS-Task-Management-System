<?php
session_start();

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="src/css/forgot-password.css"> <!-- Linked CSS -->
</head>
<body>
    <div class="image">
        <div class="wrapper">
            <div class="logo"></div> <!-- Placeholder for the logo -->
            <div class="form-container">
                <h1>Forgot Password</h1>
                <form method="POST" action="dashboard/admin/authentication/admin-class.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                    <button type="submit" name="btn-forgot-password">Send Reset Link</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
