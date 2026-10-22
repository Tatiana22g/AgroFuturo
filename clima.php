
<?php

session_start();

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";
$esAdmin = $usuarioLogueado && ($_SESSION["rol"] ?? "") === "Administrador";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Clima | AgroFuturo</title>

    <link
        rel="stylesheet"
        href="styles.css"
    >

</head>

<body>

<header class="header">

    <a
        class="logo"
        href="index_principal.php"
    >

        <img
            src="logo.png"
            alt="Logo AgroFuturo"
        >

        <span>

            <strong>
                AGRO<span>FUTURO</span>
            </strong>

            <small>
                Innovación y tecnología para el campo
            </small>

        </span>

    </a>

    <button
        class="menu-toggle"
        id="menuToggle"
        type="button"
    >
        ☰
    </button>

    <nav
        class="nav"
        id="nav"
    >

        <a href="index_principal.php">
            Inicio
        </a>

        <a href="quienessomos.php">
            Quiénes Somos
        </a>

        <a href="noticias.php">
            Novedades
        </a>

        <a href="calendario.php">
            Calendario
        </a>

        <a href="clima.php" class="active">
            Clima
        </a>

        <a href="cultivos.php">
            Cultivos
        </a>

        <a href="foro.php">
            Foro
        </a>

        <a href="ciclo_planta.php">
            Ciclo planta
        </a>

        <a href="contacto.php">
            Contáctanos
        </a>

        <?php if ($usuarioLogueado): ?>

            <span class="usuario-saludo">
                Hola,
                <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>
            </span>

            <?php if ($esAdmin): ?>

                <a
                    href="administrador/index.php"
                    class="login-btn"
                >
                    Panel Admin
                </a>

            <?php endif; ?>

            <a
                href="logout.php"
                class="login-btn"
            >
                Cerrar Sesión
            </a>

        <?php else: ?>

            <a
                href="iniciodesesion.php"
                class="login-btn"
            >
                Iniciar Sesión
            </a>

        <?php endif; ?>

    </nav>

</header>

<section class="page-banner">

    <div class="banner-overlay"></div>

    <div class="banner-content">

        <p>
            AGROFUTURO
        </p>

        <h1>
            Clima Inteligente
        </h1>

        <span>
            El clima también forma parte de tu cosecha.
        </span>

    </div>

</section>

<main class="page-content">

    <section class="weather-section">

        <small>
            INFORMACIÓN METEOROLÓGICA
        </small>

        <h2>
            Clima de hoy
        </h2>

        <div class="weather-date">

            <span id="fecha">
                Cargando fecha...
            </span>

            <strong id="hora">
                Cargando hora...
            </strong>

        </div>

        <div class="weather-main">

            <div class="temperature">

                <strong id="temp">
                    --°C
                </strong>

                <p id="estado">
                    Cargando clima...
                </p>

            </div>

            <div class="weather-info">

                <div>

                    <strong id="humedad">
                        --%
                    </strong>

                    <span>
                        Humedad
                    </span>

                </div>

                <div>

                    <strong id="viento">
                        -- km/h
                    </strong>

                    <span>
                        Viento
                    </span>

                </div>

                <div>

                    <strong id="minima">
                        --°C
                    </strong>

                    <span>
                        Mínima
                    </span>

                </div>

            </div>

        </div>

        <div class="weather-advice">

            <strong>
                Recomendación agrícola
            </strong>

            <p id="consejo">
                Consultando las condiciones actuales...
            </p>

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
<script src="clima.js"></script>

</body>

</html>
