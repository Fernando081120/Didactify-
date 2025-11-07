<?php 
require "Funciones/conecta.php";
session_start();

$con = conecta();

if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php'); 
    exit();
}

// Filtrar solo pedidos cerrados
$sql = "SELECT p.id, p.fecha, SUM(pp.cantidad * pp.precio) AS total 
        FROM pedidos p
        JOIN pedidos_productos pp ON p.id = pp.id_pedido
        WHERE p.status = 1
        GROUP BY p.id";
$res = $con->query($sql);
$num = $res->num_rows;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de pedidos cerrados</title>
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
        }

        .tabla-pedidos {
            display: flex;
            flex-direction: column;
        }

        .fila {
            display: flex;
            border-bottom: 1px solid #ddd;
        }

        .fila.header {
            font-weight: bold;
            background-color: #FFD700;
        }

        .fila div {
            padding: 10px;
            flex: 1;
            border-right: 1px solid #ddd;
            display: flex;
            justify-content: center; 
            align-items: center; 
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

        .btn-ver {
            background-color: #f4ba21;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-ver:hover {
            background-color: #138496;
        }

    </style>
</head>
<body>
    <?php
        include('menu_navegacion.php');
    ?>
    <div class="container">
        <h1>Listado de pedidos cerrados (<?php echo $num; ?>)</h1>

        <div class="tabla-pedidos">
            <div class="fila header">
                <div>ID Pedido</div>
                <div>Fecha</div>
                <div>Total</div>
                <div>Ver detalle</div>
            </div>

            <?php while($row = $res->fetch_array()): ?>
            <div class="fila" id="fila-<?php echo $row['id']; ?>">
                <div><?php echo $row["id"]; ?></div>
                <div><?php echo $row["fecha"]; ?></div>
                <div><?php echo number_format($row["total"], 2); ?></div>

                <!-- Botón Ver Detalle -->
                <div>
                    <a href="verDetalle_pedidos.php?id=<?php echo $row['id']; ?>" class="btn-ver">Ver detalle</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
