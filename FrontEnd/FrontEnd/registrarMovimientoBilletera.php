<?php
session_start();
require_once 'consumoEndpoint.php';
require_once 'db.php'; // conexión PDO a tu BD

header('Content-Type: application/json; charset=utf-8');

$token = 'udp.2025';

// 1) Leer JSON enviado por fetch
$rawBody = file_get_contents('php://input');
$data    = json_decode($rawBody, true);

if (!is_array($data)) {
    echo json_encode(['ok' => false, 'error' => 'JSON inválido']);
    exit;
}

$tipo  = $data['tipo']  ?? null;   // 'carga' | 'retiro'
$monto = $data['monto'] ?? null;   // número

if ($tipo === null || $monto === null) {
    echo json_encode(['ok' => false, 'error' => 'Faltan datos (tipo o monto)']);
    exit;
}

$monto = (int) $monto;

// 2) Determinar billetera desde la API UDP (igual que antes)
$billetera   = null;
$idBilletera = $_SESSION['usuario']['id_billetera'] ?? null;

// a) si hay id_billetera en sesión, lo usamos
if ($idBilletera !== null) {
    $resp = getApiData(
        "https://udp.coningenio.cl/casino/api/v1/billetera/?id=" . urlencode($idBilletera),
        $token
    );
    if ($resp !== null && count($resp) > 0) {
        $billetera = $resp[0];
    }
}

// b) fallback: primera billetera activa
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

$idBilleteraReal = (int) $billetera['id'];
$saldoBase       = isset($billetera['saldo']) ? (int)$billetera['saldo'] : 0;

// 3) Normalizar el monto según el tipo
// En la tabla local guardamos positivo si suma, negativo si resta.
$tipoMovimiento = null;
switch ($tipo) {
    case 'carga':
        $tipoMovimiento = 'carga';
        $monto = abs($monto);   // suma saldo
        break;

    case 'retiro':
        $tipoMovimiento = 'retiro';
        $monto = -abs($monto);  // resta saldo
        break;

    default:
        echo json_encode(['ok' => false, 'error' => 'Tipo no válido: ' . $tipo]);
        exit;
}

// 4) INSERT en tu tabla local billetera_movimientos
try {
    $stmt = $pdo->prepare("
        INSERT INTO billetera_movimientos (billetera_id_api, tipo, monto)
        VALUES (:billetera_id_api, :tipo, :monto)
    ");
    $stmt->execute([
        ':billetera_id_api' => $idBilleteraReal,
        ':tipo'             => $tipoMovimiento,
        ':monto'            => $monto
    ]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error al guardar en BD: ' . $e->getMessage()]);
    exit;
}

// 5) Calcular la suma de TODOS los movimientos locales para esta billetera
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(monto), 0) AS total_mov
    FROM billetera_movimientos
    WHERE billetera_id_api = :id
");
$stmt->execute([':id' => $idBilleteraReal]);
$row      = $stmt->fetch();
$totalMov = $row ? (int)$row['total_mov'] : 0;

// 6) Saldo final = saldo base de la API + lo que llevas movido localmente
$nuevoSaldo = $saldoBase + $totalMov;

// 7) Respuesta al JS
echo json_encode([
    'ok'          => true,
    'nuevo_saldo' => $nuevoSaldo
]);
