<?php
require_once '../../database/dbconnection.php';
require_once '../admin/authentication/admin-class.php';
require_once 'update-profile.php';

class ProfilePage
{
    private $admin;
    private $profile;
    private $userId;
    private $userData;

    public function __construct()
    {
        $this->admin = new ADMIN();
        if (!$this->admin->isUserLoggedIn()) {
            $this->admin->redirect('../../');
        }

        $this->userId = $_SESSION['adminSession'];
        $this->profile = new Profile($this->admin);
        $this->userData = $this->profile->getUserData($this->userId);
    }

    public function handleFormSubmission()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $profilePicture = null;

            if (!empty($_FILES['profile_picture']['tmp_name'])) {
                $uploadResult = $this->profile->uploadProfilePicture($_FILES['profile_picture'], $this->userId);
                if ($uploadResult === "profile.jpg") {
                    $profilePicture = $uploadResult;
                } else {
                    echo $uploadResult;  // Display error if upload fails
                }
            }

            $this->profile->updateUserData($this->userId, $fullname, $email, $profilePicture);

            if (!empty($_POST['new_password'])) {
                $this->profile->changePassword($this->userId, $_POST['new_password']);
            }

            header("Location: profile.php");
            exit;
        }
    }

    public function render()
    {
        $profilePicture = "../uploads/{$this->userId}/profile.jpg";
        if (!file_exists($profilePicture)) {
            $profilePicture = "default-profile.jpg";  // Fallback to default if no custom profile picture
        }

        $fullname = htmlspecialchars($this->userData['fullname']);
        $email = htmlspecialchars($this->userData['email']);
        $role = strtoupper($this->userData['role']);

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../src/css/user-dashboard.css">
    <link rel="stylesheet" href="../../src/css/index.css">
    <link rel="stylesheet" href="../../src/css/edit-profile.css">
    <link rel="icon" href="../../src/css/img/CCS-LOGO.png" type="image/x-icon">
</head>

<body>
    <div class="side-bar">
        <img class="profile-pic" src="$profilePicture" alt="Profile Picture">
        <span class="user-indicator">$role $fullname</span>
        <h3><a href="chairperson-dashboard.php">DASHBOARD</a></h3>
        <h3><a href="add-task.php">ADD TASK</a></h3>
        <h3><a href="task-list.php">TASK LIST</a></h3>
        <h3><a href="profile.php" class="active">PROFILE</a></h3>
        <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
    </div>

    <div class="content">
        <h1 class="content-title">Edit Your Profile</h1>
        <form action="profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-input" accept="image/*">
            </div>
            <div class="form-group">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-input" value="$fullname" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="$email" required>
            </div>
            <div class="form-group">
                <label for="new_password" class="form-label">New Password (optional)</label>
                <input type="password" name="new_password" class="form-input" placeholder="New Password (optional)">
            </div>
            <button type="submit" class="form-button">Update Profile</button>
        </form>
    </div>
</body>
</html>
HTML;
    }
}

$page = new ProfilePage();
$page->handleFormSubmission();
$page->render();
