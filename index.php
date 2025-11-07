<?php
session_start();
include('Funciones/config.php');

// Obtener promociones
$query_promociones = "SELECT * FROM promociones WHERE eliminado = 0 ORDER BY RAND() LIMIT 3";
$stmt_promociones = $pdo->prepare($query_promociones);
$stmt_promociones->execute();
$promociones = $stmt_promociones->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio | Didactify</title>
  <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
  <script src="js/jquery-3.3.1.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .promo-carousel {
      position: relative;
      max-width: 1010px;
      margin: 40px auto;
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      background-color: #fff;
    }

    .carousel-track {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .carousel-slide {
      min-width: 100%;
      box-sizing: border-box;
      text-align: center;
      padding: 5px;
      cursor: pointer;
    }

    .carousel-slide img {
      width: 40%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 12px;
    }

    .carousel-slide h2 {
      margin: 10px 0;
      font-size: 1.2rem;
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      font-size: 2rem;
      padding: 10px 14px;
      cursor: pointer;
      border-radius: 50%;
      z-index: 5;
      transition: background-color 0.3s;
    }

    .carousel-btn:hover {
      background-color: rgba(0, 0, 0, 0.7);
    }

    .carousel-btn.prev {
      left: 10px;
    }

    .carousel-btn.next {
      right: 10px;
    }

    .productos {
      margin: 40px auto;
      max-width: 90%;
    }

    .producto-lista {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
    }

    .producto-item {
      width: 30%;
      margin: 20px 0;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .producto-item img {
      width: 100px;
      height: auto;
      margin-bottom: 10px;
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
      border-color: #007BFF;
      box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
    }

    .producto-item button {
      background-color: #007BFF;
      color: white;
      margin-top: 8px;
      border: none;
      display: block;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease, transform 0.2s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .producto-item button:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }

    .producto-item button:active {
      background-color: #004085;
      transform: scale(1);
    }

    .cantidad-contenedor {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .cantidad-contenedor label {
      font-size: 1rem;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php include('Funciones/header.php'); ?>

<!-- Título -->
<div style="margin-top: 70px; text-align: center;">
    <h1></h1>
    <h1></h1>
</div>

<!-- Carrusel de promociones -->
<div class="promo-carousel">
  <div class="carousel-track">
    <?php foreach ($promociones as $promocion): ?>
      <div class="carousel-slide">
        <!-- <h2><?php // echo htmlspecialchars($promocion['nombre']); ?></h2> -->
        <img src="Administrador/archivos/<?php echo htmlspecialchars($promocion['archivo_n']); ?>" alt="Promoción">
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-btn prev" id="prevBtn">&#10094;</button>
  <button class="carousel-btn next" id="nextBtn">&#10095;</button>
</div>


<!-- Productos -->
<div class="productos">
  <h2 style="text-align: center;">Productos Para Ti:</h2>
  <div class="producto-lista">
    <?php
    $query_productos = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY RAND() LIMIT 6";
    $stmt_productos = $pdo->prepare($query_productos);
    $stmt_productos->execute();
    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($productos)) {
      foreach ($productos as $producto): ?>
        <div class="producto-item">
          <img src="Administrador/archivos/<?php echo htmlspecialchars($producto['archivo_n']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
          <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
          <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
          <p>Precio: $<?php echo htmlspecialchars($producto['costo']); ?></p>
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="cantidad-contenedor">
              <label for="cantidad_<?php echo $producto['id']; ?>">Cantidad:</label>
              <input type="number" min="1" value="1" id="cantidad_<?php echo $producto['id']; ?>">
            </div>
            <button onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)">Agregar al carrito</button>
          <?php endif; ?>
        </div>
      <?php endforeach;
    } else {
      echo "<p>No hay productos disponibles.</p>";
    }
    ?>
  </div>
</div>




<!-- JS para carrusel y carrito -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(document.querySelectorAll('.carousel-slide'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentIndex = 0;

    function updateCarousel() {
      const offset = -currentIndex * 100;
      track.style.transform = `translateX(${offset}%)`;
    }

    nextBtn.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % slides.length;
      updateCarousel();
    });

    prevBtn.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      updateCarousel();
    });

    slides.forEach(slide => {
      slide.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateCarousel();
      });
    });

    setInterval(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      updateCarousel();
    }, 3000);
  });

  function agregarAlCarrito(productoId) {
    const cantidad = document.getElementById(`cantidad_${productoId}`).value;
    if (cantidad > 0) {
      fetch('insertar_producto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
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

<?php include('Funciones/footer.php'); ?>
</body>
</html>
