<?php
// Config/database.php
// Database configuration file using PDO

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'db_setoran_hafalan');

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Attempt to connect without database name to auto-create it if it doesn't exist
            try {
                $dsn_temp = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
                $conn_temp = new PDO($dsn_temp, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                // Create database
                $conn_temp->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
                
                // Retry connecting to the created database
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
                
                // If tables are empty, import schema
                $this->importInitialSchema();
            } catch (PDOException $ex) {
                die("Connection Failed: " . $ex->getMessage());
            }
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function importInitialSchema() {
        $sqlFile = dirname(__DIR__) . '/database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            // Remove comments and execute statements
            $this->conn->exec($sql);
        }
    }
}
