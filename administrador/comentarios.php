<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "eliminar") {

        $id_comentario = intval($_POST["id_comentario"] ?? 0);

        if ($id_comentario > 0) {

            $sql = "DELETE FROM comentarios WHERE id_comentario = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_comentario
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Comentario eliminado correctamente.";
            } else {
                $mensaje = "No se pudo eliminar el comentario.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$sql_comentarios = "SELECT
                       co.id_comentario,
                       co.comentario,
                       co.fecha_comentario,
                       u.nombre_completo,
                       u.usuario,
                       f.titulo AS publicacion
                    FROM comentarios co
                    INNER JOIN usuarios u
                        ON co.id_usuario = u.id_usuario
                    INNER JOIN foro f
                        ON co.id_publicacion = f.id_publicacion
                    ORDER BY co.id_comentario DESC";

$comentarios = mysqli_query($conexion, $sql_comentarios);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Comentarios | AgroFuturo</title>

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

        <a
            href="comentarios.php"
            class="activo"
        >
            Comentarios
        </a>

        <a href="mensajes.php">
            Mensajes
        </a>

        <a href="../index_principal.php">
            Ver página web
        </a>

    </aside>

    <main class="contenido">

        <h2>
            Administrar comentarios
        </h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Consulta y administra los comentarios realizados en las publicaciones del foro.
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

                        <th>Usuario</th>

                        <th>Publicación</th>

                        <th>Comentario</th>

                        <th>Fecha</th>

                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($comentarios) > 0): ?>

                        <?php while ($comentario = mysqli_fetch_assoc($comentarios)): ?>

                            <tr>

                                <td>
                                    <?php echo $comentario["id_comentario"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($comentario["nombre_completo"]); ?>
                                    <br>
                                    <small>
                                        @<?php echo htmlspecialchars($comentario["usuario"]); ?>
                                    </small>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($comentario["publicacion"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($comentario["comentario"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($comentario["fecha_comentario"]); ?>
                                </td>

                                <td>

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="accion"
                                            value="eliminar"
                                        >

                                        <input
                                            type="hidden"
                                            name="id_comentario"
                                            value="<?php echo $comentario["id_comentario"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar este comentario?');"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6">
                                No hay comentarios registrados.
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