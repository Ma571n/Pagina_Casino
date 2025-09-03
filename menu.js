getMenu('menuPrincipal')
    function getMenu(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="index.html">
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
                        Iniciar Sesión
                    </button>
                            <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Resgistro de Usuario</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!--<form action="accioncrearcuenta.html" method="POST">-->
                            <div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" placeholder="name@example.com">
                                        <label for="floatingInput">Correo Electronico</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password" placeholder="Password">
                                        <label for="floatingPassword">Contraseña</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" onclick="validarLogin(this)">Crear Cuenta</button>
                            </div>
                            </div>
                            </div>
                            <!--</form>-->
                        </div>
                        </div>

                                    <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            registrarse
            </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Resgistro de Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!--<form action="accioncrearcuenta.html" method="POST">-->
                    <div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                 <div class="form-floating mb-3">
                                <div class="form-floating">
                                <input type="username" class="form-control" id="username" placeholder="username">
                                <label for="floatingPassword">Nombre de Usuario</label>
                                </div>
                                <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" placeholder="name@example.com">
                                <label for="floatingInput">Correo Electronico</label>
                                </div>
                                <div class="form-floating">
                                <input type="password" class="form-control" id="password" placeholder="Password">
                                <label for="floatingPassword">Contraseña</label>
                                </div>
                                <div class="form-floating">
                                <input type="password" class="form-control" id="password" placeholder="Password2">
                                <label for="floatingPassword">Repita Contraseña</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" onclick="validarLogin(this)">Crear Cuenta</button>
                    </div>
                    </div>
                    </div>
                    <!--</form>-->
                </div>
                </div>       
                    </button>
                </div>
            </div>
        </div>
    </nav>`
}