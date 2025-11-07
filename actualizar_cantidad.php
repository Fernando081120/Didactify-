<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('Funciones/config.php');

// Decodificar los datos de la entrada JSON
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al decodificar JSON: ' . json_last_error_msg()
    ]);
    exit();
}

if (!$data) {
    echo json_encode([
        'success' => false,
        'error' => 'Datos inválidos o no recibidos.',
        'input' => file_get_contents('php://input') // Muestra el cuerpo recibido
    ]);
    exit();
}

// Obtener y validar los datos del payload
$idProducto = isset($data['id_producto']) ? (int)$data['id_producto'] : 0;
$idPedido = isset($data['id_pedido']) ? (int)$data['id_pedido'] : 0;
$cantidad = isset($data['cantidad']) && is_numeric($data['cantidad']) ? (int)$data['cantidad'] : 0;


if ($idProducto <= 0 || $idPedido <= 0 || $cantidad <= 0) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos o faltantes.']);
    exit();
}

// Verificar el precio del producto (si es necesario)
$queryPrecio = "SELECT costo FROM productos WHERE id = :id_producto";
$stmtPrecio = $pdo->prepare($queryPrecio);
$stmtPrecio->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);

if (!$stmtPrecio->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener el precio del producto.']);
    exit();
}

$precio = $stmtPrecio->fetchColumn();
if ($precio === false) {
    echo json_encode(['success' => false, 'error' => 'Precio no encontrado para el producto.']);
    exit();
}

// Actualizar la cantidad en la tabla pedidos_productos
$query = "UPDATE pedidos_productos SET cantidad = :cantidad WHERE id_pedido = :id_pedido AND id_producto = :id_producto";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
$stmt->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
$stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'precio' => $precio]);
} else {
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la cantidad', 'details' => $errorInfo]);
}
?>
