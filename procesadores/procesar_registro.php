<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitización y obtención de datos
    $username = $conexion->real_escape_string($_POST['username']);
    $email = $conexion->real_escape_string($_POST['email']);
    $password = $_POST['password']; 
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $fecha_registro = date("Y-m-d H:i:s");
    $sql = "INSERT INTO usuarios (username, email, password_hash, fecha_registro, rol) 
             VALUES ('$username', '$email', '$password_hash', '$fecha_registro', 'user')";  

    // Bloque Try-Catch para manejar la duplicidad de entrada (Error 1062)
    try {
        $conexion->query($sql);
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => '¡Registro exitoso! Tu cuenta ha sido creada. Por favor, inicia sesión.'
        ];

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Error: El nombre de usuario o correo electrónico ya está registrado. Intenta con otro.'
            ];
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Hubo un error al procesar tu solicitud. Código: ' . $e->getCode()
            ];
        }
    }

    $conexion->close();
    header("Location: ../paginas/formulario_acceso.php"); 
    exit(); 
    
} else {
    header("Location: ../paginas/formulario_acceso.php"); 
    exit();
}
?>