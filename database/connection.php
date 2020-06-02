<?php

class Database
{
    // Credentials
    private $servername = 'localhost';
    private $db_name = 'track_order';
    private $username = 'admin';
    private $password = 'admin';
    public $conn;

    // Connection to db
    public function openConnection()
    {
        try {
            $this->conn = null;
            $dsn = "mysql:host=$this->servername;dbname=$this->db_name";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
        return $this->conn;
    }
}
