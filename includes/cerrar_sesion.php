<?php
include 'conexion.php'; 

// 2. Establecer el mensaje de despedida ANTES de destruir la sesión
$_SESSION['mensaje'] = [
    'tipo' => 'success',
    'texto' => 'Tu sesión ha sido cerrada exitosamente. ¡Vuelve pronto!'
];

// 3. Limpiar y destruir la sesión 
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: ../paginas/formulario_acceso.php");
exit();
?>