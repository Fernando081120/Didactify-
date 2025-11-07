<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";
    $res = $con->query($sql);
    
    if ($res) {
        echo 1; 
    } else {
        echo 0;
    }
}
?>
