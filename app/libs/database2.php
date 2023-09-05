<?php
class Database2 {
    private $host,$db,$user,$password,$charset,$pdo;
    function __construct()
    {
        $this->host = "172.16.15.155";
        $this->db = "Moodle";
        $this->user = "VirtualIbero";
        $this->password = "V1rtu4|1b3r0";
    }

    function connect(){
        // $connectionOptions = [
        //     "Database" => $this->db,
        //     "Uid" => $this->user,
        //     "PWD" => $this->password
        // ];
        // var_dump($connectionOptions);die();
        try {
            $conn = new PDO("sqlsrv:Server=$this->host;database=$this->db", $this->user,$this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión establecida correctamente";
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }
}