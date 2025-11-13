<?php
include_once('api/consumoEndpoint.php');
// --- Example usage of the function ---
$endpoint = 'https://udp.coningenio.cl/casino/api/v1/persona/?id=1';
$token = 'udp.2025';

$personas = getApiData($endpoint, $token);

if ($personas !== null) {
    echo '<div class="row">';
    foreach ($proyectos as $proyecto) {
        if($proyecto['activo']){
            $tarjeta = "
                <div class='col-4 g-4'>
                    <div class='card border-danger'>
                        <div class='card-header text-center bg-danger text-white'>
                            Proyecto Semestral
                        </div>
                        <div class='card-body'>
                            <h5 class='card-title text-center'>" . htmlspecialchars($proyecto['nombre']) . "</h5>
                            <hr>
                            " . htmlspecialchars($proyecto['descripcion']) . "
                            <hr>
                            <strong>Integrantes</strong>
                            " . htmlspecialchars($proyecto['integrantes']) . "
                        </div>
                        <div class='card-footer'>
                            <a href='".htmlspecialchars($proyecto['url'])."' target='_blank'><button class='btn btn-dark w-100'><i class='fa fa-link'></i> Acceso</button></a>
                        </div>
                    </div>
                </div>
            ";
            echo $tarjeta;
        }
    }
    echo '</div>';
} else {
    echo "Error: No se pudo obtener la información. Por favor, revisa la URL y el token.";
}

?>

function getMenuLogged(_id, userName) {
    const elemento = document.getElementById(_id);
    elemento.innerHTML = `<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://i.pinimg.com/736x/40/9c/2f/409c2f2f42c7c3589b5e5cddedba30c2.jpg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                El casino de los K
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
                <div class="d-flex align-items-center">
                    <!-- Icono de usuario con menú desplegable -->
                    <div class="dropdown">
                        <button class="btn btn-link text-light text-decoration-none dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                            ${userName}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="DatosPersonales.php"><i class="bi bi-person me-2"></i>Perfil de Usuario</a></li>
                            <li><a class="dropdown-item" href="Billetera.php"><i class="bi bi-wallet2 me-2"></i>Billetera</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="cerrarSesion()"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>`;
}

// Función para cerrar sesión
function cerrarSesion() {
    localStorage.removeItem('usuarioLogueado');
    localStorage.removeItem('nombreUsuario');
    // Actualizar el navbar a la versión sin login
    getMenu('menuPrincipal');
    getFooter('menuFooter');
    // Recargar la página para asegurar que todos los componentes se actualicen
    window.location.href = 'index.html';
}