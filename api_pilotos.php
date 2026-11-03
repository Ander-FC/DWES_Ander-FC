<?php

function consultarOpenF1($url){
    if (!function_exists('curl_init')) {
        die('Activa la extensión cURL de PHP para consultar OpenF1.');
    }

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPGET => true,
    ]);

    $respuesta = curl_exec($curl);
    if ($respuesta === false) {
        die('Error cURL: ' . curl_error($curl));
    }
    $estado = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($estado !== 200) {
        die('OpenF1 devolvió el estado HTTP ' . $estado . '.');
    }

    $datos = json_decode($respuesta, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die('La respuesta de OpenF1 no es válida.');
    }

    return $datos;
}

$anio = date('Y');
$sesiones = consultarOpenF1("https://api.openf1.org/v1/sessions?year={$anio}&session_name=Race");

$sesion = null;
foreach ($sesiones as $candidata) {
    if (strtotime($candidata['date_start']) <= time()
        && ($sesion === null || $candidata['date_start'] > $sesion['date_start'])) 
    {
        $sesion = $candidata;
    }
}

if ($sesion === null) {
    die('Todavía no hay carreras celebradas en la temporada actual.');
}

$pilotos = [];
foreach ([16, 44] as $numero) {
    $datos = consultarOpenF1(
        'https://api.openf1.org/v1/drivers?session_key=' . $sesion['session_key'] . '&driver_number=' . $numero
    );

    if (empty($datos)) {
        die("No se encontraron datos para el piloto {$numero}.");
    }

    $pilotos[] = $datos[0];
}

foreach ($pilotos as $piloto) {
    echo 'Piloto ' . $piloto['driver_number'] . ': ' . $piloto['full_name'] . PHP_EOL;
    echo 'Escudería: ' . $piloto['team_name'] . PHP_EOL . PHP_EOL;
}
