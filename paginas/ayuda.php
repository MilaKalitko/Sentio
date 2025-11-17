<?php
session_start();
include '../includes/conexion.php'; 

// Verifica si el usuario está logueado para mostrar el menú dinámico
$usuario_logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styless.css">
    <title>Ayuda y Contacto - Sentio</title>
</head>
<body class="ayuda-page">
    <header>
        <div class="menu">
            <a href="../index.php" class="logo-link"><img src="../assets/logo.png" alt="Logo de Sentio"></a>
            <nav id="nav-menu" class="nav">
                <ul class="nav-list">
                    <li><a href="../index.php">Registro diario</a></li>
                    <li><a href="calendario.php">Mi calendario</a></li>
                    <li><a href="herramientas.php">Herramientas</a></li>
                    
                    <?php if ($usuario_logueado): ?>
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
        <section class="help-card">
            <h1 class="help-title">Recuerda, tú eres importante</h1>
            <p>A veces las cosas pueden sentirse difíciles, pero no estás solo/a.
                Pedir ayuda no está mal — al contrario, es un paso valiente y lleno de amor propio 💕</p>
            <p>Si necesitas hablar con alguien o recibir orientación, hay líneas
                disponibles las 24 horas, todos los días del año.
                Puedes comunicarte de forma gratuita y confidencial con los
                siguientes números:</p>
            
            <ul>
                <li class="linea-salud"><strong>Salud Mental: 0800 999 0091</strong> (Apoyo y orientación sobre salud mental, disponible las 24 hs)</li>
                <li class="linea-violencia"><strong>Violencia Familiar o Sexual: 137</strong> (Atención inmediata y acompañamiento)</li>
                <li class="linea-mujer"><strong>Mujeres en situación de violencia: 144</strong> (Línea gratuita y confidencial)</li>
            </ul>

            <p class="final-message">✨ No dudes en llamar. Tu bienestar importa. Tu vida importa. Tú importas.</p>
        </section>
    </main>

    <footer>
        <p>No estas solo</p>
        <a href="ayuda.php" class="footer-link-ayuda">Si necesitas ayuda apreta aquí</a>
    </footer>
    
    <script src="../scripts.js"></script>
</body>
</html>