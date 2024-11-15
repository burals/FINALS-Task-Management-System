<?php
    require_once 'authentication/admin-class.php';

    $admin = new ADMIN();
    if(!$admin->isUserLoggedIn())
    {
     $admin->redirect('../../');
    }

    $smtm = $admin->runQuery(("SELECT * FROM user WHERE id = :id"));
    $smtm->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $smtm->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="../../index.css">
    <link rel="stylesheet" href="../../index.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD</title>
</head>
<body>
    
    <button class="signout"><a href="authentication/admin-class.php?admin_signout">SIGN OUT</a></button>

    <h1 class="WC">WELCOME <br>
        <div class="user_n">
            <?php echo $user_data['username'] ?> 
        </div>
    </h1>
</body>
</html>