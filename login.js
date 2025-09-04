getModalLogin();

function getModalLogin(){
    const modal = document.getElementById('modals');
    modal.innerHTML = `
    <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ingreso al Casino</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- <form action="accion_login.html" method="POST"> -->
                <div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div id="msgAlerta" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                                    <strong>Error</strong> Revise los campos marcados en contorno rojo.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email"
                                        placeholder="name@example.com">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password"
                                        placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="validarLogin(this)">Ingresar</button>
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
    `;
}