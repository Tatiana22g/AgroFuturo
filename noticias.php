



<?php

session_start();



require_once "conexion.php";



$usuarioLogueado = isset($_SESSION["id_usuario"]);

$nombreUsuario = $_SESSION["nombre"] ?? "";

$esAdmin = $usuarioLogueado &&

    ($_SESSION["rol"] ?? "") === "Administrador";



if (empty($_SESSION["token_noticias"])) {

    $_SESSION["token_noticias"] = bin2hex(random_bytes(32));

}



$mensajeError = "";

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    (isset($_POST["eliminar_noticia"]) || isset($_POST["guardar_edicion"]))
) {
    if (!$usuarioLogueado) {
        header("Location: iniciodesesion.php");
        exit;
    }

    $tokenRecibido = $_POST["token_noticias"] ?? "";

    if (!is_string($tokenRecibido) || !hash_equals($_SESSION["token_noticias"], $tokenRecibido)) {
        $mensajeError = "La solicitud no es válida. Recarga la página.";
    } else {
        $idNoticiaAccion = filter_input(INPUT_POST, "id_noticia", FILTER_VALIDATE_INT);
        $idUsuarioActual = (int) $_SESSION["id_usuario"];

        if (!$idNoticiaAccion || $idNoticiaAccion < 1) {
            $mensajeError = "La noticia seleccionada no es válida.";
        } elseif (isset($_POST["eliminar_noticia"])) {
            if ($esAdmin) {
                $sqlAccion = "DELETE FROM noticias WHERE id_noticia = ?";
                $stmtAccion = mysqli_prepare($conexion, $sqlAccion);
                if ($stmtAccion) {
                    mysqli_stmt_bind_param($stmtAccion, "i", $idNoticiaAccion);
                }
            } else {
                $sqlAccion = "DELETE FROM noticias WHERE id_noticia = ? AND id_usuario = ?";
                $stmtAccion = mysqli_prepare($conexion, $sqlAccion);
                if ($stmtAccion) {
                    mysqli_stmt_bind_param($stmtAccion, "ii", $idNoticiaAccion, $idUsuarioActual);
                }
            }

            if (!$stmtAccion) {
                $mensajeError = "No se pudo preparar la eliminación.";
            } elseif (mysqli_stmt_execute($stmtAccion)) {
                $filasEliminadas = mysqli_stmt_affected_rows($stmtAccion);
                mysqli_stmt_close($stmtAccion);
                if ($filasEliminadas > 0) {
                    header("Location: noticias.php?eliminada=1");
                    exit;
                }
                $mensajeError = "Solo puedes eliminar tus propias noticias.";
            } else {
                $mensajeError = "No se pudo eliminar la noticia.";
                mysqli_stmt_close($stmtAccion);
            }
        } else {
            $tituloEditado = trim($_POST["titulo"] ?? "");
            $contenidoEditado = trim($_POST["contenido"] ?? "");

            if ($tituloEditado === "" || $contenidoEditado === "") {
                $mensajeError = "Completa el título y el contenido.";
            } elseif (mb_strlen($tituloEditado) > 200) {
                $mensajeError = "El título no puede superar los 200 caracteres.";
            } elseif (mb_strlen($contenidoEditado) > 10000) {
                $mensajeError = "El contenido no puede superar los 10000 caracteres.";
            } else {
                if ($esAdmin) {
                    $sqlAccion = "UPDATE noticias SET titulo = ?, contenido = ? WHERE id_noticia = ?";
                    $stmtAccion = mysqli_prepare($conexion, $sqlAccion);
                    if ($stmtAccion) {
                        mysqli_stmt_bind_param($stmtAccion, "ssi", $tituloEditado, $contenidoEditado, $idNoticiaAccion);
                    }
                } else {
                    $sqlAccion = "UPDATE noticias SET titulo = ?, contenido = ? WHERE id_noticia = ? AND id_usuario = ?";
                    $stmtAccion = mysqli_prepare($conexion, $sqlAccion);
                    if ($stmtAccion) {
                        mysqli_stmt_bind_param($stmtAccion, "ssii", $tituloEditado, $contenidoEditado, $idNoticiaAccion, $idUsuarioActual);
                    }
                }

                if (!$stmtAccion) {
                    $mensajeError = "No se pudo preparar la modificación.";
                } elseif (mysqli_stmt_execute($stmtAccion)) {
                    mysqli_stmt_close($stmtAccion);
                    header("Location: noticias.php?modificada=1");
                    exit;
                } else {
                    $mensajeError = "No se pudo modificar la noticia.";
                    mysqli_stmt_close($stmtAccion);
                }
            }
        }
    }
}




if (

    $_SERVER["REQUEST_METHOD"] === "POST" &&

    isset($_POST["publicar_noticia"])

) {

    if (!$usuarioLogueado) {

        header("Location: iniciodesesion.php");

        exit;

    }



    $token = $_POST["token_noticias"] ?? "";



    if (

        !is_string($token) ||

        !hash_equals($_SESSION["token_noticias"], $token)

    ) {

        $mensajeError = "La solicitud no es válida. Recarga la página.";

    } else {

        $titulo = trim($_POST["titulo"] ?? "");

        $contenido = trim($_POST["contenido"] ?? "");

        $idUsuario = (int) $_SESSION["id_usuario"];



        if ($titulo === "" || $contenido === "") {

            $mensajeError = "Completa el título y el contenido.";

        } elseif (mb_strlen($titulo) > 200) {

            $mensajeError = "El título no puede superar los 200 caracteres.";

        } elseif (mb_strlen($contenido) > 10000) {

            $mensajeError = "El contenido no puede superar los 10000 caracteres.";

        } else {

            $imagen = "";



            $sqlInsertar = "INSERT INTO noticias

                (titulo, contenido, imagen, fecha_publicacion, id_usuario)

                VALUES (?, ?, ?, NOW(), ?)";



            $stmt = mysqli_prepare($conexion, $sqlInsertar);



            if ($stmt) {

                mysqli_stmt_bind_param(

                    $stmt,

                    "sssi",

                    $titulo,

                    $contenido,

                    $imagen,

                    $idUsuario

                );



                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_close($stmt);

                    unset($_SESSION["token_noticias"]);



                    header("Location: noticias.php?publicado=1");

                    exit;

                } else {

                    $mensajeError = "No se pudo guardar la novedad.";

                    mysqli_stmt_close($stmt);

                }

            } else {

                $mensajeError = "No se pudo preparar el registro de la novedad.";

            }

        }

    }

}



$sql = "SELECT

            n.id_noticia,
            n.id_usuario,

            n.titulo,

            n.contenido,

            n.imagen,

            n.fecha_publicacion,

            u.nombre_completo

        FROM noticias n

        LEFT JOIN usuarios u

            ON n.id_usuario = u.id_usuario

        ORDER BY n.fecha_publicacion DESC";



$noticias = mysqli_query($conexion, $sql);

$errorConsulta = !$noticias;

$noticiaEditar = null;

if ($usuarioLogueado && isset($_GET["editar"])) {
    $idEditar = filter_input(INPUT_GET, "editar", FILTER_VALIDATE_INT);

    if ($idEditar && $idEditar > 0) {
        if ($esAdmin) {
            $sqlEditar = "SELECT id_noticia, id_usuario, titulo, contenido FROM noticias WHERE id_noticia = ?";
            $stmtEditar = mysqli_prepare($conexion, $sqlEditar);
            if ($stmtEditar) {
                mysqli_stmt_bind_param($stmtEditar, "i", $idEditar);
            }
        } else {
            $idUsuarioActual = (int) $_SESSION["id_usuario"];
            $sqlEditar = "SELECT id_noticia, id_usuario, titulo, contenido FROM noticias WHERE id_noticia = ? AND id_usuario = ?";
            $stmtEditar = mysqli_prepare($conexion, $sqlEditar);
            if ($stmtEditar) {
                mysqli_stmt_bind_param($stmtEditar, "ii", $idEditar, $idUsuarioActual);
            }
        }

        if (isset($stmtEditar) && $stmtEditar) {
            mysqli_stmt_execute($stmtEditar);
            $resultadoEditar = mysqli_stmt_get_result($stmtEditar);
            $noticiaEditar = mysqli_fetch_assoc($resultadoEditar) ?: null;
            mysqli_stmt_close($stmtEditar);
        }

        if (!$noticiaEditar) {
            $mensajeError = "No puedes modificar esa noticia o no existe.";
        }
    }
}


?>



<!DOCTYPE html>

<html lang="es">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <title>Novedades | AgroFuturo</title>



    <link rel="stylesheet" href="styles.css">

</head>



<body>



<header class="header">



    <a class="logo" href="index_principal.php">

        <img src="logo.png" alt="Logo AgroFuturo">



        <span>

            <strong>AGRO<span>FUTURO</span></strong>

            <small>Innovación y tecnología para el campo</small>

        </span>

    </a>



    <button

        class="menu-toggle"

        id="menuToggle"

        type="button"

        aria-label="Abrir menú"

    >

        ☰

    </button>



    <nav class="nav">



        <a href="index_principal.php">Inicio</a>

        <a href="quienessomos.php">Quiénes Somos</a>

        <a href="noticias.php" class="active">Novedades</a>

        <a href="calendario.php">Calendario</a>

        <a href="clima.php">Clima</a>

        <a href="cultivos.php">Cultivos</a>

        <a href="foro.php">Foro</a>

        <a href="ciclo_planta.php">Ciclo planta</a>

        <a href="contacto.php">Contáctanos</a>



        <?php if ($esAdmin): ?>



            <span class="usuario-saludo">

                Hola,

                <?php

                echo htmlspecialchars(

                    $nombreUsuario,

                    ENT_QUOTES,

                    "UTF-8"

                );

                ?>

            </span>



            <a href="administrador/index.php" class="login-btn">

                Panel Admin

            </a>



            <a href="logout.php" class="login-btn">

                Cerrar Sesión

            </a>



        <?php elseif ($usuarioLogueado): ?>



            <span class="usuario-saludo">

                Hola,

                <?php

                echo htmlspecialchars(

                    $nombreUsuario,

                    ENT_QUOTES,

                    "UTF-8"

                );

                ?>

            </span>



            <a href="logout.php" class="login-btn">

                Cerrar Sesión

            </a>



        <?php else: ?>



            <a href="iniciodesesion.php" class="login-btn">

                Iniciar Sesión

            </a>



        <?php endif; ?>



    </nav>



</header>



<section class="page-banner">



    <div class="banner-overlay"></div>



    <div class="banner-content">



        <p>AGROFUTURO</p>



        <h1>Novedades</h1>



        <span>

            Las noticias, tendencias y novedades más importantes

            del sector agrícola.

        </span>



    </div>



</section>



<main class="page-content">



    <section class="news-section">



        <small>ACTUALIDAD DEL CAMPO</small>



        <h2>Últimas novedades agrícolas</h2>



        <p class="calendar-description">

            Mantente informado sobre temas relacionados con la agricultura,

            la tecnología, los cultivos y las nuevas prácticas que pueden

            contribuir al desarrollo del campo.

        </p>



        <?php if (

            isset($_GET["publicado"]) &&

            $_GET["publicado"] === "1"

        ): ?>



            <p role="status">

                Tu novedad se publicó correctamente.

            </p>



        <?php endif; ?>



        <?php if (isset($_GET["eliminada"]) && $_GET["eliminada"] === "1"): ?>
            <p role="status">La noticia se eliminó correctamente.</p>
        <?php endif; ?>

        <?php if (isset($_GET["modificada"]) && $_GET["modificada"] === "1"): ?>
            <p role="status">La noticia se modificó correctamente.</p>
        <?php endif; ?>

        <?php if ($mensajeError !== ""): ?>



            <p role="alert">

                <?php

                echo htmlspecialchars(

                    $mensajeError,

                    ENT_QUOTES,

                    "UTF-8"

                );

                ?>

            </p>



        <?php endif; ?>



        <?php if ($usuarioLogueado): ?>



            <section class="news-box">



                <h3>Comparte una novedad agrícola</h3>



                <p>

                    Comparte una noticia, experiencia o consejo

                    con la comunidad AgroFuturo.

                </p>



                <form action="noticias.php" method="POST">



                    <input

                        type="hidden"

                        name="token_noticias"

                        value="<?php

                        echo htmlspecialchars(

                            $_SESSION["token_noticias"],

                            ENT_QUOTES,

                            "UTF-8"

                        );

                        ?>"

                    >



                    <p>

                        <label for="titulo">

                            Título de la novedad

                        </label>

                    </p>



                    <input

                        type="text"

                        id="titulo"

                        name="titulo"

                        maxlength="200"

                        placeholder="Escribe el título de tu novedad"

                        required

                    >



                    <p>

                        <label for="contenido">

                            Contenido de la novedad

                        </label>

                    </p>



                    <textarea

                        id="contenido"

                        name="contenido"

                        rows="6"

                        maxlength="10000"

                        placeholder="Escribe aquí tu noticia o experiencia agrícola..."

                        required

                    ></textarea>



                    <p>

                        <button type="submit" name="publicar_noticia">

                            Publicar novedad

                        </button>

                    </p>



                </form>



            </section>



        <?php else: ?>



            <section class="news-box">



                <h3>¿Quieres compartir una novedad?</h3>



                <p>

                    Inicia sesión para publicar noticias y experiencias

                    agrícolas en AgroFuturo.

                </p>



                <a href="iniciodesesion.php" class="login-btn">

                    Iniciar sesión

                </a>



            </section>



        <?php endif; ?>



        <?php if ($noticiaEditar): ?>
            <section class="news-box">
                <h3>Modificar noticia</h3>
                <form action="noticias.php" method="POST">
                    <input type="hidden" name="token_noticias" value="<?php echo htmlspecialchars($_SESSION["token_noticias"], ENT_QUOTES, "UTF-8"); ?>">
                    <input type="hidden" name="id_noticia" value="<?php echo (int) $noticiaEditar["id_noticia"]; ?>">

                    <p><label for="titulo_edicion">Título de la noticia</label></p>
                    <input type="text" id="titulo_edicion" name="titulo" maxlength="200" value="<?php echo htmlspecialchars($noticiaEditar["titulo"], ENT_QUOTES, "UTF-8"); ?>" required>

                    <p><label for="contenido_edicion">Contenido de la noticia</label></p>
                    <textarea id="contenido_edicion" name="contenido" rows="6" maxlength="10000" required><?php echo htmlspecialchars($noticiaEditar["contenido"], ENT_QUOTES, "UTF-8"); ?></textarea>

                    <p>
                        <button type="submit" name="guardar_edicion" value="1">Guardar cambios</button>
                        <a href="noticias.php">Cancelar</a>
                    </p>
                </form>
            </section>
        <?php endif; ?>

        <div class="news-grid">



            <?php if ($errorConsulta): ?>



                <article class="news-box">

                    <h3>No se pudieron cargar las novedades</h3>

                    <p>

                        Verifica la conexión con la base de datos

                        y la estructura de la tabla noticias.

                    </p>

                </article>



            <?php elseif (mysqli_num_rows($noticias) > 0): ?>



                <?php while ($noticia = mysqli_fetch_assoc($noticias)): ?>



                    <article class="news-box">



                        <?php if (!empty($noticia["imagen"])): ?>



                            <img

                                src="<?php

                                echo htmlspecialchars(

                                    $noticia["imagen"],

                                    ENT_QUOTES,

                                    "UTF-8"

                                );

                                ?>"

                                alt="<?php

                                echo htmlspecialchars(

                                    $noticia["titulo"],

                                    ENT_QUOTES,

                                    "UTF-8"

                                );

                                ?>"

                                loading="lazy"

                            >



                        <?php endif; ?>



                        <small>

                            <?php

                            $fecha = strtotime(

                                $noticia["fecha_publicacion"]

                            );



                            echo $fecha

                                ? date("d/m/Y", $fecha)

                                : "Fecha no disponible";

                            ?>

                        </small>



                        <h3>

                            <?php

                            echo htmlspecialchars(

                                $noticia["titulo"],

                                ENT_QUOTES,

                                "UTF-8"

                            );

                            ?>

                        </h3>



                        <p>

                            <?php

                            echo nl2br(

                                htmlspecialchars(

                                    $noticia["contenido"],

                                    ENT_QUOTES,

                                    "UTF-8"

                                )

                            );

                            ?>

                        </p>



                        <small>

                            Publicado por:

                            <?php

                            echo htmlspecialchars(

                                $noticia["nombre_completo"] ?? "Usuario",

                                ENT_QUOTES,

                                "UTF-8"

                            );

                            ?>

                        </small>

                        <?php
                        $esAutorNoticia = $usuarioLogueado &&
                            (int) $_SESSION["id_usuario"] === (int) $noticia["id_usuario"];
                        if ($esAutorNoticia || $esAdmin):
                        ?>
                            <p>
                                <a href="noticias.php?editar=<?php echo (int) $noticia["id_noticia"]; ?>">Modificar</a>
                            </p>
                            <form action="noticias.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">
                                <input type="hidden" name="token_noticias" value="<?php echo htmlspecialchars($_SESSION["token_noticias"], ENT_QUOTES, "UTF-8"); ?>">
                                <input type="hidden" name="id_noticia" value="<?php echo (int) $noticia["id_noticia"]; ?>">
                                <button type="submit" name="eliminar_noticia" value="1">Eliminar</button>
                            </form>
                        <?php endif; ?>

                    </article>



                <?php endwhile; ?>



            <?php else: ?>



                <article class="news-box">

                    <h3>No hay novedades publicadas</h3>

                    <p>

                        Actualmente no hay novedades disponibles.

                        ¡Inicia sesión y comparte la primera!

                    </p>

                </article>



            <?php endif; ?>



        </div>



    </section>



    <section class="calendar-section">



        <small>INFORMACIÓN DESTACADA</small>



        <h2>Temas importantes para el productor</h2>



        <div class="calendar-grid">



            <div class="calendar-card">



                <span class="moon-icon"></span>



                <h3>Planificación de cultivos</h3>



                <p>

                    Antes de sembrar es importante conocer el cultivo,

                    revisar las condiciones del terreno y establecer

                    fechas aproximadas para cada etapa.

                </p>



            </div>



            <div class="calendar-card">



                <span class="moon-icon"></span>



                <h3>Clima y agricultura</h3>



                <p>

                    La temperatura, las lluvias, la humedad y otros

                    factores climáticos pueden influir en el crecimiento

                    y desarrollo de las plantas.

                </p>



            </div>



            <div class="calendar-card">



                <span class="moon-icon"></span>



                <h3>Innovación tecnológica</h3>



                <p>

                    Las plataformas digitales permiten consultar

                    información agrícola, registrar datos y organizar

                    diferentes actividades del campo.

                </p>



            </div>



            <div class="calendar-card">



                <span class="moon-icon"></span>



                <h3>Fases de crecimiento</h3>



                <p>

                    Conocer las diferentes etapas de una planta ayuda

                    a identificar cuándo necesita agua, nutrientes,

                    luz y otros cuidados.

                </p>



            </div>



        </div>



    </section>



    <section class="news-section">



        <small>CONOCE MÁS</small>



        <h2>Información para aprender y aplicar</h2>



        <div class="news-grid">



            <article class="news-box">



                <small>AGRICULTURA SOSTENIBLE</small>



                <h3>Siembra sostenible</h3>



                <p>

                    La agricultura sostenible busca producir alimentos

                    aprovechando responsablemente los recursos naturales.

                    Algunas prácticas incluyen el cuidado del suelo,

                    el ahorro de agua y el aprovechamiento de residuos

                    orgánicos.

                </p>



            </article>



            <article class="news-box">



                <small>TECNOLOGÍA AGRÍCOLA</small>



                <h3>Tecnología aplicada al campo</h3>



                <p>

                    Las plataformas web permiten consultar información

                    sobre cultivos, clima, calendarios y recomendaciones

                    de manera organizada.

                </p>



            </article>



            <article class="news-box">



                <small>MEDIO AMBIENTE</small>



                <h3>Prácticas agrícolas sostenibles</h3>



                <p>

                    Reducir desperdicios, cuidar el agua y conservar

                    la calidad del suelo son acciones que contribuyen

                    a una agricultura más responsable.

                </p>



            </article>



            <article class="news-box">



                <small>PLANIFICACIÓN</small>



                <h3>Calendario agrícola</h3>



                <p>

                    Un calendario agrícola permite organizar actividades

                    relacionadas con los cultivos, desde la siembra

                    hasta la cosecha.

                </p>



            </article>



            <article class="news-box">



                <small>RECURSOS NATURALES</small>



                <h3>Cuidado y conservación del suelo</h3>



                <p>

                    Observar la humedad, el drenaje y las características

                    del suelo ayuda a seleccionar un cultivo y planificar

                    sus cuidados.

                </p>



            </article>



            <article class="news-box">



                <small>MANEJO DEL AGUA</small>



                <h3>Uso responsable del agua</h3>



                <p>

                    El riego debe adaptarse a las necesidades de cada

                    cultivo y a las condiciones del clima, evitando

                    tanto el exceso como la falta de humedad.

                </p>



            </article>



        </div>



    </section>



</main>



<footer>



    <span>

        © 2026 AgroFuturo - Todos los derechos reservados

    </span>



    <div>

        Síguenos en: &nbsp; ◎ &nbsp; f &nbsp; ▶ &nbsp; ◉

    </div>



    <i>Juntos cultivamos el futuro ♡</i>



</footer>



<script src="script.js"></script>



</body>

</html>
