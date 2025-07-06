<?php
// config/Database.php

// Ensure Composer autoloader is included for phpdotenv
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); // Go up one directory to find .env
$dotenv->load();

class Database {
    // Private properties to hold database credentials from .env
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    /**
     * Constructor to load credentials from environment variables
     */
    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    /**
     * Get the database connection
     * @return PDO
     */
    public function connect() {
        $this->conn = null;

        try {
            // Create a new PDO instance
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            // Set PDO error mode to exception for better error handling
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // If connection fails, display error message
            echo 'Connection Error: ' . $e->getMessage();
            // In a production environment, you should log this error and not display it
            error_log('Database Connection Error: ' . $e->getMessage());
            die(); // Terminate script execution
        }

        return $this->conn;
    }
}
?>
