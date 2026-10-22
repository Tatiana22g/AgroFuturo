<?php

include("conexion.php");

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["registroCorreo"] ?? "");
    $clave = $_POST["registroPassword"] ?? "";
    $confirmar = $_POST["confirmPassword"] ?? "";
    $terminos = isset($_POST["terminos"]);

    if (empty($nombre) || empty($correo) || empty($clave) || empty($confirmar)) {

        $mensaje = "Debes completar todos los campos.";
        $tipo_mensaje = "error";

    } elseif (!$terminos) {

        $mensaje = "Debes aceptar los términos y condiciones.";
        $tipo_mensaje = "error";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Ingresa un correo electrónico válido.";
        $tipo_mensaje = "error";

    } elseif (strlen($clave) < 6) {

        $mensaje = "La contraseña debe tener mínimo 6 caracteres.";
        $tipo_mensaje = "error";

    } elseif ($clave !== $confirmar) {

        $mensaje = "Las contraseñas no coinciden.";
        $tipo_mensaje = "error";

    } else {

        $buscar = "SELECT id_usuario FROM usuarios WHERE correo = ?";

        $stmt = $conexion->prepare($buscar);

        $stmt->bind_param("s", $correo);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $mensaje = "Este correo ya está registrado.";
            $tipo_mensaje = "error";

        } else {

            $usuario = strtolower(explode("@", $correo)[0]);

            $buscarUsuario = "SELECT id_usuario FROM usuarios WHERE usuario = ?";

            $stmtUsuario = $conexion->prepare($buscarUsuario);

            $stmtUsuario->bind_param("s", $usuario);

            $stmtUsuario->execute();

            $resultadoUsuario = $stmtUsuario->get_result();

            if ($resultadoUsuario->num_rows > 0) {

                $usuario = $usuario . rand(100, 999);

            }

            $claveSegura = password_hash(
                $clave,
                PASSWORD_DEFAULT
            );

            $id_rol = 2;

            $sql = "INSERT INTO usuarios
                    (usuario, correo, clave, nombre_completo, id_rol, activo)
                    VALUES (?, ?, ?, ?, ?, 1)";

            $stmtInsertar = $conexion->prepare($sql);

            $stmtInsertar->bind_param(
                "ssssi",
                $usuario,
                $correo,
                $claveSegura,
                $nombre,
                $id_rol
            );

            if ($stmtInsertar->execute()) {

                header("Location: iniciodesesion.php?registro=exitoso");

                exit();

            } else {

                $mensaje = "No fue posible crear la cuenta.";

                $tipo_mensaje = "error";
            }
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

    <title>AgroFuturo | Registro</title>

    <link
        rel="stylesheet"
        href="styles.css"
    >

</head>

<body>

<header class="header">

    <a
        href="index_principal.php"
        class="logo"
    >

        <span class="logo-icon"></span>

        <span>

            <strong>
                AGRO<span>FUTURO</span>
            </strong>

            <small>
                Innovación y tecnología para el campo
            </small>

        </span>

    </a>

    <nav id="nav">

          <a href="index_principal.php">Inicio</a>

    <a href="quienessomos.php">Quiénes Somos</a>

    <a href="noticias.php">Novedades</a>

    <a href="calendario.php">Calendario</a>

    <a href="clima.php">Clima</a>

    <a href="cultivos.php">Cultivos</a>

    <a href="foro.php">Foro</a>

    <a href="ciclo_planta.php">Ciclo planta</a>

    <a href="contacto.php">Contáctanos</a>

    <a href="iniciodesesion.php">Iniciar Sesión</a>

    </nav>

</header>

<main class="auth-page">

    <section class="auth-box register-box">

        <h1>
            Crear una cuenta
        </h1>

        <p class="auth-subtitle">
            Únete a la comunidad de AgroFuturo
        </p>

        <?php if (!empty($mensaje)): ?>

            <div class="login-error">

                <?php
                echo htmlspecialchars($mensaje);
                ?>

            </div>

        <?php endif; ?>

        <form
            id="registerForm"
            action="registro.php"
            method="POST"
        >

            <label for="nombre">
                Nombre completo
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="auth-input"
                placeholder="Tu nombre completo"
                value="<?php echo htmlspecialchars($_POST["nombre"] ?? ""); ?>"
                required
            >

            <label for="registroCorreo">
                Correo electrónico
            </label>

            <input
                type="email"
                id="registroCorreo"
                name="registroCorreo"
                class="auth-input"
                placeholder="ejemplo@correo.com"
                value="<?php echo htmlspecialchars($_POST["registroCorreo"] ?? ""); ?>"
                required
            >

            <label for="registroPassword">
                Contraseña
            </label>

            <input
                type="password"
                id="registroPassword"
                name="registroPassword"
                class="auth-input"
                placeholder="Crea una contraseña"
                minlength="6"
                required
            >

            <label for="confirmPassword">
                Confirmar contraseña
            </label>

            <input
                type="password"
                id="confirmPassword"
                name="confirmPassword"
                class="auth-input"
                placeholder="Repite tu contraseña"
                minlength="6"
                required
            >

            <label class="terms">

                <input
                    type="checkbox"
                    name="terminos"
                    required
                >

                Acepto los términos y condiciones.

            </label>

            <button
                type="submit"
                class="auth-button"
            >
                Crear cuenta
            </button>

        </form>

        <div class="auth-divider">
            o
        </div>

        <p class="register-text">

            ¿Ya tienes una cuenta?

            <a href="iniciodesesion.php">
                Inicia sesión
            </a>

        </p>

        <a
            href="index_principal.php"
            class="back-home"
        >
            ← Volver al inicio
        </a>

    </section>

</main>

<footer>

    <span>
        © 2026 AgroFuturo
    </span>

    <i>
        Juntos cultivamos el futuro ♡
    </i>

</footer>

<script src="script.js"></script>

</body>

</html>

