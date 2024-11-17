<?php
    include_once 'config/settings-configuration.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body class="image">
    <!-- Wrapper for alignment -->
    <div class="wrapper">
        <div class="logo"></div> <!-- Add the logo above the form -->
        
        <div class="form-container">
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <h1 class="Tittle">SIGN UP</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="username" placeholder="Enter Username" required> <br>
                <input type="email" name="email" placeholder="Enter Email" required> <br>
                <input type="password" name="password" placeholder="Enter Password" required> <br>
                <button type="submit" name="btn-signup">SIGN UP</button> <br>
                <a href="index.php" class="signin-link">Already have an account? Sign In</a>
            </form>
        </div>
    </div>
</body>
</html>



