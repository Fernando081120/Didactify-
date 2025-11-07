<?php
session_start();
include('Funciones/config.php');

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents('php://input'), true);
$id_producto = $data['id_producto'];
$cantidad = $data['cantidad'];
$id_cliente = $_SESSION['user_id']; // El cliente actual

// Verificar si ya existe un pedido abierto para el cliente
$query = "SELECT id FROM pedidos WHERE id_cliente = :id_cliente AND status = 0 LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    // Si no existe un pedido abierto, crear uno nuevo
    $query = "INSERT INTO pedidos (id_cliente, fecha, status) VALUES (:id_cliente, NOW(), 0)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $id_pedido = $pdo->lastInsertId(); // Obtener el ID del nuevo pedido
} else {
    $id_pedido = $pedido['id'];
}

// Obtener el precio del producto
$query = "SELECT costo FROM productos WHERE id = :id_producto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el producto ya existe en el carrito
$query = "SELECT id, cantidad FROM pedidos_productos WHERE id_pedido = :id_pedido AND id_producto = :id_producto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
$stmt->execute();
$productoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($productoExistente) {
    // Si el producto ya está en el carrito, actualizar la cantidad
    $nuevaCantidad = $productoExistente['cantidad'] + $cantidad;
    $query = "UPDATE pedidos_productos SET cantidad = :cantidad WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cantidad', $nuevaCantidad, PDO::PARAM_INT);
    $stmt->bindParam(':id', $productoExistente['id'], PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Producto actualizado en el carrito.']);
} else {
    // Si el producto no está en el carrito, insertarlo
    $query = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio) VALUES (:id_pedido, :id_producto, :cantidad, :precio)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $producto['costo'], PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito.']);
}
?>
