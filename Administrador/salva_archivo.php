<?php
    $file_name = $_FILES['archivo']['name'];
    $file_tmp = $_FILES['archivo']['tmp_name'];

    $arreglo = explode(".", $file_name);
    $len = count($arreglo);
    $pos = $len - 1;
    $ext = $arreglo[$pos];

    $dir = "archivos/";

    $file_enc = md5_file($file_tmp);
    $fileName = "$file_enc.$ext";

    echo "file_name : $file_name <br>";
    echo "file_tmp : $file_tmp <br>";
    echo "ext : $ext <br>";
    echo "fileName : $fileName <br>";

    copy($file_tmp, $dir.$fileName);
?>


