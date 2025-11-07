<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['correo'])) {
    $correo = $_POST['correo'];

    $query = "SELECT * FROM empleados WHERE correo = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo 'existe'; 
    } else {
        echo 'no existe';
    }

    $stmt->close();
}

$con->close(); 
?>
