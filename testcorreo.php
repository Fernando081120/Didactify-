<?php
$connection = fsockopen('smtp.gmail.com', 587, $errno, $errstr, 10);
if (!$connection) {
    echo "Error: $errstr ($errno)";
} else {
    echo "Conexión exitosa al servidor SMTP";
    fclose($connection);
}
?>