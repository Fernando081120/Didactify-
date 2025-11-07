<?php
session_start();
include('Funciones/config.php');

// Obtener el cliente actual
$id_cliente = $_SESSION['user_id'];

// Obtener el último pedido finalizado del cliente
$query = "SELECT id FROM pedidos WHERE id_cliente = :id_cliente AND status = 1 ORDER BY id DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header('Location: productos.php');
    exit();
}

$id_pedido = $pedido['id'];

// Obtener los productos del pedido
$query = "SELECT p.nombre, pp.cantidad, pp.precio 
          FROM pedidos_productos pp
          JOIN productos p ON pp.id_producto = p.id
          WHERE pp.id_pedido = :id_pedido";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPedido = array_reduce($productos, function ($carry, $item) {
    return $carry + $item['cantidad'] * $item['precio'];
}, 0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Finalizado</title>
      <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 50px;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 40px;
            line-height: 1.5;
        }

        table {
            width: 80%;
            margin: 0 auto 30px auto;
            border-collapse: collapse;
            text-align: left;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total {
            font-weight: bold;
        }

        a {
            text-decoration: none;
            font-size: 1.1rem;
            color: #ffffff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #45a049;
        }

        a:active {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <h1>¡Pedido Finalizado con Éxito!</h1>
    <p>Tu pedido ha sido procesado correctamente y se encuentra en estado de finalización. A continuación, te mostramos los detalles:</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td>$<?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Total del Pedido:</td>
                <td class="total">$<?php echo number_format($totalPedido, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <a href="productos.php">Volver a la tienda</a>
</body>
</html>
