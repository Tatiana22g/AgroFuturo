<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {

        $nombre = trim($_POST["nombre"] ?? "");
        $departamento = trim($_POST["departamento"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");

        if ($nombre !== "" && $departamento !== "") {

            $sql = "INSERT INTO regiones
                    (nombre, departamento, descripcion)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nombre,
                $departamento,
                $descripcion
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $mensaje = "Región agregada correctamente.";

        } else {

            $mensaje = "Completa el nombre y el departamento.";

        }
    }

    if ($accion === "eliminar") {

        $id_region = intval($_POST["id_region"] ?? 0);

        if ($id_region > 0) {

            $sql = "DELETE FROM regiones WHERE id_region = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $id_region
            );

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Región eliminada correctamente.";
            } else {
                $mensaje = "No se puede eliminar esta región porque tiene cultivos asociados.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$sql_regiones = "SELECT
                    id_region,
                    nombre,
                    departamento,
                    descripcion
                 FROM regiones
                 ORDER BY id_region DESC";

$regiones = mysqli_query($conexion, $sql_regiones);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regiones | AgroFuturo</title>
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

        <a href="index.php">Inicio</a>

        <a href="usuarios.php">Usuarios</a>

        <a href="noticias.php">Noticias</a>

        <a href="cultivos.php">Cultivos</a>

        <a href="regiones.php" class="activo">Regiones</a>

        <a href="calendario.php">Calendario agrícola</a>

        <a href="fases.php">Fases fenológicas</a>

        <a href="registros.php">Registros fenológicos</a>

        <a href="foro.php">Foro</a>

        <a href="comentarios.php">Comentarios</a>

        <a href="mensajes.php">Mensajes</a>

        <a href="../index_principal.php">Ver página web</a>

    </aside>

    <main class="contenido">

        <h2>Administrar regiones</h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Gestiona las regiones agrícolas registradas en AgroFuturo.
        </p>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>

        <?php endif; ?>

        <section class="informacion">

            <h3>Agregar nueva región</h3>

            <form method="POST">

                <input
                    type="hidden"
                    name="accion"
                    value="crear"
                >

                <div style="margin-bottom: 18px;">

                    <label for="nombre">
                        Nombre de la región
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        required
                        placeholder="Ejemplo: Valle de Aburrá"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label for="departamento">
                        Departamento
                    </label>

                    <input
                        type="text"
                        id="departamento"
                        name="departamento"
                        required
                        placeholder="Ejemplo: Antioquia"
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
                        placeholder="Descripción de la región"
                        style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #dce4da; border-radius: 8px; font-family: inherit; resize: vertical;"
                    ></textarea>

                </div>

                <button
                    type="submit"
                    class="btn"
                >
                    Agregar región
                </button>

            </form>

        </section>

        <h2 style="margin-top: 35px;">
            Regiones registradas
        </h2>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Región</th>
                        <th>Departamento</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($regiones) > 0): ?>

                        <?php while ($region = mysqli_fetch_assoc($regiones)): ?>

                            <tr>

                                <td>
                                    <?php echo $region["id_region"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($region["nombre"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($region["departamento"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($region["descripcion"] ?? ""); ?>
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
                                            name="id_region"
                                            value="<?php echo $region["id_region"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta región?');"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">
                                No hay regiones registradas.
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