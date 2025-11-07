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
    <title>Alta de promociones</title>
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
        #mensaje-codigo {
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
            var archivo = $('input[type="file"]').val();
            var errorDiv = $('#error');

            errorDiv.html('');
            var mensajeError = '';

            if (!nombre || !archivo) {
                mensajeError = "Faltan campos por llenar.";
                errorDiv.html(mensajeError).show();

                setTimeout(function () {
                    errorDiv.hide();
                }, 5000);
                return false; 
            }
            return true;
        }

        /**$(document).ready(function () {
            $('#nombre').on('blur', function () {
                var nombre = $(this).val();
                var mensajeCodigo = $('#mensaje-codigo');
                mensajeCodigo.html(''); 

                if (nombre) {
                    $.ajax({
                        url: 'verificar_nombre.php', 
                        type: 'POST',
                        data: { nombre: nombre },
                        success: function (respuesta) {
                            if (respuesta === 'existe') {
                                mensajeCodigo.html('El nombre ' + nombre + ' ya existe.').show();

                                setTimeout(function () {
                                    mensajeCodigo.hide();
                                }, 5000);
                            }
                        }
                    });
                }
            });
        }); */
    </script>
</head>
<body>
    <?php
        include('menu_navegacion.php');
    ?>
    <h1>Alta de promociones</h1>

    <form name="Formulario01" method="post" action="promociones_salva.php" enctype="multipart/form-data" onsubmit="return validarFormulario();" novalidate>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Escribe el nombre" required /> 

        <label>Imagen de la promocion:</label><br>
        <input type="file" name="archivo" required><br><br>

        <input type="submit" value="Guardar" />
        <div id="error"></div> 

        <a href="promociones_lista.php">Regresar al listado</a>
    </form>
</body>
</html>
