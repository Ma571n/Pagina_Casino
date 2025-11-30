<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el sistema de sesiones y navbar
session_start();
include_once('consumoEndpoint.php');

$endpoint = 'https://udp.coningenio.cl/casino/api/v1/mesa/';
$token = 'udp.2025';

$mesas = getApiData($endpoint, $token);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #312e81;
        }

        html {
            scroll-behavior: smooth;
        }

        .custom-navbar {
            background-color: #4c2ca3 !important;
        }

        .navbar-brand {
            font-weight: 600;
            color: #ffffff !important;
        }
        
        /* Carrusel */
        .carousel-container {
            margin-top: 80px;
            overflow: hidden;
            width: 100%;
            max-width: 1500px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 20px;
        }

        .carousel-caption {
            background: rgba(142, 142, 142, 0.385);
            width: 100%;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 20px;
            border-radius: 0 0 20px 20px;
        }

        .btn-iniciar-sesion {
            background: #3a2b8f;
            color: #ffffff;
            border: none;
        }

        .btn-iniciar-sesion:hover {
            background: #2a1d6f;
        }

        .content {
            padding: 2rem 0;
        }

        .barra-reglas {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: #4c2ca3;
            margin: 20px auto;
            max-width: 550px;
            position: fixed;
            bottom: -13px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1020;
            width: 70%;
        }

        .barra-reglas .btn-primary {
            background-color: #6649b8;
            border: none;
            font-weight: bold;
        }

        h2 {
            font-size: 20px;
            text-align: center;
            color: white;
            margin-bottom: -20px;
        }

        .acerca {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
            border-radius: 20px;
        }

        .caja-acerca {
            background-color: #4440b4;
            padding: 20px;
            width: 732px;
            border-radius: 20px;
            text-align: left;
        }

        .contenido-acerca {
            font-size: 20px;
            background-color: #8a47be;
            padding: 3px 8px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .sobre {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 40px;
            flex-wrap: wrap;
            color: whitesmoke;
        }

        .programador {
            background-color: #8a47be;
            padding: 20px;
            border-radius: 20px;
            font-size: 16px;
            color: #ddd;
        }

        .cajitas {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .ruleta-container {
            background: #4440b4;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 9px black;
            max-width: 1500px;
            margin: 20px auto;
            color: #ffffff;
            position: relative;
        }

        .reglas-label {
            position: absolute;
            background: #8a47be;
            color: white;
            padding: 5px 15px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 18px;
        }

        .ruleta-container h2 {
            color: white;
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 24px;
        }

        .ruleta-container ul li {
            color: #ffffff;
            padding: 8px 0;
            padding-left: 25px;
            font-size: 16px;
        }

        .pie-pagina {
            background-color: #4c2ca3;
            color: #dcd6ff;
            padding: 28px 0 110px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin-top: 3rem;
        }

        /* Estilos para el dropdown del usuario logueado */
        .dropdown-menu {
            background-color: #4c2ca3;
            border: 1px solid #6649b8;
        }

        .dropdown-item {
            color: #ffffff;
        }

        .dropdown-item:hover {
            background-color: #6649b8;
            color: #ffffff;
        }

        .text-danger {
            color: #ff6b6b !important;
        }
    </style>
</head>
<body>
    <!-- Navbar PHP integrado -->
    <?php include 'navbar.php'; ?>
    
    <main>
        <section id="inicio" class="carousel-container container">
    <?php
    // Seleccionar mesa activa con menor apuesta mínima
    $mesaSeleccionada = null;

    if ($mesas !== null && count($mesas) > 0) {
        foreach ($mesas as $mesa) {
            if (!empty($mesa['activo'])) { // solo mesas activas
                if ($mesaSeleccionada === null || $mesa['apuesta']['minima'] < $mesaSeleccionada['apuesta']['minima']) {
                    $mesaSeleccionada = $mesa;
                }
            }
        }
    }

    if ($mesaSeleccionada !== null):
        // Datos de la mesa (pero ya NO mostraremos nombre ni descripción)
        $apuestaMin   = number_format($mesaSeleccionada['apuesta']['minima'], 0, ',', '.');
        $apuestaMax   = number_format($mesaSeleccionada['apuesta']['maxima'], 0, ',', '.');
        $estado       = htmlspecialchars($mesaSeleccionada['estado']['nombre']);
    ?>

        <div id="carouselMesas" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner rounded-5">

                <div class="carousel-item active">

                    <?php
                    // Si está logueado: link directo a la ruleta
                    // Si NO está logueado: abre modal de login
                    $attrHref   = '';
                    $attrExtras = '';

                    if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
                        $attrHref = 'href="laruletadefinitiva.php"';
                    } else {
                        $attrHref   = 'href="#"';
                        $attrExtras = 'data-bs-toggle="modal" data-bs-target="#loginModal"';
                    }
                    ?>

                    <!-- Imagen clickeable -->
                    <a <?php echo $attrHref . ' ' . $attrExtras; ?> style="display:block;">
                        <img src="https://blog.meridianbet.pe/wp-content/uploads/2025/06/Ruleta-Europea-Casino-juegos-de-mesa.jpg"
                             class="d-block w-100"
                             alt="Mesa de ruleta"
                             style="height: 500px; object-fit: cover;">
                    </a>

                    <div class="carousel-caption d-none d-md-block">

                        <!-- 🔥 Quitamos nombre, descripción y nombre del juego -->

                        <!-- Solo mostramos apuestas + estado -->
                        <p>
                            Apuesta mínima: $<?php echo $apuestaMin; ?> |
                            Máxima: $<?php echo $apuestaMax; ?><br>
                            Estado: <?php echo $estado; ?>
                        </p>

                    </div>
                </div>

            </div>
        </div>

    <?php else: ?>
        <p class="text-center text-danger mt-4">
            No hay mesas activas disponibles.
        </p>
    <?php endif; ?>
</section>




        
        <!-- Barra de menú -->
        <section>
            <div class="barra-reglas container rounded-3">
                <div class="barra-reglas-memor row text-center p-4">
                    <div class="col-4">
                        <a href="#inicio" class="btn btn-primary w-100">Inicio</a>
                    </div>
                    <div class="col-4">
                        <a href="#acerca" class="btn btn-primary w-100">Acerca de</a>
                    </div>
                    <div class="col-4">
                        <a href="#reglas" class="btn btn-primary w-100">Reglas</a>
                    </div>
                </div>
            </div>
        </section>

        <h2>Bienvenido a "El casino de los K". Conoce a nuestro equipo y aprende las reglas antes de jugar.</h2>

        

        <div id="acerca" class="sobre">
            <!-- Acerca de -->
            <div class="acerca">
                <div class="caja-acerca">
                    <div class="contenido-acerca"> Acerca de </div>
                    <h3>¿Qué es?</h3>
                    <p>Un proyecto académico centrado en desarrollar habilidades tanto en desarrollo de una página web, como en el manejo de las herramientas proporcionadas en equipo para la realización del proyecto en el que se crea un juego de ruleta americana de un casino online a través de las siguientes tecnologías:</p>
                    <ul>
                        <li><strong> VisualStudioCode:</strong> Para la creación de HTML y CSS.</li>
                        <li><strong> GitHub:</strong> Para la edición complementaria de todos los miembros del grupo del código.</li>
                    </ul>
                    <p><strong>Aclaración:</strong> Como se menciona anteriormente este es un proyecto académico con fines únicamente educativos, por lo cual este mismo no busca promover ningún tipo de ludopatía o adicción a estos juegos de apuestas.</p>
                </div>
            </div>
            
            <!-- Equipo -->
            <div class="acerca">
                <div class="caja-acerca">
                    <div class="contenido-acerca"> Equipo </div>
                    <h3> Desarrolladores </h3>
                    <div class="cajitas">
                        <div class="programador">
                            <p><strong>Martin Mondaca</strong> | Programador Principal</p>
                        </div>
                        <div class="programador">
                            <p><strong>Matías Neira</strong> | Programador Principal</p>
                        </div>
                        <div class="programador">
                            <p><strong>David Fuentes</strong> | Programador Principal</p>
                        </div>
                        <div class="programador">
                            <p><strong>Nicolás González</strong> | Programador Secundario | Diseñador</p>
                        </div>
                        <div class="programador">
                            <p><strong>Paulette Saavedra</strong> | Programador Secundario | Diseñador</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reglas -->
            <div id="reglas" class="ruleta-container">
                <div class="reglas-label">Reglas</div>
                <h2>La Ruleta</h2>
                <p>La ruleta, o ruleta de casino, es un juego simple que consiste en una rueda con casillas enumeradas del 0 al 36. La mitad de los números (1-36) son negros y la otra mitad son rojos, mientras que el 0 es verde. El jugador apuesta a dónde cree que caerá una pequeña bola. Para este proyecto en particular, las apuestas funcionan asi:</p>
                <ul>
                    <li><strong>Rojo o Negro:</strong> Si se apuesta a uno de estos colores y la bola cae en un número de ese color, el jugador gana el doble de lo apostado (1:1).</li>
                    <li><strong>Par o Impar:</strong> Si se apuesta a los números pares o impares y la bola cae en un número que cumple esa condición, el jugador gana el doble de lo apostado (1:1).</li>
                    <li><strong>Número Único:</strong> Si el jugador apuesta a un solo número (del 0 al 36) y la bola cae en ese número, ¡la ganancia es mucho mayor! Se paga 35 veces lo apostado (35:1)</li>
                    <li><strong>Verde:</strong> Si la bola cae en el 0 (verde), todas las apuestas a rojo, negro, par o impar se pierden</li>
                </ul>
                <p>Establece límites de tiempo y dinero. Juega por diversión y evita perseguir pérdidas, a veces se gana y a veces se pierde!</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>