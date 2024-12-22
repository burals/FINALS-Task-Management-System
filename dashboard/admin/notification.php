<?php
// Check if there are any notifications in the session
if (isset($_SESSION['success'])):
?>
    <div class="notification success">
        <p><?= $_SESSION['success']; ?></p>
    </div>
<?php
    unset($_SESSION['success']); // Remove the success message after displaying it
elseif (isset($_SESSION['error'])):
?>
    <div class="notification error">
        <p><?= $_SESSION['error']; ?></p>
    </div>
<?php
    unset($_SESSION['error']); // Remove the error message after displaying it
endif;
?>

<!-- Add styles for the notifications -->
<style>
.notification {
    padding: 10px 20px;
    margin: 15px 0;
    border-radius: 5px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    text-align: center;
}

.success {
    background-color: #28a745;
    color: #fff;
}

.error {
    background-color: #dc3545;
    color: #fff;
}
</style>
