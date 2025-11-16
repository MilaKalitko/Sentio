<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servidor = "localhost";
$usuario_bd = "root";    
$password_bd = "";       
$base_de_datos = "sentio_db"; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion = new mysqli($servidor, $usuario_bd, $password_bd, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de Conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>