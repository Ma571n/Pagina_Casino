<?php
// ======================= GET ===========================
function getApiData(string $url, string $bearerToken): ?array
{
    $options = [
        'http' => [
            'method' => 'GET',
            'header' =>
                "Authorization: Bearer " . $bearerToken . "\r\n" .
                "Accept: application/json\r\n"
        ]
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }
    return $data;
}

// ======================= POST ==========================
// Enviar JSON con método POST al endpoint de la API
function postApiData(string $url, string $bearerToken, array $payload): ?array
{
    // variable global para depurar
    global $lastPostDebug;

    $jsonBody = json_encode($payload);

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  =>
                "Authorization: Bearer " . $bearerToken . "\r\n" .
                "Accept: application/json\r\n" .
                "Content-Type: application/json\r\n",
            'content' => $jsonBody
        ]
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    // guardar info de depuración SIEMPRE
    $lastPostDebug = [
        'url'     => $url,
        'payload' => $payload,
        'headers' => isset($http_response_header) ? $http_response_header : null,
        'raw'     => $response
    ];

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }
    return $data;
}





/*
<?php
include_once('api/consumoEndpoint.php');
// --- Example usage of the function ---
$endpoint = 'https://udp.coningenio.cl/api/v1/proyecto/';
$token = 'udp.2025';

$proyectos = getApiData($endpoint, $token);

if ($proyectos !== null) {
    echo '<div class="row">';
    foreach ($proyectos as $proyecto) {
        if($proyecto['activo']){
            $tarjeta = "
                <div class='col-4 g-4'>
                    <div class='card border-danger'>
                        <div class='card-header text-center bg-danger text-white'>
                            Proyecto Semestral
                        </div>
                        <div class='card-body'>
                            <h5 class='card-title text-center'>" . htmlspecialchars($proyecto['nombre']) . "</h5>
                            <hr>
                            " . htmlspecialchars($proyecto['descripcion']) . "
                            <hr>
                            <strong>Integrantes</strong>
                            " . htmlspecialchars($proyecto['integrantes']) . "
                        </div>
                        <div class='card-footer'>
                            <a href='".htmlspecialchars($proyecto['url'])."' target='_blank'><button class='btn btn-dark w-100'><i class='fa fa-link'></i> Acceso</button></a>
                        </div>
                    </div>
                </div>
            ";
            echo $tarjeta;
        }
    }
    echo '</div>';
} else {
    echo "Error: No se pudo obtener la información. Por favor, revisa la URL y el token.";
}

?>
*/

?>