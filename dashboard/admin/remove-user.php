<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Call the modified removeUser function
    if ($admin->removeUser($userId)) {
        header("Location: user-list.php?success=User+status+updated+to+not_active");
    } else {
        header("Location: user-list.php?error=Failed+to+update+user+status");
    }
} else {
    header("Location: user-list.php?error=Invalid+user+ID");
}
