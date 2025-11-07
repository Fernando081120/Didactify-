<?php
session_start();
include('Funciones/config.php'); // Archivo para conectar a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Para asegurar que la altura ocupe toda la pantalla */
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 40px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }
        .container {
            background-color: white;
            padding: 60px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 80px auto; /* Centrado del contenedor */
            flex: 1; /* Esto hace que el contenedor ocupe el espacio disponible */
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 16px;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <header>
        <?php include('Funciones/header.php'); // Incluir el encabezado ?>
    </header>

    <div class="container">
        <h1>Formulario de Contacto</h1>
        <form id="contact-form">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required><br>

            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea><br>

            <button type="submit">Enviar</button>
        </form>

        <div id="response-message"></div>

        <!-- Botón para regresar a productos -->
        <button class="back-button" onclick="window.location.href='productos.php'">Regresar</button>
    </div>

    <footer>
        <?php include('Funciones/footer.php'); // Incluir el pie de página ?>
    </footer>

    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var responseMessage = document.getElementById('response-message');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'recibe.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    responseMessage.innerHTML = '<p class="message">Correo enviado exitosamente.</p>';
                    document.getElementById('contact-form').reset(); // Limpiar el formulario
                } else {
                    responseMessage.innerHTML = '<p class="error">Error al enviar el correo. Por favor, inténtalo de nuevo.</p>';
                }
            };

            xhr.onerror = function() {
                responseMessage.innerHTML = '<p class="error">Hubo un problema con la conexión. Inténtalo más tarde.</p>';
            };

            xhr.send(formData);
        });
    </script>

</body>
</html>
