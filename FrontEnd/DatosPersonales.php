<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil | Usuario</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --nav-h: 44px;
            --page-bg: #ffffff;
            --panel-bg: #f4f5f8;
        }

        
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--page-bg);
            color: #1f1f1f;
        }

        
        main {
            flex: 1;
            padding-top: calc(var(--nav-h) + 8px);
            
        }

        
        .custom-navbar {
            background: #4c2ca3;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 600;
            color: #ffffff;
        }

       
        .full-bleed {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        
        .card-perfil {
            max-width: 1890px;
            margin: 16px auto 0;
            background: #ffffff;
            border: 2px solid #b388ff;
            border-radius: 22px;
            padding: 38px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
        }

        
        .form-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

       
        .form-label {
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }

        .input-rounded {
            background: #eef1f3;
            border: 1px solid #e0e3e7;
            border-radius: 18px;
            padding: 14px 16px;
            height: 48px;
        }

        .input-rounded::placeholder {
            color: #9aa0a6;
        }

        
        .input-group .input-group-text {
            background: #eef1f3;
            border: 1px solid #e0e3e7;
            border-left: none;
            border-radius: 0 18px 18px 0;
        }

        .input-group .input-rounded {
            border-right: none;
            border-radius: 18px 0 0 18px;
        }

        
        .btn-guardar {
            background: #4c7bf7;
            border: none;
            font-weight: 800;
            padding: 12px 22px;
            border-radius: 14px;
        }

        .btn-guardar:hover {
            filter: brightness(.95);
        }

        
        .pie-pagina {
            background-color: #4c2ca3;
            color: #dcd6ff;
            padding: 28px 0 110px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin-top: 3rem;
        }

        
        #barraInfo .nav-link.active {
            background-color: #5534A4 !important;
            color: #fff !important;
            border-color: #5534A4 !important;
            border-radius: 14px;
        }
    </style>
</head>

<body>
    <!-- Navbar (fixed-top desde menu.js). La clase custom-navbar recibe la sombra -->
    <header id="menuPrincipal"></header>

    <main>
        <!-- Tabs a todo el ancho, pegadas bajo la navbar -->
        <section class="tabs-wrap full-bleed">
            <section id="barraInfo" class="barra-principal"></section>
        </section>

        <!-- Tarjeta con formulario en 2 columnas -->
        <section class="card-perfil">
            <form id="formPerfil">
                <div class="form-grid">
                    <!-- Columna izquierda -->
                    <div>
                        <label class="form-label" for="username">Username (RUT)</label>
                        <input id="username" name="username" type="text" class="form-control input-rounded"
                            placeholder="11.111.111-1" value="11.111.111-1">

                        <label class="form-label mt-3" for="fecha">Fecha de Nacimiento</label>
                        <div class="input-group">
                            <input id="fecha" name="fecha" type="date" class="form-control input-rounded"
                                placeholder="01-01-2002" value="2002-01-01">
                            </span>
                        </div>

                    </div>

                    <!-- Columna derecha -->
                    <div>
                        <label class="form-label" for="nombre">Nombre y Apellido</label>
                        <input id="nombre" name="nombre" type="text" class="form-control input-rounded"
                            placeholder="Nombre Apellido" value="Nombre Apellido">

                        <label class="form-label mt-3" for="correo">Correo Electrónico</label>
                        <input id="correo" name="correo" type="email" class="form-control input-rounded"
                            placeholder="correo@mail.udp.cl" value="correo@mail.udp.cl">
                    </div>
                </div>

                <div class="mt-4">
                    <button id="guardarPerfil" type="submit" class="btn btn-guardar">Guardar cambios</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Contenedor del footer (div para evitar <footer> anidado) -->
    <div id="menuFooter"></div>

    <!-- Scripts -->
    <script src="navbarconlogin.js"></script>
    <script src="footer.js"></script>
    <script src="barraInformacion.js"></script>
    <script src="autentificacion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>