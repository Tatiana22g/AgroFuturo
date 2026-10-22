<?php

require_once "verificar_admin.php";
require_once "../conexion.php";

$mensaje = "";

if (isset($_GET["cambiar_estado"])) {

    $id_usuario = intval($_GET["cambiar_estado"]);

    $sql_estado = "UPDATE usuarios
                   SET activo = IF(activo = 1, 0, 1)
                   WHERE id_usuario = ?";

    $stmt_estado = mysqli_prepare($conexion, $sql_estado);
    mysqli_stmt_bind_param($stmt_estado, "i", $id_usuario);
    mysqli_stmt_execute($stmt_estado);
    mysqli_stmt_close($stmt_estado);

    header("Location: usuarios.php");
    exit();
}

$sql = "SELECT
            u.id_usuario,
            u.usuario,
            u.correo,
            u.nombre_completo,
            u.activo,
            u.fecha_registro,
            r.nombre AS rol
        FROM usuarios u
        INNER JOIN roles r
            ON u.id_rol = r.id_rol
        ORDER BY u.id_usuario DESC";

$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuarios | AgroFuturo</title>

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

        <a href="usuarios.php" class="activo">
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

        <a href="mensajes.php">
            Mensajes
        </a>

        <a href="../index_principal.php">
            Ver página web
        </a>

    </aside>

    <main class="contenido">

        <h2>Usuarios registrados</h2>

        <p style="margin-bottom: 25px; color: #748174;">
            Consulta los usuarios registrados en AgroFuturo y administra su estado.
        </p>

        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha de registro</th>
                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($resultado) > 0): ?>

                        <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>

                            <tr>

                                <td>
                                    <?php echo $usuario["id_usuario"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($usuario["usuario"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($usuario["correo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($usuario["nombre_completo"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($usuario["rol"]); ?>
                                </td>

                                <td>

                                    <?php if ($usuario["activo"] == 1): ?>

                                        <span style="color: #397b30; font-weight: 700;">
                                            Activo
                                        </span>

                                    <?php else: ?>

                                        <span style="color: #a33b3b; font-weight: 700;">
                                            Inactivo
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($usuario["fecha_registro"]); ?>
                                </td>

                                <td>

                                    <a
                                        href="usuarios.php?cambiar_estado=<?php echo $usuario["id_usuario"]; ?>"
                                        class="btn"
                                    >
                                        <?php echo $usuario["activo"] == 1 ? "Desactivar" : "Activar"; ?>
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="8">
                                No hay usuarios registrados.
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