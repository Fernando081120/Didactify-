<?php
session_start();
include('Funciones/config.php');

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents('php://input'), true);
$id_pedido = $data['id_pedido'];

// Verificar si el pedido pertenece al cliente actual
$id_cliente = $_SESSION['user_id'];
$query = "SELECT id FROM pedidos WHERE id_cliente = :id_cliente AND id = :id_pedido AND status = 0";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    // Cambiar el estado del pedido a finalizado
    $query = "UPDATE pedidos SET status = 1 WHERE id = :id_pedido";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontró el pedido o no tienes acceso a este.']);
}
?>
