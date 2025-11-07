<?php
session_start();
if (isset($_SESSION['usuario_nombre'])) {
    header('Location: bienvenida.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
            text-align: center;
        }
        input {
            margin-bottom: 10px;
            padding: 10px;
            width: 40%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #mensaje {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('#loginForm').submit(function (e) {
                e.preventDefault();

                var correo = $('#correo').val();
                var pass = $('#pass').val();
                var mensajeDiv = $('#mensaje');

                if (!correo || !pass) {
                    mensajeDiv.text('Por favor, llena todos los campos.');
                    return;
                }

                $.ajax({
                    url: 'verificar_usuario.php',
                    type: 'POST',
                    data: { correo: correo, pass: pass },
                    success: function (respuesta) {
                        respuesta = respuesta.trim();
                        
                        if (respuesta === 'existe') {
                            window.location.href = 'bienvenida.php'; 
                        } else {
                            mensajeDiv.text('Usuario o contraseña incorrectos.');
                        }
                    },
                    error: function () {
                        mensajeDiv.text('Ocurrió un error, intenta de nuevo.');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h1>Inicio de Sesión</h1>
    <form id="loginForm" novalidate>
        <input type="text" id="correo" placeholder="Correo" required><br>
        <input type="password" id="pass" placeholder="Contraseña" required><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <div id="mensaje"></div>
</body>
</html>
