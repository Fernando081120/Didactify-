<?php 
define("HOST",'localhost');
define("BD",'u133563347_proyecto');
define("USER_BD",'u133563347_root');
define("PASS_BD",'D1d4ct1F1');

function conecta() {
    $con = new mysqli(HOST, USER_BD, PASS_BD, BD);
    if($con->connect_error) {
        die("Error de conexion: " . $con->connect_error());
    }
    
    return $con;
}
?>