<?php

include '../includes/conexion.php';

// Si no está logueado como admin o no es POST, redirigir
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] !== 'admin' || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../paginas/admin.php");
    exit();
}

// Lógica de ELIMINACIÓN
if (isset($_POST['id_emocion_eliminar'])) {
    $id_emocion = $_POST['id_emocion_eliminar'];
    
    $sql_rutas = "SELECT archivo_carita, archivo_muneco FROM emociones_disponibles WHERE id_emocion = ?";
    $stmt_rutas = $conexion->prepare($sql_rutas);
    $stmt_rutas->bind_param("i", $id_emocion);
    $stmt_rutas->execute();
    $resultado_rutas = $stmt_rutas->get_result();
    $emocion_data = $resultado_rutas->fetch_assoc();
    $stmt_rutas->close();
    
    $sql_delete = "DELETE FROM emociones_disponibles WHERE id_emocion = ?";
    $stmt_delete = $conexion->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_emocion);

    try {
        if ($stmt_delete->execute()) {
             if ($emocion_data) {
                $base_dir = __DIR__ . '/../';
                $path_carita = $base_dir . $emocion_data['archivo_carita'];
                $path_muneco = $base_dir . $emocion_data['archivo_muneco'];
                if (file_exists($path_carita)) {
                    unlink($path_carita);
                }
                if (file_exists($path_muneco)) {
                    unlink($path_muneco);
                }
            }
            
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => "Emoción eliminada exitosamente."];
        } else {
            throw new Exception("Error al ejecutar la eliminación en la base de datos.");
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) { 
            $_SESSION['mensaje'] = [
                'tipo' => 'error', 
                'texto' => "No se puede eliminar: Esta emoción ya está siendo utilizada por un registro diario de un usuario."
            ];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error de BD: " . $e->getMessage()];
        }
    }
    
    $stmt_delete->close();
    $conexion->close();
    header("Location: ../paginas/gestion_emociones.php");
    exit();
} 

$nombre_emocion = $conexion->real_escape_string(trim($_POST['nombre']));
$color_hex = $conexion->real_escape_string($_POST['color_hex']);
$carpeta_destino = "../assets/emociones/";

$archivos_subidos = [
    'carita' => $_FILES['archivo_carita'],
    'muneco' => $_FILES['archivo_muneco']
];
$rutas_finales = [];
$errores = [];

foreach ($archivos_subidos as $tipo => $archivo) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        $errores[] = "Error al subir el archivo de {$tipo}. Código: " . $archivo['error'];
        continue;
    }
    if (mime_content_type($archivo['tmp_name']) != 'image/png') {
        $errores[] = "El archivo de {$tipo} debe ser de formato PNG.";
        continue;
    }
    
    $nombre_base = strtolower(str_replace(' ', '_', $nombre_emocion)) . "_{$tipo}.png";
    $ruta_db = "assets/emociones/" . $nombre_base; 
    $ruta_final = $carpeta_destino . $nombre_base;
    
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_final)) {
        $errores[] = "Fallo al mover el archivo de {$tipo} al destino.";
        continue;
    }
    $rutas_finales[$tipo] = $ruta_db; 
}

// 2. Insertar en BD si no hay errores de archivo
if (empty($errores)) {
    $sql = "INSERT INTO emociones_disponibles (nombre, color_hex, archivo_carita, archivo_muneco) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    $stmt->bind_param("ssss", 
        $nombre_emocion, 
        $color_hex, 
        $rutas_finales['carita'], 
        $rutas_finales['muneco']
    );

    try {
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => "Emoción '{$nombre_emocion}' y archivos guardados exitosamente."];
        } else {
            throw new Exception("Fallo en la inserción SQL.");
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errores[] = "Ya existe una emoción con el nombre '{$nombre_emocion}'.";
        } else {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
    $stmt->close();
}

// 3. Manejo de Errores Final y Redirección
if (!empty($errores)) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => implode(" ", $errores)];
}

$conexion->close();
header("Location: ../paginas/gestion_emociones.php");
exit();
?>
