<?php
header('Content-Type: application/javascript; charset=UTF-8');
?>

getMenuBilletera('barraBilletera');

function getMenuBilletera(_id) {
  const elemento = document.getElementById(_id);
  if (!elemento) return;

  // Leer saldo sincronizado desde localStorage
  const SALDO_KEY = 'saldoActual';
  const guardado = localStorage.getItem(SALDO_KEY);
  let saldoTexto = '--';

  if (guardado !== null && !isNaN(parseInt(guardado, 10))) {
    saldoTexto = parseInt(guardado, 10).toLocaleString('es-CL');
  }

  elemento.innerHTML = `
    <nav class="navbar navbar-expand-lg bg-body-tertiary rounded-3 shadow mx-3 my-3 px-3">
      <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarBilleteraNav" aria-controls="navbarBilleteraNav"
            aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarBilleteraNav">

          <!-- LINKS DE LA BARRA DE BILLETERA -->
          <div class="navbar-nav nav-links-custom me-auto">
            <a class="nav-link" href="CargarDinero.php" data-path="CargarDinero.php">
              Cargar monto
            </a>
            <a class="nav-link" href="RetirarDinero.php" data-path="RetirarDinero.php">
              Retirar monto
            </a>
            <a class="nav-link" href="Billetera.php" data-path="Billetera.php">
              Histórico
            </a>
          </div>

          <!-- SALDO A LA DERECHA -->
          <div class="d-flex align-items-center ms-auto">
            <small class="text-muted me-2 mb-0">Saldo disponible</small>
            <strong>$ <span id="saldoBarraBilletera">${saldoTexto}</span></strong>
          </div>

        </div>
      </div>
    </nav>
  `;

  // Activar la pestaña correspondiente según la página actual
  const current = location.pathname.split('/').pop();

  elemento.querySelectorAll('.nav-link').forEach(a => {
    const target = a.getAttribute('data-path');
    if (target === current) {
      a.classList.add('active');
    }
  });
}
