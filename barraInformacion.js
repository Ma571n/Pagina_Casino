getMenu('barraInfo')
    function getMenu(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<nav class="navbar navbar-expand-lg bg-body-tertiary rounded-3 shadow mx-3 my-3 px-3">
                <div class="container-fluid"> <!-- Para usar todo el ancho disponible -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                        aria-label="Toggle navigation"> <!-- Boton para sacar el menu (si se encoje la pantalla) -->
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <!-- La parte que se despliega del menu -->
                        <div class="navbar-nav nav-links-custom"> <!-- Ajusta los links en la barra -->
                            <a class="nav-link active" aria-current="page" href="DatosPersonales.html">Datos personales</a>
                            <a class="nav-link" href="Contacto.html">Contacto</a>
                            <a class="nav-link" href="Seguridad.html">Seguridad</a>
                            <a class="nav-link" href="Billetera.html">Billetera virtual</a>
                        </div>
                    </div>
                </div>
            </nav>`
}