<?php

require_once '../../database/dbconnection.php';
include_once 'Profile.php'; // Include your Profile class

class Profile
{
    private $admin;

    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    // Get user data by ID
    public function getUserData($userId)
    {
        $stmt = $this->admin->runQuery("SELECT * FROM user WHERE id = :id");
        $stmt->execute(array(":id" => $userId));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user details
    public function updateUserData($userId, $fullname, $email, $profilePicture = null)
    {
        $query = "UPDATE user SET fullname = :fullname, email = :email";
        if ($profilePicture) {
            $query .= ", profile_picture = :profile_picture";
        }
        $query .= " WHERE id = :id";

        $stmt = $this->admin->runQuery($query);
        $params = [
            ':fullname' => $fullname,
            ':email' => $email,
            ':id' => $userId
        ];

        if ($profilePicture) {
            $params[':profile_picture'] = $profilePicture;
        }

        $stmt->execute($params);
    }

    // Handle file upload
    public function uploadProfilePicture($file, $userId)
    {
        // Set up directories
        $targetDir = "../uploads/";
        $userFolder = $targetDir . $userId; // Folder for the user based on their ID
        if (!file_exists($userFolder)) {
            mkdir($userFolder, 0777, true); // Create the folder if it doesn't exist
        }

        // Define the old profile picture path
        $existingFile = $userFolder . '/profile.jpg'; // Assuming the image is stored as 'profile.jpg'

        // Delete the old profile picture if exists
        if (file_exists($existingFile)) {
            unlink($existingFile); // Delete the old image
        }

        // Define the path for the new uploaded image
        $targetFile = $userFolder . '/profile.jpg'; // All profile pictures will be named 'profile.jpg'
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        if (getimagesize($file["tmp_name"]) === false) {
            return "File is not an image.";
        }

        // Check file size (limit to 2MB)
        if ($file["size"] > 2000000) {
            return "Sorry, your file is too large.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return "Sorry, only JPG, JPEG, PNG files are allowed.";
        }

        // Attempt to upload the file
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "profile.jpg"; // Successfully uploaded, return the image filename
        } else {
            return "Sorry, there was an error uploading your file.";
        }
    }

    // Change password
    public function changePassword($userId, $newPassword)
    {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->admin->runQuery("UPDATE user SET password = :password WHERE id = :id");
        $stmt->execute([
            ':password' => $newPasswordHash,
            ':id' => $userId
        ]);
    }
}
