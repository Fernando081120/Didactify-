<?php
// Inicia la sesión
session_start();

// Incluye el archivo de configuración para la base de datos
include('Funciones/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura y valida los datos del formulario
    $correo = $_POST['correo'];
    $pass = md5($_POST['pass']); // Usa password_hash() en producción

    // Consulta para verificar las credenciales del usuario
    $query = "SELECT id, nombre FROM clientes WHERE correo = :correo AND pass = :pass AND eliminado = 0";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':pass', $pass);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="shortcut icon" href="Administrador/archivos/logo.ico" />
    <style>
        /* Fondo tecnológico */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: 
              linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
              url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Caja de inicio de sesión */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 35px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            text-align: center;
        }

        h1 {
            text-align: center;
            color: #222;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
        }

        input[type="email"], 
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #bbb;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        /* 🔹 Botón pequeño fuera de la caja */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: rgba(0,123,255,0.9);
            color: white;
            border: none;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: rgba(0,86,179,1);
        }
    </style>
</head>
<body>
    <!-- 🔹 Botón fuera de la caja -->
    <a href="index.php" class="back-button">←</a>

    <!-- Caja de inicio de sesión -->
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" required>
            
            <button type="submit">Iniciar Sesión</button>
            <a href="Administrador/index.php">Administrador</a>
        </form>
    </div>
</body>
</html>
