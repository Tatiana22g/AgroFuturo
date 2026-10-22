<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $id_cultivo = intval($_POST["id_cultivo"] ?? 0);
        $nombre_fase = trim($_POST["nombre_fase"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $dias_aproximados = intval($_POST["dias_aproximados"] ?? 0);

        if ($id_cultivo > 0 && $nombre_fase !== "") {

            $sql = "INSERT INTO fases_fenologicas
                    (id_cultivo, nombre_fase, descripcion, dias_aproximados)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "issi",
                $id_cultivo,
                $nombre_fase,
                $descripcion,
                $dias_aproximados
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Fase fenológica agregada correctamente.";
            } else {
                $mensaje = "No se pudo agregar la fase.";
            }

            mysqli_stmt_close($stmt);

        } else {

            $mensaje = "Selecciona un cultivo y escribe el nombre de la fase.";

        }
    }

    if ($accion === "eliminar") {

        $id_fase = intval($_POST["id_fase"] ?? 0);

        if ($id_fase > 0) {

            $sql = "DELETE FROM fases_fenologicas
                    WHERE id_fase = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_fase
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Fase eliminada correctamente.";
            } else {
                $mensaje = "No se pudo eliminar la fase.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$sql_cultivos = "SELECT
                    id_cultivo,
                    nombre
                 FROM cultivos
                 ORDER BY nombre";

$cultivos = mysqli_query($conexion, $sql_cultivos);

$sql_fases = "SELECT
                 f.id_fase,
                 f.nombre_fase,
                 f.descripcion,
                 f.dias_aproximados,
                 c.nombre AS cultivo
              FROM fases_fenologicas f
              INNER JOIN cultivos c
                  ON f.id_cultivo = c.id_cultivo
              ORDER BY f.id_fase DESC";

$fases = mysqli_query($conexion, $sql_fases);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Fases fenológicas | AgroFuturo</title>

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

        <a
            href="fases.php"
            class="activo"
        >
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

        <h2>
            Administrar fases fenológicas
        </h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Gestiona las etapas de desarrollo de los cultivos registrados.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <section class="informacion">

            <h3>
                Agregar nueva fase
            </h3>

            <form method="POST">

                <input
                    type="hidden"
                    name="accion"
                    value="crear"
                >

                <div style="margin-bottom: 18px;">

                    <label for="id_cultivo">
                        Cultivo
                    </label>

                    <select
                        id="id_cultivo"
                        name="id_cultivo"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                        <option value="">
                            Selecciona un cultivo
                        </option>

                        <?php while ($cultivo = mysqli_fetch_assoc($cultivos)): ?>

                            <option
                                value="<?php echo $cultivo["id_cultivo"]; ?>"
                            >
                                <?php echo htmlspecialchars($cultivo["nombre"]); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="nombre_fase">
                        Nombre de la fase
                    </label>

                    <input
                        type="text"
                        id="nombre_fase"
                        name="nombre_fase"
                        required
                        placeholder="Ejemplo: Germinación"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        placeholder="Describe esta fase del cultivo"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="dias_aproximados">
                        Días aproximados
                    </label>

                    <input
                        type="number"
                        id="dias_aproximados"
                        name="dias_aproximados"
                        min="1"
                        placeholder="Ejemplo: 7"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <button
                    type="submit"
                    class="btn"
                >
                    Agregar fase
                </button>

            </form>

        </section>

        <h2 style="margin-top: 35px;">
            Fases registradas
        </h2>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Cultivo</th>

                        <th>Fase</th>

                        <th>Descripción</th>

                        <th>Días</th>

                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($fases) > 0): ?>

                        <?php while ($fase = mysqli_fetch_assoc($fases)): ?>

                            <tr>

                                <td>
                                    <?php echo $fase["id_fase"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($fase["cultivo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($fase["nombre_fase"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($fase["descripcion"] ?? ""); ?>
                                </td>

                                <td>

                                    <?php

                                    echo $fase["dias_aproximados"] !== null
                                        ? $fase["dias_aproximados"] . " días"
                                        : "No definido";

                                    ?>

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
                                            name="id_fase"
                                            value="<?php echo $fase["id_fase"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta fase?');"
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
                                No hay fases fenológicas registradas.
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