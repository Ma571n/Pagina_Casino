
<?php
session_start();
require_once 'consumoEndpoint.php'; // ajusta el nombre/ruta si se llama distinto

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
                break;
            }
        }
    }
}

$saldoInicial = 0;
if ($billetera !== null && isset($billetera['saldo'])) {
    $saldoInicial = (int)$billetera['saldo'];
}
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

        #barraBilletera .nav-link.active {
            background-color: #4c7bf7 !important;
            color: #fff !important;
            border-color: #4c7bf7 !important;
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

        .saldo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .saldo-box {
            background: #f3f0ff;
            border-radius: 14px;
            padding: 10px 18px;
            border: 1px solid #b388ff;
        }

        .saldo-box span.label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b5ca5;
        }

        .saldo-box span.valor {
            font-size: 22px;
            font-weight: 700;
            color: #311b92;
        }
    </style>
</head>

<body>

    <!-- Navbar PHP integrado -->
    <?php include 'navbar.php'; ?>

    <main>
        <section id="barraInfo" class="barra-principal"></section>
        <section id="barraBilletera"></section>

        <section class="card-perfil">

            <div class="saldo-header">
                <h2 class="h5 mb-0">Billetera</h2>
                <div class="saldo-box">
                    <span class="label d-block">Saldo disponible</span>
                    $ <span class="valor" id="saldo-billetera"><?php echo $saldoInicial; ?></span>
                </div>
            </div>

            <div class="table-responsive" style="max-height:420px;">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>ABONO (+)</th>
                            <th>RETIROS (-)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>07/09/2025 · 23:38</td>
                            <td class="monto-pos">+ $20.000</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td>06/09/2025 · 18:21</td>
                            <td class="monto-pos">+ $10.000</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td>04/09/2025 · 15:08</td>
                            <td>—</td>
                            <td class="monto-neg">- $25.000</td>
                        </tr>
                        <tr>
                            <td>04/09/2025 · 14:09</td>
                            <td class="monto-pos">+ $10.000</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td>03/09/2025 · 12:05</td>
                            <td class="monto-pos">+ $20.000 </td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>
    </main>

    <div id="menuFooter"></div>

    <script src="navbarconlogin.js"></script>
    <script src="footer.js"></script>
    <script src="barraInformacion.php"></script>
    <script src="barraBilletera.php"></script>
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

    <!-- Sincronizar saldo con localStorage (mismo que ruleta) -->
    <script>
        const SALDO_KEY = 'saldoActual';
        const spanSaldo = document.getElementById('saldo-billetera');

        const saldoEndpoint = <?php echo $saldoInicial; ?>;
        const guardado = localStorage.getItem(SALDO_KEY);
        const numGuardado = guardado !== null ? parseInt(guardado, 10) : NaN;

        let saldoMostrar = saldoEndpoint;

        if (!isNaN(numGuardado)) {
            // Ya jugó en la ruleta: usamos el saldo que quedó allí
            saldoMostrar = numGuardado;
        } else {
            // Primera vez que entra: guardamos el saldo que viene del endpoint
            localStorage.setItem(SALDO_KEY, saldoEndpoint);
        }

        if (spanSaldo) {
            // Opcional: formatear con puntos de miles
            spanSaldo.textContent = saldoMostrar.toLocaleString('es-CL');
        }
    </script>

</body>
</html>
