function validarLogin(_btn) {
    _btn.classList.add('disabled');
    _btn.innerText = 'Validando...';
    const msg = document.getElementById('msgAlerta');
    const username = document.getElementById('email');
    const password = document.getElementById('password');
    // alert('Validando Login');
    let errores = 0;
    if (username.value == '') {
        username.classList.add('is-invalid');
        errores++;
    } else {
        //console.log(username.value);
        username.classList.remove('is-invalid');
        username.classList.add('is-valid');
    }

    if (password.value == '') {
        password.classList.add('is-invalid');
        errores++;
    } else {
        // console.log(password.value);
        password.classList.remove('is-invalid');
        password.classList.add('is-valid');
    }

    if (errores > 0) {
        msg.classList.remove('d-none');
        _btn.classList.remove('disabled');
        _btn.innerText = 'Ingresar';
    }

    if (errores == 0) {
        window.location.href = "accion_login.html";
    }
}