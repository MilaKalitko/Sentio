<?php
include 'conexion.php';

// Bloqueo de página: si no está logueado, redirige
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: formulario_acceso.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener la información completa del usuario
$sql = "SELECT username, email, fecha_registro FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {
    $datos_usuario = $resultado->fetch_assoc();
} else {
    header("Location: cerrar_sesion.php");
    exit();
}
$stmt->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styless.css">
    <title>Sentio / Perfil de Usuario</title>
</head>

<body>
    <header>
        <div class="menu">
            <a href="index.php" class="logo-link"><img src="./assets/logo.png" alt="Logo de Sentio"></a>
            
            <nav id="nav-menu" class="nav">
                <ul class="nav-list">
                    <li><a href="index.php">Registro diario</a></li>
                    <li><a href="calendario.php">Mi calendario</a></li>
                    <li><a href="herramientas.html">Herramientas</a></li>
                    
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
                        <li><a href="perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                    <?php else: ?>
                        <li><a href="formulario_acceso.php">Iniciar sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <button id="burger-button" class="burger-menu" aria-label="Abrir menú">
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
            </button>
        </div>
    </header>

    <main class="help-page-container"> 
        <div class="help-card" style="max-width: 500px; text-align: center;">
            <h1 class="help-title">Hola, <?php echo htmlspecialchars($datos_usuario['username']); ?></h1>
            <p>Aquí puedes revisar la información de tu cuenta.</p>
            
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <p style="font-weight: bold; margin-bottom: 5px;">Correo Electrónico:</p>
            <p style="margin-bottom: 20px;"><?php echo htmlspecialchars($datos_usuario['email']); ?></p>
            
            <p style="font-weight: bold; margin-bottom: 5px;">Miembro desde:</p>
            <p style="margin-bottom: 30px;"><?php echo date("d/m/Y", strtotime($datos_usuario['fecha_registro'])); ?></p>
            
            <a href="cerrar_sesion.php" class="btn-explorar" style="background-color: #FF9AA2; display: inline-block;">
                Cerrar Sesión
            </a>
        </div>
    </main>

    <script src="scripts.js"></script> 
</body>
</html>