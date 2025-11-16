<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styless.css">
    <title>Sentio / Acceso</title>
</head>

<body>
    <?php
include 'conexion.php'; 
$mensaje_js = null;
if (isset($_SESSION['mensaje'])) {
    $mensaje_js = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); 
}
?>
    <header>
        <div class="menu">
            <a href="index.php" class="logo-link"><img src="./assets/logo.png" alt="Logo de Sentio"></a>

            <nav id="nav-menu" class="nav">
                <ul class="nav-list">
                    <li><a href="index.php">Registro diario</a></li>
                     <li><a href="calendario.php">Mi calendario</a></li>
                    <li><a href="herramientas.html">Herramientas</a></li>
                    <li><a href="formulario_acceso.php">Iniciar sesión</a></li>
                </ul>
            </nav>

            <button id="burger-button" class="burger-menu" aria-label="Abrir menú">
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
            </button>
        </div>
    </header>

    <div id="modal-mensaje" class="modal-oculto">
        <div class="modal-contenido">
            <h2 id="modal-titulo"></h2>
            <p id="modal-texto"></p>
            <button id="modal-cerrar" class="btn-modal-cerrar">Aceptar</button> 
        </div>
    </div>
</div>
    <section class="formularios_sesion">

        <div id="form-login" class="iniciar_sesion">
    <div class="titulo">
        <h1>Bienvenido a Sentio</h1>
        <p>La web que cuida tus sentimientos</p>
    </div>
    <div class="titulo">
        <h2>Iniciar Sesión</h2>
        <p>Complete los siguientes campos</p>
    </div>
    <form class="formulario" action="iniciar_sesion.php" method="POST">
        <label for="user-login">Nombre de usuario:</label>
        <input type="text" id="user-login" name="username" placeholder="Usuario..." required>
        
        <label for="pass-login">Contraseña:</label>
        <input type="password" id="pass-login" name="password" placeholder="Contraseña..." required>
        
        <button class="submit" type="submit">Iniciar Sesión</button>
    </form> 
    <p class="texto_alternativo">
        ¿No tienes cuenta? <a href="#" id="show-register">Registrate aquí</a>
    </p>
</div>

        <div id="form-register" class="iniciar_sesion form-oculto">
    <div class="titulo">
        <h1>Crea tu Cuenta</h1>
        <p>Es rápido y sencillo</p>
    </div>
    <div class="titulo">
        <h2>Registro</h2>
        <p>Complete los siguientes campos</p>
    </div>
    <form class="formulario" action="procesar_registro.php" method="POST">
        <label for="user-register">Nombre de usuario:</label>
        <input type="text" id="user-register" name="username" placeholder="Usuario..." required>

        <label for="email-register">Correo electrónico:</label>
        <input type="email" id="email-register" name="email" placeholder="Correo electrónico..." required>

        <label for="pass-register">Contraseña:</label>
        <input type="password" id="pass-register" name="password" placeholder="Contraseña..." required>

        <button class="submit" type="submit">Registrarse</button>
    </form>
    <p class="texto_alternativo">
        ¿Ya tienes cuenta? <a href="#" id="show-login">Inicia sesión aquí</a>
    </p>
</div>

    </section>

    <script>
        const mensajePHP = <?php echo json_encode($mensaje_js); ?>;
    </script>
    <script src="scripts.js"></script>
</body>
</html>