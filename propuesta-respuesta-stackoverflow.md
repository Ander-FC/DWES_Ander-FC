# Propuesta de respuesta

El formulario ya envía los datos a PHP. Desde ese archivo, haz una petición
POST a EnviaYa con cURL, decodifica el JSON y muestra los campos necesarios.
Adapta el nombre de la variable de la API key a tu archivo:

    <?php
    require_once __DIR__ . '/key.php';

    $datos = [
        'carrier_account' => null,
        'api_key' => $apiKey,
        'carrier' => $_POST['paqueteria'] ?? '',
        'shipment_number' => trim($_POST['guia'] ?? ''),
    ];

    $curl = curl_init('https://enviaya.com.mx/api/v1/trackings');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($datos),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);

    $respuesta = curl_exec($curl);
    if ($respuesta === false) {
        exit('Error cURL: ' . curl_error($curl));
    }

    $resultado = json_decode($respuesta, true);
    curl_close($curl);

    if (!is_array($resultado)) {
        exit('La respuesta de EnviaYa no es válida.');
    }

    echo htmlspecialchars($resultado['shipment_status'] ?? 'Estado no disponible', ENT_QUOTES, 'UTF-8');

Documentación: https://www.php.net/manual/es/book.curl.php
