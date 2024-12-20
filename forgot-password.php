<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Forgot Password</h1>

    <!-- Step 1: Enter Email -->
    <form action="dashboard/admin/authentication/admin-class.php" method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" name="btn-send-otp">Send OTP</button>
    </form>

    <!-- Step 2: Enter OTP -->
    <form action="dashboard/admin/authentication/admin-class.php" method="POST">
        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <button type="submit" name="btn-verify-otp">Verify OTP</button>
    </form>

    <!-- Step 3: Enter New Password -->
    <form action="dashboard/admin/authentication/admin-class.php" method="POST">
        <label for="new-password">Enter New Password:</label>
        <input type="password" id="new-password" name="new_password" required>
        <label for="confirm-password">Confirm New Password:</label>
        <input type="password" id="confirm-password" name="confirm_password" required>
        <button type="submit" name="btn-reset-password">Reset Password</button>
    </form>
</body>
</html>
