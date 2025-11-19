<?php
session_start();
include '../includes/conexion.php';

// --- PROTECCIÓN DE ACCESO: SOLO ADMIN ---
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] !== 'admin') {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Acceso denegado. No tienes permisos de administrador.'];
    header("Location: formulario_acceso.php");
    exit();
}

// Obtener todas las emociones disponibles para listarlas
$sql_select = "SELECT id_emocion, nombre, color_hex, archivo_carita FROM emociones_disponibles ORDER BY nombre ASC";
$resultado_emociones = $conexion->query($sql_select);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styless.css"> <title>Sentio / Gestión de Emociones</title>
</head>
<body>
    <?php include '../includes/menu_admin.php'; ?> <main class="help-page-container">
        <div class="help-card admin-gestion-card">
            <h1 class="help-title">Cargar Nueva Emoción</h1>
            
            <form action="../procesadores/procesar_emocion_admin.php" method="POST" enctype="multipart/form-data" class="formulario">
                
                <label for="nombre">Nombre de la Emoción:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="color">Código de Color (HEX):</label>
                <input type="color" id="color" name="color_hex" value="#FF9AA2" required>
                
                <label for="carita">Archivo de la Carita (PNG):</label>
                <input type="file" id="carita" name="archivo_carita" accept=".png" required>
                
                <label for="muneco">Archivo del Muñeco (PNG):</label>
                <input type="file" id="muneco" name="archivo_muneco" accept=".png" required>
                
                <button class="submit" type="submit">Guardar Emoción</button>
            </form>

            <hr class="admin-separador-line">
            <h2 class="admin-subtitle">Emociones Existentes</h2>
            
            <div class="listado-emociones">
                <?php if ($resultado_emociones->num_rows > 0): ?>
                    <table class="emocion-table">
                        <thead>
                            <tr class="table-header-row">
                                <th class="table-cell">ID</th>
                                <th class="table-cell">Nombre</th>
                                <th class="table-cell">Color</th>
                                <th class="table-cell">Carita</th>
                                <th class="table-cell">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($emocion = $resultado_emociones->fetch_assoc()): ?>
                            <tr>
                                <td class="table-cell table-cell-center"><?php echo $emocion['id_emocion']; ?></td>
                                <td class="table-cell"><?php echo htmlspecialchars($emocion['nombre']); ?></td>
                                <td class="table-cell table-cell-center">
                                    <span class="color-preview-circle" style="background-color: <?php echo htmlspecialchars($emocion['color_hex']); ?>;"></span>
                                    <?php echo htmlspecialchars($emocion['color_hex']); ?>
                                </td>
                                <td class="table-cell table-cell-center">
                                    <img src="<?php echo htmlspecialchars('../' . $emocion['archivo_carita']); ?>" alt="<?php echo htmlspecialchars($emocion['nombre']); ?>" class="emocion-preview-img">
                                </td>
                                <td class="table-cell table-cell-center">
                                    <form action="../procesadores/procesar_emocion_admin.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta emoción?');">
                                        <input type="hidden" name="id_emocion_eliminar" value="<?php echo $emocion['id_emocion']; ?>">
                                        <button type="submit" class="submit delete-button">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="admin-vacio-msg">Aún no hay emociones cargadas en el sistema. ¡Usa el formulario de arriba!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        const mensajePHP = <?php echo json_encode(isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null); ?>;
        <?php if (isset($_SESSION['mensaje'])) unset($_SESSION['mensaje']); ?>
    </script>
    <script src="../scripts.js"></script> </body>
</html>
