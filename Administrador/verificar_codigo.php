<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];

    $query = "SELECT * FROM productos WHERE codigo = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $codigo);
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
