<?php
// cerrar_sesion.php
include 'conexion.php'; 

// 1. Establecer el mensaje de despedida ANTES de destruir la sesión
$_SESSION['mensaje'] = [
    'tipo' => 'success',
    'texto' => 'Tu sesión ha sido cerrada exitosamente. ¡Vuelve pronto!'
];

// 2. Limpiar todas las variables de sesión
// Esto es importante para vaciar el arreglo $_SESSION, aunque session_destroy() se encargue de la limpieza total.
$_SESSION = array();

// 3. Si se desea destruir la cookie de sesión (la práctica segura)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión (el archivo en el servidor)
session_destroy();

// 5. Redirigir al formulario de acceso para mostrar el modal
header("Location: formulario_acceso.php");
exit();
?>