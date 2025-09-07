
function cerrarModalYLuego(modalId, callback) {
  const el = document.getElementById(modalId);

  // Si el elemento ya no existe (porque se reemplazó el header), limpia y sigue
  if (!el) {
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('paddingRight');
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    if (typeof callback === 'function') callback();
    return;
  }

  // Obtiene o crea la instancia de Bootstrap.Modal
  let modal = bootstrap.Modal.getInstance(el);
  if (!modal) modal = new bootstrap.Modal(el);

  // Al terminar de ocultarse, ejecuta el callback UNA sola vez
  function onHidden() {
    el.removeEventListener('hidden.bs.modal', onHidden);
    if (typeof callback === 'function') callback();
  }
  el.addEventListener('hidden.bs.modal', onHidden);

  // Ocultar modal 
  modal.hide();
}




function validarRegistro(event) {
  event.preventDefault();

  // Simulación de registro
  const username = document.getElementById('username')?.value || '';
  const password = document.getElementById('passwordRegistro')?.value || '';
  const password2 = document.getElementById('password2')?.value || '';

  if (password !== password2) {
    alert('Las contraseñas no coinciden');
    return false;
  }

  // Guardamos sesión simulada
  localStorage.setItem('usuarioLogueado', 'true');
  localStorage.setItem('nombreUsuario', username);

  
  cerrarModalYLuego('exampleModal2', function () {
    if (typeof getMenuLogged === 'function') {
      getMenuLogged('menuPrincipal', username);
    }
    if (typeof getFooterLogged === 'function') {
      getFooterLogged('menuFooter', username);
    }
  });

  return false;
}

/**
 * Valida el LOGIN simulado.
 * Cierra el modal primero -> luego actualiza menús con el usuario.
 * (Tu versión original hacía getMenuLogged y DESPUÉS intentaba cerrar el modal) :contentReference[oaicite:2]{index=2}
 */
function validarLogin(event) {
  event.preventDefault();

  // Simulación de login
  const email = document.getElementById('email')?.value || '';
  const nombreUsuario = email.includes('@') ? email.split('@')[0] : email || 'usuario';

  // Guardamos sesión simulada
  localStorage.setItem('usuarioLogueado', 'true');
  localStorage.setItem('nombreUsuario', nombreUsuario);

  // 1) Cierra el modal de LOGIN
  // 2) Al cerrarse, actualiza navbar y footer con versión "logueada"
  cerrarModalYLuego('exampleModal', function () {
    if (typeof getMenuLogged === 'function') {
      getMenuLogged('menuPrincipal', nombreUsuario);
    }
    if (typeof getFooterLogged === 'function') {
      getFooterLogged('menuFooter', nombreUsuario);
    }
  });

  return false;
}

/**
 * Verifica el estado de autenticación al cargar cada página
 * y pinta el navbar/footer correspondiente.
 * (Igual que tu versión actual, pero mantenemos defensas por si alguna función no está) :contentReference[oaicite:3]{index=3}
 */
function verificarAutenticacion() {
  const usuarioLogueado = localStorage.getItem('usuarioLogueado');
  const nombreUsuario = localStorage.getItem('nombreUsuario');

  if (usuarioLogueado === 'true' && nombreUsuario) {
    // Usuario logueado -> navbar y footer con nombre
    if (typeof getMenuLogged === 'function') {
      getMenuLogged('menuPrincipal', nombreUsuario);
    }
    if (typeof getFooterLogged === 'function') {
      getFooterLogged('menuFooter', nombreUsuario);
    }
  } else {
    // Usuario NO logueado -> navbar y footer "públicos"
    if (typeof getMenu === 'function') {
      getMenu('menuPrincipal');
    }
    if (typeof getFooter === 'function') {
      getFooter('menuFooter');
    }
  }
}

// Ejecutar verificación al cargar el DOM
document.addEventListener('DOMContentLoaded', function () {
  verificarAutenticacion();
});
