<?php

session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include("conexion.php");

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $usuarioLogueado) {

    $titulo = trim($_POST["titulo"] ?? "");
    $contenido = trim($_POST["contenido"] ?? "");

    if ($titulo === "" || $contenido === "") {

        $mensaje = "Debes completar todos los campos.";
        $tipo = "error";

    } else {

        try {

            $sql = "INSERT INTO foro
                    (id_usuario, titulo, contenido)
                    VALUES (?, ?, ?)";

            $stmt = $conexion->prepare($sql);

            $idUsuario = $_SESSION["id_usuario"];

            $stmt->bind_param(
                "iss",
                $idUsuario,
                $titulo,
                $contenido
            );

            $stmt->execute();
            $stmt->close();

            $mensaje = "Publicación creada correctamente.";
            $tipo = "exito";

            $titulo = "";
            $contenido = "";

        } catch (Exception $e) {

            $mensaje = "No fue posible publicar el tema.";
            $tipo = "error";
        }
    }
}

$sqlPublicaciones = "
    SELECT
        f.id_publicacion,
        f.titulo,
        f.contenido,
        f.fecha_publicacion,
        u.nombre_completo
    FROM foro f
    INNER JOIN usuarios u
        ON f.id_usuario = u.id_usuario
    ORDER BY f.fecha_publicacion DESC
";

$resultadoPublicaciones = $conexion->query($sqlPublicaciones);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Foro Agrícola | AgroFuturo</title>

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
        aria-label="Abrir menú"
        aria-expanded="false"
    >
        ☰
    </button>

    <nav
        class="nav"
        id="nav"
    >

     <a href="index_principal.php">Inicio</a>

    <a href="quienessomos.php">Quiénes Somos</a>

    <a href="noticias.php">Novedades</a>

    <a href="calendario.php">Calendario</a>

    <a href="clima.php">Clima</a>

    <a href="cultivos.php">Cultivos</a>

    <a href="foro.php" class="active">Foro</a>

    <a href="ciclo_planta.php">Ciclo planta</a>

    <a href="contacto.php">Contáctanos</a>

        <?php if ($usuarioLogueado): ?>

            <span class="usuario-saludo">
                Hola,
                <?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?>
            </span>

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
            Foro y Guía Agrícola
        </h1>

        <span>
            Aprende a sembrar, cuidar y cosechar tus cultivos.
        </span>

    </div>

</section>


<main class="page-content">

    <section class="calendar-section">

        <small>
            APRENDE CON AGROFUTURO
        </small>

        <h2>
            Guía para cultivar
        </h2>

        <p class="calendar-description">
            En este espacio encontrarás información sencilla y práctica
            sobre diferentes actividades agrícolas. Aprende paso a paso
            cómo preparar el terreno, seleccionar las semillas, sembrar,
            cuidar tus cultivos y realizar una cosecha adecuada.
        </p>

        <p class="calendar-description">
            Recuerda que cada cultivo tiene necesidades diferentes.
            Factores como el clima, el tipo de suelo, la disponibilidad
            de agua, la temperatura y la época del año pueden influir
            en su desarrollo.
        </p>

    </section>


    <section class="lunar-info">

        <small>
            GUÍA DE SIEMBRA
        </small>

        <h2>
            ¿Cómo sembrar correctamente?
        </h2>

        <div class="lunar-steps">

            <div class="lunar-step">
                <h3>Prepara el terreno</h3>
                <p>
                    Limpia el terreno y elimina piedras, malezas y otros
                    elementos que puedan dificultar el crecimiento de las
                    plantas. Procura elegir un espacio con condiciones
                    adecuadas de luz y ventilación.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Prepara la tierra</h3>
                <p>
                    Remueve la tierra y agrega materia orgánica o compost
                    para mejorar su estructura y aportar nutrientes.
                    Evita que el suelo quede demasiado compacto.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Selecciona las semillas</h3>
                <p>
                    Utiliza semillas de buena calidad y apropiadas para
                    las condiciones de tu región. Revisa que estén en
                    buen estado antes de realizar la siembra.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Siembra las semillas</h3>
                <p>
                    Realiza pequeños agujeros y coloca las semillas a una
                    profundidad adecuada según el cultivo. Deja suficiente
                    espacio entre las plantas para permitir su desarrollo.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Riega adecuadamente</h3>
                <p>
                    Mantén la tierra húmeda sin generar exceso de agua.
                    La cantidad y frecuencia del riego dependen del
                    cultivo, el tipo de suelo y las condiciones climáticas.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Cuida el cultivo</h3>
                <p>
                    Retira las malezas, revisa las plantas y observa
                    periódicamente si existen señales de plagas,
                    enfermedades o deficiencias.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Observa el crecimiento</h3>
                <p>
                    Registra los cambios que presenta la planta durante
                    sus diferentes etapas. Observar su crecimiento ayuda
                    a identificar necesidades y problemas a tiempo.
                </p>
            </div>

            <div class="lunar-step">
                <h3>Realiza la cosecha</h3>
                <p>
                    Recoge los frutos, semillas, hojas o raíces cuando
                    hayan alcanzado el nivel de madurez adecuado para
                    cada cultivo.
                </p>
            </div>

        </div>

    </section>


    <section class="calendar-section">

        <small>
            APRENDE SOBRE CULTIVOS
        </small>

        <h2>
            Guías rápidas
        </h2>

        <div class="calendar-grid">

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Maíz</h3>
                <p>
                    El maíz necesita buena iluminación, un suelo con
                    nutrientes y suficiente espacio para desarrollarse.
                    Durante su crecimiento es importante controlar las
                    malezas y mantener una humedad adecuada.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    evita el exceso de agua y observa regularmente
                    las hojas para detectar posibles problemas.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Tomate</h3>
                <p>
                    El tomate puede iniciar su desarrollo en semilleros
                    antes de ser trasplantado. Requiere buena iluminación,
                    suelo con nutrientes y riego controlado.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    evita mojar excesivamente las hojas y proporciona
                    soporte a la planta cuando sea necesario.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Zanahoria</h3>
                <p>
                    La zanahoria necesita un suelo suelto y profundo para
                    permitir que su raíz se desarrolle correctamente.
                    Es importante retirar piedras y evitar terrenos
                    demasiado compactos.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    mantén una humedad constante durante la germinación.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Lechuga</h3>
                <p>
                    La lechuga necesita humedad adecuada y condiciones
                    favorables para su crecimiento. Puede cultivarse en
                    espacios pequeños siempre que tenga suficiente luz.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    evita el exceso de agua y revisa las hojas
                    frecuentemente.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Fresa</h3>
                <p>
                    La fresa requiere un suelo con buen drenaje y
                    suficiente iluminación. Es importante controlar
                    la humedad para evitar problemas en las raíces.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    evita que los frutos permanezcan en contacto directo
                    con un suelo demasiado húmedo.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Mango</h3>
                <p>
                    El mango es un cultivo que necesita espacio para
                    desarrollar su copa y raíces. Requiere condiciones
                    adecuadas de temperatura, iluminación y humedad.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    selecciona un lugar amplio y con buena iluminación.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Papa</h3>
                <p>
                    La papa se desarrolla a partir de tubérculos-semilla
                    y necesita un suelo suelto que facilite el crecimiento
                    de los tubérculos.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    evita los suelos demasiado compactos y controla
                    adecuadamente la humedad.
                </p>
            </div>

            <div class="calendar-card">
                <span class="moon-icon"></span>
                <h3>Fríjol</h3>
                <p>
                    El fríjol necesita un suelo con buen drenaje y
                    condiciones adecuadas de humedad. Algunas variedades
                    requieren soporte para su crecimiento.
                </p>
                <p>
                    <strong>Consejo:</strong>
                    revisa las plantas regularmente y controla las malezas.
                </p>
            </div>

        </div>

    </section>


    <section class="lunar-info">

        <small>
            CALENDARIO Y PLANIFICACIÓN
        </small>

        <h2>
            Planifica mejor tus cultivos
        </h2>

        <p class="calendar-description">
            Una buena planificación permite organizar las actividades
            agrícolas y hacer seguimiento al desarrollo de cada cultivo.
            AgroFuturo busca facilitar este proceso mediante información
            sobre temporadas, condiciones climáticas y etapas de crecimiento.
        </p>

        <div class="lunar-steps">

            <div class="lunar-step">
                <span>01</span>
                <h3>Selecciona el cultivo</h3>
                <p>
                    Identifica qué cultivo deseas establecer y conoce
                    sus principales necesidades.
                </p>
            </div>

            <div class="lunar-step">
                <span>02</span>
                <h3>Revisa el clima</h3>
                <p>
                    Consulta las condiciones climáticas de tu región
                    antes de programar las actividades.
                </p>
            </div>

            <div class="lunar-step">
                <span>03</span>
                <h3>Consulta el calendario</h3>
                <p>
                    Utiliza la información disponible para organizar
                    las fechas de siembra, cuidado y cosecha.
                </p>
            </div>

            <div class="lunar-step">
                <span>04</span>
                <h3>Registra los avances</h3>
                <p>
                    Anota las actividades realizadas y los cambios
                    observados durante el desarrollo del cultivo.
                </p>
            </div>

        </div>

    </section>


    <section class="lunar-info foro-comunidad">

        <small>
            COMUNIDAD AGROFUTURO
        </small>

        <h2>
            Comparte tus conocimientos
        </h2>

        <p class="calendar-description">
            El foro de AgroFuturo es un espacio de aprendizaje y
            comunicación donde los usuarios pueden compartir
            experiencias, realizar preguntas e intercambiar
            conocimientos sobre agricultura.
        </p>

        <?php if ($usuarioLogueado): ?>

            <div class="foro-publicar">

                <div class="foro-publicar-header">

                    <small>
                        PARTICIPA EN LA COMUNIDAD
                    </small>

                    <h3>
                        Crea una nueva publicación
                    </h3>

                    <p>
                        Comparte una pregunta, experiencia o consejo
                        con la comunidad AgroFuturo.
                    </p>

                </div>

                <form
                    action="foro.php"
                    method="POST"
                    class="foro-form"
                >

                    <div class="foro-campo">

                        <label for="titulo">
                            Título de la publicación
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            placeholder="Ejemplo: ¿Cómo cuidar un cultivo de tomate?"
                            maxlength="200"
                            value="<?php echo htmlspecialchars($titulo ?? ""); ?>"
                            required
                        >

                    </div>

                    <div class="foro-campo">

                        <label for="contenido">
                            Tu publicación
                        </label>

                        <textarea
                            id="contenido"
                            name="contenido"
                            rows="7"
                            placeholder="Escribe aquí tu pregunta, experiencia o consejo..."
                            required
                        ><?php echo htmlspecialchars($contenido ?? ""); ?></textarea>

                    </div>

                    <div class="foro-form-footer">

                        <span>
                            Publicando como:
                            <strong>
                                <?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?>
                            </strong>
                        </span>

                        <button
                            type="submit"
                            class="foro-publicar-btn"
                        >
                            Publicar tema
                        </button>

                    </div>

                </form>

                <?php if ($mensaje !== ""): ?>

                    <div
                        class="<?php echo $tipo === 'exito' ? 'mensaje-exito' : 'mensaje-error'; ?>"
                    >
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>

                <?php endif; ?>

            </div>

        <?php else: ?>

            <div class="foro-login">

                <div class="foro-login-texto">

                    <small>
                        PARTICIPA EN LA COMUNIDAD
                    </small>

                    <h3>
                        ¿Quieres publicar en el foro?
                    </h3>

                    <p>
                        Puedes leer todas las publicaciones sin iniciar
                        sesión. Para crear y publicar un tema debes
                        iniciar sesión en AgroFuturo.
                    </p>

                </div>

                <a
                    href="iniciodesesion.php"
                    class="foro-login-btn"
                >
                    Iniciar Sesión
                </a>

            </div>

        <?php endif; ?>

    </section>


    <section class="calendar-section">

        <small>
            PUBLICACIONES DE LA COMUNIDAD
        </small>

        <h2>
            Temas recientes
        </h2>

        <?php if ($resultadoPublicaciones->num_rows > 0): ?>

            <div class="foro-publicaciones">

                <?php while ($publicacion = $resultadoPublicaciones->fetch_assoc()): ?>

                    <article class="foro-publicacion">

                        <div class="foro-publicacion-top">

                            <span>
                                Comunidad AgroFuturo
                            </span>

                            <time>
                                <?php
                                echo date(
                                    "d/m/Y H:i",
                                    strtotime($publicacion["fecha_publicacion"])
                                );
                                ?>
                            </time>

                        </div>

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $publicacion["titulo"]
                            );
                            ?>
                        </h3>

                        <p class="foro-contenido">
                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $publicacion["contenido"]
                                )
                            );
                            ?>
                        </p>

                        <div class="foro-autor">

                            Publicado por
                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $publicacion["nombre_completo"]
                                );
                                ?>
                            </strong>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="foro-vacio">

                <h3>
                    Aún no hay publicaciones
                </h3>

                <p>
                    Sé la primera persona en compartir una experiencia,
                    pregunta o consejo agrícola.
                </p>

            </div>

        <?php endif; ?>

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


<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const nav = document.getElementById("nav");

    if (!menuToggle || !nav) return;

    menuToggle.addEventListener("click", function () {
        nav.classList.toggle("menu-abierto");
        menuToggle.setAttribute(
            "aria-expanded",
            nav.classList.contains("menu-abierto") ? "true" : "false"
        );
    });

    // Cerrar el menú al seleccionar una opción en pantallas pequeñas.
    nav.querySelectorAll("a").forEach(function (enlace) {
        enlace.addEventListener("click", function () {
            nav.classList.remove("menu-abierto");
            menuToggle.setAttribute("aria-expanded", "false");
        });
    });

    // Si se vuelve a una pantalla grande, quitar el estado móvil.
    window.addEventListener("resize", function () {
        if (window.innerWidth > 900) {
            nav.classList.remove("menu-abierto");
            menuToggle.setAttribute("aria-expanded", "false");
        }
    });
});
</script>

</body>

</html>
 