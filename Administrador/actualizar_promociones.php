<?php
require "Funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];
$nombre = $_POST['nombre'];


if (!empty($nombre)) {
    //$password = md5($password);
    $query = "UPDATE promociones SET nombre=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $nombre, $id);
} /**else {
    $query = "UPDATE promociones SET nombre=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $id);
}*/

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $nombreReal = $_FILES['archivo']['name'];
    $nombreEncriptado = md5(time() . $nombreReal) . "." . pathinfo($nombreReal, PATHINFO_EXTENSION);

    move_uploaded_file($archivoTmp, "archivos/$nombreEncriptado");

    $queryFoto = "UPDATE promociones SET archivo_n=?, archivo=? WHERE id=?";
    $stmtFoto = $con->prepare($queryFoto);
    $stmtFoto->bind_param("ssi", $nombreEncriptado, $nombreReal, $id);
    $stmtFoto->execute();
}

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$con->close();
?>
