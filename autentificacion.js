// Función para validar registro
function validarRegistro(event) {
    event.preventDefault();

    // Aquí iría tu lógica de validación de registro real
    // Por ahora simulamos un registro exitoso
    const username = document.getElementById('username').value;
    const password = document.getElementById('passwordRegistro').value;
    const password2 = document.getElementById('password2').value;

    // Validación básica de contraseñas
    if (password !== password2) {
        alert('Las contraseñas no coinciden');
        return false;
    }

    // Guardamos en localStorage que el usuario está logueado
    localStorage.setItem('usuarioLogueado', 'true');
    localStorage.setItem('nombreUsuario', username);

    // Actualizar el navbar inmediatamente
    getMenuLogged('menuPrincipal', username);

    // Cerrar el modal de registro
    const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal2'));
    modal.hide();

    return false;
}

// Función para validar login
function validarLogin(event) {
    event.preventDefault();

    // Aquí iría tu lógica de validación real
    // Por ahora simulamos un login exitoso
    const email = document.getElementById('email').value;

    // Simulamos que obtenemos el nombre del usuario (en un caso real vendría del backend)
    const nombreUsuario = email.split('@')[0];

    // Guardamos en localStorage que el usuario está logueado
    localStorage.setItem('usuarioLogueado', 'true');
    localStorage.setItem('nombreUsuario', nombreUsuario);

    // Actualizar el navbar inmediatamente
    getMenuLogged('menuPrincipal', nombreUsuario);

    // Cerrar el modal de login
    const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
    modal.hide();

    return false;
}

// Función para verificar autenticación
function verificarAutenticacion() {
    const usuarioLogueado = localStorage.getItem('usuarioLogueado');
    const nombreUsuario = localStorage.getItem('nombreUsuario');

    if (usuarioLogueado === 'true' && nombreUsuario) {
        // Si está logueado, cargar el navbar con el nombre de usuario
        getMenuLogged('menuPrincipal', nombreUsuario);
    } else {
        // Si no está logueado, cargar el navbar normal
        getMenu('menuPrincipal');
    }
}

// Al cargar la página, verificar si el usuario está autenticado
document.addEventListener('DOMContentLoaded', function() {
    verificarAutenticacion();
});