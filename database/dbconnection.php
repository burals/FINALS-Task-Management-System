<?php

class Database 
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        if (in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1']) || strpos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
            // Local development settings
            $this->host = "localhost";
            $this->db_name = "itelec3";
            $this->username = "root";
            $this->password = "";
        } else {
            // Production settings
            $this->host = "production_host"; // Replace with actual values
            $this->db_name = "production_db_name";
            $this->username = "production_username";
            $this->password = "production_password";
        }
    }

    public function dbConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            if (strpos($_SERVER['SERVER_NAME'], 'localhost') !== false || in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1'])) {
                echo "Connection error: " . $exception->getMessage();
            } else {
                error_log("Database connection error: " . $exception->getMessage());
                die("Connection error. Please try again later.");
            }
        }
        return $this->conn;
    }
}

?>
