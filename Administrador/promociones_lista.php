<?php 
require "Funciones/conecta.php";
session_start();

$con = conecta();

if (!isset($_SESSION['usuario_nombre'])) {
    header('Location: index.php'); 
    exit();
}

$sql = "SELECT * FROM promociones WHERE eliminado = 0";
$res = $con->query($sql);
$num = $res->num_rows;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de promociones</title>
    <link rel="shortcut icon" href="archivos/logo.ico" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #DCDCDC;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #F6EDE8;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .btn-crear {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-crear:hover {
            background-color: #218838;
        }

        .tabla-promociones {
            display: flex;
            flex-direction: column;
        }

        .fila {
            display: flex;
            border-bottom: 1px solid #ddd;
        }

        .fila.header {
            font-weight: bold;
            background-color: #008B8B;
        }

        .fila div {
            padding: 10px;
            flex: 1;
            border-right: 1px solid #ddd;
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .fila div:last-child {
            border-right: none;
        }

        .fila:nth-child(even) {
            background-color: #f2f2f2;
        }

        .fila:hover {
            background-color: #e9ecef;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .btn-eliminar:focus {
            outline: none;
        }

        .btn-eliminar:active {
            background-color: #bd2130;
        }

        .btn-editar {
            background-color: #f46121;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-editar:hover {
            background-color: #0056b3;
        }

        .btn-ver {
            background-color: #f4ba21;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-ver:hover {
            background-color: #138496;
        }
    </style>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                $.ajax({
                    url: 'eliminar_promociones.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response == 1) {
                            $('#fila-' + id).remove();
                            alert('Promocion eliminada exitosamente.');
                        } else {
                            alert('Error al eliminar el Promocion.');
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <?php
        include('menu_navegacion.php')
    ?>
    <div class="container">
        <h1>Listado de promociones (<?php echo $num; ?>)</h1>        
        <a href="promociones_alta.php" class="btn-crear">Crear nuevo registro</a>

        <div class="tabla-promociones">
            <div class="fila header">
                <div>ID</div>
                <div>Nombre</div>
                <div>Ver detalle</div>
                <div>Editar</div>
                <div>Eliminar</div>
            </div>

            <?php while($row = $res->fetch_array()): ?>
            <div class="fila" id="fila-<?php echo $row['id']; ?>">
                <div><?php echo $row["id"]; ?></div>
                <div><?php echo $row["nombre"]; ?></div>

                <!-- Botón Ver Detalle -->
                <div>
                    <a href="verDetalle_promociones.php?id=<?php echo $row['id']; ?>" class="btn-ver">Ver</a>
                </div>

                <!-- Botón Editar (usando formulario POST) -->
                <div>
                    <form action="editar_promociones.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-editar">Editar</button>
                    </form>
                </div>

                <!-- Botón Eliminar -->
                <div>
                    <button class="btn-eliminar" onclick="confirmDelete(<?php echo $row['id']; ?>)">Eliminar</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
