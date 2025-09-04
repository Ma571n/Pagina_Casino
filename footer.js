getFooter('menuFooter')
    function getFooter(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<footer class="text-center pie-pagina">
        <div class="container">
            <p class="mb-0">© 2023 MiSitioWeb. Todos los derechos reservados.</p>
            <div class="mt-2">
                <a href="#" class="text-decoration-none text-dark me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-decoration-none text-dark me-3"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-decoration-none text-dark me-3"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-decoration-none text-dark"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>`
    }

