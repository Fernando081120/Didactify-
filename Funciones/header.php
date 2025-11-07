<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tiendaa</title>
    <link rel="stylesheet" href="css/styles.css">  <!-- Estilos principales -->
    <script src="js/jquery-3.3.1.min.js"></script>  <!-- Script principal -->
</head>

<header style="background: linear-gradient(90deg, #1750ac, #1e88e5); box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
    <div class="header-container" style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; padding: 8px 20px;">
        
        <!-- Logo -->
        <div class="logo" style="display: flex; align-items: center; gap: 10px;">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: white; font-weight: bold; font-size: 20px;">
                <img src="Administrador/archivos/logo.png" alt="Logotipo" style="height: 55px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.5)); transition: transform 0.3s ease;">
            </a>
        </div>

        <!-- Barra de navegación -->
        <nav>
            <div class="nav-buttons" style="display: flex; gap: 10px;">
                <a href="productos.php" class="nav-button">Productos</a>
                <a href="contacto.php" class="nav-button">Contacto</a>

                <!-- Si el usuario está logueado -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="carrito.php" class="nav-button">Ver Carrito</a>
                    <a href="logout.php" class="nav-button">Salir</a>
                    <span class="nav-button" style="color: white; font-weight: bold; text-shadow: 0 0 5px rgba(255,255,255,0.4); background: none; border: none; box-shadow: none;">Bienvenido <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <?php else: ?>
                    <!-- Si el usuario no está logueado -->
                    <a href="login.php" class="nav-button">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<!-- 🔹 Estilos mejorados de botones -->
<style>
.nav-button {
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  padding: 8px 18px;
  color: white;
  font-weight: 500;
  text-decoration: none;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  backdrop-filter: blur(4px);
  box-shadow: 0 2px 5px rgba(255, 255, 255, 0.1);
}

/* Hover */
.nav-button:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
  border-color: rgba(255, 255, 255, 0.6);
}

/* Active */
.nav-button:active {
  transform: scale(0.97);
  box-shadow: 0 1px 4px rgba(255, 255, 255, 0.2);
}
</style>

</html>





