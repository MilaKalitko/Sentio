<?php
include 'conexion.php'; 

// Verifica si el usuario está logueado para mostrar contenido o menú dinámico
$usuario_logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE;
$id_usuario = $usuario_logueado ? $_SESSION['id_usuario'] : 0;
$fecha_hoy = date('Y-m-d');

// --- 1. CONSULTA DE EMOCIÓN REGISTRADA HOY ---
$emocion_del_dia = null;
if ($usuario_logueado) {
    $sql_registro = "
        SELECT 
            ed.nombre, 
            ed.archivo_muneco, 
            ed.color_hex
        FROM emociones_registro er
        JOIN emociones_disponibles ed ON er.id_emocion = ed.id_emocion
        WHERE er.id_usuario = ? AND DATE(er.fecha_registro) = ?
        LIMIT 1";

    $stmt_registro = $conexion->prepare($sql_registro);
    if ($stmt_registro) {
        $stmt_registro->bind_param("is", $id_usuario, $fecha_hoy);
        $stmt_registro->execute();
        $resultado_registro = $stmt_registro->get_result();
        
        if ($resultado_registro->num_rows > 0) {
            $emocion_del_dia = $resultado_registro->fetch_assoc();
        }
        $stmt_registro->close();
    }
}

// --- 2. CONSULTA DE EMOCIONES DISPONIBLES ---
$emociones = [];
$sql_disponibles = "SELECT id_emocion, nombre, archivo_carita, archivo_muneco, color_hex FROM emociones_disponibles ORDER BY nombre ASC";
$resultado_disponibles = $conexion->query($sql_disponibles);

if ($resultado_disponibles) {
    while ($fila = $resultado_disponibles->fetch_assoc()) {
        $emociones[] = $fila;
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sentir esta bien">
    <meta name="author" content="Milani Kalitko">
    <link rel="stylesheet" href="styless.css"> 
    <title>Sentio</title>
    <link rel="icon" href="./assets/icono.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div id="modal-mensaje" class="modal-oculto"> 
        <div class="modal-contenido">
            <h2 id="modal-titulo"></h2>
            <p id="modal-texto"></p>
            <button id="modal-cerrar" class="btn-modal-cerrar">Aceptar</button> 
        </div>
    </div>
    
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
    <main class="emociones">
        <article>
            <div class="emocion_diaria" id="emocion-diaria-box" style="
                <?php if ($emocion_del_dia) echo 'background-color: ' . htmlspecialchars($emocion_del_dia['color_hex']) . '30;'; ?>
            ">
                <?php if ($emocion_del_dia): ?>
                    <?php $nombre_capitalizado = ucfirst(htmlspecialchars($emocion_del_dia['nombre'])); ?>
                    <h1>¡Hoy te sentiste <?php echo $nombre_capitalizado; ?>!</h1>
                    <img id="muneco-diario" src="<?php echo htmlspecialchars($emocion_del_dia['archivo_muneco']); ?>" alt="Muñeco con la emoción <?php echo $nombre_capitalizado; ?>">
                    <p>¡Registro guardado! Vuelve mañana para un nuevo registro.</p>
                <?php else: ?>
                    <h1>Selecciona tu emoción</h1>
                    <img id="muneco-diario" src="./assets/munequito.png" alt="Ilustración de una persona">
                    <p>Recuerda los días grises son una oportunidad de empezar de nuevo</p>
                <?php endif; ?>
            </div>
        </article>
        
        <?php if (!$emocion_del_dia): ?>
            <article>
                <div class="registro_emocion">
                    <h2>¿Cómo te sientes hoy?</h2>
                    
                    <form action="procesar_registro_diario.php" method="POST" id="form-registro-emocion">
                        <input type="hidden" name="emocion_id" id="emocion-seleccionada-id" value="">

                        <div class="opciones_emociones">
                            <?php if (empty($emociones)): ?>
                                <p style="text-align: center; grid-column: span 3;">Aún no hay emociones cargadas por el administrador.</p>
                            <?php else: ?>
                                <?php foreach ($emociones as $emocion): ?>
                                    <div>
                                        <img 
                                            src="<?php echo htmlspecialchars($emocion['archivo_carita']); ?>" 
                                            alt="<?php echo htmlspecialchars($emocion['nombre']); ?>"
                                            data-id="<?php echo $emocion['id_emocion']; ?>"
                                            data-emocion="<?php echo htmlspecialchars($emocion['nombre']); ?>"
                                            data-muneco-src="<?php echo htmlspecialchars($emocion['archivo_muneco']); ?>"
                                            data-color="<?php echo htmlspecialchars($emocion['color_hex']); ?>"
                                            class="carita-opcion"
                                        >
                                        <p><?php echo htmlspecialchars($emocion['nombre']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="registro-nota-container"> 
                            <label for="nota">Añadir un comentario (opcional):</label>
                            <textarea 
                                id="nota" 
                                name="nota" 
                                rows="2" 
                                placeholder="¿Qué te hizo sentir así hoy?" 
                                class="registro-nota-textarea"
                            ></textarea>
                        </div>
                        
                        <button id="btn-registrar-emocion" type="submit" disabled>
                            Registrar emoción
                        </button>
                    </form>
                    
                    <?php if (!$usuario_logueado): ?>
                        <p class="aviso-no-logueado">
                            Debes <a href="formulario_acceso.php">iniciar sesión</a> para registrar tu emoción.
                        </p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endif; ?>
    </main>

    <footer>
        <p>No estas solo</p>
        <a href="ayuda.html" style="color: #FF9AA2; cursor: pointer;">Si necesitas ayuda apreta aquí</a>
    </footer>
    
    <script>
        const emocionesData = <?php echo json_encode($emociones); ?>;
        const usuarioLogueado = <?php echo json_encode($usuario_logueado); ?>;
        const mensajePHP = <?php echo json_encode(isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null); ?>;
        <?php if (isset($_SESSION['mensaje'])) unset($_SESSION['mensaje']); ?>
    </script>
    
    <script src="scripts.js"></script>
</body>
</html>