
<?php
session_start();

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";
$esAdmin = $usuarioLogueado && ($_SESSION["rol"] ?? "") === "admin";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Calendario Lunar | AgroFuturo</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

<header class="header">

    <a class="logo" href="index_principal.php">
        <img src="logo.png" alt="Logo AgroFuturo">

        <span>
            <strong>AGRO<span>FUTURO</span></strong>
            <small>Innovación y tecnología para el campo</small>
        </span>
    </a>

    <nav class="nav">

        <a href="index_principal.php">Inicio</a>
        <a href="quienessomos.php">Quiénes Somos</a>
        <a href="noticias.php">Novedades</a>
        <a href="calendario.php" class="active">Calendario</a>
        <a href="clima.php">Clima</a>
        <a href="cultivos.php">Cultivos</a>
        <a href="foro.php">Foro</a>
        <a href="ciclo_planta.php">Ciclo planta</a>
        <a href="contacto.php">Contáctanos</a>

        <?php if ($usuarioLogueado): ?>

            <span class="usuario-saludo">
                Hola, <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>
            </span>

            <?php if ($esAdmin): ?>
                <a href="admin/dashboard.php">Panel Admin</a>
            <?php endif; ?>

            <a href="logout.php">Cerrar Sesión</a>

        <?php else: ?>

            <a href="iniciodesesion.php" class="login-btn">Iniciar Sesión</a>

        <?php endif; ?>

    </nav>

</header>

<section class="page-banner">

    <div class="banner-overlay"></div>

    <div class="banner-content">
        <p>AGROFUTURO</p>

        <h1>Calendario Lunar Agrícola</h1>

        <span>
            Planifica las actividades de tu cultivo según las fases de la Luna.
        </span>
    </div>

</section>

<main class="page-content">

    <section class="calendar-section">

        <small>PLANIFICACIÓN AGRÍCOLA</small>

        <h2>Fases de la Luna</h2>

        <p class="calendar-description">
            El calendario lunar agrícola organiza algunas actividades del campo
            teniendo en cuenta las diferentes fases de la Luna. En AgroFuturo
            puedes consultar recomendaciones tradicionales para orientar la
            siembra, el crecimiento y la cosecha de tus cultivos.
        </p>

        <section class="lunar-today">

            <small>CALENDARIO LUNAR</small>

            <h2>Fase lunar de hoy</h2>

            <div class="moon-today">

                <div class="moon-display" id="moonDisplay"></div>

                <div class="moon-information">

                    <h3 id="moonPhase">
                        Calculando fase lunar...
                    </h3>

                    <p>
                        <strong>Fecha:</strong>
                        <span id="moonDate"></span>
                    </p>

                    <p id="moonDescription">
                        Consulta la fase lunar correspondiente al día de hoy.
                    </p>

                    <div class="moon-recommendation">

                        <strong>Recomendación agrícola</strong>

                        <p id="moonRecommendation">
                            Cargando recomendación...
                        </p>

                    </div>

                </div>

            </div>

        </section>

        <div class="calendar-grid">

            <div class="calendar-card">

                <span class="moon-icon"></span>

                <h3>Luna Nueva</h3>

                <p>
                    <strong>Siembra:</strong>
                    cultivos de raíz.
                </p>

                <p>
                    <strong>Recomendación:</strong>
                    preparar el suelo y planificar nuevas siembras.
                </p>

            </div>

            <div class="calendar-card">

                <span class="moon-icon"></span>

                <h3>Cuarto Creciente</h3>

                <p>
                    <strong>Siembra:</strong>
                    cultivos de hojas y plantas que crecen sobre el suelo.
                </p>

                <p>
                    <strong>Recomendación:</strong>
                    favorecer el desarrollo vegetativo.
                </p>

            </div>

            <div class="calendar-card">

                <span class="moon-icon"></span>

                <h3>Luna Llena</h3>

                <p>
                    <strong>Actividad:</strong>
                    observar el desarrollo y estado de los cultivos.
                </p>

                <p>
                    <strong>Recomendación:</strong>
                    realizar labores de mantenimiento y cuidado.
                </p>

            </div>

            <div class="calendar-card">

                <span class="moon-icon"></span>

                <h3>Cuarto Menguante</h3>

                <p>
                    <strong>Cosecha:</strong>
                    momento tradicionalmente asociado con la recolección.
                </p>

                <p>
                    <strong>Recomendación:</strong>
                    realizar podas y preparar el terreno.
                </p>

            </div>

        </div>

    </section>

    <section class="lunar-info">

        <small>AGROFUTURO</small>

        <h2>¿Cómo utilizar el calendario lunar?</h2>

        <div class="lunar-steps">

            <div class="lunar-step">

                <span>01</span>

                <h3>Consulta la fase lunar</h3>

                <p>
                    Identifica en qué fase se encuentra la Luna antes de
                    planificar una actividad agrícola.
                </p>

            </div>

            <div class="lunar-step">

                <span>02</span>

                <h3>Selecciona tu cultivo</h3>

                <p>
                    Ten en cuenta las características y necesidades específicas
                    del cultivo que deseas trabajar.
                </p>

            </div>

            <div class="lunar-step">

                <span>03</span>

                <h3>Planifica tu actividad</h3>

                <p>
                    Utiliza la información como una orientación adicional junto
                    con el clima, el suelo y las condiciones de tu región.
                </p>

            </div>

        </div>

    </section>

</main>

<footer>

    <span>
        © 2026 AgroFuturo - Todos los derechos reservados
    </span>

    <div>
        Síguenos en: &nbsp; ◎ &nbsp; f &nbsp; ▶ &nbsp; ◉
    </div>

    <i>
        Juntos cultivamos el futuro ♡
    </i>

</footer>

<script src="script.js"></script>

</body>
</html>
