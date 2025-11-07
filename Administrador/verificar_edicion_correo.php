<?php
require "Funciones/conecta.php";
$con = conecta();

$correo = $_POST['correo'];
$id = $_POST['id'];

$query = "SELECT id FROM empleados WHERE correo = '$correo' AND id != $id";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    echo 'existe';
} else {
    echo 'no_existe';
}
mysqli_close($con);
?>