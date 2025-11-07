<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú del Sistema</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .menu {
            width: 100%;
            background-color: #F87F5A; 
            border-radius: 0px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .menu tr {
            height: 50px;
        }
        .menu td {
            padding: 0;
            text-align: center;
        }
        .menu a {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .menu a:hover {
            background-color: #DE7657; 
        }
    </style>
</head>
<body>
    <table class="menu" align="center">
        <tr>
            <td><a href="bienvenida.php">INICIO</a></td>
            <td><a href="empleados_lista.php">EMPLEADOS</a></td>
            <td><a href="productos_lista.php">PRODUCTOS</td>
            <td><a href="promociones_lista.php">PROMOCIONES</a></td>
            <td><a href="pedidos_lista.php">PEDIDOS</a></td>
            <td><a href="logout.php">SALIR</a></td>
        </tr>
    </table>
</body>
</html>
