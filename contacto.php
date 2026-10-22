
<?php

session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include("conexion.php");

$mensaje = "";
$tipo = "";

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";
$correoUsuario = $_SESSION["correo"] ?? "";

$mensajeEnviado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $mensajeTexto = trim($_POST["mensaje"] ?? "");

    if ($nombre === "" || $correo === "" || $mensajeTexto === "") {

        $mensaje = "Debes completar todos los campos.";
        $tipo = "error";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "El correo no es válido.";
        $tipo = "error";

    } else {

        try {

            $sql = "INSERT INTO mensajes_contacto
                    (nombre, correo, mensaje)
                    VALUES (?, ?, ?)";

            $stmt = $conexion->prepare($sql);

            $stmt->bind_param(
                "sss",
                $nombre,
                $correo,
                $mensajeTexto
            );

            $stmt->execute();

            $mensaje = "Mensaje enviado correctamente.";
            $tipo = "exito";
            $mensajeEnviado = true;

            $stmt->close();

        } catch (Exception $e) {

            $mensaje = "Error al guardar el mensaje: " . $e->getMessage();
            $tipo = "error";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Contáctanos | AgroFuturo</title>

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

        <a href="clima.php">
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

        <a
            href="contacto.php"
            class="active"
        >
            Contáctanos
        </a>


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
            Contáctanos
        </h1>

        <span>
            Estamos aquí para ayudarte.
        </span>

    </div>

</section>


<main class="page-content">

    <section class="contact-section">


        <div class="contact-info">

            <small>
                HABLEMOS
            </small>


            <h2>
                Conecta con AgroFuturo
            </h2>


            <p>
                ¿Tienes alguna pregunta, sugerencia o necesitas
                información? Escríbenos.
            </p>


            <div class="contact-item">

                <span>

                    <strong>
                        Correo
                    </strong>

                    agrofuturo@gmail.com

                </span>

            </div>


            <div class="contact-item">

                <span>

                    <strong>
                        Ubicación
                    </strong>

                    Medellín, Colombia

                </span>

            </div>


            <div class="contact-item">

                <span>

                    <strong>
                        Teléfono
                    </strong>

                    +57 300 000 0000

                </span>

            </div>

        </div>


        <form
            class="contact-form"
            action="contacto.php"
            method="POST"
        >

            <input
                type="text"
                name="nombre"
                placeholder="Tu nombre"
                value="<?php echo htmlspecialchars($_POST["nombre"] ?? $nombreUsuario); ?>"
                required
            >


            <input
                type="email"
                name="correo"
                placeholder="Tu correo"
                value="<?php echo htmlspecialchars($_POST["correo"] ?? $correoUsuario); ?>"
                required
            >


            <textarea
                name="mensaje"
                placeholder="Escribe tu mensaje..."
                rows="6"
                required
><?php echo $mensajeEnviado ? "" : htmlspecialchars($_POST["mensaje"] ?? ""); ?></textarea>


            <button
                type="submit"
            >
                Enviar mensaje →
            </button>


            <?php if ($mensaje !== ""): ?>

                <div
                    style="
                        margin-top: 20px;
                        padding: 15px;
                        border-radius: 10px;
                        background: <?php echo $tipo === 'exito' ? '#dff5e1' : '#ffe0e0'; ?>;
                        color: <?php echo $tipo === 'exito' ? '#176b2c' : '#9b1c1c'; ?>;
                    "
                >

                    <?php
                    echo htmlspecialchars($mensaje);
                    ?>

                </div>

            <?php endif; ?>

        </form>

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
