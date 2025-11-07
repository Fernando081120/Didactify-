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
    <title>Alta de productos</title>
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
            var codigo = $('#codigo').val();
            var descripcion = $('#descripcion').val();
            var costo = $('#costo').val();
            var stock = $('#stock').val();
            var archivo = $('input[type="file"]').val();
            var errorDiv = $('#error');

            errorDiv.html('');
            var mensajeError = '';

            if (!nombre || !codigo || !descripcion || !costo || !stock || !archivo) {
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
            $('#codigo').on('blur', function () {
                var codigo = $(this).val();
                var mensajeCodigo = $('#mensaje-codigo');
                mensajeCodigo.html(''); 

                if (codigo) {
                    $.ajax({
                        url: 'verificar_codigo.php', 
                        type: 'POST',
                        data: { codigo: codigo },
                        success: function (respuesta) {
                            if (respuesta === 'existe') {
                                mensajeCodigo.html('El codigo ' + codigo + ' ya existe.').show();

                                setTimeout(function () {
                                    mensajeCodigo.hide();
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
    <h1>Alta de productos</h1>

    <form name="Formulario01" method="post" action="productos_salva.php" enctype="multipart/form-data" onsubmit="return validarFormulario();" novalidate>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Escribe el nombre" required /> 

        <label for="codigo">Codigo:</label>
        <input type="text" name="codigo" id="codigo" placeholder="Escribe el codigo" required /> 
        <div id="mensaje-codigo"></div> 

        <label for="Descripcion">Descripcion:</label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Escribe la descripcion" required />

        <label for="costo">Costo:</label>
        <input type="text" name="costo" id="costo" placeholder="Escribe el costo" required /> 

        <label for="stock">Stock:</label>
        <input type="text" name="stock" id="stock" placeholder="Escribe el stock" required />
        
        <label>Foto del producto:</label><br>
        <input type="file" name="archivo" required><br><br>

        <input type="submit" value="Guardar" />
        <div id="error"></div> 

        <a href="productos_lista.php">Regresar al listado</a>
    </form>
</body>
</html>
