<?php

class Database{
    private $host,$db,$user,$password,$charset,$pdo;

    function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db = $_ENV['DB_DATABASE'];
        $this->user = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->charset = $_ENV['DB_CHARSET'];
    }

    function connect()
    {
        try {
            $connection = "mysql:host=". $this->host .";dbname=". $this->db .";charset=". $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($connection,$this->user,$this->password, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            print_r("Error connection: ".$e->getMessage());
        }
    }
    
    function insert_id(){
        return $this->pdo->lastInsertId();
    }
}