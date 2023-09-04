<?php
class Database{
    private $host,$db,$user,$password,$charset,$pdo;
    function __construct()
    {
        /*$this->host = 'localhost';
        $this->db = 'u266816196_ILPoF';
        $this->user = 'u266816196_YCF0b';
        $this->password = 'Yeiner91041755542.';
        $this->charset = 'utf8mb4';*/
        $this->host = "127.0.0.1";
        $this->user = "u266816196_YCF0b";
        $this->password = "Yeiner91041755542.";   
    }

    function connect(){
        /*try {
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
        }*/
        try {
        
            // Crea una instancia de PDO
            $dsn = "mysql:host=".$this->host .";port=3306";
            $conexion = new PDO($dsn, $this->user, $this->password);
        
            // Configura PDO para lanzar excepciones en errores
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            // Ejecuta una consulta para obtener la lista de bases de datos
            $consulta = $conexion->query("SHOW DATABASES");
        
            // Obtiene los resultados en un array
            $basesDeDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            // Itera a través de la lista de bases de datos
            foreach ($basesDeDatos as $fila) {
                echo $fila['Database'] . "<br>";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    function insert_Id(){
        return $this->pdo->lastInsertId();
    }
}