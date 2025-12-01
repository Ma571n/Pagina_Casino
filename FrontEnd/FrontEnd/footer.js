
function getFooterLogged(_id, username) {
    const elemento = document.getElementById(_id);
    elemento.innerHTML = `
        <footer class="text-center pie-pagina">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div><strong>Casino de los K</strong> · Bienvenido, ${username}</div>
                <div class="d-flex gap-3">
                    <a class="link-light" href="DatosPersonales.php">Perfil</a>
                    <a class="link-light" href="Billetera.php">Billetera</a>
                </div>
            </div>
        </footer>
    `;
}
function getFooter(_id) {
    const elemento = document.getElementById(_id);
    elemento.innerHTML = `
        <footer class="text-center pie-pagina">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div><strong>Casino de los K</strong> · Proyecto académico - UDP</div>
                <div class="d-flex gap-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Iniciar Sesión</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal2">Registrarse</a>
                </div>
            </div>
        </footer>
    `;
}