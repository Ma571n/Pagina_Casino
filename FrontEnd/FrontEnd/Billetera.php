<?php
session_start();
require_once 'consumoEndpoint.php';
require_once 'db.php';

$token = 'udp.2025';

// 1) Determinar qué billetera usar
$billetera = null;
$idBilletera = $_SESSION['usuario']['id_billetera'] ?? null;

// a) Si tienes id_billetera en sesión, intenta usarla
if ($idBilletera !== null) {
    $resp = getApiData(
        "https://udp.coningenio.cl/casino/api/v1/billetera/?id=" . urlencode($idBilletera),
        $token
    );
    if ($resp !== null && count($resp) > 0) {
        $billetera = $resp[0];
    }
}

// b) Si no hay nada en sesión o falló, toma la primera billetera activa como demo
if ($billetera === null) {
    $resp = getApiData(
        "https://udp.coningenio.cl/casino/api/v1/billetera/",
        $token
    );
    if ($resp !== null) {
        foreach ($resp as $b) {
            if (!empty($b['activo'])) {
                $billetera = $b;
                // Guardar en sesión para que TODAS las páginas usen la misma billetera
                $_SESSION['usuario']['id_billetera'] = $b['id'] ?? null;
                break;
            }
        }
    }
}

// ================== Saldo y movimientos desde la base local ==================

// Saldo base que viene del endpoint
$saldoBase       = 0;
$idBilleteraReal = null;

if ($billetera !== null) {
    $saldoBase       = isset($billetera['saldo']) ? (int)$billetera['saldo'] : 0;
    $idBilleteraReal = $billetera['id'] ?? $idBilletera;
}

$movimientos = [];
$totalMov    = 0;

if ($idBilleteraReal !== null) {
    // 1) Movimientos para el histórico
    $stmt = $pdo->prepare("
        SELECT id, billetera_id_api, tipo, monto, fecha
        FROM billetera_movimientos
        WHERE billetera_id_api = :id
        ORDER BY fecha DESC, id DESC
    ");
    $stmt->execute([':id' => $idBilleteraReal]);
    $movimientos = $stmt->fetchAll();

    // 2) Suma total de montos (positivos y negativos)
    $stmt2 = $pdo->prepare("
        SELECT COALESCE(SUM(monto), 0) AS total_mov
        FROM billetera_movimientos
        WHERE billetera_id_api = :id
    ");
    $stmt2->execute([':id' => $idBilleteraReal]);
    $row      = $stmt2->fetch();
    $totalMov = $row ? (int)$row['total_mov'] : 0;
}

// Saldo inicial mostrado en la barra = saldo API + movimientos
$saldoInicial = $saldoBase + $totalMov;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil | Billetera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --nav-h: 44px;
            --page-bg: #fff;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--page-bg);
            color: #1f1f1f;
        }

        main {
            flex: 1;
            padding-top: calc(var(--nav-h) + 8px);
        }

        .custom-navbar {
            background: #4c2ca3;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        .navbar-brand {
            font-weight: 600;
            color: #fff;
        }

        .card-perfil {
            max-width: 1890px;
            margin: 16px auto 0;
            background: #fff;
            border: 2px solid #b388ff;
            border-radius: 22px;
            padding: 26px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
        }

        .pie-pagina {
            background: #4c2ca3;
            color: #dcd6ff;
            padding: 28px 0 110px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin-top: 3rem;
        }

        #barraInfo .nav-link.active {
            background-color: #5534a1 !important;
            color: #fff !important;
            border-color: #5534a1 !important;
            border-radius: 14px;
        }

        .monto-pos {
            color: #2e7d32;
            font-weight: 600;
        }

        .monto-neg {
            color: #c62828;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <!-- Navbar PHP integrado -->
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Barra de info general (la que ya tenías) -->
        <section id="barraInfo" class="barra-principal"></section>

         <!-- Barra BILLETERA específica de esta página -->
        <section class="mt-2">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">

                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="CargarDinero.php">
                            <i class="bi bi-arrow-up-circle me-1"></i> Cargar monto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="RetirarDinero.php">
                            <i class="bi bi-arrow-down-circle me-1"></i> Retirar monto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Billetera.php">
                            <i class="bi bi-clock-history me-1"></i> Histórico
                        </a>
                    </li>
                </ul>

                <div class="text-end">
                    <small class="text-muted d-block">Saldo disponible</small>
                    <strong>$ <span id="saldoBarraBilletera">
    <?php echo number_format($saldoInicial, 0, ',', '.'); ?>
</span></strong>
                </div>

            </div>
        </section>

        <!-- Card con histórico -->
        <section class="card-perfil">
            <h2 class="h5 mb-3">Histórico de transacciones</h2>

            <div class="table-responsive" style="max-height:420px;">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>DETALLE</th>
                            <th>ABONO (+)</th>
                            <th>RETIRO (-)</th>
                            <th>JUEGO (+/-)</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (empty($movimientos)): ?>
        <tr>
            <td colspan="5" class="text-center text-muted">
                No hay movimientos registrados todavía.
            </td>
        </tr>
    <?php else: ?>
        <?php foreach ($movimientos as $mov): 
            $fechaRaw = $mov['fecha'] ?? '';
            $tipo     = $mov['tipo']  ?? '';
            $montoRaw = (int)($mov['monto'] ?? 0);

            // Formatear fecha
            $fecha = $fechaRaw;
            if (!empty($fechaRaw)) {
                $dt = DateTime::createFromFormat('Y-m-d H:i:s', $fechaRaw);
                if ($dt !== false) {
                    $fecha = $dt->format('d/m/Y · H:i');
                } else {
                    $fecha = htmlspecialchars($fechaRaw);
                }
            }

            $montoAbs     = abs($montoRaw);
            $montoFormato = number_format($montoAbs, 0, ',', '.');

            $detalle   = 'Movimiento';
            $abonoCol  = '—';
            $retiroCol = '—';
            $juegoCol  = '—';

            switch ($tipo) {
                case 'carga':
                    $detalle  = 'Carga de dinero';
                    $abonoCol = '<span class="monto-pos">+ $' . $montoFormato . '</span>';
                    break;

                case 'retiro':
                    $detalle   = 'Retiro de dinero';
                    $retiroCol = '<span class="monto-neg">- $' . $montoFormato . '</span>';
                    break;

                case 'juego':
                    $detalle = 'Movimiento por juego';
                    if ($montoRaw > 0) {
                        $juegoCol = '<span class="monto-pos">+ $' . $montoFormato . '</span>';
                    } elseif ($montoRaw < 0) {
                        $juegoCol = '<span class="monto-neg">- $' . $montoFormato . '</span>';
                    } else {
                        $juegoCol = '<span class="text-muted">$0</span>';
                    }
                    break;
            }
        ?>
            <tr>
                <td><?php echo $fecha; ?></td>
                <td><?php echo htmlspecialchars($detalle); ?></td>
                <td><?php echo $abonoCol; ?></td>
                <td><?php echo $retiroCol; ?></td>
                <td><?php echo $juegoCol; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>

    <div id="menuFooter"></div>

    <script src="navbarconlogin.js"></script>
    <script src="footer.js"></script>
    <script src="barraInformacion.php"></script>
    <script src="autentificacion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Footer -->
    <footer class="pie-pagina">
        <div class="container text-center">
            <p>&copy; 2024 El casino de los K. Proyecto académico.</p>
            <?php if (isset($_SESSION['usuario'])): ?>
                <p class="text-muted">Sesión activa como: <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            <?php else: ?>
                <p class="text-muted">Inicia sesión para acceder a todos los juegos</p>
            <?php endif; ?>
        </div>
    </footer>

    <!-- Sincronizar saldo con localStorage (igual que la ruleta) -->
     <?php
// Clave única por usuario
$userKey = 'anonimo';

if (isset($_SESSION['usuario']['id'])) {
    $userKey = 'user_' . $_SESSION['usuario']['id'];
}
?>


</body>
</html>
