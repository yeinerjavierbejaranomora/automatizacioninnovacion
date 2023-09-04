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
        try {
        
            // Crea una instancia de PDO
            $conexion = new PDO($this->host, $this->user, $this->password);
        
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
}