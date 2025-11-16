<?php
// admin.php
include 'conexion.php'; 

// --- PROTECCIÓN DE ACCESO: SOLO ADMIN ---
// Si no está logueado o no es administrador, redirige
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] !== 'admin') {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Acceso denegado.'];
    header("Location: formulario_acceso.php");
    exit();
}

// ----------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styless.css">
    <title>Sentio / Panel de Administración</title>
</head>
<body>
    
    <?php include 'menu_admin.php'; ?> 

    <main class="help-page-container">
        <div class="help-card" style="max-width: 800px; text-align: center;">
            <h1 class="help-title">Bienvenido, Administrador (<?php echo $_SESSION['username']; ?>)</h1>
            <p style="font-size: 1.2em; margin-bottom: 20px;">
                Desde aquí puedes gestionar el contenido central de Sentio.
            </p>
            
            <div style="display: flex; justify-content: space-around; gap: 20px;">
                <a href="gestion_emociones.php" class="submit" style="flex-grow: 1;">Gestionar Emociones</a>
                </div>
        </div>
    </main>

    <script>
        const mensajePHP = <?php echo json_encode(isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null); ?>;
        <?php if (isset($_SESSION['mensaje'])) unset($_SESSION['mensaje']); ?>
    </script>
    <script src="scripts.js"></script>
</body>
</html>