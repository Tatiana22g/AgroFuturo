
<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";
$tipoMensaje = "";

$meses = [
    "Enero", "Febrero", "Marzo", "Abril",
    "Mayo", "Junio", "Julio", "Agosto",
    "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $id_cultivo = (int) ($_POST["id_cultivo"] ?? 0);
        $mes_siembra = trim($_POST["mes_siembra"] ?? "");
        $mes_cosecha = trim($_POST["mes_cosecha"] ?? "");
        $recomendacion = trim($_POST["recomendacion"] ?? "");

        if (
            $id_cultivo > 0 &&
            in_array($mes_siembra, $meses, true) &&
            in_array($mes_cosecha, $meses, true)
        ) {

            $sql = "INSERT INTO calendario_agricola
                    (id_cultivo, mes_siembra, mes_cosecha, recomendacion)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "isss",
                    $id_cultivo,
                    $mes_siembra,
                    $mes_cosecha,
                    $recomendacion
                );

                if (mysqli_stmt_execute($stmt)) {
                    $mensaje = "Periodo agrícola agregado correctamente.";
                    $tipoMensaje = "exito";
                } else {
                    $mensaje = "No se pudo agregar el periodo agrícola.";
                    $tipoMensaje = "error";
                }

                mysqli_stmt_close($stmt);

            } else {
                $mensaje = "No se pudo preparar la consulta.";
                $tipoMensaje = "error";
            }

        } else {
            $mensaje = "Selecciona un cultivo y los meses correspondientes.";
            $tipoMensaje = "error";
        }
    }

    if ($accion === "eliminar") {

        $id_calendario = (int) ($_POST["id_calendario"] ?? 0);

        if ($id_calendario > 0) {

            $sql = "DELETE FROM calendario_agricola
                    WHERE id_calendario = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "i",
                    $id_calendario
                );

                if (
                    mysqli_stmt_execute($stmt) &&
                    mysqli_stmt_affected_rows($stmt) > 0
                ) {
                    $mensaje = "Periodo agrícola eliminado correctamente.";
                    $tipoMensaje = "exito";
                } else {
                    $mensaje = "No se encontró el registro o no se pudo eliminar.";
                    $tipoMensaje = "error";
                }

                mysqli_stmt_close($stmt);

            } else {
                $mensaje = "No se pudo preparar la consulta.";
                $tipoMensaje = "error";
            }

        } else {
            $mensaje = "El registro seleccionado no es válido.";
            $tipoMensaje = "error";
        }
    }
}

$sqlCultivos = "SELECT id_cultivo, nombre
                FROM cultivos
                ORDER BY nombre ASC";

$resultadoCultivos = mysqli_query($conexion, $sqlCultivos);

$totalCultivos = $resultadoCultivos
    ? mysqli_num_rows($resultadoCultivos)
    : 0;

$sqlCalendario = "SELECT
                    ca.id_calendario,
                    ca.mes_siembra,
                    ca.mes_cosecha,
                    ca.recomendacion,
                    c.nombre AS nombre_cultivo
                  FROM calendario_agricola ca
                  INNER JOIN cultivos c
                    ON ca.id_cultivo = c.id_cultivo
                  ORDER BY c.nombre ASC";

$resultadoCalendario = mysqli_query($conexion, $sqlCalendario);

$totalRegistros = $resultadoCalendario
    ? mysqli_num_rows($resultadoCalendario)
    : 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario agrícola | AgroFuturo</title>
    <link rel="stylesheet" href="estilo_admin.css">
</head>

<body>

<header class="header">

    <div class="logo">
        AgroFuturo
    </div>

    <div class="admin-info">

        <span>
            Hola,
            <?php
            echo htmlspecialchars(
                $_SESSION["nombre"] ?? "Administrador",
                ENT_QUOTES,
                "UTF-8"
            );
            ?>
        </span>

        <a href="../logout.php" class="btn-cerrar">
            Cerrar sesión
        </a>

    </div>

</header>

<div class="contenedor">

    <aside class="sidebar">

        <h3>Administración</h3>

        <a href="index.php">Inicio</a>

        <a href="usuarios.php">Usuarios</a>

        <a href="noticias.php">Noticias</a>

        <a href="cultivos.php">Cultivos</a>

        <a href="regiones.php">Regiones</a>

        <a href="calendario.php" class="activo">
            Calendario agrícola
        </a>

        <a href="fases.php">Fases fenológicas</a>

        <a href="registros.php">Registros fenológicos</a>

        <a href="foro.php">Foro</a>

        <a href="comentarios.php">Comentarios</a>

        <a href="mensajes.php">Mensajes</a>

        <a href="../index_principal.php">Ver página web</a>

    </aside>

    <main class="contenido">

        <h2>Calendario agrícola</h2>

        <p>
            Administra los periodos de siembra y cosecha
            de los cultivos registrados en AgroFuturo.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <div class="estadisticas">

            <div class="tarjeta">
                <h3>Cultivos registrados</h3>
                <p><?php echo $totalCultivos; ?></p>
            </div>

            <div class="tarjeta">
                <h3>Periodos agrícolas registrados</h3>
                <p><?php echo $totalRegistros; ?></p>
            </div>

        </div>

        <section>

            <h3>Agregar periodo agrícola</h3>

            <form method="POST" action="calendario.php">

                <input type="hidden" name="accion" value="crear">

                <p>
                    <label for="id_cultivo">Cultivo:</label>
                    <br>

                    <select name="id_cultivo" id="id_cultivo" required>

                        <option value="">Selecciona un cultivo</option>

                        <?php if ($resultadoCultivos): ?>

                            <?php
                            mysqli_data_seek($resultadoCultivos, 0);

                            while ($cultivo = mysqli_fetch_assoc($resultadoCultivos)):
                            ?>

                                <option value="<?php echo (int) $cultivo["id_cultivo"]; ?>">
                                    <?php
                                    echo htmlspecialchars(
                                        $cultivo["nombre"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </option>

                            <?php endwhile; ?>

                        <?php endif; ?>

                    </select>
                </p>

                <p>
                    <label for="mes_siembra">Mes de siembra:</label>
                    <br>

                    <select name="mes_siembra" id="mes_siembra" required>

                        <option value="">Selecciona el mes</option>

                        <?php foreach ($meses as $mes): ?>

                            <option value="<?php echo $mes; ?>">
                                <?php echo $mes; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </p>

                <p>
                    <label for="mes_cosecha">Mes de cosecha:</label>
                    <br>

                    <select name="mes_cosecha" id="mes_cosecha" required>

                        <option value="">Selecciona el mes</option>

                        <?php foreach ($meses as $mes): ?>

                            <option value="<?php echo $mes; ?>">
                                <?php echo $mes; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </p>

                <p>
                    <label for="recomendacion">Recomendaciones:</label>
                    <br>

                    <textarea
                        name="recomendacion"
                        id="recomendacion"
                        rows="4"
                        placeholder="Escribe recomendaciones para la siembra y cosecha..."
                    ></textarea>
                </p>

                <button type="submit" class="btn">
                    Guardar periodo agrícola
                </button>

            </form>

        </section>

        <section>

            <h3>Periodos agrícolas registrados</h3>

            <?php if ($resultadoCalendario && $totalRegistros > 0): ?>

                <div class="tabla-contenedor">

                    <table>

                        <thead>

                            <tr>
                                <th>Cultivo</th>
                                <th>Siembra</th>
                                <th>Cosecha</th>
                                <th>Recomendaciones</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($registro = mysqli_fetch_assoc($resultadoCalendario)): ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $registro["nombre_cultivo"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $registro["mes_siembra"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $registro["mes_cosecha"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo nl2br(
                                            htmlspecialchars(
                                                $registro["recomendacion"] ?? "",
                                                ENT_QUOTES,
                                                "UTF-8"
                                            )
                                        );
                                        ?>
                                    </td>

                                    <td>

                                        <form
                                            method="POST"
                                            action="calendario.php"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este periodo agrícola?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="accion"
                                                value="eliminar"
                                            >

                                            <input
                                                type="hidden"
                                                name="id_calendario"
                                                value="<?php echo (int) $registro["id_calendario"]; ?>"
                                            >

                                            <button type="submit" class="btn">
                                                Eliminar
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <p>
                    Todavía no hay periodos agrícolas registrados.
                </p>

            <?php endif; ?>

        </section>

        <p>
            <a href="index.php">Volver al panel administrativo</a>
        </p>

    </main>

</div>

</body>

</html>

