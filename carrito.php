<?php
session_start();
include('Funciones/config.php');

$id_cliente = $_SESSION['user_id']; // Cliente actual

// Obtener el pedido abierto
$query = "SELECT id FROM pedidos WHERE id_cliente = :id_cliente AND status = 0 LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header('Location: productos.php');
    exit();
}

$id_pedido = $pedido['id'];

// Obtener productos del carrito
$query = "SELECT p.id, p.nombre, pp.cantidad, pp.precio 
          FROM pedidos_productos pp
          JOIN productos p ON pp.id_producto = p.id
          WHERE pp.id_pedido = :id_pedido";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmt->execute();
$productosCarrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPedido = array_reduce($productosCarrito, function ($carry, $item) {
    return $carry + $item['cantidad'] * $item['precio'];
}, 0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
      <link rel="shortcut icon" href="logo.ico" />
    <style>
        /* Estilos para la página de carrito */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        main {
            flex: 1;
            padding: 20px;
            margin-top: 100px;
            margin-bottom: 100px;
        }

        .productos-carrito {
            margin-bottom: 20px;
        }

        .producto-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .producto-item p {
            margin: 0;
        }

        .producto-item .cantidad {
            font-weight: bold;
        }

        .finalizar-btn, .regresar-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .finalizar-btn:hover, .regresar-btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .botones {
            margin-top: 20px;
            text-align: center;
        }

        .eliminar-btn {
            padding: 8px 16px;
            background-color: #f44336; /* Rojo */
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .eliminar-btn:hover {
            background-color: #d32f2f; /* Rojo oscuro */
            transform: scale(1.05); /* Efecto de agrandamiento */
        }

        .eliminar-btn:focus {
            outline: none;
        }

        .eliminar-btn:active {
            background-color: #b71c1c; /* Rojo más oscuro al hacer clic */
        }

        .mensaje-vacio {
            text-align: center;
            font-size: 18px;
            margin-top: 50px;
            color: #555;
        }

        .producto-item input {
            width: 60px;
            text-align: center;
        }

        .finalizar-btn, .regresar-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .finalizar-btn:hover, .regresar-btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            width: 100%;
            position: relative;
            bottom: 0;
        }
    </style>
</head>
<body>
<header>
    <h1>Carrito de Compras</h1>
    <?php include('Funciones/header.php'); ?>
</header>
<main>
    <div id="mensaje" style="display:none; text-align:center;"></div>
    <?php if (empty($productosCarrito)): ?>
        <p class="mensaje-vacio">No hay productos en el carrito.</p>
    <?php else: ?>
        <div class="productos-carrito">
            <?php foreach ($productosCarrito as $producto): ?>
                <div class="producto-item" id="producto-<?php echo $producto['id']; ?>">
                    <p><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    <p>Precio: $<?php echo $producto['precio']; ?></p>
                    <label for="cantidad_<?php echo $producto['id']; ?>">Cantidad:</label>
                    <input type="number" min="1" value="<?php echo $producto['cantidad']; ?>" 
                    placeholder="Cantidad" id="cantidad_<?php echo $producto['id']; ?>" 
                    onchange="actualizarCantidad(<?php echo $producto['id']; ?>)">
                    <p>Total: $<span id="subtotal-<?php echo $producto['id']; ?>">
                        <?php echo $producto['cantidad'] * $producto['precio']; ?>
                    </span></p>
                    <button class="eliminar-btn" onclick="eliminarProducto(<?php echo $producto['id']; ?>)">Eliminar</button>
                </div>
            <?php endforeach; ?>
        </div>
        <p>Total del Pedido: $<span id="total-pedido"><?php echo $totalPedido; ?></span></p>
        <div class="botones">
            <button class="regresar-btn" onclick="window.location.href='productos.php'">Regresar</button>
            <button class="finalizar-btn" onclick="finalizarPedido()">Finalizar Pedido</button>
        </div>
    <?php endif; ?>
</main>
<footer>
    <?php include('Funciones/footer.php'); ?>
</footer>
<script>
    const mensaje = document.getElementById('mensaje');

    function mostrarMensaje(texto, tipo = 'success') {
        mensaje.textContent = texto;
        mensaje.style.color = tipo === 'success' ? 'green' : 'red';
        mensaje.style.display = 'block';
        setTimeout(() => mensaje.style.display = 'none', 3000);
    }

    function actualizarCantidad(idProducto) {
    const cantidadInput = document.getElementById(`cantidad_${idProducto}`);
    const nuevaCantidad = cantidadInput.value;

    if (nuevaCantidad <= 0) {
        mostrarMensaje('La cantidad debe ser mayor a 0.', 'error');
        return;
    }

    fetch('actualizar_cantidad.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ 
        id_producto: idProducto, 
        id_pedido: <?php echo $id_pedido; ?>, 
        cantidad: parseInt(nuevaCantidad,10) 
    })
    })

    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const subtotal = nuevaCantidad * data.precio;
            document.getElementById(`subtotal-${idProducto}`).textContent = subtotal.toFixed(2);
            actualizarTotalPedido();
            mostrarMensaje('Cantidad actualizada con éxito.');
        } else {
            mostrarMensaje('Error al actualizar la cantidad.', 'error');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        mostrarMensaje('Error de red. Por favor, inténtalo de nuevo.', 'error');
    });

    }

    function actualizarTotalPedido() {
        let total = 0;
        const subtotales = document.querySelectorAll('[id^="subtotal-"]');
        
        subtotales.forEach(subtotal => {
            total += parseFloat(subtotal.textContent);
        });
        
        document.getElementById('total-pedido').textContent = total.toFixed(2);
    }

    function eliminarProducto(idProducto) {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
            fetch('eliminarProducto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto, id_pedido: <?php echo $id_pedido; ?> })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`producto-${idProducto}`).remove();
                actualizarTotalPedido();
                mostrarMensaje('Producto eliminado con éxito.');
            } else {
                mostrarMensaje('Error al eliminar el producto.', 'error');
            }
        })
        .catch(() => {
            mostrarMensaje('Error de red. Por favor, inténtalo de nuevo más tarde.', 'error');
        });
        }
    }


    function finalizarPedido() {
        if (confirm("¿Deseas finalizar el pedido?")) {
            fetch('finalizarPedido.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_pedido: <?php echo $id_pedido; ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje('Pedido finalizado con éxito.');
                    setTimeout(() => window.location.href = 'pedidoFinalizado.php', 2000);
                } else {
                    mostrarMensaje('Error al finalizar el pedido.', 'error');
                }
            });
        }
    }
</script>
</body>
</html>
