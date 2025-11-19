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
    <title>Entrada del Blog - Sentio</title>
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

    <main class="blog-post-container">
        <article class="blog-post-card">
            <h1 id="post-title"></h1>
            <img id="post-image" src="" alt="Imagen principal del post">
            <div class="post-content-full">
                <h2 id="post-subtitle"></h2>
                <p id="post-text"></p>
            </div>
        </article>
    </main>

    <footer>
    <p>No estas solo</p>
    <a href="ayuda.php" class="footer-link-ayuda">Si necesitas ayuda apreta aquí</a> 
</footer>
    
    <script src="../assets/js/postsData.js"></script> 
    <script src="../scripts.js"></script>
</body>
</html>
