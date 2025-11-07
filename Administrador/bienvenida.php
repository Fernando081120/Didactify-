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
    <title>Bienvenida</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; 
        }

        header {
            background-color: #007bff; 
            color: white;
            padding: 10px 20px; 
            text-align: center;
        }

        .contenido {
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            height: calc(100vh - 60px); 
            text-align: center; 
        }

        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <?php
        include('menu_navegacion.php');
    ?>

    <div class="contenido">
        <h1>Hola, <?php echo $_SESSION['usuario_nombre']; ?>. Bienvenido al sistema.</h1>
    </div>

</body>
</html>
