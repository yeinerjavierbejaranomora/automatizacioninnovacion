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
        $host = '127.16.15.155';
        $user = "VirtualIbero";
        $password = "V1rtu4|1b3r0";
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
        // Establece la conexión a MySQL
$conexion = new mysqli($this->host, $this->user, $this->password);

// Verifica si hay errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener la lista de bases de datos
$query = "SHOW DATABASES";
$resultado = $conexion->query($query);

// Verifica si la consulta fue exitosa
if ($resultado) {
    echo "Bases de datos disponibles:<br>";
    while ($fila = $resultado->fetch_assoc()) {
        echo $fila['Database'] . "<br>";
    }
} else {
    echo "Error al ejecutar la consulta: " . $conexion->error;
}

// Cierra la conexión
$conexion->close();
    }

    function insert_Id(){
        return $this->pdo->lastInsertId();
    }
}