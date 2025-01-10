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
    $_SESSION['sesion_turl'] = $_POST['turl_inp'];
    $_SESSION['sesion_murl'] = $_POST['murl_inp'];
    $_SESSION['sesion_cur'] = $_POST['cur_inp'];
    $_SESSION['sesion_am'] = $_POST['amount_inp'];
    $_SESSION['sesion_card'] = $_POST['card_inp'];
    $_SESSION['sesion_expm'] = $_POST['expm_inp'];
    $_SESSION['sesion_expy'] = $_POST['expy_inp'];
    $_SESSION['sesion_cvv'] = $_POST['cvv_inp'];

} else {
    header("Location: apiform.php");
    exit;
}

if (isset($_POST['3ds_inp'])) {
    $value_3ds = $_POST['3ds_inp'];
} else {
    $value_3ds = 'off';
}

// Construcción de los XML SOAP
$req_sale_no_3ds = '
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
'; // Reemplazar con el XML correspondiente

$req_sale_3ds = '
<SOAP-ENV:Envelope
 xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <SOAP-ENV:Body>
        <ns4:IPGApiOrderRequest
 xmlns:ns4="http://ipg-online.com/ipgapi/schemas/ipgapi"
 xmlns:ns2="http://ipg-online.com/ipgapi/schemas/v1"
 >
            <ns2:Transaction>
                <ns2:CreditCardTxType>
                    <ns2:StoreId>' . strval($_SESSION['sesion_sid']) . '</ns2:StoreId>
                    <ns2:Type>sale</ns2:Type>
                </ns2:CreditCardTxType>
                <ns2:CreditCardData>
                    <ns2:CardNumber>' . strval($_SESSION['sesion_card']) . '</ns2:CardNumber>
                    <ns2:ExpMonth>' . strval($_SESSION['sesion_expm']) . '</ns2:ExpMonth>
                    <ns2:ExpYear>' . strval($_SESSION['sesion_expy']) . '</ns2:ExpYear>
                    <ns2:CardCodeValue>' . strval($_SESSION['sesion_cvv']) . '</ns2:CardCodeValue>
                </ns2:CreditCardData>
                <ns2:CreditCard3DSecure>
                    <ns2:AuthenticateTransaction>true</ns2:AuthenticateTransaction>
                    <ns2:TermUrl>' . strval($_SESSION['sesion_turl']) . '</ns2:TermUrl>
                    <ns2:ThreeDSMethodNotificationURL>' . strval($_SESSION['sesion_murl']) . '</ns2:ThreeDSMethodNotificationURL>
                    <ns2:ThreeDSRequestorChallengeIndicator>01</ns2:ThreeDSRequestorChallengeIndicator>
                    <ns2:ThreeDSRequestorChallengeWindowSize>01</ns2:ThreeDSRequestorChallengeWindowSize>
                </ns2:CreditCard3DSecure>
                <ns2:Payment>
                    <ns2:ChargeTotal>' . strval($_SESSION['sesion_am']) . '</ns2:ChargeTotal>
                    <ns2:Currency>' . strval($_SESSION['sesion_cur']) . '</ns2:Currency>
                </ns2:Payment>
            </ns2:Transaction>
        </ns4:IPGApiOrderRequest>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
';
$requestsoap = ($value_3ds === 'on' || $value_3ds === 'ON') ? $req_sale_3ds : $req_sale_no_3ds;

// Función para hacer beautify del XML
function beautifyXml($xmlString) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlString);
    return $dom->saveXML();
}

// Función para buscar el campo Secure3DMethodForm en la respuesta
function extractSecure3DMethodForm($xmlResponse) {
    $dom = new DOMDocument;
    $dom->loadXML($xmlResponse);
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace("v1", "http://ipg-online.com/ipgapi/schemas/v1");
    $formNode = $xpath->query("//v1:Secure3DMethodForm");
    return $formNode->length > 0 ? $formNode->item(0)->nodeValue : "Campo no encontrado";
}

// Función para extraer datos de la transacción del XML de respuesta
function extractTransactionData($xmlResponse) {
    $dom = new DOMDocument;
    $dom->loadXML($xmlResponse);
    $xpath = new DOMXPath($dom);

    // Registrar el namespace 'ipgapi' para buscar nodos en ese contexto
    $xpath->registerNamespace("ipgapi", "http://ipg-online.com/ipgapi/schemas/ipgapi");

    // Buscar el nodo <ipgapi:IpgTransactionId>
    $ipgTransactionIdNode = $xpath->query("//ipgapi:IpgTransactionId");
    $ipgTransactionId = $ipgTransactionIdNode->length > 0 ? $ipgTransactionIdNode->item(0)->nodeValue : "IpgTransactionId no encontrado";

    // Buscar el nodo <ipgapi:OrderId>
    $orderIdNode = $xpath->query("//ipgapi:OrderId");
    $orderId = $orderIdNode->length > 0 ? $orderIdNode->item(0)->nodeValue : "OrderId no encontrado";

    // Devolver los valores encontrados
    return [
        "IpgTransactionId" => $ipgTransactionId,
        "OrderId" => $orderId
    ];
}

// Reemplazar con el XML correspondiente

// Función para realizar la solicitud SOAP
function sendSoapRequest($url, $requestBody, $username, $password, $certPath, $certPassword) {
    $ch = curl_init($url);

    // Configuración de cURL
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

    // Configurar las cabeceras HTTP
    $headers = [
        "Content-Type: text/xml; charset=utf-8",
        "SOAPAction: \"\"",
        "Content-Length: " . strlen($requestBody)
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Configurar autenticación básica
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

    // Configurar el certificado P12
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, "P12");
    curl_setopt($ch, CURLOPT_SSLCERT, $certPath);
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $certPassword);

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
        curl_close($ch);
        return ["error" => true, "message" => $error_msg];
    }

    curl_close($ch);
    return ["error" => false, "response" => $response];
}

// Llamar a la función para enviar la solicitud SOAP
$soapUrl = "https://test.ipg-online.com/ipgapi/services";
$result = sendSoapRequest(
    $soapUrl,
    $requestsoap,
    $_SESSION['sesion_user'],
    $_SESSION['sesion_upass'],
    $_SESSION['sesion_cpath'],
    $_SESSION['sesion_cpass']
);

if ($result['error']) {
    $formattedResponse = "Error: " . htmlspecialchars($result['message']);
} else {
    $formattedResponse = beautifyXml($result['response']);
    $secure3DMethodForm = extractSecure3DMethodForm($result['response']);
    $responseData = extractTransactionData($result['response']);
    $_SESSION['sesion_oid'] = $responseData['OrderId'];
    $_SESSION['sesion_trxid'] = $responseData['IpgTransactionId'];
}

$iframe_received = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ipg="http://ipg-online.com/ipgapi/schemas/ipgapi" xmlns:v1="http://ipg-online.com/ipgapi/schemas/v1">
    <soapenv:Header/>
    <soapenv:Body>
        <ipg:IPGApiOrderRequest>
            <v1:Transaction>
                <v1:CreditCardTxType>
                    <v1:StoreId>' . strval($_SESSION['sesion_sid']) . '</v1:StoreId>
                    <v1:Type>sale</v1:Type>
                </v1:CreditCardTxType>
                <v1:CreditCard3DSecure>
                    <v1:Secure3DMethodNotificationStatus>RECEIVED</v1:Secure3DMethodNotificationStatus>
                </v1:CreditCard3DSecure>
                <v1:TransactionDetails>
                    <v1:IpgTransactionId>' . strval($_SESSION['sesion_trxid']) . '</v1:IpgTransactionId>
                </v1:TransactionDetails>
            </v1:Transaction>
        </ipg:IPGApiOrderRequest>
    </soapenv:Body>
</soapenv:Envelope>
';

if ($value_3ds === 'on' || $value_3ds === 'ON') {
    $resultiframe = sendSoapRequest(
        $soapUrl,
        $iframe_received,
        $_SESSION['sesion_user'],
        $_SESSION['sesion_upass'],
        $_SESSION['sesion_cpath'],
        $_SESSION['sesion_cpass']
    );
    
    if ($resultiframe['error']) {
        $formattedResponse2 = "Error: " . htmlspecialchars($resultiframe['message']);
    } else {
        $formattedResponse2 = beautifyXml($resultiframe['response']);
        $secure3DMethodForm2 = extractSecure3DMethodForm($resultiframe['response']);
        $responseData2 = extractTransactionData($resultiframe['response']);
        $_SESSION['sesion_oid'] = $responseData2['OrderId'];
        $_SESSION['sesion_trxid'] = $responseData2['IpgTransactionId'];
    }
} else {
    $formattedResponse2 = 'No 3DS';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAP Response</title>
</head>
<body>
    <pre><?php echo $secure3DMethodForm ?? ''; ?></pre>

    <h1>SOAP Request</h1>
    <pre><?php echo htmlspecialchars($requestsoap); ?></pre>

    <h1>SOAP Response</h1>
    <pre><?php echo htmlspecialchars($formattedResponse); ?></pre>

    <h1>Data</h1>
    <pre><?php echo 'oid: ' . htmlspecialchars($_SESSION['sesion_oid'] ?? ''); ?></pre>
    <pre><?php echo 'IPGtransactionId: ' . htmlspecialchars($_SESSION['sesion_trxid'] ?? ''); ?></pre>

    <h1>iFrame response</h1>
    <pre><?php echo htmlspecialchars($formattedResponse2); ?></pre>
</body>
</html>
