<?php
require_once __DIR__ . '/../../../database/dbconnection.php';
include_once __DIR__ . '/../../../config/settings-configuration.php';
require_once __DIR__ . '/../../../src/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ADMIN
{
    private $conn;
    private $settings;
    private $smtp_email;
    private $smtp_password;

    public function __construct()
    {
        $this->settings = new SystemConfig();
        $this->smtp_email = $this->settings->getSmtpEmail();
        $this->smtp_password = $this->settings->getSmtpPassword();

        $database = new Database();
        $this->conn =  $database->dbConnection();
    }

    public function sendOtp($otp, $email)
    {
        if ($email == Null) {
            echo "<script>alert('No email found'); window.location.href = '../../../';</script>";
            exit;
        } else {
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Email already taken. Please try another one'); window.location.href = '../../../';</script>";
                exit;
            } else {
                $_SESSION['OTP'] = $otp;

                $subject = "OTP VERIFICATION";
                $message = "
              <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>OTP Verification</title>
                    <style> 
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f5f5f5;
                            margin: 0; padding: 0;
                            }

                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 30px;
                            background-color: #ffffff;
                            border-radius: 4px;
                            box-shadow: 2px 4px rgba(0, 0, 0, 0.1);
                            }

                        h1 {
                            color: #333333;
                            font-size: 24px;
                            margin-bottom: 20px;
                            }

                        p {
                            color: #666666;
                            font-size: 16px;
                            margin-bottom: 10px;
                            }

                        .button {
                        display: inline-block; 
                        padding: 12px 24px; 
                        background-color: #0088cc; 
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                           }

                        .logo {
                            display: block;
                            text-align: center;
                            margin-bottom: 30px;
                            }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='logo'>
                            <img src='cid: logo' alt='Logo' width='150'>
                        </div>
                        <h1>OTP Verification</h1>
                        <p>Hello, $email</p>
                        <p>Your OTP is: $otp</p>
                        <p>If you didn't request an OTP, please ignore this email.</p>
                        <p>Thank you!</p>
                    </div>
                </body>
                </html>";

                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                echo "<script>alert('We sent the OTP to $email'); window.location.href = '../../../verify-otp.php';</script>";
            }
        }
    }

    public function verifyOTP($username, $email, $password, $tokencode, $otp, $csrf_token)
    {
        if ($otp == $_SESSION['OTP']) {
            unset($_SESSION['OTP']);

            $this->addAdmin($csrf_token, $username, $email, $password);

            $subject = "VERIFICATION SUCCESS";
                $message = "
              <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Verification SUCCESS</title>
                    <style> 
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f5f5f5;
                            margin: 0; padding: 0;
                            }

                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 30px;
                            background-color: #ffffff;
                            border-radius: 4px;
                            box-shadow: 2px 4px rgba(0, 0, 0, 0.1);
                            }

                        h1 {
                            color: #333333;
                            font-size: 24px;
                            margin-bottom: 20px;
                            }

                        p {
                            color: #666666;
                            font-size: 16px;
                            margin-bottom: 10px;
                            }

                        .button {
                        display: inline-block; 
                        padding: 12px 24px; 
                        background-color: #0088cc; 
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                           }

                        .logo {
                            display: block;
                            text-align: center;
                            margin-bottom: 30px;
                            }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='logo'>
                            <img src='cid: logo' alt='Logo' width='150'>
                        </div>
                        <h1>Welcome</h1>
                        <p>Hello,<strong>$email</strong></p>
                        <p>Welcome to Ace System</p>
                        <p>If you did not sign up for an account, you can safely ignore this email.</p>
                        <p>Thank you!</p>
                    </div>
                </body>
                </html>";

                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                echo "<script>alert('Thank You'); window.location.href = '../../../';</script>";

                unset($_SESSION['not_verify_username']);
                unset($_SESSION['not_verify_email']);
                unset($_SESSION['not_verify_password']);
        } else if ($otp == NULL) {
            echo "<script>alert('No OTP Found'); window.location.href = '../../../verify-otp.php';</script>";
            exit;
        }else {
            echo "<script>alert('It appears that the OTP you entered is invalid'); window.location.href = '../../../verify-otp.php';</script>";
        }

        }
    public function addAdmin($csrf_token, $username, $email, $password)
    {
        $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Email already exists.'); window.location.href = '../../../';</script>";
            exit;
        }


        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../';</script>";
            exit;
        }

        unset($_SESSION['csrf_token']);

        $hash_password = md5($password);

        $stmt =  $this->runQuery('INSERT INTO user (username, email, password) VALUES (:username, :email, :password)');
        $exec = $stmt->execute(array(
            ":username" => $username,
            ":email" => $email,
            ":password" => $hash_password
        ));

        if ($exec) {
            echo "<script>alert('Admin Added Successfully.'); window.location.href = '../../../';</script>";
            exit;
        } else {
            echo "<script>alert('Erorr Adding Admin.'); window.location.href = '../../../';</script>";
            exit;
        }
    }

    public function adminSignin($email, $password, $csrf_token)
    {
        try {
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../';</script>";
                exit;
            }

            unset($_SESSION['csrf_token']);

            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1 && $userRow['password'] == md5($password)) {
                $activity = "Has Successfully signed in";
                $user_id = $userRow['id'];
                $this->logs($activity, $user_id);

                $_SESSION['adminSession'] = $user_id;

                echo "<script>alert('Welcome'); window.location.href = '../';</script>";
                exit;
            } else {
                echo "<script>alert('Invalid Credentials.'); window.location.href = '../../../';</script>";
                exit;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    public function adminSignout()
    {
        unset($_SESSION['adminSession']);
        echo "<script>alert('Sign Out Succefully.'); window.location.href = '../../../';</script>";
        exit;
    }

    function send_email($email, $message, $subject, $smtp_email, $smtp_password)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->addAddress($email);
        $mail->Username = $smtp_email;
        $mail->Password = $smtp_password;
        $mail->setFrom($smtp_email, "DHVSU CCS Task Manager");
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->Send();
    }

    public function logs($activity, $user_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array(":user_id" => $user_id, ":activity" => $activity));
    }


    public function isUserLoggedIn()
    {
        if (isset($_SESSION['adminSession'])) {
            return true;
        }
    }
    public function redirect()
    {
        echo "<script>alert('Admin must loggin first'); window.location.href = '../../../';</script>";
        exit;
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if (isset($_POST['btn-signup'])) {
    $_SESSION['not_verify_username'] = trim($_POST['username']);
    $_SESSION['not_verify_email'] = trim($_POST['email']);
    $_SESSION['not_verify_password'] = trim($_POST['password']);

    $email = trim($_POST['email']);
    $otp = rand(100000, 999999);
    $addAdmin = new ADMIN();
    $addAdmin->sendOtp($otp, $email);
}

   

if (isset($_POST['btn-signin'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $adminSignin = new ADMIN();
    $adminSignin->adminSignin($email, $password, $csrf_token);
}
if (isset($_GET['admin_signout'])) {
    $adminSignout = new ADMIN();
    $adminSignout->adminSignout();
}

//
// -- Forgot Password OTP 
if (isset($_POST['btn-forgot-password'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $email = trim($_POST['email']);

    if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../forgot-password.php';</script>";
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['reset_email'] = $email;
    $_SESSION['OTP'] = $otp;

    $adminForgotPassword = new ADMIN();
    $adminForgotPassword->sendOtp($otp, $email);

    echo "<script>alert('We sent an OTP to $email.'); window.location.href = '../../../reset-password.php';</script>";
    exit;
}

//
// -- Here is Reset Password 
if (isset($_POST['btn-reset-password'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $otp = trim($_POST['otp']);
    $new_password = trim($_POST['new_password']);
    $email = $_SESSION['reset_email'];

    if ($otp == $_SESSION['OTP']) {
        unset($_SESSION['OTP']);
        unset($_SESSION['reset_email']);

        $hash_password = md5($new_password);
        $adminResetPassword = new ADMIN();
        $stmt = $adminResetPassword->runQuery("UPDATE user SET password = :password WHERE email = :email");
        $exec = $stmt->execute(array(":password" => $hash_password, ":email" => $email));

        if ($exec) {
            echo "<script>alert('Password reset successfully.'); window.location.href = '../../../';</script>";
        } else {
            echo "<script>alert('Error resetting password.'); window.location.href = '../../../reset-password.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP.'); window.location.href = '../../../reset-password.php';</script>";
    }
}

// THIS IS SENDING OTP AREA
// FOR FORGOT PASSWORD
if (isset($_POST['btn-send-otp'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate OTP and store it in the session
        session_start();
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to the user's email
        mail($email, "Your OTP Code", "Your OTP is: $otp", "From: $senderEmail");

        echo "OTP sent to your email.";
    } else {
        echo "Email not found.";
    }
}
//-----------------
if (isset($_POST['btn-verify-otp'])) {
    session_start();
    $enteredOtp = $_POST['otp'];

    // Check OTP validity
    if ($enteredOtp == $_SESSION['otp']) {
        echo "OTP verified. You can now reset your password.";
        // Move user to the password reset step
    } else {
        echo "Invalid OTP.";
    }
}
//---------------------
if (isset($_POST['btn-reset-password'])) {
    session_start();
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        // Update the password in the database
        require_once '../config.php';
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $_SESSION['email']]);

        echo "Password reset successful.";
        session_destroy();
    } else {
        echo "Passwords do not match.";
    }
}

?>