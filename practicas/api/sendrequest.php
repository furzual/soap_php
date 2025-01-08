<?php
session_start();
if (isset($_POST['environment-sel']) && 
    isset($_POST['storeid_inp']) && 
    isset($_POST['user_inp']) && 
    isset($_POST['upass_inp']) && 
    isset($_POST['cpath_inp']) && 
    isset($_POST['cpass_inp'])) {
    
    $_SESSION['sesion_env'] = $_POST['environment-sel'];
    $_SESSION['sesion_sid'] = $_POST['storeid_inp'];
    $_SESSION['sesion_user'] = $_POST['user_inp'];
    $_SESSION['sesion_upass'] = $_POST['upass_inp'];
    $_SESSION['sesion_cpath'] = $_POST['cpath_inp'];
    $_SESSION['sesion_cpass'] = $_POST['cpass_inp'];
    $_SESSION['sesion_sol'] = $_POST['solution-sel'];
    $_SESSION['sesion_cur'] = $_POST['cur_inp'];
    $_SESSION['sesion_am'] = $_POST['amount_inp'];
    $_SESSION['sesion_card'] = $_POST['card_inp'];
    $_SESSION['sesion_expm'] = $_POST['expm_inp'];
    $_SESSION['sesion_expy'] = $_POST['expy_inp'];
    $_SESSION['sesion_cvv'] = $_POST['cvv_inp'];
    $_SESSION['sesion_3ds'] = $_POST['3ds_inp'];
} else {
    header("Location: apiform.php");
    exit;
}

// Construcción del XML SOAP
$imprime = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ipg="http://ipg-online.com/ipgapi/schemas/ipgapi" xmlns:v1="http://ipg-online.com/ipgapi/schemas/v1">
    <soapenv:Header/>
    <soapenv:Body>
        <ipg:IPGApiOrderRequest>
            <v1:Transaction>
                <v1:CreditCardTxType>
                    <v1:StoreId>' . strval($_SESSION['sesion_sid']) . '</v1:StoreId>
                    <v1:Type>sale</v1:Type>
                </v1:CreditCardTxType>
                <v1:CreditCardData>
                    <v1:CardNumber>' . strval($_SESSION['sesion_card']) . '</v1:CardNumber>
                    <v1:ExpMonth>' . strval($_SESSION['sesion_expm']) . '</v1:ExpMonth>
                    <v1:ExpYear>' . strval($_SESSION['sesion_expy']) . '</v1:ExpYear>
                    <v1:CardCodeValue>' . strval($_SESSION['sesion_cvv']) . '</v1:CardCodeValue>
                </v1:CreditCardData>
                <v1:Payment>
                    <v1:ChargeTotal>' . strval($_SESSION['sesion_am']) . '</v1:ChargeTotal>
                    <v1:Currency>' . strval($_SESSION['sesion_cur']) . '</v1:Currency>
                </v1:Payment>
            </v1:Transaction>
        </ipg:IPGApiOrderRequest>
    </soapenv:Body>
</soapenv:Envelope>
';

// Configuración del endpoint SOAP
$soapUrl = "https://test.ipg-online.com/ipgapi/services"; // URL del servicio SOAP

// Inicializar cURL
$ch = curl_init($soapUrl);

// Configuración de cURL
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $imprime);

// Configurar las cabeceras HTTP
$headers = [
    "Content-Type: text/xml; charset=utf-8",
    "SOAPAction: \"\"",
    "Content-Length: " . strlen($imprime)
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Configurar autenticación básica
curl_setopt($ch, CURLOPT_USERPWD, $_SESSION['sesion_user'] . ":" . $_SESSION['sesion_upass']);

// Configurar el certificado P12
curl_setopt($ch, CURLOPT_SSLCERTTYPE, "P12");
curl_setopt($ch, CURLOPT_SSLCERT, $_SESSION['sesion_cpath']);
curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $_SESSION['sesion_cpass']);

// Habilitar la verificación SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

// Configurar el tiempo de espera
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Obtener la respuesta como string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud
$response = curl_exec($ch);

// Manejo de errores
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}
curl_close($ch);

// Función para hacer beautify del XML
function beautifyXml($xmlString) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlString);
    return $dom->saveXML();
}

// Beautify de la solicitud SOAP
$formattedRequest = beautifyXml($imprime);

// Beautify de la respuesta SOAP si no hay errores
$formattedResponse = isset($error_msg) ? $error_msg : beautifyXml($response);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAP Response</title>
</head>
<body>
    <h1>SOAP Request</h1>
    <pre><?php echo htmlspecialchars($formattedRequest); ?></pre>

    <h1>SOAP Response</h1>
    <pre><?php echo htmlspecialchars($formattedResponse); ?></pre>
</body>
</html>
