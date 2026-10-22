
<?php
require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $id_cultivo = (int) ($_POST["id_cultivo"] ?? 0);
        $mes_siembra = trim($_POST["mes_siembra"] ?? "");
        $mes_cosecha = trim($_POST["mes_cosecha"] ?? "");
        $recomendacion = trim($_POST["recomendacion"] ?? "");

        if (
            $id_cultivo > 0 &&
            $mes_siembra !== "" &&
            $mes_cosecha !== ""
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
                    $mensaje = "Registro agregado correctamente.";
                    $tipoMensaje = "exito";
                } else {
                    $mensaje = "No se pudo agregar el registro.";
                    $tipoMensaje = "error";
                }

                mysqli_stmt_close($stmt);
            } else {
                $mensaje = "No se pudo preparar la consulta.";
                $tipoMensaje = "error";
            }
        } else {
            $mensaje = "Completa los campos obligatorios.";
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
                mysqli_stmt_bind_param($stmt, "i", $id_calendario);

                if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
                    $mensaje = "Registro eliminado correctamente.";
                    $tipoMensaje = "exito";
                } else {
                    $mensaje = "No se encontró el registro o no se pudo eliminar.";
                    $tipoMensaje = "error";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}

$sqlCultivos = "SELECT id_cultivo, nombre
                FROM cultivos
                ORDER BY nombre ASC";

$resultadoCultivos = mysqli_query($conexion, $sqlCultivos);

$sqlCalendario = "SELECT
                    ca.id_calendario,
                    ca.id_cultivo,
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

$totalCultivos = $resultadoCultivos
    ? mysqli_num_rows($resultadoCultivos)
    : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar calendario agrícola | AgroFuturo</title>
    <link rel="stylesheet" href="estilo_admin.css">

    <style>
        .contenido-calendario {
            padding: 25px;
        }

        .encabezado-pagina {
            margin-bottom: 25px;
        }

        .encabezado-pagina h1 {
            margin-bottom: 8px;
        }

        .estadisticas-calendario {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .estadistica {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        }

        .estadistica h3 {
            margin: 0 0 10px;
            font-size: 15px;
        }

        .estadistica p {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #3f7d32;
        }

        .tarjeta-calendario {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        }

        .formulario-calendario {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .campo-calendario {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .campo-completo {
            grid-column: 1 / -1;
        }

        .campo-calendario label {
            font-weight: 600;
        }

        .campo-calendario input,
        .campo-calendario select,
        .campo-calendario textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            border: 1px solid #d4ddd2;
            border-radius: 8px;
            font: inherit;
        }

        .campo-calendario textarea {
            min-height: 100px;
            resize: vertical;
        }

        .boton-guardar {
            background: #3f7d32;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .boton-guardar:hover {
            background: #2e6125;
        }

        .tabla-contenedor {
            overflow-x: auto;
        }

        .tabla-calendario {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-calendario th,
        .tabla-calendario td {
            padding: 13px;
            border-bottom: 1px solid #e6eae4;
            text-align: left;
            vertical-align: top;
        }

        .tabla-calendario th {
            background: #f0f5ed;
        }

        .boton-eliminar {
            background: #c0392b;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .mensaje-calendario {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .mensaje-calendario.exito {
            background: #e6f5e5;
            color: #28632b;
        }

        .mensaje-calendario.error {
            background: #fdebea;
            color: #a12622;
        }

        .sin-registros {
            padding: 20px;
            text-align: center;
            color: #666;
        }

        @media (max-width: 650px) {
            .contenido-calendario {
                padding: 15px;
            }

            .formulario-calendario {
                grid-template-columns: 1fr;
            }

            .campo-completo {
                grid-column: auto;
            }

            .tarjeta-calendario {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<div class="contenido-calendario">

    <header class="encabezado-pagina">
        <h1>Calendario agrícola</h1>
        <p>Administra los periodos de siembra y cosecha de los cultivos registrados en AgroFuturo.</p>
    </header>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje-calendario <?= htmlspecialchars($tipoMensaje) ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <section class="estadisticas-calendario">
        <div class="estadistica">
            <h3>Cultivos registrados</h3>
            <p><?= $totalCultivos ?></p>
        </div>

        <div class="estadistica">
            <h3>Periodos agrícolas registrados</h3>
            <p><?= $totalRegistros ?></p>
        </div>
    </section>

    <section class="tarjeta-calendario">
        <h2>Agregar periodo agrícola</h2>
        <p>Selecciona un cultivo y define sus meses de siembra y cosecha.</p>

        <?php if ($totalCultivos > 0): ?>
            <form method="POST" class="formulario-calendario">
                <input type="hidden" name="accion" value="crear">

                <div class="campo-calendario campo-completo">
                    <label for="id_cultivo">Cultivo</label>
                    <select name="id_cultivo" id="id_cultivo" required>
                        <option value="">Selecciona un cultivo</option>

                        <?php while ($cultivo = mysqli_fetch_assoc($resultadoCultivos)): ?>
                            <option value="<?= (int) $cultivo["id_cultivo"] ?>">
                                <?= htmlspecialchars($cultivo["nombre"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="campo-calendario">
                    <label for="mes_siembra">Mes de siembra</label>
                    <select name="mes_siembra" id="mes_siembra" required>
                        <option value="">Selecciona el mes</option>
                        <?php
                        $meses = [
                            "Enero", "Febrero", "Marzo", "Abril",
                            "Mayo", "Junio", "Julio", "Agosto",
                            "Septiembre", "Octubre", "Noviembre", "Diciembre"
                        ];
                        foreach ($meses as $mes):
                        ?>
                            <option value="<?= $mes ?>"><?= $mes ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo-calendario">
                    <label for="mes_cosecha">Mes de cosecha</label>
                    <select name="mes_cosecha" id="mes_cosecha" required>
                        <option value="">Selecciona el mes</option>
                        <?php foreach ($meses as $mes): ?>
                            <option value="<?= $mes ?>"><?= $mes ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo-calendario campo-completo">
                    <label for="recomendacion">Recomendaciones</label>
                    <textarea
                        name="recomendacion"
                        id="recomendacion"
                        placeholder="Escribe recomendaciones para la siembra y cosecha..."
                    ></textarea>
                </div>

                <div class="campo-calendario campo-completo">
                    <button type="submit" class="boton-guardar">
                        Guardar periodo agrícola
                    </button>
                </div>
            </form>
        <?php else: ?>
            <p class="sin-registros">
                Primero debes registrar cultivos en la sección de Cultivos.
            </p>
        <?php endif; ?>
    </section>

    <section class="tarjeta-calendario">
        <h2>Periodos agrícolas registrados</h2>

        <?php if ($resultadoCalendario && $totalRegistros > 0): ?>
            <div class="tabla-contenedor">
                <table class="tabla-calendario">
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
                                <td><?= htmlspecialchars($registro["nombre_cultivo"]) ?></td>
                                <td><?= htmlspecialchars($registro["mes_siembra"]) ?></td>
                                <td><?= htmlspecialchars($registro["mes_cosecha"]) ?></td>
                                <td>
                                    <?= nl2br(htmlspecialchars($registro["recomendacion"] ?? "")) ?>
                                </td>
                                <td>
                                    <form
                                        method="POST"
                                        onsubmit="return confirm('¿Estás segura de que deseas eliminar este periodo agrícola?');"
                                    >
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input
                                            type="hidden"
                                            name="id_calendario"
                                            value="<?= (int) $registro["id_calendario"] ?>"
                                        >
                                        <button type="submit" class="boton-eliminar">
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
            <p class="sin-registros">
                Todavía no hay periodos agrícolas registrados.
            </p>
        <?php endif; ?>
    </section>

    <p>
        <a href="index.php">Volver al panel administrativo</a>
    </p>

</div>

</body>
</html>
