<?php
include('Funciones/config.php');
$data = json_decode(file_get_contents('php://input'), true);

$id_producto = $data['id_producto'];
$id_pedido = $data['id_pedido'];

$query = "DELETE FROM pedidos_productos WHERE id_pedido = :id_pedido AND id_producto = :id_producto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

echo json_encode(['success' => $stmt->execute()]);
?>
