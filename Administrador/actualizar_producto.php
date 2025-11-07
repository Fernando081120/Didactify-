<?php
require "Funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descipcion = $_POST['descripcion'];
$codigo = $_POST['codigo'];
$stock = $_POST['stock'];
$costo = $_POST['costo'];

if (!empty($codigo)) {
    //$password = md5($password);
    $query = "UPDATE productos SET nombre=?, descripcion=?, codigo=?, costo=?, stock=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $nombre, $descipcion, $codigo, $costo, $stock, $id);
} else {
    $query = "UPDATE productos SET nombre=?, descripcion=?, costo=?, stock=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $descipcion, $costo, $stock, $id);
}

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $nombreReal = $_FILES['archivo']['name'];
    $nombreEncriptado = md5(time() . $nombreReal) . "." . pathinfo($nombreReal, PATHINFO_EXTENSION);

    move_uploaded_file($archivoTmp, "archivos/$nombreEncriptado");

    $queryFoto = "UPDATE productos SET archivo_n=?, archivo=? WHERE id=?";
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
