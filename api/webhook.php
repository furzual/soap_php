<?php

// Registrar los datos enviados al webhook
$input = file_get_contents('php://input');
$headers = getallheaders();

// Obtener la hora actual en formato legible
$dateTime = date("Y-m-d H:i:s");

// Guardar la información recibida para depuración en un archivo de log
file_put_contents('webhook.log', "Received at: " . $dateTime . "\n", FILE_APPEND);
file_put_contents('webhook.log', "Headers:\n" . print_r($headers, true) . "\n", FILE_APPEND);
file_put_contents('webhook.log', "Body:\n" . $input . "\n", FILE_APPEND);

// Decodificar si es JSON o XML
if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'application/json') !== false) {
    $data = json_decode($input, true); // Decodificar JSON
    $decodedData = print_r($data, true);
} elseif (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'xml') !== false) {
    $data = new SimpleXMLElement($input); // Decodificar XML
    $decodedData = $data->asXML();
} else {
    $data = $input; // Si no es JSON ni XML, se guarda como texto
    $decodedData = $data;
}

// Responder al webhook e imprimir los datos recibidos
http_response_code(200); // Respuesta OK
echo "<h1>Webhook Received</h1>";
echo "<h2>Received at</h2>";
echo "<p>" . htmlspecialchars($dateTime) . "</p>";
echo "<h2>Headers</h2>";
echo "<pre>" . htmlspecialchars(print_r($headers, true)) . "</pre>";
echo "<h2>Body</h2>";
echo "<pre>" . htmlspecialchars($decodedData) . "</pre>";
?>
