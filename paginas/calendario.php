<?php
session_start();
include '../includes/conexion.php';

// --- VERIFICACIÓN DE SESIÓN Y LÓGICA DE DATOS ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Debes iniciar sesión para ver tu calendario.'];
    header("Location: formulario_acceso.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$frases = [
    "La vida no se trata de esperar a que pase la tormenta, sino de aprender a bailar bajo la lluvia.",
    "El secreto para salir adelante es empezar.",
    "Hoy es un buen día para tener un buen día."
];
$frase_del_dia = $frases[array_rand($frases)];
$color_usuario = '#CDB4F4'; 

// --- CONSULTA PRINCIPAL DE REGISTROS Y ESTADÍSTICAS ---
$sql = "
    SELECT 
        er.fecha_registro,
        er.nota,
        ed.nombre AS emocion_nombre,
        ed.color_hex
    FROM 
        emociones_registro er
    JOIN 
        emociones_disponibles ed ON er.id_emocion = ed.id_emocion
    WHERE 
        er.id_usuario = ?
    ORDER BY 
        er.fecha_registro ASC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$registros_calendario = [];
$estadisticas_emociones = [];
$total_dias_registrados = 0;

while ($fila = $resultado->fetch_assoc()) {
    $fecha_solo_dia = date('Y-m-d', strtotime($fila['fecha_registro']));
    
    // 1. Datos para el Calendario
    $registros_calendario[$fecha_solo_dia] = [
        'color' => $fila['color_hex'],
        'emocion' => $fila['emocion_nombre'],
        'datetime' => $fila['fecha_registro'],
        'nota' => $fila['nota'] 
    ];
    
    // 2. Datos para Estadísticas
    $nombre = $fila['emocion_nombre'];
    if (!isset($estadisticas_emociones[$nombre])) {
        $estadisticas_emociones[$nombre] = ['count' => 0, 'color' => $fila['color_hex']];
    }
    $estadisticas_emociones[$nombre]['count']++;
    $total_dias_registrados++;
}

if (!empty($estadisticas_emociones)) {
    uasort($estadisticas_emociones, function($a, $b) { return $b['count'] <=> $a['count']; });
    $emocion_mas_frecuente = reset($estadisticas_emociones);
    $color_usuario = $emocion_mas_frecuente['color'];
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
    <title>Mi calendario</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../lib/multilingual-calendar-date-picker/jquery.calendar.css">
    <script src="../lib/multilingual-calendar-date-picker/jquery.calendar.js"></script>
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
                <span class="burger-bar"></span><span class="burger-bar"></span><span class="burger-bar"></span>
            </button>
        </div>
    </header>

    <main class="emociones-dashboard"> 
        
        <article class="calendario-cont">
            <h1 class="historial-titulo">Mi Historial</h1>
            <div id="mi-calendario-sentio"></div>
            
            <div id="emotion-popup" class="popup-emocion-detalle">
                <p class="popup-linea-emocion">
                    <strong>Emoción:</strong> <span id="popup-emocion" class="popup-emocion-nombre"></span>
                </p>
                <hr class="popup-separador">
                
                <p class="popup-linea-nota">
                    <span id="popup-nota" class="popup-nota-texto"></span>
                </p>
            </div>
        </article>

        <article class="estadisticas-cont">
            <div class="saludo-stats">
                <h1 class="saludo-usuario-color" style="color: <?php echo htmlspecialchars($color_usuario); ?>;">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                <hr class="stats-separador">
                <div id="stats-resumen">
                    <?php if (!empty($estadisticas_emociones)): ?>
                        <?php foreach ($estadisticas_emociones as $nombre => $datos): ?>
                            <p class="stats-linea">
                                Días <?php echo htmlspecialchars($nombre); ?>: 
                                <span class="stats-contador" style="color: <?php echo htmlspecialchars($datos['color']); ?>;">
                                    <?php echo $datos['count']; ?> días
                                </span>
                            </p>
                        <?php endforeach; ?>
                        <p class="stats-total">Total: <?php echo $total_dias_registrados; ?> registros.</p>
                    <?php else: ?>
                        <p>Aún no tienes registros de emociones. ¡Empieza en la página de Registro Diario!</p>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="stats-separador-grande">

            <div class="frase-diaria">
                <h2 class="frase-titulo">Frase del Día</h2>
                <p id="frase-texto" class="frase-texto">
                    "<?php echo htmlspecialchars($frase_del_dia); ?>"
                </p>
            </div>
        </article>
    </main>

    <footer>
        <p>No estas solo</p>
        <a href="ayuda.php" class="footer-link-ayuda">Si necesitas ayuda apreta aquí</a>
    </footer>
    
    <script src="../scripts.js"></script>
    <script>
    const registrosEmociones = <?php echo json_encode($registros_calendario); ?>; 

    $(document).ready(function() {
        $('#mi-calendario-sentio').calendar({
            isInline: true, 
            emotionsData: registrosEmociones, 
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            days: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do']
        });
        
        // Lógica para ocultar el popup al hacer clic fuera 
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#mi-calendario-sentio').length && !$(e.target).closest('#emotion-popup').length) {
                $('#emotion-popup').hide();
            }
        });
    });
    </script>
</body>
</html>