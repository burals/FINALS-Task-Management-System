<?php

require_once '../../database/dbconnection.php';
require_once '../admin/authentication/admin-class.php';

class Profile
{
    private $admin;

    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    public function getUserData($userId)
    {
        $stmt = $this->admin->runQuery("SELECT * FROM user WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    public function uploadProfilePicture($file, $userId)
    {
        $targetDir = "../uploads/";
        $userFolder = $targetDir . $userId;

        if (!file_exists($userFolder)) {
            mkdir($userFolder, 0777, true);
        }

        $existingFile = $userFolder . '/profile.jpg';
        if (file_exists($existingFile)) {
            unlink($existingFile);
        }

        $targetFile = $userFolder . '/profile.jpg';
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (getimagesize($file["tmp_name"]) === false) {
            return "File is not an image.";
        }

        if ($file["size"] > 2000000) {
            return "File is too large.";
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            return "Only JPG, JPEG, and PNG files are allowed.";
        }

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "profile.jpg";
        } else {
            return "Error uploading file.";
        }
    }

    public function changePassword($userId, $newPassword)
    {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->admin->runQuery("UPDATE user SET password = :password WHERE id = :id");
        $stmt->execute([':password' => $newPasswordHash, ':id' => $userId]);
    }
}
