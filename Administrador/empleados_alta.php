<?php
session_start();

if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de empleados</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto; 
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        input[type="text"],
        input[type="password"],
        input[type='file'],
        select {
            width: calc(100% - 22px); 
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        #error,
        #mensaje-correo {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validarFormulario() {
            var nombre = $('#nombre').val();
            var apellidos = $('#apellidos').val();
            var correo = $('#correo').val();
            var pass = $('#pass').val();
            var rol = $('#rol').val();
            var archivo = $('input[type="file"]').val();
            var errorDiv = $('#error');

            errorDiv.html('');
            var mensajeError = '';

            if (!nombre || !apellidos || !correo || !pass || rol == "0" || !archivo) {
                mensajeError = "Faltan campos por llenar.";
                errorDiv.html(mensajeError).show();

                setTimeout(function () {
                    errorDiv.hide();
                }, 5000);
                return false; 
            }
            return true;
        }

        $(document).ready(function () {
            $('#correo').on('blur', function () {
                var correo = $(this).val();
                var mensajeCorreo = $('#mensaje-correo');
                mensajeCorreo.html(''); 

                if (correo) {
                    $.ajax({
                        url: 'verificar_correo.php', 
                        type: 'POST',
                        data: { correo: correo },
                        success: function (respuesta) {
                            if (respuesta === 'existe') {
                                mensajeCorreo.html('El correo ' + correo + ' ya existe.').show();

                                setTimeout(function () {
                                    mensajeCorreo.hide();
                                }, 5000);
                            }
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <?php
        include('menu_navegacion.php');
    ?>
    <h1>Alta de empleados</h1>

    <form name="Formulario01" method="post" action="empleados_salva.php" enctype="multipart/form-data" onsubmit="return validarFormulario();" novalidate>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Escribe tu nombre" required /> 

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" placeholder="Escribe tus apellidos" required /> 

        <label for="correo">Correo:</label>
        <input type="text" name="correo" id="correo" placeholder="Escribe tu correo" required />
        <div id="mensaje-correo"></div> 

        <label for="pass">Contraseña:</label>
        <input type="password" name="pass" id="pass" placeholder="Escribe tu password" required /> 

        <label for="rol">Rol:</label>
        <select name="rol" id="rol" required>
            <option value="0">Selecciona tu rol</option>
            <option value="1">Empleado</option>
            <option value="2">Gerente</option>
            <option value="3">Ejecutivo</option>
        </select><br>
        
        <label>Foto del Empleado:</label><br>
        <input type="file" name="archivo" required><br><br>

        <input type="submit" value="Guardar" />
        <div id="error"></div> 

        <a href="empleados_lista.php">Regresar al listado</a>
    </form>
</body>
</html>
