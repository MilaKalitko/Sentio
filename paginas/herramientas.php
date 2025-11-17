<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styless.css">
    <link rel="icon" href="../assets/icono.ico"> <title>Herramientas</title>
</head>
<body>
    <?php
session_start(); 
include '../includes/conexion.php'; 

$usuario_logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE;
?>
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
    
    <main>
        <section class="hero">
            <div class="hero-content">
                <img src="../assets/logo.png" alt="Sentio">
                <h1>TU BIENESTAR IMPORTA</h1>
                <p>Por eso creamos este espacio con recursos pensados especialmente para ti.</p>
                <a href="#posts" class="btn-explorar">Explorar ahora</a>
            </div>
            </section>

        <section id="posts" class="posts-section">
            <h2>Últimos artículos</h2>
            <div class="posts-grid">
            </div>
        </section>
    </main>

    <script src="../assets/js/postsData.js"></script> 
    <script src="../scripts.js"></script>
</body>
</html>