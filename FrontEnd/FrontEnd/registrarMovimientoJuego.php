<?php
session_start();
require_once 'consumoEndpoint.php';
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$token = 'udp.2025';

$rawBody = file_get_contents('php://input');
$data    = json_decode($rawBody, true);

if (!is_array($data)) {
    echo json_encode(['ok' => false, 'error' => 'JSON inválido']);
    exit;
}

$monto     = $data['monto']      ?? null; -
$partidaId = $data['partida_id'] ?? null; 

if ($monto === null) {
    echo json_encode(['ok' => false, 'error' => 'Falta monto']);
    exit;
}

$monto = (int)$monto;

$billetera   = null;
$idBilletera = $_SESSION['usuario']['id_billetera'] ?? null;

if ($idBilletera !== null) {
    $resp = getApiData(
        "https://udp.coningenio.cl/casino/api/v1/billetera/?id=" . urlencode($idBilletera),
        $token
    );
    if ($resp !== null && count($resp) > 0) {
        $billetera = $resp[0];
    }
}

if ($billetera === null) {
    $resp = getApiData(
        "https://udp.coningenio.cl/casino/api/v1/billetera/",
        $token
    );
    if ($resp !== null) {
        foreach ($resp as $b) {
            if (!empty($b['activo'])) {
                $billetera = $b;
                break;
            }
        }
    }
}

if ($billetera === null || !isset($billetera['id'])) {
    echo json_encode(['ok' => false, 'error' => 'No se pudo determinar billetera']);
    exit;
}

$idBilleteraReal = (int)$billetera['id'];
$saldoBase       = isset($billetera['saldo']) ? (int)$billetera['saldo'] : 0;

try {
    $stmt = $pdo->prepare("
        INSERT INTO billetera_movimientos (billetera_id_api, tipo, monto)
        VALUES (:billetera_id_api, 'juego', :monto)
    ");
    $stmt->execute([
        ':billetera_id_api' => $idBilleteraReal,
        ':monto'            => $monto
    ]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al guardar en BD: ' . $e->getMessage()]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(monto), 0) AS total_mov
    FROM billetera_movimientos
    WHERE billetera_id_api = :id
");
$stmt->execute([':id' => $idBilleteraReal]);
$row      = $stmt->fetch();
$totalMov = $row ? (int)$row['total_mov'] : 0;

$nuevoSaldo = $saldoBase + $totalMov;

echo json_encode([
    'ok'          => true,
    'nuevo_saldo' => $nuevoSaldo
]);
