<?php
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

function postApiData(string $url, string $bearerToken, array $payload): ?array
{
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
?>