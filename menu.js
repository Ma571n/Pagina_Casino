getMenu('menuPrincipal')
    function getMenu(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
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
                    <button type="button" class="btn btn-iniciar-sesion" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Iniciar Sesión
                    </button>
                    <!-- Modal -->
                       <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Iniciar sesión</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
      <div class="modal-body">
        <div class="row">
          <!-- Imagen -->
          <div class="col-md-6 d-flex align-items-center justify-content-center">
            <img src="https://i.pinimg.com/736x/d2/d7/60/d2d760ac5984ab443b8fe97a70cf54de.jpg" alt="Imagen de ejemplo" class="img-fluid rounded">
          </div>

          <!-- Formulario -->
          <div class="col-md-6">
            <form>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                <label for="email">Correo Electrónico</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                <label for="password">Contraseña</label>
              </div>

              <!-- Opciones Formulario -->
              <div class="d-grid gap-2 mb-3">
              <button type="submit" class="btn btn-primary" onclick="validarLogin(event)">
                  Iniciar Sesión
                </button>  
              <button type="button" class="btn btn-link p-0 text-start" style="text-decoration: none;" onclick="alert('Redirigir a registro')">
                  ¿No posees una cuenta? Regístrate aquí
                </button>
                <button type="button" class="btn btn-link p-0 text-start" style="text-decoration: none;" onclick="alert('Redirigir a recuperar contraseña')">
                  ¿Olvidó su contraseña? Recuperar contraseña
                </button>
              </div>

              <!-- Botón cerrar alineado a la derecha -->
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
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                            Registrarse
                    </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">registrarse</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index" method="POST">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                 <div class="form-floating mb-3">
                                <div class="form-floating mb-3">
                                <input type="username" class="form-control" id="username" placeholder="username">
                                <label for="floatingPassword">Nombre de Usuario</label>
                                </div>
                                <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" placeholder="name@example.com">
                                <label for="floatingInput">Correo Electronico</label>
                                </div>
                                <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" placeholder="Password">
                                <label for="floatingPassword">Contraseña</label>
                                </div>
                                <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password2" placeholder="Password">
                                <label for="floatingPassword">Repetir Contraseña</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" onclick="validarLogin(this)">Crear Cuenta</button>
                    </div>
                    </div>
                    </form>
                </div>
                </div>       
                   
                </div>
            </div>
        </div>
    </nav>`
}