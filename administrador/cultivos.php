<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $nombre = trim($_POST["nombre"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $tipo = trim($_POST["tipo"] ?? "");
        $tiempo_cosecha = intval($_POST["tiempo_cosecha"] ?? 0);
        $id_region = intval($_POST["id_region"] ?? 0);

        $siembra = trim($_POST["siembra"] ?? "");
        $luz = trim($_POST["luz"] ?? "");
        $riego = trim($_POST["riego"] ?? "");
        $suelo = trim($_POST["suelo"] ?? "");
        $cuidados = trim($_POST["cuidados"] ?? "");
        $plagas = trim($_POST["plagas"] ?? "");
        $cosecha = trim($_POST["cosecha"] ?? "");

        if ($nombre !== "" && $id_region > 0) {

            $sql = "INSERT INTO cultivos
                    (
                        nombre,
                        descripcion,
                        tipo,
                        tiempo_cosecha,
                        id_region,
                        siembra,
                        luz,
                        riego,
                        suelo,
                        cuidados,
                        plagas,
                        cosecha
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssiiissssss",
                $nombre,
                $descripcion,
                $tipo,
                $tiempo_cosecha,
                $id_region,
                $siembra,
                $luz,
                $riego,
                $suelo,
                $cuidados,
                $plagas,
                $cosecha
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $mensaje = "Cultivo agregado correctamente.";

        } else {

            $mensaje = "Completa el nombre y selecciona una región.";

        }
    }

    if ($accion === "eliminar") {

        $id_cultivo = intval($_POST["id_cultivo"]);

        $sql = "DELETE FROM cultivos WHERE id_cultivo = ?";

        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id_cultivo
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $mensaje = "Cultivo eliminado correctamente.";
    }
}

$sql_regiones = "SELECT
                    id_region,
                    nombre,
                    departamento
                 FROM regiones
                 ORDER BY nombre";

$regiones = mysqli_query($conexion, $sql_regiones);

$sql_cultivos = "SELECT
                    c.id_cultivo,
                    c.nombre,
                    c.descripcion,
                    c.tipo,
                    c.tiempo_cosecha,
                    c.siembra,
                    c.luz,
                    c.riego,
                    c.suelo,
                    c.cuidados,
                    c.plagas,
                    c.cosecha,
                    r.nombre AS region,
                    r.departamento
                 FROM cultivos c
                 LEFT JOIN regiones r
                    ON c.id_region = r.id_region
                 ORDER BY c.id_cultivo DESC";

$cultivos = mysqli_query($conexion, $sql_cultivos);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cultivos | AgroFuturo</title>

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

        <a href="noticias.php">
            Noticias
        </a>

        <a href="cultivos.php" class="activo">
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

        <h2>Administrar cultivos</h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Gestiona los cultivos registrados en AgroFuturo.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <section class="informacion">

            <h3>Agregar nuevo cultivo</h3>

            <form method="POST">

                <input
                    type="hidden"
                    name="accion"
                    value="crear"
                >

                <div style="margin-bottom: 18px;">

                    <label for="nombre">
                        Nombre del cultivo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="tipo">
                        Tipo
                    </label>

                    <input
                        type="text"
                        id="tipo"
                        name="tipo"
                        placeholder="Ejemplo: Hortaliza"
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
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="tiempo_cosecha">
                        Tiempo de cosecha en días
                    </label>

                    <input
                        type="number"
                        id="tiempo_cosecha"
                        name="tiempo_cosecha"
                        min="1"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="siembra">
                        Siembra
                    </label>

                    <textarea
                        id="siembra"
                        name="siembra"
                        rows="3"
                        placeholder="Ejemplo: Sembrar entre enero y marzo."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="luz">
                        Luz
                    </label>

                    <textarea
                        id="luz"
                        name="luz"
                        rows="3"
                        placeholder="Ejemplo: Requiere buena exposición solar."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="riego">
                        Riego
                    </label>

                    <textarea
                        id="riego"
                        name="riego"
                        rows="3"
                        placeholder="Ejemplo: Riego moderado y frecuente."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="suelo">
                        Suelo
                    </label>

                    <textarea
                        id="suelo"
                        name="suelo"
                        rows="3"
                        placeholder="Ejemplo: Suelo fértil y con buen drenaje."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="cuidados">
                        Cuidados
                    </label>

                    <textarea
                        id="cuidados"
                        name="cuidados"
                        rows="3"
                        placeholder="Ejemplo: Retirar malezas y revisar el crecimiento."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="plagas">
                        Plagas
                    </label>

                    <textarea
                        id="plagas"
                        name="plagas"
                        rows="3"
                        placeholder="Ejemplo: Pulgones, mosca blanca."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="cosecha">
                        Cosecha
                    </label>

                    <textarea
                        id="cosecha"
                        name="cosecha"
                        rows="3"
                        placeholder="Ejemplo: Cosechar cuando los frutos alcancen su madurez."
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="id_region">
                        Región
                    </label>

                    <select
                        id="id_region"
                        name="id_region"
                        required
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                        <option value="">
                            Selecciona una región
                        </option>

                        <?php while ($region = mysqli_fetch_assoc($regiones)): ?>

                            <option value="<?php echo $region["id_region"]; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $region["nombre"] . " - " . $region["departamento"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <button type="submit" class="btn">
                    Agregar cultivo
                </button>

            </form>

        </section>

        <h2 style="margin-top: 35px;">
            Cultivos registrados
        </h2>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Cosecha</th>
                        <th>Región</th>
                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($cultivos) > 0): ?>

                        <?php while ($cultivo = mysqli_fetch_assoc($cultivos)): ?>

                            <tr>

                                <td>
                                    <?php echo $cultivo["id_cultivo"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($cultivo["nombre"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($cultivo["tipo"] ?? ""); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($cultivo["descripcion"] ?? ""); ?>
                                </td>

                                <td>
                                    <?php
                                    echo $cultivo["tiempo_cosecha"] !== null
                                        ? $cultivo["tiempo_cosecha"] . " días"
                                        : "No definido";
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        ($cultivo["region"] ?? "Sin región") .
                                        " - " .
                                        ($cultivo["departamento"] ?? "")
                                    );
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
                                            name="id_cultivo"
                                            value="<?php echo $cultivo["id_cultivo"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar este cultivo?');"
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
                                No hay cultivos registrados.
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