<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "leer") {

        $id_mensaje = intval($_POST["id_mensaje"] ?? 0);

        if ($id_mensaje > 0) {

            $sql = "UPDATE mensajes_contacto
                    SET estado = 'Leído'
                    WHERE id_mensaje = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_mensaje
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Mensaje marcado como leído.";
            } else {
                $mensaje = "No se pudo actualizar el mensaje.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if ($accion === "eliminar") {

        $id_mensaje = intval($_POST["id_mensaje"] ?? 0);

        if ($id_mensaje > 0) {

            $sql = "DELETE FROM mensajes_contacto
                    WHERE id_mensaje = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_mensaje
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Mensaje eliminado correctamente.";
            } else {
                $mensaje = "No se pudo eliminar el mensaje.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$sql_mensajes = "SELECT
                    id_mensaje,
                    nombre,
                    correo,
                    mensaje,
                    fecha_envio,
                    estado
                 FROM mensajes_contacto
                 ORDER BY id_mensaje DESC";

$mensajes = mysqli_query($conexion, $sql_mensajes);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mensajes | AgroFuturo</title>

    <link
        rel="stylesheet"
        href="estilo_admin.css"
    >

</head>

<body>

<header class="header">

    <div class="logo">
        AgroFuturo
    </div>

    <div class="admin-info">

        <span>
            Hola,
            <?php echo htmlspecialchars($_SESSION["nombre"] ?? "Administrador"); ?>
        </span>

        <a
            href="../logout.php"
            class="btn-cerrar"
        >
            Cerrar sesión
        </a>

    </div>

</header>

<div class="contenedor">

    <aside class="sidebar">

        <h3>Administración</h3>

        <a href="index.php">
            Inicio
        </a>

        <a href="usuarios.php">
            Usuarios
        </a>

        <a href="noticias.php">
            Noticias
        </a>

        <a href="cultivos.php">
            Cultivos
        </a>

        <a href="regiones.php">
            Regiones
        </a>

        <a href="calendario.php">
            Calendario agrícola
        </a>

        <a href="fases.php">
            Fases fenológicas
        </a>

        <a href="registros.php">
            Registros fenológicos
        </a>

        <a href="foro.php">
            Foro
        </a>

        <a href="comentarios.php">
            Comentarios
        </a>

        <a
            href="mensajes.php"
            class="activo"
        >
            Mensajes
        </a>

        <a href="../index_principal.php">
            Ver página web
        </a>

    </aside>

    <main class="contenido">

        <h2>
            Mensajes de contacto
        </h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Consulta y administra los mensajes enviados desde el formulario de contacto.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nombre</th>

                        <th>Correo</th>

                        <th>Mensaje</th>

                        <th>Fecha</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($mensajes) > 0): ?>

                        <?php while ($mensajeFila = mysqli_fetch_assoc($mensajes)): ?>

                            <tr>

                                <td>
                                    <?php echo $mensajeFila["id_mensaje"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($mensajeFila["nombre"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($mensajeFila["correo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($mensajeFila["mensaje"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($mensajeFila["fecha_envio"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($mensajeFila["estado"]); ?>
                                </td>

                                <td>

                                    <?php if ($mensajeFila["estado"] === "Pendiente"): ?>

                                        <form
                                            method="POST"
                                            style="margin-bottom: 8px;"
                                        >

                                            <input
                                                type="hidden"
                                                name="accion"
                                                value="leer"
                                            >

                                            <input
                                                type="hidden"
                                                name="id_mensaje"
                                                value="<?php echo $mensajeFila["id_mensaje"]; ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn"
                                            >
                                                Marcar leído
                                            </button>

                                        </form>

                                    <?php endif; ?>

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="accion"
                                            value="eliminar"
                                        >

                                        <input
                                            type="hidden"
                                            name="id_mensaje"
                                            value="<?php echo $mensajeFila["id_mensaje"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar este mensaje?');"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7">
                                No hay mensajes de contacto.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>

</html>