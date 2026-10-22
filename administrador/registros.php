<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $id_cultivo = intval($_POST["id_cultivo"] ?? 0);
        $id_fase = intval($_POST["id_fase"] ?? 0);
        $fecha_registro = $_POST["fecha_registro"] ?? "";
        $observacion = trim($_POST["observacion"] ?? "");

        if ($id_cultivo > 0 && $id_fase > 0 && $fecha_registro !== "") {

            $sql = "INSERT INTO registros_fenologicos
                    (id_cultivo, id_fase, fecha_registro, observacion)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "iiss",
                $id_cultivo,
                $id_fase,
                $fecha_registro,
                $observacion
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Registro fenológico agregado correctamente.";
            } else {
                $mensaje = "No se pudo agregar el registro.";
            }

            mysqli_stmt_close($stmt);

        } else {

            $mensaje = "Completa el cultivo, la fase y la fecha.";

        }
    }

    if ($accion === "eliminar") {

        $id_registro = intval($_POST["id_registro"] ?? 0);

        if ($id_registro > 0) {

            $sql = "DELETE FROM registros_fenologicos
                    WHERE id_registro = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_registro
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Registro eliminado correctamente.";
            } else {
                $mensaje = "No se pudo eliminar el registro.";
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
                 f.id_cultivo,
                 f.nombre_fase,
                 c.nombre AS cultivo
              FROM fases_fenologicas f
              INNER JOIN cultivos c
                  ON f.id_cultivo = c.id_cultivo
              ORDER BY c.nombre, f.id_fase";

$fases = mysqli_query($conexion, $sql_fases);

$sql_registros = "SELECT
                     rf.id_registro,
                     rf.fecha_registro,
                     rf.observacion,
                     c.nombre AS cultivo,
                     f.nombre_fase AS fase
                  FROM registros_fenologicos rf
                  INNER JOIN cultivos c
                      ON rf.id_cultivo = c.id_cultivo
                  INNER JOIN fases_fenologicas f
                      ON rf.id_fase = f.id_fase
                  ORDER BY rf.id_registro DESC";

$registros = mysqli_query($conexion, $sql_registros);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registros fenológicos | AgroFuturo</title>

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

        <a
            href="registros.php"
            class="activo"
        >
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
            Administrar registros fenológicos
        </h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Registra y consulta el estado de desarrollo de los cultivos.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <section class="informacion">

            <h3>
                Agregar registro fenológico
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

                    <label for="id_fase">
                        Fase fenológica
                    </label>

                    <select
                        id="id_fase"
                        name="id_fase"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                        <option value="">
                            Selecciona una fase
                        </option>

                        <?php while ($fase = mysqli_fetch_assoc($fases)): ?>

                            <option
                                value="<?php echo $fase["id_fase"]; ?>"
                            >
                                <?php
                                echo htmlspecialchars(
                                    $fase["cultivo"] . " - " . $fase["nombre_fase"]
                                );
                                ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="fecha_registro">
                        Fecha del registro
                    </label>

                    <input
                        type="date"
                        id="fecha_registro"
                        name="fecha_registro"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="observacion">
                        Observación
                    </label>

                    <textarea
                        id="observacion"
                        name="observacion"
                        rows="4"
                        placeholder="Escribe una observación sobre el cultivo"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <button
                    type="submit"
                    class="btn"
                >
                    Agregar registro
                </button>

            </form>

        </section>

        <h2 style="margin-top: 35px;">
            Registros realizados
        </h2>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Cultivo</th>

                        <th>Fase</th>

                        <th>Fecha</th>

                        <th>Observación</th>

                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($registros) > 0): ?>

                        <?php while ($registro = mysqli_fetch_assoc($registros)): ?>

                            <tr>

                                <td>
                                    <?php echo $registro["id_registro"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($registro["cultivo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($registro["fase"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($registro["fecha_registro"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($registro["observacion"] ?? ""); ?>
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
                                            name="id_registro"
                                            value="<?php echo $registro["id_registro"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar este registro?');"
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
                                No hay registros fenológicos.
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