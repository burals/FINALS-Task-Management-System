<?php
class USER {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($db_con = null) {
        if ($db_con) {
            $this->db = $db_con;
        } else {
            try {
                $this->db = new PDO("mysql:host=localhost;dbname=itelec3-v2", "root", "");
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                exit;
            }
        }
    }

    // Run a database query
    public function runQuery($sql) {
        return $this->db->prepare($sql);
    }

    // Log in a user
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['userSession'] = $user['id'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Check if user is logged in
    public function isUserLoggedIn() {
        return isset($_SESSION['userSession']);
    }

    // Redirect to a different page
    public function redirect($url) {
        header("Location: $url");
        exit;
    }

    // Log out a user
    public function logout() {
        unset($_SESSION['userSession']);
        session_destroy();
        return true;
    }
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
