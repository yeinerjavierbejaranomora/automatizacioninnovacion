<?php
class Database2 {
    private $host,$db,$user,$password,$charset,$pdo;
    function __construct()
    {
        $this->host = "172.16.15.155";
        $this->user = "VirtualIbero";
        $this->password = "V1rtu4|1b3r0";
    }

    function connect(){
        $conexion = new mysqli($this->host, $this->user, $this->password);
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        $query = "SHOW DATABASES";
        $resultado = $conexion->query($query);
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
}