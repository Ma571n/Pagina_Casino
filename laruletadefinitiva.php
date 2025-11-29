<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino K - Aesthetic Final v2</title>
    <style>
        /* --- ESTÉTICA FLAT / AESTHETIC --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #5D478B; /* Morado K */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            user-select: none;
        }

        /* --- MARCO BLANCO ESTILO "POLAROID" --- */
        .page-frame {
            background-color: rgba(0, 0, 0, 0.2); 
            border: 15px solid white; /* El borde blanco */
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

        /* --- IZQUIERDA: RULETA --- */
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

        /* Contenedor de cada número (gajo + texto) */
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

        /* --- DERECHA: DASHBOARD Y TABLERO --- */
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
            padding-bottom: 10px;
        }
        .logo { font-size: 28px; font-weight: bold; letter-spacing: 2px; }
        
        .dashboard {
            display: flex; gap: 20px; align-items: center;
            background: rgba(255,255,255,0.1);
            padding: 10px 20px; border-radius: 12px;
        }
        .saldo { font-size: 20px; font-weight: bold; min-width: 120px; }
        
        .fichas-list { display: flex; gap: 10px; }
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

        /* --- TABLERO --- */
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
        
        /* AQUÍ ESTÁ EL CAMBIO: AMARILLO CON TEXTO BLANCO */
        .bg-yellow { background-color: #FFB300; color: white; } 
        
        .cero { grid-row: 1 / span 3; grid-column: 1; font-size: 22px; }

        /* Filas de controles */
        .grid-bottom { display: grid; gap: 4px; margin-top: 4px; }
        
        /* Ajuste: Fila principal de controles */
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

        /* Ficha puesta en el tablero */
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

    </style>
</head>
<body>

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
                        <span class="saldo">Saldo: $<span id="saldo">1000</span></span>
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

    <script>
        // --- CONFIGURACIÓN ---
        let saldo = 1000;
        let ficha = 10;
        let apuestas = [];
        let girando = false;
        window.rotGlobal = 0;

        const nums = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];
        const rojos = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];

        const ruleta = document.getElementById('ruleta');
        const gridNums = document.getElementById('grid-nums');
        const saldoTxt = document.getElementById('saldo');
        const msg = document.getElementById('mensaje');

        // --- INICIO ---
        function init() {
            // 1. Dibujar Ruleta (CON NÚMEROS)
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

            // 2. Dibujar Tablero (Números 1-36)
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

            // 3. Botones de Columnas
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

        // --- LÓGICA DE JUEGO ---
        function selFicha(v, el) {
            ficha = v;
            document.querySelectorAll('.ficha').forEach(x => x.classList.remove('activa'));
            el.classList.add('activa');
        }

        function apostar(tipo, val, el) {
            if(girando || saldo < ficha) return;
            saldo -= ficha;
            saldoTxt.innerText = saldo;
            apuestas.push({tipo, val, cant: ficha});

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
            document.querySelectorAll('.chip-on-board').forEach(e => e.remove());
            msg.innerText = "Mesa limpia";
        }

        function deshacer() {
            if(girando || !apuestas.length) return;
            const ult = apuestas.pop();
            saldo += ult.cant;
            saldoTxt.innerText = saldo;
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
            const esPar = n!==0 && n%2===0;
            const esImpar = n!==0 && n%2!==0;
            const col1 = [1,4,7,10,13,16,19,22,25,28,31,34];
            const col2 = [2,5,8,11,14,17,20,23,26,29,32,35];
            const col3 = [3,6,9,12,15,18,21,24,27,30,33,36];

            apuestas.forEach(ap => {
                let gano = false;
                let m = 0;

                if(ap.tipo==='pleno' && ap.val===n) { gano=true; m=35; }
                if(ap.tipo==='color' && ((ap.val==='rojo' && esRojo) || (ap.val==='negro' && esNegro))) { gano=true; m=1; }
                if(ap.tipo==='paridad' && ((ap.val==='par' && esPar) || (ap.val==='impar' && esImpar))) { gano=true; m=1; }
                if(ap.tipo==='mitad' && ((ap.val==='bajo' && n>=1 && n<=18) || (ap.val==='alto' && n>=19))) { gano=true; m=1; }
                if(ap.tipo==='columna') {
                    if((ap.val===1 && col1.includes(n)) || (ap.val===2 && col2.includes(n)) || (ap.val===3 && col3.includes(n))) { gano=true; m=2; }
                }

                if(gano) ganancia += ap.cant * (m+1);
            });

            saldo += ganancia;
            saldoTxt.innerText = saldo;
            document.getElementById('resultado-num').innerText = n;

            const neto = ganancia - totalApuesta;
            if(neto > 0) msg.innerText = `¡GANASTE $${neto}! Salió el ${n}`;
            else if (neto < 0) msg.innerText = `Salió el ${n}. Perdiste.`;
            else msg.innerText = `Salió el ${n}. Empate.`;

            document.querySelectorAll('.chip-on-board').forEach(e => e.remove());
            apuestas = [];
        }

        init();
    </script>
</body>
</html>