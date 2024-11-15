<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if(!$admin->isUserLoggedIn()){
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/style.css">
    <title>ADMIN DASHBOARD</title>
</head>
<body>
    <div class="main-container">
        <div class="container2">
            <h1>WELCOME <?php echo $user_data['email']?></h1>
            <button class="sign-out"> 
                <a href="authentication/admin-class.php?admin_signout">SIGN OUT</a>
            </button>
        </div>
        <div class="character">
            <img src="../../src/img/character.png" alt="img">
        </div>
    </div>
</body>
</html>