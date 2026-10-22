<?php
session_start();

require_once("conexion.php");

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["loginCorreo"] ?? "");
    $clave = $_POST["loginPassword"] ?? "";

    if ($correo === "" || $clave === "") {
        $mensaje = "Debes completar todos los campos.";
        $tipo_mensaje = "error";
    } else {
        $sql = "SELECT
                    u.id_usuario,
                    u.usuario,
                    u.correo,
                    u.clave,
                    u.nombre_completo,
                    u.activo,
                    r.nombre AS rol
                FROM usuarios AS u
                INNER JOIN roles AS r ON u.id_rol = r.id_rol
                WHERE u.correo = ?
                LIMIT 1";

        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            $mensaje = "No se pudo preparar la consulta de inicio de sesión.";
            $tipo_mensaje = "error";
        } else {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows === 1) {
                $usuarioEncontrado = $resultado->fetch_assoc();

                if ((int)$usuarioEncontrado["activo"] !== 1) {
                    $mensaje = "Esta cuenta se encuentra desactivada.";
                    $tipo_mensaje = "error";
                } else {
                    $claveGuardada = $usuarioEncontrado["clave"];

                    // Acepta contraseñas cifradas y, temporalmente,
                    // contraseñas antiguas guardadas como texto normal.
                    $claveCorrecta = password_verify($clave, $claveGuardada);

                    if (!$claveCorrecta && hash_equals((string)$claveGuardada, (string)$clave)) {
                        $claveCorrecta = true;

                        // Actualiza la contraseña antigua a un hash seguro.
                        $nuevoHash = password_hash($clave, PASSWORD_DEFAULT);
                        $sqlActualizar = "UPDATE usuarios SET clave = ? WHERE id_usuario = ?";
                        $stmtActualizar = $conexion->prepare($sqlActualizar);

                        if ($stmtActualizar) {
                            $idUsuarioActualizar = (int)$usuarioEncontrado["id_usuario"];
                            $stmtActualizar->bind_param("si", $nuevoHash, $idUsuarioActualizar);
                            $stmtActualizar->execute();
                            $stmtActualizar->close();
                        }
                    }

                    if (!$claveCorrecta) {
                        $mensaje = "El correo o la contraseña son incorrectos.";
                        $tipo_mensaje = "error";
                    } else {
                        session_regenerate_id(true);

                        $_SESSION["id_usuario"] = (int)$usuarioEncontrado["id_usuario"];
                        $_SESSION["usuario"] = $usuarioEncontrado["usuario"];
                        $_SESSION["nombre"] = $usuarioEncontrado["nombre_completo"];
                        $_SESSION["correo"] = $usuarioEncontrado["correo"];
                        $_SESSION["rol"] = $usuarioEncontrado["rol"];

                        $stmt->close();
                        $conexion->close();

                        if (strcasecmp(trim($usuarioEncontrado["rol"]), "Administrador") === 0) {
                            header("Location: administrador/index.php");
                            exit();
                        }

                        header("Location: index_principal.php");
                        exit();
                    }
                }
            } else {
                $mensaje = "El correo o la contraseña son incorrectos.";
                $tipo_mensaje = "error";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroFuturo | Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header">
    <a href="index_principal.php" class="logo">
        <img src="logo.png" alt="Logo AgroFuturo">
        <span>
            <strong>AGRO<span>FUTURO</span></strong>
            <small>Innovación y tecnología para el campo</small>
        </span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
        ☰
    </button>

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
        <a href="iniciodesesion.php" class="login-btn">Iniciar Sesión</a>
    </nav>
</header>

<main class="auth-page">
    <section class="auth-box">
        <h1>Bienvenido de nuevo</h1>
        <p class="auth-subtitle">Inicia sesión en tu cuenta de AgroFuturo</p>

        <?php if ($mensaje !== ""): ?>
            <div class="login-error">
                <?php echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8"); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="iniciodesesion.php" method="POST">
            <label for="loginCorreo">Correo electrónico</label>
            <input
                type="email"
                id="loginCorreo"
                name="loginCorreo"
                placeholder="ejemplo@correo.com"
                autocomplete="email"
                required
                value="<?php echo htmlspecialchars($_POST["loginCorreo"] ?? "", ENT_QUOTES, "UTF-8"); ?>"
            >

            <label for="loginPassword">Contraseña</label>
            <input
                type="password"
                id="loginPassword"
                name="loginPassword"
                placeholder="Ingresa tu contraseña"
                autocomplete="current-password"
                required
            >

            <div class="form-options">
                <label class="remember">
                    <input type="checkbox" name="recordarme">
                    <span>Recordarme</span>
                </label>

                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="auth-button">
                Iniciar Sesión
            </button>
        </form>

        <div class="auth-divider">
            <span>o</span>
        </div>

        <p class="register-text">
            ¿No tienes una cuenta?
            <a href="registro.php">Regístrate</a>
        </p>

        <a href="index_principal.php" class="back-home">
            ← Volver al inicio
        </a>
    </section>
</main>

<footer>
    <span>© 2026 AgroFuturo - Todos los derechos reservados</span>
    <div>Síguenos en: &nbsp; ◎ &nbsp; f &nbsp; ▶ &nbsp; ◉</div>
    <i>Juntos cultivamos el futuro ♡</i>
</footer>

<script src="script.js"></script>
</body>
</html>
