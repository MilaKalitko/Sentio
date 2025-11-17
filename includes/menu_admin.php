<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="menu">
        <a href="../paginas/admin.php" class="logo-link"><img src="../assets/logo.png" alt="Logo de Sentio"></a>

        <nav id="nav-menu" class="nav">
            <ul class="nav-list">
                <li><a href="gestion_emociones.php">Gestionar Emociones</a></li>
                <li><a href="admin.php">Dashboard Admin</a></li>
                <li><a href="../includes/cerrar_sesion.php">Cerrar Sesión (<?php echo $_SESSION['username']; ?>)</a></li>
            </ul>
        </nav>

        <button id="burger-button" class="burger-menu" aria-label="Abrir menú">
            <span class="burger-bar"></span>
            <span class="burger-bar"></span>
            <span class="burger-bar"></span>
        </button>
    </div>
</header>