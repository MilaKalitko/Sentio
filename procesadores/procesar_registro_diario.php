<?php
include '../includes/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE || $_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Debes iniciar sesión para registrar tu emoción.'];
    header("Location: ../paginas/formulario_acceso.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_emocion = isset($_POST['emocion_id']) ? intval($_POST['emocion_id']) : 0;
$nota = $conexion->real_escape_string($_POST['nota'] ?? '');
$fecha_registro = date("Y-m-d H:i:s");

if ($id_emocion <= 0) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Por favor, selecciona una emoción antes de registrar.'];
    header("Location: ../index.php");
    exit();
}

$sql = "INSERT INTO emociones_registro (id_usuario, id_emocion, nota, fecha_registro) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    error_log("Error de preparación SQL: " . $conexion->error);
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error interno al preparar el registro.'];
    header("Location: ../index.php");
    exit();
}

$stmt->bind_param("iiss", $id_usuario, $id_emocion, $nota, $fecha_registro);

if ($stmt->execute()) {
    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '¡Emoción registrada exitosamente!'];
} else {
    error_log("Error de ejecución SQL: " . $stmt->error);
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'No se pudo guardar el registro. Falla de DB: ' . $stmt->error];
}

$stmt->close();
$conexion->close();
header("Location: ../index.php");
exit();
?>