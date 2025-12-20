<?php

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $host = 'localhost';
        $username = 'root'; 
        $password = ''; 
        $database = 'Innovatechmain';
        
        try {
            $this->conn = new mysqli($host, $username, $password, $database);
            
            if ($this->conn->connect_error) {
                throw new Exception("Conexão falhou: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log($e->getMessage());
            die("Erro de conexão com o banco de dados.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }
    
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
}

function getConnection() {
    return Database::getInstance()->getConnection();
}
?>