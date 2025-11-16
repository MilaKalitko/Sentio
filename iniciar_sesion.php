<?php
include 'conexion.php'; 
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $destino = ($_SESSION['rol'] === 'admin') ? 'admin.php' : 'index.php';
    header("Location: " . $destino); 
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username_ingresado = $conexion->real_escape_string($_POST['username']);
    $password_ingresada = $_POST['password'];
    $sql = "SELECT id_usuario, username, password_hash, rol FROM usuarios WHERE username = '$username_ingresado'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        $password_hash_bd = $usuario['password_hash'];

        // 3. Verificar la contraseña
        if (password_verify($password_ingresada, $password_hash_bd)) {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['rol'];
            $conexion->close();
            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin.php"); 
            } else {
                header("Location: index.php"); 
            }
            exit();

        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Contraseña incorrecta. Verifica tu usuario y contraseña.'
            ];
        }

    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Nombre de usuario no encontrado.'
        ];
    }
    
    $conexion->close();
    header("Location: formulario_acceso.php"); 
    exit();
} else {
    header("Location: formulario_acceso.php");
    exit();
}
?>