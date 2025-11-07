<?php
require "Funciones/conecta.php";
$con = conecta();

$nombre = $_POST['nombre'];
$id = $_POST['id'];

$query = "SELECT id FROM promociones WHERE nombre = '$nombre' AND id != $id";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    echo 'existe';
} else {
    echo 'no_existe';
}
mysqli_close($con);
?>