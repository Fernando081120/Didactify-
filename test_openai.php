<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include "/home/u133563347/domains/didactify.com.mx/secure/openai_key.php";



$payload = [
    "model" => "gpt-5",
    "messages" => [
        ["role" => "user", "content" => "Hola, prueba de conexión desde Hostinger"]
    ]
];

if (!$OPENAI_API_KEY) {
    die("No se cargó la API KEY");
}

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $OPENAI_API_KEY
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "Error: $err";
} else {
    $data = json_decode($response, true);
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
