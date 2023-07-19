<?php
class Database{
    private $host,$db,$user,$password,$charset,$pdo;
    function __construct()
    {
        $this->host = 'localhost';
        $this->db = 'u266816196_ILPoF';
        $this->user = 'root';
        $this->password = '';
        $this->charset = 'utf8mb4';
    }

    function connect(){
        try {
            $connection = "mysql:host=". $this->host .";dbname=". $this->db .";charset=". $this->charset;
            $options = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    =>PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES      =>false,
            ];

            $this->pdo = new PDO($connection,$this->user,$this->password,$options);
            return $this->pdo;
        } catch (PDOException $e) {
            print_r("Error connection: ".$e->getMessage());
        }
    }

    function insert_Id(){
        return $this->pdo->lastInsertId();
    }
}