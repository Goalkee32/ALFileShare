<?php

class Server {
    // Serv initals
    private $dsn = "mysql:dbname=alfileshare;host=127.0.0.1";
    private $username = "root";
    private $password = "NhEtzNQ{48L3E0M"; // CHANGE THIS
    private $connection;

    // Create PDO Connection
    public function __construct() {
        $this->connection = null;
    }

    // Get PDO connection
    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO($this->dsn, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}
