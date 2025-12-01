<?php
$_method = $_SERVER['REQUEST_METHOD'];
$_host = $_SERVER['HTTP_HOST'];
$_uri = $_SERVER['REQUEST_URI'];
$_partes = explode('/', $_uri);

$_parametros = explode('?',$_partes[count($_partes)-1])[1];
$_parametroID = explode('id=', $_parametros)[1];

header("Access-Control-Allow-Origin: *");
header("Access-Control-ALlow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS"); 
header("Content-Type: application/json; charset=UTF-8");

$_authorization = null;
try {
    if (isset(getallheaders()['Authorization'])) {
        $_authorization = getallheaders()['Authorization'];
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'No tiene autorizacion']);
    }
} catch (Exception $e) {
    echo 'error';
}

$_token_get = 'Bearer udp.2025';
$_token_post = 'Bearer udp.2025';
$_token_patch = 'Bearer udp.2025';
$_token_disable = 'Bearer udp.2025';
$_token_put = 'Bearer udp.2025';
$_token_options = 'Bearer 2025.udp';
