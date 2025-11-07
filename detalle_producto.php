<?php
session_start();
include('Funciones/config.php');

$id = $_GET['id'] ?? 0;
$query = "SELECT * FROM productos WHERE id = :id AND eliminado = 0";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}

/* ✅ IA PARA RECOMENDACIONES */
include "/home/u133563347/domains/didactify.com.mx/secure/openai_key.php"; 

// Obtener catálogo (solo nombre y descripción)
$stmt = $pdo->query("SELECT id, nombre, descripcion FROM productos WHERE eliminado = 0");
$catalogo = $stmt->fetchAll(PDO::FETCH_ASSOC);

$input_data = [
    "producto_actual" => $producto,
    "catalogo" => $catalogo
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $OPENAI_API_KEY"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-4.1-mini",
        "messages" => [
            [
                "role" => "user",
                "content" => "Dado este producto y este catálogo, devuelve SOLO un JSON con los IDs de los 3 productos más similares. " . json_encode($input_data)
            ]
        ]
    ])
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$ids_recomendados = [];

if (!empty($data['choices'][0]['message']['content'])) {
    
    $content = $data['choices'][0]['message']['content'];

    // Quitar ``` y ```json si vienen
    $content = preg_replace('/```json|```/', '', $content);

    // Convertir a JSON
    $json = json_decode(trim($content), true);

    // Extraer IDs
    if (isset($json['similares'])) {
        $ids_recomendados = $json['similares'];
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Hace que el contenido ocupe toda la altura */
        }

        header {
            position: sticky;
            top: 0;
            width: 100%;
            background-color: #fff;
            z-index: 1000;
        }

        footer {
            margin-top: auto; /* Esto hace que el pie de página siempre esté al final */
            background-color: #555;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        /* Contenedor principal */
        .producto-container {
            max-width: 600px;
            width: 90%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            text-align: center;
            margin: 80px auto 0; /* Ajuste para que no quede pegado a la parte superior */
        }

        /* Imagen del producto */
        .producto-container img {
            max-width: 100%; /* Ajusta el tamaño máximo al ancho del contenedor */
            max-height: 300px; /* Limita la altura máxima */
            object-fit: contain; /* Mantiene la proporción de la imagen */
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Título del producto */
        .producto-container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Información del producto */
        .producto-container p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        /* Botones e input */
        .acciones {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .acciones input[type="number"] {
            width: 60px;
            padding: 5px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .acciones button, .acciones a {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #555;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Sin subrayado */
            transition: background-color 0.3s ease;
        }

        .acciones button:hover, .acciones a:hover {
            background-color: #777;
        }

        .acciones button:active, .acciones a:active {
            background-color: #444;
        }

        /* Botón de regreso */
        .regresar {
            display: block;
            margin: 20px auto 0;
            text-align: center;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #555;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .regresar:hover {
            background-color: #777;
        }

        .regresar:active {
            background-color: #444;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .producto-container h1 {
                font-size: 20px;
            }

            .producto-container p {
                font-size: 14px;
            }

            .acciones button, .acciones a {
                font-size: 14px;
            }

            .regresar {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <?php include('Funciones/header.php'); // Incluir el encabezado ?>
    </header>

    <div class="producto-container">
        <img src="Administrador/archivos/<?php echo htmlspecialchars($producto['archivo_n']); ?>" 
             alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($producto['costo'], 2); ?></p>
        <p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="acciones">
                <input type="number" min="1" value="1" id="cantidad_<?php echo $producto['id']; ?>"> <!-- Valor por defecto: 1 -->
                <button onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)">Agregar al carrito</button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($ids_recomendados)): ?>
            <h2 style="margin-top:25px;">Productos recomendados</h2>
            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-bottom:20px;">
        
                <?php
                $ids = implode(",", $ids_recomendados);
                $recStmt = $pdo->query("SELECT id, nombre, archivo_n FROM productos WHERE id IN ($ids)");
                $recomendados = $recStmt->fetchAll(PDO::FETCH_ASSOC);
        
                foreach ($recomendados as $rec): ?>
                    <div style="width:150px; text-align:center;">
                        <a href="detalle_producto.php?id=<?php echo $rec['id']; ?>">
                            <img src="Administrador/archivos/<?php echo $rec['archivo_n']; ?>" 
                                 style="width:100%; height:120px; object-fit:contain; border-radius:8px;">
                            <p><?php echo htmlspecialchars($rec['nombre']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
        
            </div>
        <?php endif; ?>

        <!-- Botón de regreso -->
        <a href="productos.php" class="regresar">Regresar</a>
    </div>

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

    <footer>
        <?php include('Funciones/footer.php'); // Incluir el pie de página ?>
    </footer>
</body>
</html>
