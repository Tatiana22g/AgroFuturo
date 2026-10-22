

<?php

session_start();

require_once "conexion.php";

$usuarioLogueado = isset($_SESSION["id_usuario"]);
$nombreUsuario = $_SESSION["nombre"] ?? "";
$esAdmin = $usuarioLogueado &&
    ($_SESSION["rol"] ?? "") === "Administrador";

function escapar($valor)
{
    return htmlspecialchars(
        (string) ($valor ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

function normalizarTexto($texto)
{
    $texto = mb_strtolower(trim((string) $texto), "UTF-8");

    return strtr($texto, [
        "á" => "a",
        "é" => "e",
        "í" => "i",
        "ó" => "o",
        "ú" => "u",
        "ü" => "u",
        "ñ" => "n"
    ]);
}

function obtenerCategoria($tipo)
{
    $tipo = normalizarTexto($tipo);

    $categorias = [
        "cereal" => "cereales",
        "cereales" => "cereales",

        "hortaliza" => "hortalizas",
        "hortalizas" => "hortalizas",

        "fruta" => "frutas",
        "frutas" => "frutas",

        "leguminosa" => "leguminosas",
        "leguminosas" => "leguminosas",

        "aromatica" => "aromaticas",
        "aromaticas" => "aromaticas",

        "tuberculo" => "tuberculos",
        "tuberculos" => "tuberculos",
        "raiz" => "tuberculos",
        "raices" => "tuberculos",
        "tuberculos y raices" => "tuberculos"
    ];

    return $categorias[$tipo] ?? "otros";
}

$sql = "SELECT
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
        ORDER BY c.nombre ASC";

$resultado = mysqli_query($conexion, $sql);

$errorConsulta = !$resultado;
$cultivos = [];

if ($resultado) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $cultivos[] = $fila;
    }
}

$totalCultivos = count($cultivos);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="Consulta cultivos agrícolas, sus cuidados, períodos de siembra y cosecha con AgroFuturo."
    >

    <title>Cultivos | AgroFuturo</title>

    <link rel="stylesheet" href="styles.css">

    <style>
        .crop-search {
            width: 100%;
            max-width: 650px;
            margin: 30px auto;
            text-align: left;
        }

        .crop-search label {
            display: block;
            margin-bottom: 10px;
            color: #17352d;
            font-weight: 700;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px;
            border: 1px solid #d7e2d5;
            border-radius: 12px;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-box:focus-within {
            border-color: #4d8f38;
            box-shadow: 0 0 0 3px rgba(77, 143, 56, 0.12);
        }

        .search-box input {
            width: 100%;
            min-width: 0;
            padding: 15px 0;
            border: none;
            outline: none;
            background: transparent;
            color: #17352d;
            font: inherit;
        }

        .search-box input::placeholder {
            color: #89958b;
        }

        .search-icon {
            color: #4d8f38;
            font-size: 25px;
        }

        .crop-search-hint {
            margin-top: 9px;
            color: #718075;
            font-size: 13px;
        }

        .crop-count {
            margin: 24px 0;
            color: #4d8f38;
            font-size: 14px;
            font-weight: 600;
        }

        .crop-card[hidden],
        #noResults[hidden] {
            display: none !important;
        }

        .crop-icon {
            display: inline-block;
            max-width: 100%;
            overflow-wrap: anywhere;
        }

        .no-results {
            margin: 35px 0;
            text-align: center;
            color: #718075;
        }

        @media (max-width: 600px) {
            .crop-search {
                margin: 22px auto;
            }

            .crop-categories {
                gap: 8px;
            }
        }
    </style>

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
        aria-expanded="false"
    >
        ☰
    </button>

    <nav class="nav">

        <a href="index_principal.php">Inicio</a>

        <a href="quienessomos.php">Quiénes Somos</a>

        <a href="noticias.php">Novedades</a>

        <a href="calendario.php">Calendario</a>

        <a href="clima.php">Clima</a>

        <a href="cultivos.php" class="active">Cultivos</a>

        <a href="foro.php">Foro</a>

        <a href="ciclo_planta.php">Ciclo planta</a>

        <a href="contacto.php">Contáctanos</a>

        <?php if ($esAdmin): ?>

            <span class="usuario-saludo">
                Hola, <?php echo escapar($nombreUsuario); ?>
            </span>

            <a href="administrador/index.php" class="login-btn">
                Panel Admin
            </a>

            <a href="logout.php" class="login-btn">
                Cerrar Sesión
            </a>

        <?php elseif ($usuarioLogueado): ?>

            <span class="usuario-saludo">
                Hola, <?php echo escapar($nombreUsuario); ?>
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

        <h1>Cultivos</h1>

        <span>
            Conoce, clasifica y planifica mejor tus cultivos.
        </span>

    </div>

</section>


<main class="page-content">

    <section class="crops-section">

        <small>CONOCE TUS CULTIVOS</small>

        <h2>Categorías de cultivos</h2>

        <p class="section-description">
            Explora diferentes cultivos agrícolas, conoce sus características,
            consulta sus cuidados y encuentra información sobre su siembra
            y cosecha.
        </p>


        <div class="crop-search">

            <label for="searchCultivo">
                Buscar un cultivo
            </label>

            <div class="search-box">

                <input
                    type="search"
                    id="searchCultivo"
                    placeholder="Ejemplo: tomate, café, papa..."
                    autocomplete="off"
                    aria-label="Buscar cultivos por nombre, descripción o región"
                >

                <span class="search-icon" aria-hidden="true">
                    ⌕
                </span>

            </div>

            <p class="crop-search-hint">
                Escribe el nombre, el tipo de cultivo o la región que deseas consultar.
            </p>

        </div>


        <div class="crop-categories">

            <button
                type="button"
                class="category-btn active"
                data-category="todos"
                aria-pressed="true"
            >
                Todos
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="cereales"
                aria-pressed="false"
            >
                Cereales
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="hortalizas"
                aria-pressed="false"
            >
                Hortalizas
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="frutas"
                aria-pressed="false"
            >
                Frutas
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="leguminosas"
                aria-pressed="false"
            >
                Leguminosas
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="aromaticas"
                aria-pressed="false"
            >
                Aromáticas
            </button>

            <button
                type="button"
                class="category-btn"
                data-category="tuberculos"
                aria-pressed="false"
            >
                Tubérculos y raíces
            </button>

        </div>


        <p
            id="cropCount"
            class="crop-count"
            aria-live="polite"
        >
            <?php
            echo $totalCultivos === 1
                ? "Hay 1 cultivo registrado."
                : "Hay " . $totalCultivos . " cultivos registrados.";
            ?>
        </p>


        <div class="crop-grid" id="cropGrid">

            <?php if ($errorConsulta): ?>

                <article class="crop-card">

                    <h3>No se pudieron cargar los cultivos</h3>

                    <p>
                        Verifica la conexión con la base de datos y la estructura
                        de las tablas cultivos y regiones.
                    </p>

                </article>

            <?php elseif ($totalCultivos > 0): ?>

                <?php foreach ($cultivos as $cultivo): ?>

                    <?php

                    $categoria = obtenerCategoria(
                        $cultivo["tipo"] ?? ""
                    );

                    $textoBusqueda = implode(" ", [
                        $cultivo["nombre"] ?? "",
                        $cultivo["descripcion"] ?? "",
                        $cultivo["tipo"] ?? "",
                        $cultivo["region"] ?? "",
                        $cultivo["departamento"] ?? "",
                        $cultivo["siembra"] ?? "",
                        $cultivo["cosecha"] ?? ""
                    ]);

                    ?>

                    <article
                        class="crop-card"
                        data-category="<?php echo escapar($categoria); ?>"
                        data-search="<?php echo escapar($textoBusqueda); ?>"
                    >

                        <span class="crop-icon">
                            <?php echo escapar($cultivo["nombre"]); ?>
                        </span>

                        <h3>
                            <?php echo escapar($cultivo["nombre"]); ?>
                        </h3>

                        <p>
                            <?php
                            echo !empty($cultivo["descripcion"])
                                ? escapar($cultivo["descripcion"])
                                : "No hay una descripción disponible para este cultivo.";
                            ?>
                        </p>


                        <div class="crop-info">

                            <div>
                                <strong>Tipo</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["tipo"])
                                        ? escapar($cultivo["tipo"])
                                        : "No especificado";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Siembra</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["siembra"])
                                        ? escapar($cultivo["siembra"])
                                        : "No especificada";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Duración</strong>

                                <span>
                                    <?php
                                    if (
                                        isset($cultivo["tiempo_cosecha"]) &&
                                        $cultivo["tiempo_cosecha"] !== "" &&
                                        $cultivo["tiempo_cosecha"] !== null
                                    ) {
                                        echo escapar($cultivo["tiempo_cosecha"]);
                                        echo " días aproximadamente.";
                                    } else {
                                        echo "No especificada.";
                                    }
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Luz</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["luz"])
                                        ? escapar($cultivo["luz"])
                                        : "No especificada";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Riego</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["riego"])
                                        ? escapar($cultivo["riego"])
                                        : "No especificado";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Suelo</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["suelo"])
                                        ? escapar($cultivo["suelo"])
                                        : "No especificado";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Cuidados</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["cuidados"])
                                        ? escapar($cultivo["cuidados"])
                                        : "No especificados";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Plagas</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["plagas"])
                                        ? escapar($cultivo["plagas"])
                                        : "No especificadas";
                                    ?>
                                </span>
                            </div>


                            <div>
                                <strong>Cosecha</strong>

                                <span>
                                    <?php
                                    echo !empty($cultivo["cosecha"])
                                        ? escapar($cultivo["cosecha"])
                                        : "No especificada";
                                    ?>
                                </span>
                            </div>


                            <?php if (!empty($cultivo["region"])): ?>

                                <div>
                                    <strong>Región</strong>

                                    <span>
                                        <?php
                                        echo escapar($cultivo["region"]);

                                        if (!empty($cultivo["departamento"])) {
                                            echo ", ";
                                            echo escapar($cultivo["departamento"]);
                                        }
                                        ?>
                                    </span>
                                </div>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php else: ?>

                <article class="crop-card">

                    <h3>No hay cultivos registrados</h3>

                    <p>
                        Agrega cultivos a la tabla cultivos de tu base de datos
                        para que aparezcan aquí.
                    </p>

                </article>

            <?php endif; ?>

        </div>


        <p
            id="noResults"
            class="no-results"
            hidden
            aria-live="polite"
        >
            No se encontraron cultivos con esa búsqueda o categoría.
            Intenta cambiar el texto o seleccionar otra categoría.
        </p>

    </section>

</main>


<footer>

    <span>
        © 2026 AgroFuturo - Todos los derechos reservados
    </span>

    <div>
        Síguenos en: &nbsp; ◎ &nbsp; f &nbsp; ▶ &nbsp; ◉
    </div>

    <i>
        Juntos cultivamos el futuro ♡
    </i>

</footer>


<script src="script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchCultivo");
    const categoryButtons = document.querySelectorAll(".category-btn");
    const cropCards = document.querySelectorAll("#cropGrid .crop-card");
    const noResults = document.getElementById("noResults");
    const cropCount = document.getElementById("cropCount");

    if (!searchInput || !cropCount || cropCards.length === 0) {
        return;
    }

    let selectedCategory = "todos";

    function normalizeText(text) {
        return String(text || "")
            .toLocaleLowerCase("es")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();
    }

    function filterCrops() {

        const searchText = normalizeText(searchInput.value);
        let visibleCount = 0;

        cropCards.forEach(function (card) {

            const category = card.dataset.category || "";
            const searchableText = normalizeText(
                (card.dataset.search || "") + " " + card.textContent
            );

            const matchesCategory =
                selectedCategory === "todos" ||
                category === selectedCategory;

            const matchesSearch =
                searchableText.includes(searchText);

            const visible = matchesCategory && matchesSearch;

            card.hidden = !visible;

            if (visible) {
                visibleCount++;
            }

        });

        if (noResults) {
            noResults.hidden = visibleCount !== 0;
        }

        cropCount.textContent = searchText
            ? "Se encontraron " + visibleCount +
              (visibleCount === 1 ? " resultados." : " resultados.")
            : "Se muestran " + visibleCount +
              (visibleCount === 1 ? " cultivo." : " cultivos.");

    }

    categoryButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            selectedCategory = button.dataset.category || "todos";

            categoryButtons.forEach(function (item) {

                const activo = item === button;

                item.classList.toggle("active", activo);
                item.setAttribute("aria-pressed", String(activo));

            });

            filterCrops();

        });

    });

    searchInput.addEventListener("input", filterCrops);

    filterCrops();

});
</script>

</body>

</html>
