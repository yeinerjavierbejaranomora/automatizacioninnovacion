<?php
// require_once 'libs/database.php';
// require_once 'libs/database2.php';
// require_once 'libs/controller.php';
// require_once 'libs/app.php';
$host = "172.16.15.155";
$usuario = "VirtualIbero";
$contraseña = "V1rtu4|1b3r0";

// Establece la conexión a MySQL
$conexion = new mysqli($host, $usuario, $contraseña);

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