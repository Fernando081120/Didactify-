<?php include 'Funciones/header.php'; ?>
<form action="contacto_envia.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    
    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" required>
    
    <label for="mensaje">Mensaje:</label>
    <textarea id="mensaje" name="mensaje" required></textarea>
    
    <button type="submit">Enviar</button>
</form>
<?php include 'Funciones/footer.php'; ?>
