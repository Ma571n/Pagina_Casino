getFooter('menuFooter')
    function getFooter(_id){
        const elemento = document.getElementById(_id);
        elemento.innerHTML = `<footer class="text-center pie-pagina">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
      <div><strong>Casino de los K</strong> · Proyecto académico — UDP</div>
      <div class="d-flex gap-3">
        <a class="link-light" href="DatosPersonales.html">Perfil</a>
        <a class="link-light" href="billetera.html">Billetera</a>
      </div>
    </div>
    </footer>`
    }

