
function cerrarModalYLuego(modalId, callback) {

  const el = document.getElementById(modalId);
  if (!el) {
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('paddingRight');
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    if (typeof callback === 'function') callback();
    return;
  }

  let modal = bootstrap.Modal.getInstance(el);
  if (!modal) modal = new bootstrap.Modal(el);

  function onHidden() {
    el.removeEventListener('hidden.bs.modal', onHidden);
    if (typeof callback === 'function') callback();
  }
  el.addEventListener('hidden.bs.modal', onHidden);

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

/* Validar el LOGIN simulado. */

function validarLogin(event) {
  event.preventDefault();

  const email = document.getElementById('email')?.value || '';
  const nombreUsuario = email.includes('@') ? email.split('@')[0] : email || 'usuario';

  localStorage.setItem('usuarioLogueado', 'true');
  localStorage.setItem('nombreUsuario', nombreUsuario);

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


 /* Verifica el estado de autenticación al cargar cada página */

function verificarAutenticacion() {
  const usuarioLogueado = localStorage.getItem('usuarioLogueado');
  const nombreUsuario = localStorage.getItem('nombreUsuario');

  if (usuarioLogueado === 'true' && nombreUsuario) {
    if (typeof getMenuLogged === 'function') {
      getMenuLogged('menuPrincipal', nombreUsuario);
    }
    if (typeof getFooterLogged === 'function') {
      getFooterLogged('menuFooter', nombreUsuario);
    }
  } else {
    if (typeof getMenu === 'function') {
      getMenu('menuPrincipal');
    }
    if (typeof getFooter === 'function') {
      getFooter('menuFooter');
    }
  }
}

document.addEventListener('DOMContentLoaded', function () {
  verificarAutenticacion();
});
