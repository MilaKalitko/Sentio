<?php
include '../includes/conexion.php';

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
    header("Location: ../includes/cerrar_sesion.php");
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
    <link rel="stylesheet" href="../styless.css">
    <title>Sentio / Perfil de Usuario</title>
</head>

<body>
    <header>
        <div class="menu">
            <a href="../index.php" class="logo-link"><img src="../assets/logo.png" alt="Logo de Sentio"></a>
            
            <nav id="nav-menu" class="nav">
                <ul class="nav-list">
                    <li><a href="../index.php">Registro diario</a></li>
                    <li><a href="calendario.php">Mi calendario</a></li>
                    <li><a href="herramientas.php">Herramientas</a></li>
                    
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
        <div class="help-card perfil-card">
            <h1 class="help-title">Hola, <?php echo htmlspecialchars($datos_usuario['username']); ?></h1>
            <p>Aquí puedes revisar la información de tu cuenta.</p>
            
            <hr class="info-separador">
            
            <div class="perfil-info-group">
                <p class="perfil-label">Correo Electrónico:</p>
                <p class="perfil-value"><?php echo htmlspecialchars($datos_usuario['email']); ?></p>
            </div>
            
            <div class="perfil-info-group">
                <p class="perfil-label">Miembro desde:</p>
                <p class="perfil-value"><?php echo date("d/m/Y", strtotime($datos_usuario['fecha_registro'])); ?></p>
            </div>
            
            <a href="../includes/cerrar_sesion.php" class="btn-explorar perfil-logout-btn">
                Cerrar Sesión
            </a>
        </div>
    </main>

    <script src="../scripts.js"></script> 
</body>
</html>