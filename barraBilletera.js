// ⚠️ CONFIGURACIÓN: Reemplaza estos valores con tus datos reales
const API_ENDPOINT_SALDO = 'https://udp.coningenio.cl/2025-2/losk/api/v1/billetera/'; // Tu URL real
const BEARER_TOKEN_KEY = 'udp.2025'; // Tu token real

/**
 * Realiza una solicitud GET con autenticación Bearer Token.
 * @param {string} endpoint - La URL del recurso.
 * @param {string} bearerToken - La clave del token Bearer.
 * @returns {Promise<any>} Una promesa con los datos JSON.
 */
async function fetchDataWithBearerToken(endpoint, bearerToken) {
    const options = {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${bearerToken}`,
            'Content-Type': 'application/json'
        }
    };

    const response = await fetch(endpoint, options);
    if (!response.ok) {
        throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    return response.json();
}

// --------------------------------------------------

function getWalletMenu(_id) {
    const elemento = document.getElementById(_id);
    
    // **1. Inyectar el HTML del menú con el marcador de posición para el saldo**
    elemento.innerHTML = `
        <nav class="navbar navbar-expand-lg bg-body-tertiary rounded-3 shadow mx-3 my-3 px-3">
          <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarWallet" aria-controls="navbarWallet" aria-expanded="false"
                aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarWallet">
              <div class="navbar-nav nav-links-custom">
                <a class="nav-link" href="Billetera.html" data-path="Billetera.html">Histórico de transacciones</a>
                <a class="nav-link" href="CargarDinero.html" data-path="CargarDinero.html">Cargar dinero</a>
                <a class="nav-link" href="RetirarDinero.html" data-path="RetirarDinero.html">Retirar dinero</a>
              </div>
              
              <span id="saldo-actual-display" class="ms-auto fw-bold">Cargando saldo...</span> 
              
            </div>
          </div>
        </nav>
    `;

    // **2. Marcar el enlace activo (Lógica que ya tenías)**
    const current = location.pathname.split('/').pop() || 'Billetera.html';
    elemento.querySelectorAll('.nav-link').forEach(a => {
        const target = a.getAttribute('data-path');
        if (target === current) {
            a.classList.add('active');
        }
    });

    // **3. Obtener el saldo real y actualizar el display**
    fetchSaldoYActualizarDisplay();
}

/**
 * Obtiene el saldo del endpoint y actualiza el elemento HTML.
 */
async function fetchSaldoYActualizarDisplay() {
    const displayElement = document.getElementById('saldo-actual-display');
    if (!displayElement) return;

    try {
        const data = await fetchDataWithBearerToken(API_ENDPOINT_SALDO, BEARER_TOKEN_KEY);
        
        // 🔑 CLAVE: Acceder al primer elemento del array y luego a la propiedad "saldo"
        // Tu respuesta es un array: [ { ..., "saldo": 10000, ... } ]
        // Por lo tanto, usamos data[0].saldo
        const saldo = data[0].saldo; 

        if (typeof saldo === 'number') {
            // Formatear el saldo con separador de miles y prefijo de moneda
            const saldoFormateado = `$${saldo.toLocaleString('es-CL')}`; // Cambia 'es-CL' si necesitas otro formato
            displayElement.textContent = `Saldo actual ${saldoFormateado}`;
        } else {
            throw new Error("El formato de saldo recibido no es un número.");
        }
        
    } catch (error) {
        console.error("No se pudo obtener el saldo:", error);
        displayElement.textContent = 'Saldo no disponible';
        displayElement.classList.add('text-danger');
    }
}

// --------------------------------------------------

// Llamada inicial para cargar el menú y el saldo
getWalletMenu('barraBilletera');