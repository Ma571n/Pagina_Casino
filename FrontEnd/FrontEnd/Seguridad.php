<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perfil | Seguridad</title>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

  <style>
    :root{
      --nav-h: 44px;
      --page-bg: #ffffff;
      --panel-bg: #f4f5f8;
    }

    html, body { height: 100%; margin: 0; }
    body {
      display: flex; flex-direction: column; min-height: 100vh;
      background: var(--page-bg);
      color: #1f1f1f;
    }

    main { flex: 1; padding-top: calc(var(--nav-h) + 8px); }

    .custom-navbar { background:#4c2ca3; box-shadow:0 2px 10px rgba(0,0,0,.15); }
    .navbar-brand { font-weight:600; color:#fff; }

    .card-perfil{
      max-width: 1890px;
      margin: 16px auto 0;
      background: #ffffff;
      border: 2px solid #b388ff;
      border-radius: 22px;
      padding: 38px;
      box-shadow: 0 10px 24px rgba(0,0,0,.12);
    }

    .form-label{ font-weight:700; color:#222; margin-bottom:6px; }
    .input-rounded{
      background:#eef1f3; border:1px solid #e0e3e7;
      border-radius:18px; padding:14px 16px; height:48px;
    }
    .input-rounded::placeholder{ color:#9aa0a6; }

    .btn-guardar{
      background:#4c7bf7; border:none; font-weight:800;
      padding: 12px 22px; border-radius: 14px;
    }
    .btn-guardar:hover{ filter: brightness(.95); }

    .pie-pagina{
      background-color:#4c2ca3; color:#dcd6ff;
      padding:28px 0 110px; border-top:1px solid rgba(255,255,255,.08);
      margin-top:3rem;
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
  <?php include 'navbar.php'; ?>
   

  <main>
    <section id="barraInfo" class="barra-principal"></section>

    <section class="card-perfil">
      <form id="formSeguridad">
        <label class="form-label" for="oldPassword">Contraseña actual</label>
        <div class="input-group">
          <input id="oldPassword" type="password" class="form-control input-rounded" value="12345">
          <span class="input-group-text toggle-pass" data-target="oldPassword">
            <i class="bi bi-eye-slash"></i>
          </span>
        </div>

        <label class="form-label mt-3" for="newPassword">Nueva contraseña</label>
        <div class="input-group">
          <input id="newPassword" type="password" class="form-control input-rounded" placeholder="Escribe tu nueva contraseña">
          <span class="input-group-text toggle-pass" data-target="newPassword">
            <i class="bi bi-eye-slash"></i>
          </span>
        </div>

        <label class="form-label mt-3" for="rePassword">Repetir nueva contraseña</label>
        <div class="input-group">
          <input id="rePassword" type="password" class="form-control input-rounded" placeholder="Repite tu nueva contraseña">
          <span class="input-group-text toggle-pass" data-target="rePassword">
            <i class="bi bi-eye-slash"></i>
          </span>
        </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-guardar">Guardar cambios</button>
                </div>
            </form>
        </section>
    </main>

    <div id="menuFooter"></div>

    <script src="navbarconlogin.js"></script>
    <script src="footer.js"></script>
    <script src="barraInformacion.php"></script>
    <script src="autentificacion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<footer class="pie-pagina">
        <div class="container text-center">
            <p>&copy; 2024 El casino de los K. Proyecto académico.</p>
            <?php if (isset($_SESSION['usuario'])): ?>
                <p class="text-muted">Sesión activa como: <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            <?php else: ?>
                <p class="text-muted">Inicia sesión para acceder a todos los juegos</p>
            <?php endif; ?>
        </div>
    </footer>
</body>
</html>
