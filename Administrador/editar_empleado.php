<?php
require "Funciones/conecta.php";
$con = conecta();

session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php');
    exit();
}

$id = $_POST['id'] ?? 0;

$query = "SELECT * FROM empleados WHERE id = $id AND eliminado = 0";
$result = mysqli_query($con, $query);
$empleado = mysqli_fetch_assoc($result);

if (!$empleado) {
    echo "Empleado no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de empleados</title>
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
        input[type="password"],
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

        #errorCorreo {
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

<h1>Edición de empleados</h1>

<form id="formEditar" method="POST" enctype="multipart/form-data" novalidate>
    <input type="hidden" id="id" name="id" value="<?php echo $empleado['id']; ?>">

    <label for="nombre">Nombre(s):</label>
    <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $empleado['nombre']; ?>" required><br>

    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" value="<?php echo $empleado['apellidos']; ?>" required><br>

    <label for="correo">Correo:</label>
    <input type="text" id="correo" name="correo" placeholder="Correo" value="<?php echo $empleado['correo']; ?>" required>
    <div id="errorCorreo"></div>

    <label for="pass">Contraseña:</label>
    <input type="password" id="pass" name="pass" placeholder="Contraseña (Opcional)"><br>

    <label for="rol">Rol:</label>
    <select id="rol" name="rol" required>
        <option value="1" <?php if ($empleado['rol'] == 'Empleado') echo 'selected'; ?>>Empleado</option>
        <option value="2" <?php if ($empleado['rol'] == 'Gerente') echo 'selected'; ?>>Gerente</option>
        <option value="3" <?php if ($empleado['rol'] == 'Ejecutivo') echo 'selected'; ?>>Ejecutivo</option>
    </select><br>

    <label>Foto del Empleado:</label>
    <input type="file" name="archivo"><br>


    <button type="submit">Guardar cambios</button>
    <div id="mensaje" style="text-align: center;"></div>
    <a href="empleados_lista.php">Regresar</a>
</form>


<script>
    $(document).ready(function () {
        let correoValido = true;

        function validarCampoVacio(campo) {
            if (campo.val().trim() === '') {
                campo.next('div').text('Este campo no puede estar vacío.').show();
                setTimeout(() => campo.next('div').hide(), 5000);
                return false;
            }
            return true;
        }

        $('#nombre, #apellidos, #correo').on('blur', function () {
            validarCampoVacio($(this));
        });

        $('#correo').on('blur', function () {
            let correo = $(this).val().trim();
            let id = $('#id').val();
            let errorCorreo = $('#errorCorreo');

            errorCorreo.text(''); 

            if (correo) {
                $.ajax({
                    url: 'verificar_edicion_correo.php',
                    type: 'POST',
                    data: { correo: correo, id: id },
                    success: function (respuesta) {
                        if (respuesta.trim() === 'existe') {
                            errorCorreo.text(`El correo ${correo} ya existe.`).show();
                            correoValido = false;
                            setTimeout(() => errorCorreo.hide(), 5000);
                        } else {
                            correoValido = true;
                        }
                    },
                    error: function () {
                        errorCorreo.text('Error al verificar el correo.').show();
                        setTimeout(() => errorCorreo.hide(), 5000);
                    }
                });
            } else {
                correoValido = true;
            }
        });

        $('#formEditar').submit(function (e) {
            e.preventDefault();
            let mensaje = $('#mensaje');

            let nombreValido = validarCampoVacio($('#nombre'));
            let apellidosValido = validarCampoVacio($('#apellidos'));
            let correoValidoLocal = validarCampoVacio($('#correo'));

            if (!nombreValido || !apellidosValido || !correoValidoLocal || !correoValido) {
                mensaje.text('Campos Vacios.').show();
                setTimeout(() => mensaje.hide(), 5000);
                return;
            }

            let formData = new FormData(this);

            $.ajax({
                url: 'actualizar_empleado.php',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (respuesta) {
                    if (respuesta === "success") {
                        window.location.href = 'empleados_lista.php';
                    } else {
                        mensaje.text('Error al actualizar el empleado.');
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
