<?php
class Database{
    private $host,$db,$user,$password,$charset,$pdo;
    function __construct()
    {
        // $this->host = 'localhost';
        $this->host = '127.16.15.155';
        $this->db = 'u266816196_ILPoF';
        // $this->user = 'u266816196_YCF0b';
        $this->user = 'VirtualIbero';
        // $this->password = 'Yeiner91041755542.';
        $this->password = 'V1rtu4|1b3r0';
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
            echo "Conexion realizada con exito";
            //return $this->pdo;
        } catch (PDOException $e) {
            print_r("Error connection: ".$e->getMessage());
        }
    }

    function insert_Id(){
        return $this->pdo->lastInsertId();
    }
}