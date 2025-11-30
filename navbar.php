<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogueado = isset($_SESSION['usuario']);

// Capturar error de login si existe (solo para fallback si AJAX falla)
$errorLogin = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<!-- ===================== -->
<!-- NAVBAR -->
<!-- ===================== -->
<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="https://i.pinimg.com/736x/40/9c/2f/409c2f2f42c7c3589b5e5cddedba30c2.jpg" width="30" height="24">
            El casino de los K
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($usuarioLogueado): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="juegos.php">Juegos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="DatosPersonales.php">Datos Personales</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="Billetera.php">Billetera</a></li>
                <?php endif; ?>
            </ul>

            <div class="d-flex">
                <?php if ($usuarioLogueado): ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Hola, <?= htmlspecialchars($_SESSION['usuario']['username']) ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="DatosPersonales.php">Datos Personales</a></li>
                            <li><a class="dropdown-item" href="Billetera.php">Billetera</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="FrontEnd/login.php?action=logout">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <button class="btn btn-iniciar-sesion me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Iniciar Sesión
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
                        Registrarse
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if (!$usuarioLogueado): ?>

<!-- ===================== -->
<!-- MODAL LOGIN -->
<!-- ===================== -->
<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="loginForm">
        <div class="modal-header">
          <h5 class="modal-title">Iniciar Sesión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="action" value="login">

          <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div id="loginError" class="text-danger mb-3" style="display:none;"></div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary w-100" type="submit">Entrar</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ===================== -->
<!-- MODAL REGISTRO -->
<!-- ===================== -->
<div class="modal fade" id="registroModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="../BackEnd/api/v1/registro.php">
        <div class="modal-header">
          <h5 class="modal-title">Registrarse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Fecha nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Confirmar contraseña</label>
            <input type="password" name="confirmPassword" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-success w-100" type="submit">Crear Cuenta</button>
        </div>
      </form>

    </div>
  </div>
</div>

<?php endif; ?>

<!-- ===================== -->
<!-- BOOTSTRAP JS -->
<!-- ===================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===================== -->
<!-- LOGIN AJAX -->
<!-- ===================== -->
<script>
document.getElementById("loginForm")?.addEventListener("submit", function(e){
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const errorDiv = document.getElementById("loginError");

    fetch("/2025-2/losk/FrontEnd/login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            location.reload(); // recarga index.php y muestra navbar logueado
        } else {
            errorDiv.style.display = "block";
            errorDiv.textContent = data.error;
        }
    })
    .catch(err => {
        errorDiv.style.display = "block";
        errorDiv.textContent = "Error de conexión. Intenta nuevamente.";
    });
});
</script>

