<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    // Encriptación de la contraseña
    //$passEnc = md5($password);

    // Verificar si el correo ya existe
    $queryVerificar = "SELECT * FROM promociones WHERE nombre = '$nombre'";
    $resultadoVerificar = mysqli_query($con, $queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        echo "El nombre $nombre ya está registrado. Por favor, utiliza otro.";
    } else {
    
        $archivo = $_FILES['archivo']['name']; 
        $archivo_tmp = $_FILES['archivo']['tmp_name']; 
        $arreglo = explode(".", $archivo);
        $ext = end($arreglo); 
        $dir = "archivos/"; 

        $archivo_n = md5_file($archivo_tmp) . ".$ext";

        if (copy($archivo_tmp, $dir . $archivo_n)) {
            $query = "INSERT INTO promociones (nombre, archivo_n, archivo) 
                      VALUES ('$nombre', '$archivo_n', '$archivo')";

            if (mysqli_query($con, $query)) {
                header('Location: promociones_lista.php');
                exit();
            } else {
                echo "Error al guardar la promocion: " . mysqli_error($con);
            }
        } else {
            echo "Error al subir la foto.";
        }
    }
} else {
    echo "Faltan campos por llenar.";
}

mysqli_close($con);
?>
