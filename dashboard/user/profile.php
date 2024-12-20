<?php
include_once 'update-profile.php';
require_once '../admin/authentication/admin-class.php';

// Instantiate the admin object and check if the user is logged in
$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

// Get the logged-in user's ID from the session
$userId = $_SESSION['adminSession'];
$profile = new Profile($admin);

// If the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $profilePicture = null;

   // Define the profile picture path (assuming it's stored in the 'uploads/{user_id}/profile.jpg')
$profilePicturePath = "../uploads/" . $user_data['id'] . "/profile.jpg";

// Check if the profile picture exists, otherwise use a default image
if (!file_exists($profilePicturePath)) {
    $profilePicturePath = "default-profile.jpg"; // Set your default profile picture
}

    // Update the user's details in the database
    $profile->updateUserData($userId, $fullname, $email, $profilePicture);

    // Handle password change if a new password is entered
    if (!empty($_POST['new_password'])) {
        $newPassword = $_POST['new_password'];
        $profile->changePassword($userId, $newPassword);
    }

    // Redirect back to the profile page after successful update
    header("Location: profile.php");
    exit;
}

// Fetch the current user data
$userData = $profile->getUserData($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../src/css/user-dashboard.css">
    <link rel="stylesheet" href="../../src/css/index.css">
    <link rel="stylesheet" href="../../src/css/edit-profile.css">
    
</head>

<body>

   <!-- Sidebar -->
   <div class="side-bar">
   <span class="user-indicator"><?= strtoupper($userData['role']) ?> <?= htmlspecialchars($userData['fullname']); ?></span>
       <h3><a href="user-dashboard.php">DASHBOARD</a></h3>
       <h3><a href="my-task.php">MY TASKS</a></h3>
       <h3><a href="profile.php" class="active">PROFILE</a></h3>
       <h3><a href="../admin/authentication/admin-class.php?admin_signout">SIGN OUT</a></h3>
   </div>

   <!-- Main Content -->
   <div class="content">
    <h1 class="content-title">Edit Your Profile</h1>

    <!-- Form to update profile information -->
    <form action="profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
        <!-- Profile Picture -->
        <div class="form-group">
            <label for="profile_picture" class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-input" accept="image/*">
        </div>

        <!-- Fullname -->
        <div class="form-group">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-input" value="<?php echo $userData['fullname']; ?>" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="<?php echo $userData['email']; ?>" required>
        </div>

        <!-- Password Change (optional) -->
        <div class="form-group">
            <label for="new_password" class="form-label">New Password (optional)</label>
            <input type="password" name="new_password" class="form-input" placeholder="New Password (optional)">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="form-button">Update Profile</button>
    </form>
</div>

   </div>

</body>

</html>
