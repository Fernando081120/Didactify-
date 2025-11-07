<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['nombre'], $_POST['codigo'], $_POST['descripcion'], $_POST['costo'], $_POST['stock'])) {
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $stock = $_POST['stock'];

    // Encriptación de la contraseña
    //$passEnc = md5($password);

    // Verificar si el correo ya existe
    $queryVerificar = "SELECT * FROM productos WHERE codigo = '$codigo'";
    $resultadoVerificar = mysqli_query($con, $queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        echo "El codigo $codigo ya está registrado. Por favor, utiliza otro.";
    } else {
    
        $archivo = $_FILES['archivo']['name']; 
        $archivo_tmp = $_FILES['archivo']['tmp_name']; 
        $arreglo = explode(".", $archivo);
        $ext = end($arreglo); 
        $dir = "archivos/"; 

        $archivo_n = md5_file($archivo_tmp) . ".$ext";

        if (copy($archivo_tmp, $dir . $archivo_n)) {
            $query = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, archivo_n, archivo) 
                      VALUES ('$nombre', '$codigo','$descripcion', '$costo', '$stock', '$archivo_n', '$archivo')";

            if (mysqli_query($con, $query)) {
                header('Location: productos_lista.php');
                exit();
            } else {
                echo "Error al guardar el producto: " . mysqli_error($con);
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
