getWalletMenu('barraBilletera');

function getWalletMenu(_id) {
  const elemento = document.getElementById(_id);
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
          <span class="ms-auto fw-bold">Saldo actual $63.000</span>
        </div>
      </div>
    </nav>
  `;

  // Detectar página actual y marcar el link como activo
  const current = location.pathname.split('/').pop() || 'Billetera.html';
  elemento.querySelectorAll('.nav-link').forEach(a => {
    const target = a.getAttribute('data-path');
    if (target === current) {
      a.classList.add('active');
    }
  });
}
