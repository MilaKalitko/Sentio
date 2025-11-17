💖 Sentio: Diario Emocional Interactivo
Sentio es una aplicación web de bienestar diseñada para ayudar a los usuarios a registrar, visualizar y comprender sus estados de ánimo diariamente. El proyecto implementa una arquitectura Full-Stack (LAMP) con autenticación segura, un panel administrativo dinámico y una visualización de historial interactiva.

Funcionalidades Principales
Registro Diario Dinámico: El usuario selecciona su emoción del día de una lista dinámica cargada por el administrador.
Visualización Interactiva: Un calendario que se pinta automáticamente con el color asociado a la emoción registrada en cada día. Al hacer clic en un día, un popup muestra la emoción y la nota de comentario guardada.
Autenticación Segura: Sistema completo de login, registro y perfil de usuario con control de acceso por rol.
Panel de Administración (RBAC): Una interfaz protegida para que el administrador gestione las emociones disponibles, incluyendo la carga de imágenes, códigos de color (color_hex) y la eliminación de registros.
Estadísticas de Historial: Dashboard que muestra un resumen de las emociones más frecuentes del usuario.

Stack Tecnologico
Backend y DB: PHP/MYSQL
Fronted: HTML5 y CSS3
Interactividad: JavaScript y JQuery
Entorno: XAMPP

Aspectos de Seguridad
Se implementaron medidas de seguridad esenciales para proteger la integridad de los datos:
Hashing de Contraseñas: Se utiliza la función nativa password_hash() de PHP para almacenar las credenciales de forma irreversible.
Protección contra Inyección SQL: Todas las interacciones con la base de datos (registros, consultas de perfil) utilizan Sentencias Preparadas ($stmt->prepare).
Integridad de Datos: Uso de Claves Foráneas (Foreign Keys) para mantener la consistencia entre los registros de usuarios y las emociones disponibles.
Inyección de Lógica: Se implementó una técnica de inyección de lógica JavaScript para pintar el calendario de forma segura, pasando los datos vía JSON.

Estructura del Proyecto
El proyecto ha sido refactorizado a una estructura MVC ligera para un mantenimiento óptimo:
Sentio/
├── assets/                  # Imágenes, iconos, y JS de datos (postsData.js)
├── includes/                # Archivos PHP incluidos (conexion.php, cerrar_sesion.php, menu_admin.php)
├── lib/                     # Librerías externas (jQuery Calendar Date Picker)
├── paginas/                 # Vistas principales (calendario.php, perfil.php, formularios, etc.)
├── procesadores/            # Scripts de lógica de negocio (procesar_registro.php, iniciar_sesion.php)
├── index.php                # Página de inicio y registro diario (Raíz)
├── scripts.js               # Lógica de frontend
└── styless.css              # Estilos CSS generales

Guía de Instalación y Ejecución
Para levantar el proyecto localmente, sigue estos pasos:
Instalar XAMPP: Asegúrate de tener XAMPP (o WAMP/MAMP) instalado y ejecutando Apache y MySQL.
Clonar Repositorio: Clona este repositorio dentro de la carpeta htdocs de XAMPP.
git clone https://github.com/MilaKalitko/Sentio.git

Configurar Base de Datos:
Crea una base de datos llamada sentio_db en phpMyAdmin.
Ejecuta las sentencias SQL para crear las tablas usuarios, emociones_disponibles y emociones_registro. (Las sentencias SQL no se suben a Git por seguridad).

Acceder: Abre tu navegador y navega a la URL del proyecto:
http://localhost/Sentio/index.php
NOTA: La funcionalidad de gestión de emociones requiere que el administrador haya subido imágenes PNG previamente a la carpeta assets/emociones/.
