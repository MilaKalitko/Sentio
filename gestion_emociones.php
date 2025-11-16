<?php
include 'conexion.php'; 

// --- PROTECCIÓN DE ACCESO: SOLO ADMIN ---
// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: formulario_acceso.php");
    exit();
}
// 2. Verificar si el rol es 'admin'
if ($_SESSION['rol'] !== 'admin') {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Acceso denegado. No tienes permisos de administrador.'];
    header("Location: index.php");
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
    <link rel="stylesheet" href="styless.css">
    <title>Sentio / Gestión de Emociones</title>
</head>
<body>
    <?php include 'menu_admin.php'; ?> 

    <main class="help-page-container">
        <div class="help-card" style="max-width: 800px;">
            <h1 class="help-title">Cargar Nueva Emoción</h1>
            
            <form action="procesar_emocion_admin.php" method="POST" enctype="multipart/form-data" class="formulario">
                
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

            <hr style="margin: 30px 0;">
            <h2 class="help-title" style="font-size: 1.8em;">Emociones Existentes</h2>
            
            <div class="listado-emociones">
                <?php if ($resultado_emociones->num_rows > 0): ?>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <thead>
                            <tr style="background-color: #CDB4F4; color: #2D2D2D;">
                                <th style="padding: 10px; border: 1px solid #ddd;">ID</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Nombre</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Color</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Carita</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($emocion = $resultado_emociones->fetch_assoc()): ?>
                            <tr>
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;"><?php echo $emocion['id_emocion']; ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($emocion['nombre']); ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background-color: <?php echo htmlspecialchars($emocion['color_hex']); ?>; border: 1px solid #333;"></span>
                                    <?php echo htmlspecialchars($emocion['color_hex']); ?>
                                </td>
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    <img src="<?php echo htmlspecialchars($emocion['archivo_carita']); ?>" alt="<?php echo htmlspecialchars($emocion['nombre']); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                </td>
                                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                    <form action="procesar_emocion_admin.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta emoción?');">
                                        <input type="hidden" name="id_emocion_eliminar" value="<?php echo $emocion['id_emocion']; ?>">
                                        <button type="submit" class="submit" style="background-color: #FF9AA2; padding: 5px 10px; margin: 0; width: auto; font-size: 14px;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center;">Aún no hay emociones cargadas en el sistema. ¡Usa el formulario de arriba!</p>
                <?php endif; ?>
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

<?php 
if (isset($conexion) && is_object($conexion)) {
    $conexion->close();
}
?>