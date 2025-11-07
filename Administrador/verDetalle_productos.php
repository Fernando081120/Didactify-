<?php
require "Funciones/conecta.php";
$con = conecta();

session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
$result = mysqli_query($con, $query);
$producto = mysqli_fetch_assoc($result);

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha del Producto</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E8FAF2;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #72D5AA;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header {
            background-color: #198401;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 20px;
        }

        .info label {
            font-weight: bold;
            font-size: 18px;
            display: block;
            margin-bottom: 5px;
        }

        .info span {
            display: block;
            font-size: 16px;
            color: #333;
            border-bottom: 1px dashed #CE0101;
            padding-bottom: 5px;
        }

        .photo {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }

        .photo-container {
            width: 120px;  
            height: 120px;   
            border-radius: 12px;
            overflow: hidden; 
            border: 4px solid #C4F3DF;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #05874F;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
        include('menu_navegacion.php'); 
?>
<div class="container">
    <div class="header">
        Ficha del Producto
    </div>

    <div class="photo">
        <div class="photo-container">
        <img src="archivos/<?php echo $producto['archivo_n']; ?>" alt="Foto del producto" width="150">
        </div>
    </div>  

    <div class="info">
        <label>Nombre:</label>
        <span><?php echo $producto['nombre']; ?></span>
    </div>

    <div class="info">
        <label>Descripcion:</label>
        <span><?php echo $producto['descripcion']; ?></span>
    </div>

    <div class="info">
        <label>Costo:</label>
        <span><?php echo $producto['costo']; ?></span>
    </div>
    
    <div class="info">
        <label>Stock:</label>
        <span><?php echo $producto['stock']; ?></span>

    <div class="button-container">
        <a href="productos_lista.php">Regresar</a>
    </div>
</div>

</body>
</html>
