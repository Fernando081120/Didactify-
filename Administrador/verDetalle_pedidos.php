<?php 
require "Funciones/conecta.php";
session_start();

$con = conecta();

if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php'); 
    exit();
}

$pedido_id = $_GET['id'];
$cliente_id = $_GET['correo'];

// Obtener los detalles del pedido (productos y subtotal)
$sql = "SELECT pp.id_producto, p.nombre AS producto, pp.cantidad, pp.precio, (pp.cantidad * pp.precio) AS subtotal
        FROM pedidos_productos pp
        JOIN productos p ON pp.id_producto = p.id
        WHERE pp.id_pedido = ?";
        
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$res = $stmt->get_result();

$total_pedido = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del pedido</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFACD;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #F6EDE8;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .tabla-pedidos {
            width: 100%;
            border-collapse: collapse;
        }

        .fila {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .fila.header {
            font-weight: bold;
            background-color: #FFD700;
        }

        .fila div {
            padding: 10px;
            flex: 1;
            text-align: center;
            border-right: 1px solid #ddd;
        }

        .fila div:last-child {
            border-right: none;
        }

        .fila:nth-child(even) {
            background-color: #f2f2f2;
        }

        .fila:hover {
            background-color: #e9ecef;
        }

        .fila .subtotal {
            font-weight: bold;
            color: #FF6347; /* Un color destacado para el subtotal */
        }

        .gran-total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            padding: 15px 0;
            background-color: #FFD700;
            color: #fff;
            border-top: 2px solid #ccc;
        }

        .gran-total div {
            display: inline-block;
            margin-right: 20px;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            background-color: #138496;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-volver:hover {
            background-color: #1e7893;
        }

                /* Estilo para centrar el botón */
        .btn-container {
            text-align: center; /* Centra el contenido del contenedor */
            margin-top: 30px;   /* Añade espacio encima del botón */
        }

    </style>
</head>
<body>
    <?php
        include('menu_navegacion.php');
    ?>
    <div class="container">
        <h1>Detalle del Pedido #<?php echo $pedido_id; ?></h1>

        <div class="tabla-pedidos">
            <div class="fila header">
                <div>Producto</div>
                <div>Cantidad</div>
                <div>Precio</div>
                <div>Subtotal</div>
            </div>

            <?php while($row = $res->fetch_array()): 
                $total_pedido += $row['subtotal'];
            ?>
            <div class="fila">
                <div><?php echo $row["producto"]; ?></div>
                <div><?php echo $row["cantidad"]; ?></div>
                <div><?php echo number_format($row["precio"], 2); ?></div>
                <div><?php echo number_format($row["subtotal"], 2); ?></div>

            </div>
            <?php endwhile; ?>

            <div class="fila">
                <div colspan="3">Gran Total</div>
                <div><?php echo number_format($total_pedido, 2); ?></div>
            </div>
           
        </div>
        <div class="btn-container">
            <a href="pedidos_lista.php" class="btn-volver">Regresar</a>
        </div>
    </div>
</body>
</html>
