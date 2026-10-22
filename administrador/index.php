
<?php

require_once "verificar_admin.php";

$nombreAdmin = $_SESSION["nombre"] ?? "Administrador";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel Administrativo | AgroFuturo</title>

    <link rel="stylesheet" href="estilo_admin.css">

</head>

<body>

<header class="header">

    <div class="logo">
        AgroFuturo
    </div>

    <div class="admin-info">

        <span>
            Hola, <?php echo htmlspecialchars($nombreAdmin); ?>
        </span>

        <a href="../logout.php" class="btn-cerrar">
            Cerrar sesión
        </a>

    </div>

</header>

<div class="contenedor">

    <aside class="sidebar">

        <h3>Administración</h3>

        <a href="index.php" class="activo">
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

        <section class="bienvenida">

            <h1>
                Bienvenido al panel administrativo
            </h1>

            <p>
                Desde aquí puedes administrar la información de AgroFuturo.
            </p>

        </section>

        <h2>
            Administración del sitio
        </h2>

        <div class="tarjetas">

            <a href="usuarios.php" class="tarjeta">

               

                <h3>
                    Usuarios
                </h3>

                <p>
                    Consulta y administra los usuarios registrados en AgroFuturo.
                </p>

                <span>
                    Administrar →
                </span>

            </a>

            <a href="noticias.php" class="tarjeta">

               

                <h3>
                    Noticias
                </h3>

                <p>
                    Administra las noticias y novedades de la plataforma.
                </p>

                <span>
                    Administrar →
                </span>

            </a>

            <a href="cultivos.php" class="tarjeta">

                

                <h3>
                    Cultivos
                </h3>

                <p>
                    Gestiona la información de los cultivos registrados.
                </p>

                <span>
                    Administrar →
                </span>

            </a>

            <a href="calendario.php" class="tarjeta">

                

                <h3>
                    Calendario agrícola
                </h3>

                <p>
                    Administra los períodos de siembra, cosecha y recomendaciones.
                </p>

                <span>
                    Administrar →
                </span>

            </a>

            <a href="fases.php" class="tarjeta">

               

                <h3>
                    Fases fenológicas
                </h3>

                <p>
                    Gestiona las diferentes fases de desarrollo de los cultivos.
                </p>

                <span>
                    Administrar →
                </span>

            </a>

            <a href="mensajes.php" class="tarjeta">

                

                <h3>
                    Mensajes
                </h3>

                <p>
                    Consulta los mensajes enviados mediante el formulario de contacto.
                </p>

                <span>
                    Ver mensajes →
                </span>

            </a>

        </div>

        <section class="informacion">

            <h3>
                Panel de administración
            </h3>

            <p>
                Desde este panel puedes gestionar los contenidos,
                usuarios y diferentes módulos de la plataforma AgroFuturo.
            </p>

        </section>

    </main>

</div>

</body>

</html>

