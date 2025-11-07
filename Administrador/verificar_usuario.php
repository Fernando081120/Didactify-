<?php
require 'Funciones/conecta.php';
$con = conecta();

// Bloquear acceso directo (solo permitir POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Acceso no permitido');
}

// Validar que existan los datos requeridos
if (empty($_POST['correo']) || empty($_POST['pass'])) {
    echo 'no existe';
    exit;
}

$correo = $_POST['correo'];
$pass   = $_POST['pass'];

// Consulta preparada para seguridad
$stmt = $con->prepare("SELECT * FROM empleados WHERE correo = ? AND eliminado = 0");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    // De momento comparas en texto plano
    if ($usuario['pass'] === $pass) {
        session_start();
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_correo'] = $usuario['correo'];

        echo 'existe';
        exit;
    } else {
        echo 'no existe';
    }
} else {
    echo 'no existe';
}

$stmt->close();
$con->close();
?>


