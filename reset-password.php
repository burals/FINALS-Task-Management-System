<?php

// Start session
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Check if token is present
if(!isset($_GET['token'])){
    echo "<script>alert('No token provided.'); window.location.href = 'index.php';</script>";
    exit;
}

$token = $_GET['token'];

// Optionally, verify the token's existence and validity before showing the form
// This can also be handled in the resetPassword method
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="src/css/reset-password.css">
</head>
<body class="image">
<div class = "con">
<div class="container">
    <h2> Reset Password</h2>
    <form method="POST" action="dashboard/admin/authentication/admin-class.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <br>
        <label class="pass" for="new_password">Password</label>
        <input type="password" class="pass1" name="new_password" placeholder="Enter your new password" required>
        <br>
       
        <input type="password" class="pass2" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm Password" required>
        <br>
        <button type="submit" name="btn-reset-password">Reset Password</button>
      
    </form>
    <a href="index.php">Back to home</a>

    </div>
</div>
</body>
</html>
