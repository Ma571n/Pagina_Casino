<?php
session_start();
require_once 'consumoEndpoint.php';
require_once 'db.php';

$token = 'udp.2025';


$billetera = null;
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
                $_SESSION['usuario']['id_billetera'] = $b['id'] ?? null;
                break;
            }
        }
    }
}

$saldoBase       = 0;
$idBilleteraReal = null;

if ($billetera !== null) {
    $saldoBase       = isset($billetera['saldo']) ? (int)$billetera['saldo'] : 0;
    $idBilleteraReal = $billetera['id'] ?? $idBilletera;
}

$totalMov = 0;
if ($idBilleteraReal !== null) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(monto), 0) AS total_mov
        FROM billetera_movimientos
        WHERE billetera_id_api = :id
    ");
    $stmt->execute([':id' => $idBilleteraReal]);
    $row      = $stmt->fetch();
    $totalMov = $row ? (int)$row['total_mov'] : 0;
}

$saldoInicial = $saldoBase + $totalMov;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Billetera | Retirar monto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --nav-h: 44px; --page-bg: #fff; }
        html, body { height: 100%; margin: 0; }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--page-bg);
            color: #1f1f1f;
        }
        main { flex: 1; padding-top: calc(var(--nav-h) + 8px); }
        .card-perfil {
            max-width: 720px;
            margin: 16px auto 0;
            background: #fff;
            border: 2px solid #b388ff;
            border-radius: 22px;
            padding: 26px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
        }
        .custom-navbar {
    background: #4c2ca3;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
}

.navbar-brand {
    font-weight: 600;
    color: #fff;
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


    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <main>
        <section id="barraInfo" class="barra-principal"></section>

        <section class="mt-2">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">

                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="CargarDinero.php">
                            <i class="bi bi-arrow-up-circle me-1"></i> Cargar monto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="RetirarDinero.php">
                            <i class="bi bi-arrow-down-circle me-1"></i> Retirar monto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Billetera.php">
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

        <section class="card-perfil">
            <h2 class="h5 mb-3">Retirar dinero de la billetera</h2>

            <form id="formRetirar" class="mt-3">
                <div class="mb-3">
                    <label for="montoRetirar" class="form-label">Monto a retirar</label>
                    <input type="number" min="100" step="100" class="form-control" id="montoRetirar" placeholder="Ej: 5000" required>
                    <div class="form-text">El retiro no puede superar tu saldo disponible.</div>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-dash-circle me-1"></i> Confirmar retiro
                </button>

                <div id="msgRetirar" class="mt-3 small"></div>
            </form>
        </section>
    </main>

    <div id="menuFooter"></div>

    <script src="navbarconlogin.js"></script>
    <script src="footer.js"></script>
    <script src="barraInformacion.php"></script>
    <script src="autentificacion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    <script>
    const spanSaldoBarra = document.getElementById('saldoBarraBilletera');
    let saldoActual = <?php echo $saldoInicial; ?>;

    if (spanSaldoBarra) {
        spanSaldoBarra.textContent = saldoActual.toLocaleString('es-CL');
    }

    const formRetirar = document.getElementById('formRetirar');
    const inputMonto  = document.getElementById('montoRetirar');
    const msgRetirar  = document.getElementById('msgRetirar');

    formRetirar.addEventListener('submit', function (e) {
        e.preventDefault();
        const monto = parseInt(inputMonto.value, 10);

        if (isNaN(monto) || monto <= 0) {
            msgRetirar.textContent = 'Ingrese un monto válido.';
            msgRetirar.className   = 'mt-3 small text-danger';
            return;
        }

        if (monto > saldoActual) {
            msgRetirar.textContent = 'No tienes saldo suficiente para retirar ese monto.';
            msgRetirar.className   = 'mt-3 small text-danger';
            return;
        }

        msgRetirar.textContent = 'Procesando...';
        msgRetirar.className   = 'mt-3 small text-muted';

        fetch('registrarMovimientoBilletera.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                tipo: 'retiro',
                monto: monto   
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) {
                msgRetirar.textContent = 'Error al registrar el retiro.';
                msgRetirar.className   = 'mt-3 small text-danger';
                console.error(data);
                return;
            }

            if (typeof data.nuevo_saldo === 'number') {
                saldoActual = data.nuevo_saldo;
                if (spanSaldoBarra) {
                    spanSaldoBarra.textContent = saldoActual.toLocaleString('es-CL');
                }
            }

            msgRetirar.textContent = `Retiro realizado: $${monto.toLocaleString('es-CL')}.`;
            msgRetirar.className   = 'mt-3 small text-success';
            inputMonto.value       = '';
        })
        .catch(err => {
            console.error(err);
            msgRetirar.textContent = 'Error de comunicación con el servidor.';
            msgRetirar.className   = 'mt-3 small text-danger';
        });
    });
</script>
</body>
</html>
