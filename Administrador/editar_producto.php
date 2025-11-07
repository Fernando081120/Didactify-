<?php
require "Funciones/conecta.php";
$con = conecta();

session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php');
    exit();
}

$id = $_POST['id'] ?? 0;

$query = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
$result = mysqli_query($con, $query);
$producto = mysqli_fetch_assoc($result);

if (!$producto) {
    echo "Empleado no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de productos</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
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
        input[type="file"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        #errorCodigo {
            color: red;
        }

        #mensaje {
            color: red;
        }

        input[type="submit"], button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
    include('menu_navegacion.php')
?>

<h1>Edición de productos</h1>

<form id="formEditar" method="POST" enctype="multipart/form-data" novalidate>
    <input type="hidden" id="id" name="id" value="<?php echo $producto['id']; ?>">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $producto['nombre']; ?>" required><br>

    <label for="codigo">Codigo:</label>
    <input type="text" id="codigo" name="codigo" placeholder="Codigo (opcional)" value="<?php echo $producto['codigo']; ?>" required><br>
    <div id="errorCodigo"></div>

    <label for="descripcion">Descripcion:</label>
    <input type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo $producto['descripcion']; ?>" required><br>

    <label for="costo">Costo:</label>
    <input type="text" id="costo" name="costo" placeholder="Costo" value="<?php echo $producto['costo']; ?>" required><br>

    <label for="stock">Stock:</label>
    <input type="text" id="stock" name="stock" placeholder="Stock" value="<?php echo $producto['stock']; ?>" required><br>

    <label>Foto del producto:</label>
    <input type="file" name="archivo"><br>

    <button type="submit">Guardar cambios</button>
    <div id="mensaje" style="text-align: center;"></div>
    <a href="productos_lista.php">Regresar</a>
</form>


<script>
    $(document).ready(function () {
        let codigoValido = true;

        function validarCampoVacio(campo) {
            if (campo.val().trim() === '') {
                campo.next('div').text('Este campo no puede estar vacío.').show();
                setTimeout(() => campo.next('div').hide(), 5000);
                return false;
            }
            return true;
        }

        $('#nombre, #descripcion, #costo, #stock').on('blur', function () {
            validarCampoVacio($(this));
        });

        $('#codigo').on('blur', function () {
            let codigo = $(this).val().trim();
            let id = $('#id').val();
            let errorCodigo = $('#errorCodigo');

            errorCodigo.text(''); 

            if (codigo) {
                $.ajax({
                    url: 'verificar_edicion_codigo.php',
                    type: 'POST',
                    data: { codigo: codigo, id: id },
                    success: function (respuesta) {
                        if (respuesta.trim() === 'existe') {
                            errorCodigo.text(`El codigo ${codigo} ya existe.`).show();
                            codigoValido = false;
                            setTimeout(() => errorCodigo.hide(), 5000);
                        } else {
                            codigoValido = true;
                        }
                    },
                    error: function () {
                        errorCodigo.text('Error al verificar el codigo.').show();
                        setTimeout(() => errorCodigo.hide(), 5000);
                    }
                });
            } else {
                codigoValido = true;
            }
        });

        $('#formEditar').submit(function (e) {
            e.preventDefault();
            let mensaje = $('#mensaje');

            let nombreValido = validarCampoVacio($('#nombre'));
            let descripcionValido = validarCampoVacio($('#descripcion'));
            let codigoValidoLocal = validarCampoVacio($('#codigo'));
            let costoValido = validarCampoVacio($('#costo'));
            let stockValido = validarCampoVacio($('#stock'))

            if (!nombreValido || !descripcionValido || !codigoValidoLocal || !codigoValido || !costoValido || !stockValido) {
                mensaje.text('Campos Vacios.').show();
                setTimeout(() => mensaje.hide(), 5000);
                return;
            }

            let formData = new FormData(this);

            $.ajax({
                url: 'actualizar_producto.php',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (respuesta) {
                    if (respuesta === "success") {
                        window.location.href = 'productos_lista.php';
                    } else {
                        mensaje.text('Error al actualizar el producto.');
                    }
                },
                error: function () {
                    mensaje.text('Error al conectar con el servidor.');
                }
            });
        });
    });
</script>

</body>
</html>
