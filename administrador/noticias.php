<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $titulo = trim($_POST["titulo"] ?? "");
        $contenido = trim($_POST["contenido"] ?? "");

        if ($titulo !== "" && $contenido !== "") {

            $id_usuario = $_SESSION["id_usuario"];

            $sql = "INSERT INTO noticias (titulo, contenido, id_usuario)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $titulo, $contenido, $id_usuario);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $mensaje = "Noticia creada correctamente.";

        } else {

            $mensaje = "Completa todos los campos.";

        }
    }

    if ($accion === "eliminar") {

        $id_noticia = intval($_POST["id_noticia"]);

        $sql = "DELETE FROM noticias WHERE id_noticia = ?";

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_noticia);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $mensaje = "Noticia eliminada correctamente.";
    }
}

$sql = "SELECT
            n.id_noticia,
            n.titulo,
            n.contenido,
            n.imagen,
            n.fecha_publicacion,
            u.nombre_completo
        FROM noticias n
        LEFT JOIN usuarios u
            ON n.id_usuario = u.id_usuario
        ORDER BY n.id_noticia DESC";

$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Noticias | AgroFuturo</title>

    <link rel="stylesheet" href="estilo_admin.css">

</head>

<body>

<header class="header">

    <div class="logo">
        AgroFuturo
    </div>

    <div class="admin-info">

        <span>
            Hola, <?php echo htmlspecialchars($_SESSION["nombre"] ?? "Administrador"); ?>
        </span>

        <a href="../logout.php" class="btn-cerrar">
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

        <a href="noticias.php" class="activo">
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

        <a href="mensajes.php">
            Mensajes
        </a>

        <a href="../index_principal.php">
            Ver página web
        </a>

    </aside>

    <main class="contenido">

        <h2>Administrar noticias</h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Crea y administra las noticias de AgroFuturo.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <section class="informacion">

            <h3>Crear nueva noticia</h3>

            <form method="POST">

                <input type="hidden" name="accion" value="crear">

                <div style="margin-bottom: 18px;">

                    <label for="titulo">
                        Título
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="contenido">
                        Contenido
                    </label>

                    <textarea
                        id="contenido"
                        name="contenido"
                        rows="6"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <button type="submit" class="btn">
                    Publicar noticia
                </button>

            </form>

        </section>

        <h2 style="margin-top: 35px;">
            Noticias publicadas
        </h2>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Título</th>
                        <th>Contenido</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($resultado) > 0): ?>

                        <?php while ($noticia = mysqli_fetch_assoc($resultado)): ?>

                            <tr>

                                <td>
                                    <?php echo $noticia["id_noticia"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($noticia["titulo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($noticia["contenido"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($noticia["nombre_completo"] ?? "Sin autor"); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($noticia["fecha_publicacion"]); ?>
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
                                            name="id_noticia"
                                            value="<?php echo $noticia["id_noticia"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta noticia?');"
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
                                No hay noticias publicadas.
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