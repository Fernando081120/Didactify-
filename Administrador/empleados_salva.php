<?php
require "Funciones/conecta.php";
$con = conecta();

if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['correo'], $_POST['pass'], $_POST['rol'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $password = $_POST['pass'];
    $rol = $_POST['rol'];

    // Encriptación de la contraseña
    $passEnc = md5($password);

    // Verificar si el correo ya existe
    $queryVerificar = "SELECT * FROM empleados WHERE correo = '$correo'";
    $resultadoVerificar = mysqli_query($con, $queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        echo "El correo $correo ya está registrado. Por favor, utiliza otro.";
    } else {
    
        $archivo = $_FILES['archivo']['name']; 
        $archivo_tmp = $_FILES['archivo']['tmp_name']; 
        $arreglo = explode(".", $archivo);
        $ext = end($arreglo); 
        $dir = "archivos/"; 

        $archivo_n = md5_file($archivo_tmp) . ".$ext";

        if (copy($archivo_tmp, $dir . $archivo_n)) {
            $query = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, archivo, archivo_n) 
                      VALUES ('$nombre', '$apellidos', '$correo', '$passEnc', '$rol', '$archivo', '$archivo_n')";

            if (mysqli_query($con, $query)) {
                header('Location: empleados_lista.php');
                exit();
            } else {
                echo "Error al guardar el empleado: " . mysqli_error($con);
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
