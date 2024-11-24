<?php
include_once 'config/settings-configuration.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form action="dashboard/admin/authentication/admin-class.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?>">
        <input type="text" name="otp" placeholder="Enter OTP" required> <br> <br>
        <input type="password" name="new_password" placeholder="Enter New Password" required> <br> <br>
        <button type="submit" name="btn-reset-password">Reset Password</button>
    </form>
</body>
</html>