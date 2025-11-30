<?php
session_start();
require_once 'consumoEndpoint.php';
require_once 'db.php';  // conexión a la BD local

$token = 'udp.2025';

// 1) Determinar qué billetera usar
$billetera = null;
$idBilletera = $_SESSION['usuario']['id_billetera'] ?? null;

// a) Si tienes id_billetera en sesión, intenta usarlo
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
                // Guardar en sesión para que todas las páginas usen la misma billetera
                $_SESSION['usuario']['id_billetera'] = $b['id'] ?? null;
                break;
            }
        }
    }
}

// Calcular saldo base desde la API + movimientos locales
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

// 🔹 clave única de saldo/localStorage si aún la usas en JS
$userKey = 'anonimo';
if (isset($_SESSION['usuario']['id'])) {
    $userKey = 'user_' . $_SESSION['usuario']['id'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino K - Ruleta</title>

    <!-- Bootstrap (por si tu navbar lo usa) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #5D478B;
            color: white;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            user-select: none;
        }

        main.ruleta-main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .page-frame {
            background-color: rgba(0, 0, 0, 0.2); 
            border: 15px solid white; 
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 1250px;
        }

        .main-layout {
            display: flex;
            gap: 50px;
            align-items: flex-start;
            justify-content: center;
        }

        .left-panel {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .ruleta-contenedor {
            position: relative;
            width: 340px; height: 340px;
            background: white; 
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .ruleta-giratoria {
            width: 100%; height: 100%;
            border-radius: 50%;
            position: relative;
            transition: transform 4s cubic-bezier(0.25, 0.1, 0.25, 1);
        }

        .numero-rueda {
            position: absolute; top: 0; left: 50%; width: 2px; height: 50%;
            transform-origin: bottom center; 
            display: flex; justify-content: center; 
            z-index: 1;
        }

        .gajo {
            position: absolute; top: 0; left: 50%; width: 0; height: 0;
            border-left: 14px solid transparent; border-right: 14px solid transparent;
            border-top: 170px solid red; 
            transform-origin: bottom center; transform: translateX(-50%);
            z-index: 0;
        }

        .numero-texto {
            position: absolute; top: 5px; left: -10px; width: 20px; 
            text-align: center;
            font-size: 14px; font-weight: bold; color: white; 
            transform: rotate(180deg); 
            z-index: 2;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.3);
        }

        .centro-rueda {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120px; height: 120px;
            background-color: #5D478B;
            border: 8px solid white;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            z-index: 5;
        }
        .texto-centro { font-size: 50px; font-weight: bold; color: white; }

        .marcador {
            position: absolute; top: -15px; left: 50%; transform: translateX(-50%);
            width: 0; height: 0; 
            border-left: 15px solid transparent; border-right: 15px solid transparent;
            border-top: 35px solid #5D478B; z-index: 10;
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 15px;
        }
        .logo { font-size: 28px; font-weight: bold; letter-spacing: 2px; }
        
        .dashboard {
            display: flex; gap: 15px; align-items: center;
        }

        .stat-box {
            background: rgba(255,255,255,0.15);
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid rgba(255,255,255,0.3);
            min-width: 100px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.2;
        }
        .stat-label { font-size: 10px; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; }
        .stat-value { font-size: 18px; }

        .fichas-list { 
            display: flex; gap: 10px; 
            margin-left: 10px; 
            padding-left: 15px;
            border-left: 2px solid rgba(255,255,255,0.2);
        }
        .ficha {
            width: 45px; height: 45px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-weight: bold; cursor: pointer; border: 3px solid transparent;
            transition: transform 0.2s; color: black; font-size: 12px;
            background: #eee;
        }
        .ficha:hover { transform: scale(1.1); }
        .ficha.activa { border-color: white; transform: scale(1.15); box-shadow: 0 0 10px rgba(255,255,255,0.5); }

        .f-10 { background-color: #FDD835; }
        .f-50 { background-color: #43A047; color: white; }
        .f-100 { background-color: #1E88E5; color: white; }
        .f-500 { background-color: #8E24AA; color: white; }

        .tablero {
            display: flex; flex-direction: column; gap: 5px;
            padding: 10px;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
        }

        .grid-nums {
            display: grid;
            grid-template-columns: 50px repeat(12, 1fr) 50px;
            grid-template-rows: repeat(3, 50px);
            gap: 4px;
        }

        .casilla {
            position: relative;
            display: flex; justify-content: center; align-items: center;
            font-weight: bold; cursor: pointer; color: white;
            border-radius: 4px; font-size: 16px;
            transition: filter 0.1s;
        }
        .casilla:hover { filter: brightness(1.2); }
        .casilla:active { transform: scale(0.95); }

        .bg-green { background-color: #00C853; }
        .bg-red { background-color: #E53935; }
        .bg-black { background-color: #212121; }
        .bg-blue { background-color: #1E88E5; }
        
        .bg-yellow { 
            background-color: #FFB300; 
            color: white !important; 
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        } 
        
        .cero { grid-row: 1 / span 3; grid-column: 1; font-size: 22px; }

        .grid-bottom { display: grid; gap: 4px; margin-top: 4px; }
        .row-2 { display: grid; grid-template-columns: repeat(4, 1fr) 2fr; gap: 4px; } 
        .row-3 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; } 

        .btn-txt { 
            font-size: 14px; text-transform: uppercase; letter-spacing: 1px; 
            padding: 15px 0; 
            background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-girar {
            background-color: white; color: #5D478B;
            font-weight: 900; font-size: 18px; letter-spacing: 2px;
            grid-column: span 1; display: flex; align-items: center; justify-content: center;
            border-radius: 4px; cursor: pointer;
        }
        .btn-girar:hover { background-color: #eee; }

        .chip-on-board {
            position: absolute; width: 22px; height: 22px; border-radius: 50%;
            background: white; border: 2px solid #333;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5); pointer-events: none;
            z-index: 10;
        }
        .chip-on-board.v-10 { background: #FDD835; }
        .chip-on-board.v-50 { background: #43A047; }
        .chip-on-board.v-100 { background: #1E88E5; }
        .chip-on-board.v-500 { background: #8E24AA; }

        #mensaje { margin-top: 10px; text-align: center; height: 20px; font-weight: 500; opacity: 0.9; }

        footer.pie-pagina {
            background: rgba(0,0,0,0.4);
            color: #eee;
            padding: 15px 0;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="ruleta-main">
        <div class="page-frame">
            <div class="main-layout">
                
                <div class="left-panel">
                    <div class="ruleta-contenedor">
                        <div class="marcador"></div>
                        <div class="ruleta-giratoria" id="ruleta"></div>
                        <div class="centro-rueda">
                            <span class="texto-centro" id="resultado-num">K</span>
                        </div>
                    </div>
                    <div id="mensaje">Haz tu apuesta</div>
                </div>

                <div class="right-panel">
                    
                    <div class="header">
                        <span class="logo">CASINO K</span>
                        
                        <div class="dashboard">
                            <div class="stat-box">
                                <span class="stat-label">Saldo</span>
                                <span class="stat-value">$<span id="saldo"><?php echo $saldoInicial; ?></span></span>
                            </div>

                            <div class="stat-box">
                                <span class="stat-label">Apuesta</span>
                                <span class="stat-value">$<span id="apuesta-total">0</span></span>
                            </div>

                            <div class="fichas-list">
                                <div class="ficha f-10 activa" onclick="selFicha(10, this)">10</div>
                                <div class="ficha f-50" onclick="selFicha(50, this)">50</div>
                                <div class="ficha f-100" onclick="selFicha(100, this)">100</div>
                                <div class="ficha f-500" onclick="selFicha(500, this)">500</div>
                            </div>
                        </div>
                    </div>

                    <div class="tablero">
                        
                        <div class="grid-nums" id="grid-nums">
                            <div class="casilla bg-green cero" onclick="apostar('pleno', 0, this)">0</div>
                        </div>

                        <div class="grid-bottom">
                            <div class="row-2">
                                <div class="casilla btn-txt bg-red" onclick="apostar('color', 'rojo', this)">Rojo</div>
                                <div class="casilla btn-txt bg-black" onclick="apostar('color', 'negro', this)">Negro</div>
                                <div class="casilla btn-txt bg-blue" onclick="apostar('paridad', 'par', this)">Par</div>
                                <div class="casilla btn-txt bg-yellow" onclick="apostar('paridad', 'impar', this)">Impar</div>
                                <div class="btn-girar" id="btnGirar">GIRAR</div>
                            </div>

                            <div class="row-3">
                                <div class="casilla btn-txt bg-red" onclick="apostar('mitad', 'bajo', this)">1 - 18</div>
                                <div class="casilla btn-txt bg-red" onclick="apostar('mitad', 'alto', this)">19 - 36</div>
                                <div class="casilla btn-txt" style="background: #555" onclick="borrar()">Borrar</div>
                                <div class="casilla btn-txt" style="background: #555" onclick="deshacer()">Deshacer</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="pie-pagina">
        <div class="container">
            <p class="mb-0">
                &copy; 2024 Casino de los K · Proyecto académico UDP
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS (para navbar modal, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // saldo inicial viene desde PHP / endpoint
        let saldo = <?php echo $saldoInicial; ?>;
        let ficha = 10;
        let apuestas = [];
        let girando = false;
        window.rotGlobal = 0;

        const SALDO_KEY = 'saldoActual';

        const nums = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
        const rojos = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];

        const ruleta = document.getElementById('ruleta');
        const gridNums = document.getElementById('grid-nums');
        const saldoTxt = document.getElementById('saldo');
        const apuestaTotalTxt = document.getElementById('apuesta-total'); 
        const msg = document.getElementById('mensaje');

        function cargarSaldoInicial() {
    // Usar SIEMPRE el saldo que viene del backend
    saldoTxt.innerText = saldo;
}

function guardarSaldo() {
    // Ya no usamos localStorage; el saldo real se guarda en la BD
    // (esta función queda por compatibilidad para no cambiar más código)
}

        function init() {
            const ang = 360 / nums.length;
            nums.forEach((n, i) => {
                const wrap = document.createElement('div');
                wrap.className = 'numero-rueda';
                wrap.style.transform = `rotate(${i * ang}deg)`;
                
                const gajo = document.createElement('div');
                gajo.className = 'gajo';
                if(n===0) gajo.style.borderTopColor = '#00C853';
                else if(rojos.includes(n)) gajo.style.borderTopColor = '#E53935';
                else gajo.style.borderTopColor = '#212121';
                
                const texto = document.createElement('span');
                texto.className = 'numero-texto';
                texto.innerText = n;

                wrap.appendChild(gajo);
                wrap.appendChild(texto);
                ruleta.appendChild(wrap);
            });

            for(let f=3; f>=1; f--) {
                for(let c=0; c<12; c++) {
                    let n = c*3 + f;
                    let div = document.createElement('div');
                    let color = rojos.includes(n) ? 'bg-red' : 'bg-black';
                    div.className = `casilla ${color}`;
                    div.innerText = n;
                    div.style.gridColumn = c + 2;
                    div.style.gridRow = 4 - f;
                    div.onclick = function() { apostar('pleno', n, this); };
                    gridNums.appendChild(div);
                }
            }

            ['1ª', '2ª', '3ª'].forEach((txt, i) => {
                let div = document.createElement('div');
                div.className = 'casilla bg-blue btn-txt';
                div.innerText = "COL";
                div.style.gridColumn = 14; 
                div.style.gridRow = 3 - i; 
                div.onclick = function() { apostar('columna', i+1, this); };
                gridNums.appendChild(div);
            });
        }

        function actualizarDisplayTotal() {
            const total = apuestas.reduce((acc, curr) => acc + curr.cant, 0);
            apuestaTotalTxt.innerText = total;
        }

        function selFicha(v, el) {
            ficha = v;
            document.querySelectorAll('.ficha').forEach(x => x.classList.remove('activa'));
            el.classList.add('activa');
        }

        function apostar(tipo, val, el) {
            if(girando || saldo < ficha) return;
            saldo -= ficha;
            saldoTxt.innerText = saldo;
            guardarSaldo();
            apuestas.push({tipo, val, cant: ficha});
            
            actualizarDisplayTotal();

            const chip = document.createElement('div');
            chip.className = `chip-on-board v-${ficha}`;
            const rx = (Math.random() * 20) - 10;
            const ry = (Math.random() * 20) - 10;
            chip.style.transform = `translate(${rx}px, ${ry}px)`;
            
            el.appendChild(chip);
            msg.innerText = "Apuesta realizada";
        }

        function borrar() {
            if(girando) return;
            apuestas.forEach(a => saldo += a.cant);
            apuestas = [];
            saldoTxt.innerText = saldo;
            guardarSaldo();
            actualizarDisplayTotal();
            document.querySelectorAll('.chip-on-board').forEach(e => e.remove());
            msg.innerText = "Mesa limpia";
        }

        function deshacer() {
            if(girando || !apuestas.length) return;
            const ult = apuestas.pop();
            saldo += ult.cant;
            saldoTxt.innerText = saldo;
            guardarSaldo();
            actualizarDisplayTotal();
            const chips = document.querySelectorAll('.chip-on-board');
            if(chips.length) chips[chips.length-1].remove();
        }

        document.getElementById('btnGirar').onclick = () => {
            if(girando) return;
            if(!apuestas.length) { alert("¡Apuesta algo!"); return; }

            girando = true;
            msg.innerText = "Girando...";
            
            const idx = Math.floor(Math.random() * nums.length);
            const numGanador = nums[idx];
            
            const angGajo = 360 / nums.length;
            const target = 360 - (idx * angGajo);
            const current = window.rotGlobal % 360;
            let delta = target - current;
            if(delta < 0) delta += 360;
            const rand = (Math.random() * 4) - 2; 

            const rotFinal = window.rotGlobal + 1800 + delta + rand;
            window.rotGlobal = rotFinal;

            ruleta.style.transform = `rotate(${rotFinal}deg)`;

            setTimeout(() => {
                girando = false;
                verificar(numGanador);
            }, 4000);
        };

                function verificar(n) {
            let ganancia = 0;
            let totalApuesta = apuestas.reduce((a,b)=>a+b.cant, 0);

            const esRojo = rojos.includes(n);
            const esNegro = !esRojo && n!==0;
            const esPar   = n!==0 && n%2===0;
            const esImpar = n!==0 && n%2!==0;
            const col1 = [1,4,7,10,13,16,19,22,25,28,31,34];
            const col2 = [2,5,8,11,14,17,20,23,26,29,32,35];
            const col3 = [3,6,9,12,15,18,21,24,27,30,33,36];

            apuestas.forEach(ap => {
                let gano = false;
                let m = 0;

                if (ap.tipo === 'pleno'   && ap.val === n)                        { gano = true; m = 35; }
                if (ap.tipo === 'color'   && ((ap.val === 'rojo'  && esRojo) ||
                                             (ap.val === 'negro' && esNegro)))   { gano = true; m = 1; }
                if (ap.tipo === 'paridad' && ((ap.val === 'par'   && esPar) ||
                                             (ap.val === 'impar' && esImpar)))   { gano = true; m = 1; }
                if (ap.tipo === 'mitad'   && ((ap.val === 'bajo' && n>=1  && n<=18) ||
                                             (ap.val === 'alto' && n>=19)))       { gano = true; m = 1; }
                if (ap.tipo === 'columna') {
                    if ((ap.val === 1 && col1.includes(n)) ||
                        (ap.val === 2 && col2.includes(n)) ||
                        (ap.val === 3 && col3.includes(n))) { gano = true; m = 2; }
                }

                if (gano) ganancia += ap.cant * (m+1);
            });

            // Actualizar saldo en el front
            saldo += ganancia;
            saldoTxt.innerText = saldo;
            guardarSaldo();
            document.getElementById('resultado-num').innerText = n;

            const neto = ganancia - totalApuesta; // puede ser +, -, o 0

            // 👉 Registrar el movimiento en el backend si hubo cambio real
            if (neto !== 0) {
                fetch('registrarMovimientoJuego.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        monto: neto,       // positivo si ganancia neta, negativo si pérdida
                        partida_id: null   // si más adelante usas id de partida, lo mandas aquí
                    })
                })
                .then(r => r.json())
                .then(data => {
                    console.log('Respuesta registrarMovimientoJuego:', data);
                    if (data.ok && typeof data.nuevo_saldo === 'number') {
                        // Ajustar saldo al real calculado en PHP (API + movimientos)
                        saldo = data.nuevo_saldo;
                        saldoTxt.innerText = saldo;
                    }
                })
                .catch(err => {
                    console.error('Error al registrar movimiento de juego:', err);
                });
            }

            // Mensaje al jugador
            if (neto > 0)      msg.innerText = `¡GANASTE $${neto}! Salió el ${n}`;
            else if (neto < 0) msg.innerText = `Salió el ${n}. Perdiste.`;
            else               msg.innerText = `Salió el ${n}. Empate.`;

            // Limpiar fichas del tablero
            document.querySelectorAll('.chip-on-board').forEach(e => e.remove());
            apuestas = [];
            actualizarDisplayTotal(); 
        }


        cargarSaldoInicial();
        init();
    </script>
</body>
</html>
