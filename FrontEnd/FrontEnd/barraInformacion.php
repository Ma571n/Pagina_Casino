getMenu('barraInfo');
function getMenu(_id) {
  const elemento = document.getElementById(_id);
  elemento.innerHTML = `
    <nav class="navbar navbar-expand-lg bg-body-tertiary rounded-3 shadow mx-3 my-3 px-3">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
            aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav nav-links-custom">
            <a class="nav-link" href="DatosPersonales.php" data-path="DatosPersonales.php">Datos personales</a>
            <a class="nav-link" href="Contacto.php" data-path="Contacto.php">Contacto</a>
            <a class="nav-link" href="Seguridad.php" data-path="Seguridad.php">Seguridad</a>
            <a class="nav-link" href="Billetera.php" data-path="Billetera.php">Billetera virtual</a>
          </div>
        </div>
      </div>
    </nav>
  `;

  const current = location.pathname.split('/').pop();

  const billeteraPages = ["Billetera.php", "CargarDinero.php", "RetirarDinero.php"];

  elemento.querySelectorAll('.nav-link').forEach(a => {
    const target = a.getAttribute('data-path');

    if (target === "Billetera.php" && billeteraPages.includes(current)) {
      a.classList.add('active');
    }
    else if (target === current) {
      a.classList.add('active');
    }
  });
}
