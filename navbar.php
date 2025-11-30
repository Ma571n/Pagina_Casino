<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogueado = isset($_SESSION['usuario']);
?>

<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="https://i.pinimg.com/736x/40/9c/2f/409c2f2f42c7c3589b5e5cddedba30c2.jpg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            El casino de los K
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($usuarioLogueado): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="juegos.php">Juegos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="DatosPersonales.php">datos personales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="Billetera.php">billetera</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if ($usuarioLogueado): ?>
                    <!-- USUARIO LOGUEADO -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="DatosPersonales.php"><i class="fas fa-user-circle"></i> datos personales</a></li>
                            <li><a class="dropdown-item" href="Billetera.php"><i class="fas fa-history"></i> billetera</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="login.php?action=logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- USUARIO NO LOGUEADO -->
                    <button type="button" class="btn btn-iniciar-sesion me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Iniciar Sesión
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
                        Registrarse
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if (!$usuarioLogueado): ?>
<!-- MODALES SOLO PARA USUARIOS NO LOGUEADOS -->

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalLabel">Iniciar sesión</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <img src="https://preview.redd.it/random-pictures-of-tyler-the-creator-i-found-on-tiktok-v0-k75n1wuuihlb1.jpg?width=600&format=pjpg&auto=webp&s=d53a0268773fa3036f3e36b0eb81d3157406448e" alt="Imagen login" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <form id="loginForm">
                            <div id="loginMessage"></div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@example.com" required>
                                <label for="loginEmail">Correo Electrónico</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Contraseña" required>
                                <label for="loginPassword">Contraseña</label>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                                <button type="button" class="btn btn-link p-0 text-start" style="text-decoration: none;" onclick="mostrarRegistro()">
                                    ¿No posees una cuenta? Regístrate aquí
                                </button>
                                <button type="button" class="btn btn-link p-0 text-start" style="text-decoration: none;">
                                    ¿Olvidó su contraseña? Recuperar contraseña
                                </button>
                            </div>
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="registroModalLabel">Registrarse</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="registroForm" action="../BackEnd/api/v1/registro.php" method="POST">
                <input type="hidden" name="action" value="registrar">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="regUsername" name="username" placeholder="Usuario" required>
                    <label for="regUsername">Usuario</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="regFecha" name="fecha_nacimiento" required>
                    <label for="regFecha">Fecha de nacimiento</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="regPassword" name="password" placeholder="Contraseña" required>
                    <label for="regPassword">Contraseña</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="regConfirmPassword" name="confirmPassword" placeholder="Confirmar Contraseña" required>
                    <label for="regConfirmPassword">Confirmar Contraseña</label>
                </div>
                <button type="submit" class="btn btn-primary">Crear Cuenta</button>
            </form>
        </div>
    </div>
</div>

<script>
function mostrarRegistro() {
    var loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
    var registroModal = new bootstrap.Modal(document.getElementById('registroModal'));
    
    loginModal.hide();
    registroModal.show();
}

// Manejar login con AJAX
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'login');
    
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('loginMessage');
        if (data.success) {
            messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loginMessage').innerHTML = '<div class="alert alert-danger">Error al procesar la solicitud</div>';
    });
});
</script>
<?php endif; ?>