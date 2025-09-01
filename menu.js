getMenu('menuPrincipal')
    function getMenu(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://i.pinimg.com/736x/40/9c/2f/409c2f2f42c7c3589b5e5cddedba30c2.jpg" alt="Logo" width="30"
                    height="24" class="d-inline-block align-text-top">
                El casino de los K
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"> </ul>
                <div class="d-flex">
                    <button class="btn btn-outline-success me-2" type="button">
                        <i></i>Iniciar Sesión
                    </button>
                    <button class="btn btn-outline-secondary" type="button">
                        <i></i>Registrarse
                    </button>
                </div>
            </div>
        </div>
    </nav>`
}