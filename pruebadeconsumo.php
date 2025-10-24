<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sssssssssss</title>
</head>
<body>
    <h1> hola mucho gusto </h1>
    <?php
include_once('../consumoEndpoint.php');
// --- Example usage of the function ---
$endpoint = 'https://udp.coningenio.cl/api/v1/genero/';
$token = 'udp.2025';

$listageneros = getApiData($endpoint, $token);

if ($$listageneros !== null) {
    var_dump($listageneros);
    echo '<div class="row">';
    foreach ($item as $$listageneros) {
        if($item['activo']){
            $salida = "" . htmlspecialchars($item['nombre']);

            echo $salida;
        }
    }
    echo '</div>';
} else {
    echo "Error: No se pudo obtener la información. Por favor, revisa la URL y el token.";
}

?>
</body>
</html>