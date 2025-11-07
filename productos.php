<?php
session_start();
include('Funciones/config.php'); // Archivo para conectar a la base de datos

// Obtener productos no eliminados
$query = "SELECT id, nombre, archivo_n, costo, codigo FROM productos WHERE eliminado = 0";
$stmt = $pdo->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
      <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
    <style>
        /* General */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Main content */
        main {
            flex: 1;
            padding: 40px;
            margin-top: 40px; /* Espacio entre el header y el contenido */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px; /* Espacio debajo del título */
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 20px; /* Espacio entre el contenido y el footer */
        }

        /* Productos */
        .productos {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .producto-item {
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .producto-item img {
            width: 100px;
            height: auto;
        }

        .producto-item button, .producto-item input {
            margin-top: 5px;
        }

        .cantidad-contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px; /* Espacio entre la etiqueta y el input */
            margin-bottom: 10px; /* Separación del botón */
        }

        .cantidad-contenedor label {
            font-size: 0.9rem;
            font-weight: bold;
        }

        .producto-item button {
            display: block; /* El botón ocupa una línea completa debajo del input */
            margin: 0 auto; /* Centrar el botón */
            margin-top: 10px; /* Separación adicional del input */
            background-color: #007BFF; /* Azul moderno */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .producto-item button:hover {
            background-color: #0056b3; /* Azul más oscuro */
            transform: scale(1.05); /* Ligero agrandamiento */
        }

        .producto-item button:active {
            background-color: #004085; /* Azul más profundo */
            transform: scale(1); /* Vuelve al tamaño normal */
        }

        .producto-item input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 1rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .producto-item input[type="number"]:focus {
            outline: none;
            border-color: #007BFF; /* Azul moderno */
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }        

    </style>
</head>
<body>
    <header>
        <?php include('Funciones/header.php'); // Incluir el encabezado ?>
    </header>

    <main>
        <h1> </h1>
        <h1>Productos </h1>
        <div class="productos">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-item">
                    <a href="detalle_producto.php?id=<?php echo $producto['id']; ?>">
                        <img src="Administrador/archivos/<?php echo htmlspecialchars($producto['archivo_n']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

                        <h3><?php echo $producto['nombre']; ?></h3>
                    </a>
                    <p>Precio: $<?php echo $producto['costo']; ?></p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="cantidad-contenedor">
                            <label for="cantidad_<?php echo $producto['id']; ?>">Cantidad:</label>
                            <input type="number" min="1" value="1" id="cantidad_<?php echo $producto['id']; ?>">
                        </div>
                        <button onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)">Agregar al carrito</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <?php include('Funciones/footer.php'); // Incluir el pie de página ?>
    </footer>

    <script>
        function agregarAlCarrito(productoId) {
            const cantidad = document.getElementById(`cantidad_${productoId}`).value;
            if (cantidad > 0) {
                fetch('insertar_producto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_producto: productoId, cantidad: cantidad })
                })
                .then(response => response.json())
                .then(data => {
                    // Mostrar mensaje de éxito
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert(data.error);
                    }
                });
            } else {
                alert('Por favor, ingresa una cantidad válida.');
            }
        }
    </script>
</body>
</html>
