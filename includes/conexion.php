<?php
// 1. Iniciar la sesión (si no está iniciada)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Definición de la Base de Datos
$servidor = "localhost";
$usuario_bd = "root"; 	
$password_bd = ""; 	 
$base_de_datos = "sentio_db"; 

// 3. Configuración para lanzar excepciones en errores SQL (necesario para try...catch)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 4. Crear la conexión
$conexion = new mysqli($servidor, $usuario_bd, $password_bd, $base_de_datos);

// 5. Verificar la conexión inicial
if ($conexion->connect_error) {
    die("Error de Conexión: " . $conexion->connect_error);
}

// 6. Establecer juego de caracteres
$conexion->set_charset("utf8");
?>