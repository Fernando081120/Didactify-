<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    $query = "SELECT * FROM promociones WHERE nombre = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $nombre);
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
