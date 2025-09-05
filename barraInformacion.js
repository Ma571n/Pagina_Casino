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
            <a class="nav-link" href="DatosPersonales.html" data-path="DatosPersonales.html">Datos personales</a>
            <a class="nav-link" href="Contacto.html" data-path="Contacto.html">Contacto</a>
            <a class="nav-link" href="Seguridad.html" data-path="Seguridad.html">Seguridad</a>
            <a class="nav-link" href="Billetera.html" data-path="Billetera.html">Billetera virtual</a>
          </div>
        </div>
      </div>
    </nav>
  `;

  // Detectar página actual
  const current = location.pathname.split('/').pop();

  // Grupos de páginas que corresponden a una sola pestaña
  const billeteraPages = ["Billetera.html", "CargarDinero.html", "RetirarDinero.html"];

  elemento.querySelectorAll('.nav-link').forEach(a => {
    const target = a.getAttribute('data-path');

    // Si la pestaña es Billetera virtual y la página actual está en ese grupo
    if (target === "Billetera.html" && billeteraPages.includes(current)) {
      a.classList.add('active');
    }
    // Para las demás pestañas, comparación directa
    else if (target === current) {
      a.classList.add('active');
    }
  });
}
