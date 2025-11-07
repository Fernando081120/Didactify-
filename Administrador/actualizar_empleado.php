<?php
require "Funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['pass'];

if (!empty($password)) {
    $password = md5($password);
    $query = "UPDATE empleados SET nombre=?, apellidos=?, correo=?, rol=?, pass=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $nombre, $apellidos, $correo, $rol, $password, $id);
} else {
    $query = "UPDATE empleados SET nombre=?, apellidos=?, correo=?, rol=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $apellidos, $correo, $rol, $id);
}

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $nombreReal = $_FILES['archivo']['name'];
    $nombreEncriptado = md5(time() . $nombreReal) . "." . pathinfo($nombreReal, PATHINFO_EXTENSION);

    move_uploaded_file($archivoTmp, "archivos/$nombreEncriptado");

    $queryFoto = "UPDATE empleados SET archivo_n=?, archivo=? WHERE id=?";
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
