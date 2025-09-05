getWalletMenu('barraBilletera');

function getWalletMenu(_id) {
    const elemento = document.getElementById(_id);
    elemento.innerHTML = `
        <nav class="navbar navbar-expand-lg bg-body-tertiary rounded-3 shadow mx-3 my-3 px-3">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarWallet" aria-controls="navbarWallet" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarWallet">
                    <div class="navbar-nav nav-links-custom">
                        <a class="nav-link" href="Billetera.html">Histórico de transacciones</a>
                        <a class="nav-link" href="Billetera-cargar.html">Cargar dinero</a>
                        <a class="nav-link" href="Billetera-retirar.html">Retirar dinero</a>
                    </div>
                </div>
                <div class="saldo-chip ms-auto">
                    Saldo actual $63.000
                </div>
            </div>
        </nav>`;
}
