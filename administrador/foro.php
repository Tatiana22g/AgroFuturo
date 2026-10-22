<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "eliminar") {

        $id_publicacion = intval($_POST["id_publicacion"] ?? 0);

        if ($id_publicacion > 0) {

            $sql = "DELETE FROM foro WHERE id_publicacion = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_publicacion
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Publicación eliminada correctamente.";
            } else {
                $mensaje = "No se pudo eliminar la publicación.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$sql_foro = "SELECT
                f.id_publicacion,
                f.titulo,
                f.contenido,
                f.fecha_publicacion,
                u.nombre_completo,
                u.usuario
             FROM foro f
             INNER JOIN usuarios u
                 ON f.id_usuario = u.id_usuario
             ORDER BY f.id_publicacion DESC";

$publicaciones = mysqli_query($conexion, $sql_foro);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Foro | AgroFuturo</title>

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

        <a
            href="foro.php"
            class="activo"
        >
            Foro
        </a>

        <a href="comentarios.php">
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
            Administrar foro
        </h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Consulta y administra las publicaciones realizadas por los usuarios.
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

                        <th>Título</th>

                        <th>Contenido</th>

                        <th>Fecha</th>

                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($publicaciones) > 0): ?>

                        <?php while ($publicacion = mysqli_fetch_assoc($publicaciones)): ?>

                            <tr>

                                <td>
                                    <?php echo $publicacion["id_publicacion"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($publicacion["nombre_completo"]); ?>
                                    <br>
                                    <small>
                                        @<?php echo htmlspecialchars($publicacion["usuario"]); ?>
                                    </small>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($publicacion["titulo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($publicacion["contenido"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($publicacion["fecha_publicacion"]); ?>
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
                                            name="id_publicacion"
                                            value="<?php echo $publicacion["id_publicacion"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta publicación?');"
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
                                No hay publicaciones en el foro.
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