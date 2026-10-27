-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 19:35:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agrofuturo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_agricola`
--

CREATE TABLE `calendario_agricola` (
  `id_calendario` int(11) NOT NULL,
  `id_cultivo` int(11) NOT NULL,
  `mes_siembra` varchar(30) DEFAULT NULL,
  `mes_cosecha` varchar(30) DEFAULT NULL,
  `recomendacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calendario_agricola`
--

INSERT INTO `calendario_agricola` (`id_calendario`, `id_cultivo`, `mes_siembra`, `mes_cosecha`, `recomendacion`) VALUES
(1, 1, 'Enero - Marzo', 'Abril - Junio', 'Mantener buena humedad y controlar plagas.'),
(2, 2, 'Febrero - Abril', 'Marzo - Mayo', 'Evitar exceso de agua.'),
(3, 3, 'Marzo - Mayo', 'Julio - Septiembre', 'Revisar humedad y desarrollo de las plantas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clima`
--

CREATE TABLE `clima` (
  `id_clima` int(11) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `temperatura` decimal(5,2) DEFAULT NULL,
  `humedad` decimal(5,2) DEFAULT NULL,
  `viento` decimal(5,2) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `consejo` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_publicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cultivos`
--

CREATE TABLE `cultivos` (
  `id_cultivo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `tiempo_cosecha` int(11) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `siembra` text DEFAULT NULL,
  `luz` varchar(150) DEFAULT NULL,
  `riego` text DEFAULT NULL,
  `suelo` text DEFAULT NULL,
  `cuidados` text DEFAULT NULL,
  `plagas` text DEFAULT NULL,
  `cosecha` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cultivos`
--

INSERT INTO `cultivos` (`id_cultivo`, `nombre`, `descripcion`, `tipo`, `tiempo_cosecha`, `id_region`, `siembra`, `luz`, `riego`, `suelo`, `cuidados`, `plagas`, `cosecha`) VALUES
(1, 'Tomate', 'Cultivo utilizado para producción de alimentos.', 'Hortaliza', 90, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Lechuga', 'Hortaliza de ciclo corto.', 'Hortaliza', 45, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Maíz', 'Cultivo de importancia alimentaria y agrícola.', 'Cereal', 120, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Arroz', 'Cereal utilizado en la alimentación.', 'Cereales', 120, NULL, 'Según la temporada y la disponibilidad de agua', 'Sol directo', 'Abundante y controlado', 'Arcilloso o franco', 'Controlar malezas y humedad', 'Chinches y barrenadores', 'Cuando el grano madura'),
(5, 'Avena', 'Cereal empleado en alimentación humana y animal.', 'Cereales', 120, NULL, 'Según variedad y clima', 'Sol directo', 'Moderado', 'Franco bien drenado', 'Controlar malezas', 'Pulgones y royas', 'Cuando los granos maduran'),
(6, 'Cebada', 'Cereal utilizado para alimentos y otros productos.', 'Cereales', 120, NULL, 'Según la región', 'Sol directo', 'Moderado', 'Franco bien drenado', 'Vigilar enfermedades', 'Pulgones y royas', 'Al madurar las espigas'),
(7, 'Espinaca', 'Hortaliza de hojas verdes comestibles.', 'Hortalizas', 50, NULL, 'Siembra directa', 'Sol suave o parcial', 'Frecuente sin encharcar', 'Fértil y húmedo', 'Mantener la humedad', 'Minadores y pulgones', 'Cortar hojas desarrolladas'),
(8, 'Pepino', 'Hortaliza de fruto alargado.', 'Hortalizas', 65, NULL, 'En suelo cálido', 'Sol directo', 'Regular', 'Fértil y drenado', 'Guiar las plantas cuando sea necesario', 'Mosca blanca y pulgones', 'Cuando el fruto se desarrolla'),
(9, 'Pimentón', 'Hortaliza de fruto utilizada en diversas preparaciones.', 'Hortalizas', 110, NULL, 'En semillero y trasplante', 'Sol directo', 'Regular', 'Fértil y bien drenado', 'Revisar hojas y frutos', 'Trips y mosca blanca', 'Cuando el fruto madura'),
(10, 'Berenjena', 'Hortaliza de fruto que requiere temperaturas cálidas.', 'Hortalizas', 120, NULL, 'En semillero y trasplante', 'Sol directo', 'Regular', 'Fértil y drenado', 'Controlar malezas', 'Ácaros y mosca blanca', 'Cuando el fruto está desarrollado'),
(11, 'Brócoli', 'Hortaliza de flores agrupadas en una cabeza.', 'Hortalizas', 90, NULL, 'Según variedad', 'Sol directo', 'Regular', 'Fértil y drenado', 'Vigilar hojas y humedad', 'Orugas y pulgones', 'Antes de abrirse las flores'),
(12, 'Coliflor', 'Hortaliza de cabeza compacta comestible.', 'Hortalizas', 100, NULL, 'Según variedad', 'Sol directo', 'Regular', 'Fértil y húmedo', 'Controlar malezas', 'Orugas y pulgones', 'Cuando la cabeza está compacta'),
(13, 'Sandía', 'Fruta de pulpa jugosa y dulce.', 'Frutas', 100, NULL, 'En suelo cálido', 'Sol directo', 'Regular', 'Arenoso y drenado', 'Dejar espacio para las guías', 'Pulgones y mosca blanca', 'Al alcanzar la madurez'),
(14, 'Melón', 'Fruta dulce cultivada en climas cálidos.', 'Frutas', 100, NULL, 'En suelo cálido', 'Sol directo', 'Regular', 'Franco y drenado', 'Evitar exceso de humedad', 'Pulgones y mosca blanca', 'Cuando presenta señales de madurez'),
(15, 'Papaya', 'Fruta tropical de crecimiento rápido.', 'Frutas', 270, NULL, 'En sitio cálido', 'Sol directo', 'Regular sin encharcar', 'Fértil y drenado', 'Proteger del viento', 'Ácaros y mosca blanca', 'Cuando cambia de color'),
(16, 'Maracuyá', 'Fruta tropical de planta trepadora.', 'Frutas', 240, NULL, 'En sitio cálido', 'Sol directo', 'Regular', 'Fértil y drenado', 'Instalar soporte', 'Ácaros y mosca de la fruta', 'Cuando el fruto madura'),
(17, 'Arveja', 'Leguminosa de semillas comestibles.', 'Leguminosas', 100, NULL, 'Según clima y variedad', 'Sol directo', 'Regular', 'Fértil y drenado', 'Instalar soporte si se necesita', 'Pulgones y minadores', 'Cuando las vainas se desarrollan'),
(18, 'Cilantro', 'Planta aromática utilizada como condimento.', 'Aromáticas', 45, NULL, 'Siembra directa', 'Sol suave o directo', 'Frecuente y moderado', 'Fértil y drenado', 'Realizar siembras escalonadas', 'Pulgones y minadores', 'Cortar hojas desarrolladas'),
(19, 'Chirimoya', 'Fruta de pulpa cremosa.', 'Frutas', 1095, NULL, 'Por injerto o semilla', 'Sol directo o parcial', 'Regular', 'Fértil y drenado', 'Manejar polinización según zona', 'Cochinillas', 'Cuando madura'),
(20, 'Borojó', 'Fruta tropical utilizada en bebidas.', 'Frutas', 1460, NULL, 'Por semilla o plántula', 'Sol parcial', 'Regular', 'Húmedo y rico en materia orgánica', 'Mantener humedad sin encharcar', 'Plagas locales y hongos', 'Cuando el fruto madura'),
(21, 'Arazá', 'Fruta amazónica de sabor ácido.', 'Frutas', 730, NULL, 'Por semilla o injerto', 'Sol directo o parcial', 'Regular', 'Fértil y húmedo', 'Proteger plantas jóvenes', 'Mosca de la fruta', 'Cuando el fruto se torna amarillo'),
(22, 'Camu camu', 'Fruta amazónica rica en vitamina C.', 'Frutas', 1095, NULL, 'Por semilla o plántula', 'Sol directo o parcial', 'Abundante según ambiente', 'Húmedo', 'Adaptar cultivo a condiciones tropicales húmedas', 'Plagas locales', 'Cuando el fruto cambia de color'),
(23, 'Fríjol cargamanto', 'Leguminosa de grano utilizada en platos tradicionales.', 'Leguminosas', 100, NULL, 'Según región y variedad', 'Sol directo', 'Regular', 'Fértil y drenado', 'Tutorar si es trepador', 'Mosca blanca y crisomélidos', 'Cuando las vainas maduran'),
(24, 'Fríjol bola roja', 'Variedad de fríjol de grano rojo.', 'Leguminosas', 90, NULL, 'Siembra directa', 'Sol directo', 'Regular', 'Franco bien drenado', 'Controlar malezas', 'Pulgones y hongos', 'Cuando las vainas maduran'),
(25, 'Fríjol negro', 'Leguminosa de semillas negras comestibles.', 'Leguminosas', 90, NULL, 'Siembra directa', 'Sol directo', 'Regular', 'Fértil y drenado', 'Evitar encharcamientos', 'Mosca blanca y roya', 'Cuando las vainas se secan'),
(26, 'Fríjol caupí', 'Leguminosa adaptada a condiciones cálidas.', 'Leguminosas', 75, NULL, 'En clima cálido', 'Sol directo', 'Moderado', 'Ligero y drenado', 'Controlar malezas tempranas', 'Pulgones y gorgojos', 'Cuando las vainas maduran'),
(27, 'Lenteja', 'Leguminosa de semillas pequeñas.', 'Leguminosas', 110, NULL, 'Según clima', 'Sol directo', 'Moderado', 'Franco bien drenado', 'Evitar exceso de humedad', 'Pulgones y hongos', 'Cuando las vainas se secan'),
(28, 'Soya', 'Leguminosa de uso alimentario e industrial.', 'Leguminosas', 120, NULL, 'En temporada cálida', 'Sol directo', 'Regular', 'Fértil y drenado', 'Inocular semilla cuando corresponda', 'Chinches y orugas', 'Cuando las vainas maduran'),
(29, 'Haba', 'Leguminosa de clima fresco.', 'Leguminosas', 150, NULL, 'Siembra directa', 'Sol directo', 'Regular', 'Fértil y drenado', 'Tutorar si es necesario', 'Pulgones y roya', 'Cuando las vainas se desarrollan'),
(30, 'Guandú', 'Leguminosa tropical usada en alimentación y sistemas agroforestales.', 'Leguminosas', 180, NULL, 'En clima cálido', 'Sol directo', 'Moderado', 'Bien drenado', 'Podar y controlar malezas', 'Pulgones y barrenadores', 'Cuando las vainas maduran'),
(31, 'Chícharo', 'Leguminosa de semillas verdes o secas.', 'Leguminosas', 90, NULL, 'En clima fresco', 'Sol directo', 'Regular', 'Fértil y drenado', 'Instalar soporte en variedades trepadoras', 'Pulgones y mildiu', 'Cuando las vainas alcanzan madurez'),
(32, 'Altramuz', 'Leguminosa de semillas utilizadas en alimentación tras procesamiento adecuado.', 'Leguminosas', 150, NULL, 'Según variedad', 'Sol directo', 'Moderado', 'Bien drenado', 'Usar variedades aptas para consumo', 'Pulgones y hongos', 'Cuando las vainas maduran'),
(33, 'Caupí morado', 'Variedad de caupí adaptada a climas cálidos.', 'Leguminosas', 80, NULL, 'Siembra directa', 'Sol directo', 'Moderado', 'Ligero y drenado', 'Controlar malezas', 'Pulgones y gorgojos', 'Cuando las vainas maduran'),
(34, 'Fríjol mungo', 'Leguminosa de grano pequeño.', 'Leguminosas', 75, NULL, 'En clima cálido', 'Sol directo', 'Moderado', 'Fértil y drenado', 'Evitar encharcamiento', 'Mosca blanca y pulgones', 'Cuando las vainas maduran'),
(35, 'Fríjol adzuki', 'Leguminosa de grano pequeño de uso alimentario.', 'Leguminosas', 100, NULL, 'Según clima', 'Sol directo', 'Regular', 'Franco bien drenado', 'Controlar malezas', 'Pulgones y gorgojos', 'Cuando las vainas maduran'),
(36, 'Algarrobo leguminoso', 'Árbol leguminoso usado en sistemas agroforestales según especie.', 'Leguminosas', 1095, NULL, 'Por semilla o plántula', 'Sol directo', 'Moderado al establecerse', 'Drenado', 'Proteger plantas jóvenes', 'Plagas locales', 'Según especie y uso'),
(37, 'Canavalia', 'Leguminosa usada como cobertura y abono verde.', 'Leguminosas', 120, NULL, 'Siembra directa', 'Sol directo', 'Moderado', 'Diversos suelos bien drenados', 'Manejar como cobertura; no consumir sin identificación adecuada', 'Plagas locales', 'Cortar como abono verde o cosechar según especie'),
(38, 'Albahaca', 'Aromática de hojas fragantes para cocina.', 'Aromáticas', 60, NULL, 'Siembra directa o semillero', 'Sol directo o parcial', 'Regular', 'Fértil y drenado', 'Pinzar brotes para estimular ramificación', 'Pulgones y mosca blanca', 'Cortar tallos antes de floración plena'),
(39, 'Perejil', 'Aromática de hojas usadas como condimento.', 'Aromáticas', 80, NULL, 'Siembra directa', 'Sol parcial o directo suave', 'Regular', 'Fértil y drenado', 'Mantener humedad durante germinación', 'Pulgones y minadores', 'Cortar hojas externas'),
(40, 'Romero', 'Arbusto aromático de hojas resistentes.', 'Aromáticas', 120, NULL, 'Por esquejes o plántulas', 'Sol directo', 'Bajo a moderado', 'Ligero y drenado', 'Evitar exceso de agua', 'Cochinillas y ácaros', 'Cortar ramas tiernas'),
(41, 'Tomillo', 'Planta aromática de hojas pequeñas.', 'Aromáticas', 90, NULL, 'Por semilla o esqueje', 'Sol directo', 'Bajo a moderado', 'Ligero y drenado', 'Podar ligeramente', 'Pulgones', 'Cortar tallos desarrollados'),
(42, 'Menta', 'Aromática de hojas refrescantes.', 'Aromáticas', 60, NULL, 'Por esquejes o división', 'Sol parcial', 'Frecuente', 'Húmedo y fértil', 'Controlar su expansión', 'Pulgones y ácaros', 'Cortar hojas y tallos'),
(43, 'Hierbabuena', 'Aromática usada en bebidas e infusiones.', 'Aromáticas', 60, NULL, 'Por división o esquejes', 'Sol parcial', 'Frecuente', 'Húmedo y fértil', 'Controlar crecimiento invasivo', 'Pulgones', 'Cortar brotes desarrollados'),
(44, 'Toronjil', 'Planta aromática de aroma cítrico.', 'Aromáticas', 70, NULL, 'Por semilla o división', 'Sol parcial', 'Regular', 'Fértil y drenado', 'Recortar para estimular brotes', 'Pulgones', 'Cosechar hojas antes de floración'),
(45, 'Limoncillo', 'Planta aromática de hojas con aroma a limón.', 'Aromáticas', 120, NULL, 'Por división de macollas', 'Sol directo', 'Regular', 'Fértil y drenado', 'Retirar hojas secas', 'Plagas ocasionales', 'Cortar hojas desarrolladas'),
(46, 'Cebollín', 'Aromática de hojas finas con sabor a cebolla.', 'Aromáticas', 60, NULL, 'Por semilla o división', 'Sol directo o parcial', 'Regular', 'Fértil y drenado', 'Cortar sin dañar la base', 'Trips', 'Cortar hojas cuando crecen'),
(47, 'Eneldo', 'Aromática cuyas hojas y semillas se usan como condimento.', 'Aromáticas', 90, NULL, 'Siembra directa', 'Sol directo', 'Regular', 'Ligero y drenado', 'Sembrar en sitio definitivo', 'Pulgones', 'Cortar hojas o recolectar semillas maduras'),
(48, 'Hinojo', 'Planta aromática de hojas y semillas anisadas.', 'Aromáticas', 100, NULL, 'Siembra directa', 'Sol directo', 'Regular', 'Fértil y drenado', 'Evitar trasplantes bruscos', 'Pulgones', 'Cosechar hojas, semillas o bulbo según variedad'),
(49, 'Salvia', 'Planta aromática de hojas perfumadas.', 'Aromáticas', 100, NULL, 'Por semilla o esqueje', 'Sol directo', 'Moderado', 'Bien drenado', 'Podar después de floración', 'Cochinillas', 'Cortar hojas desarrolladas'),
(50, 'Orégano orejón', 'Aromática de hojas carnosas usada como condimento.', 'Aromáticas', 90, NULL, 'Por esquejes', 'Sol directo o parcial', 'Moderado', 'Ligero y drenado', 'Evitar encharcamiento', 'Cochinillas', 'Cortar hojas y tallos'),
(51, 'Laurel', 'Árbol o arbusto de hojas usadas como condimento.', 'Aromáticas', 730, NULL, 'Por esqueje o plántula', 'Sol o semisombra', 'Moderado', 'Fértil y drenado', 'Podar para dar forma', 'Cochinillas', 'Recolectar hojas maduras'),
(52, 'Estragón', 'Aromática utilizada en cocina.', 'Aromáticas', 120, NULL, 'Por división o esqueje según variedad', 'Sol directo o parcial', 'Moderado', 'Bien drenado', 'Dividir matas periódicamente', 'Pulgones', 'Cortar hojas desarrolladas'),
(53, 'Mejorana', 'Aromática de sabor suave emparentada con el orégano.', 'Aromáticas', 90, NULL, 'Por semilla o esqueje', 'Sol directo', 'Moderado', 'Ligero y drenado', 'Evitar exceso de agua', 'Pulgones', 'Cosechar tallos antes de floración'),
(54, 'Cúrcuma', 'Planta cuyo rizoma se usa como especia.', 'Aromáticas', 270, NULL, 'Por rizomas', 'Sol parcial', 'Regular', 'Fértil y húmedo, drenado', 'Mantener humedad y acolchado', 'Pudriciones de rizoma', 'Cuando el follaje se seca'),
(55, 'Jengibre', 'Planta cuyo rizoma se usa como especia.', 'Aromáticas', 270, NULL, 'Por rizomas sanos', 'Sol parcial', 'Regular', 'Fértil y suelto', 'Evitar encharcamientos', 'Pudriciones y nematodos', 'Cuando el follaje amarillea'),
(56, 'Vainilla', 'Orquídea trepadora cultivada por sus vainas aromáticas.', 'Aromáticas', 1095, NULL, 'Por esquejes', 'Sombra parcial', 'Regular y humedad ambiental', 'Rico en materia orgánica y drenado', 'Proveer soporte y polinización cuando se requiera', 'Hongos y cochinillas', 'Recolectar vainas maduras según manejo'),
(57, 'Cidrón', 'Planta aromática de aroma cítrico usada en infusiones.', 'Aromáticas', 120, NULL, 'Por esquejes o plántula', 'Sol directo o parcial', 'Regular', 'Fértil y drenado', 'Podar para mantener forma', 'Pulgones', 'Cortar hojas desarrolladas'),
(58, 'Papa criolla', 'Tubérculo andino de ciclo relativamente corto.', 'Tubérculos y raíces', 120, NULL, 'Por tubérculos-semilla', 'Sol directo', 'Regular', 'Suelto y bien drenado', 'Aporcar y usar semilla sana', 'Polilla y gota', 'Cuando el follaje madura'),
(59, 'Papa pastusa', 'Variedad de papa usada en múltiples preparaciones.', 'Tubérculos y raíces', 150, NULL, 'Por tubérculos-semilla', 'Sol directo', 'Regular', 'Franco, suelto y drenado', 'Aporcar y vigilar enfermedades', 'Gota y polilla guatemalteca', 'Cuando el follaje se seca'),
(60, 'Papa sabanera', 'Variedad de papa de uso culinario.', 'Tubérculos y raíces', 150, NULL, 'Por tubérculos-semilla', 'Sol directo', 'Regular', 'Suelto y fértil', 'Aporcar y controlar malezas', 'Gota y polilla', 'Cuando los tubérculos maduran'),
(61, 'Batata', 'Raíz tuberosa de pulpa dulce.', 'Tubérculos y raíces', 120, NULL, 'Por esquejes o guías', 'Sol directo', 'Regular', 'Suelto y drenado', 'Controlar malezas al inicio', 'Gorgojos y gusanos', 'Cuando las raíces alcanzan tamaño adecuado'),
(62, 'Ñame espino', 'Tubérculo tropical utilizado en alimentación.', 'Tubérculos y raíces', 240, NULL, 'Por trozos de tubérculo-semilla', 'Sol directo', 'Regular', 'Profundo y drenado', 'Instalar tutores para variedades trepadoras', 'Nematodos y escarabajos', 'Cuando el follaje madura'),
(63, 'Malanga', 'Planta tropical cultivada por sus cormos.', 'Tubérculos y raíces', 240, NULL, 'Por cormos o cormelos', 'Sol parcial o directo', 'Regular y abundante según sistema', 'Fértil y húmedo', 'Controlar malezas y humedad', 'Pudriciones y plagas locales', 'Cuando las hojas envejecen'),
(64, 'Taro', 'Planta cultivada por sus cormos comestibles tras cocción adecuada.', 'Tubérculos y raíces', 240, NULL, 'Por cormos', 'Sol parcial', 'Abundante según variedad', 'Húmedo y fértil', 'Mantener humedad y controlar malezas', 'Pudriciones y nematodos', 'Cuando el follaje amarillea'),
(65, 'Arracacha', 'Raíz andina utilizada en sopas y purés.', 'Tubérculos y raíces', 300, NULL, 'Por colinos o propágulos', 'Sol directo o parcial', 'Regular', 'Fértil, profundo y drenado', 'Aporcar y mantener suelo suelto', 'Nematodos y pudriciones', 'Cuando las raíces alcanzan tamaño comercial'),
(66, 'Ñampí', 'Tubérculo tropical de uso alimentario.', 'Tubérculos y raíces', 240, NULL, 'Por cormos o trozos de semilla', 'Sol parcial', 'Regular', 'Fértil y húmedo, drenado', 'Controlar malezas', 'Pudriciones y nematodos', 'Cuando el follaje madura'),
(67, 'Yacón', 'Raíz tuberosa andina de sabor dulce.', 'Tubérculos y raíces', 240, NULL, 'Por rizomas con yemas', 'Sol directo', 'Regular', 'Suelto y fértil', 'Aporcar y controlar malezas', 'Plagas locales', 'Cuando la planta completa su ciclo'),
(68, 'Oca', 'Tubérculo andino de distintos colores.', 'Tubérculos y raíces', 180, NULL, 'Por tubérculos-semilla', 'Sol directo', 'Regular', 'Suelto y drenado', 'Mantener humedad estable', 'Gusanos de suelo', 'Cuando el follaje madura'),
(69, 'Anón', 'Fruta tropical de pulpa dulce y segmentada.', 'Frutas', 730, NULL, 'Por semilla o injerto', 'Sol directo', 'Moderado', 'Drenado', 'Proteger plántulas', 'Cochinillas', 'Cuando los segmentos se separan'),
(70, 'Rabano', 'Raiz comestible de crecimiento rapido.', 'Hortalizas', 30, NULL, 'Siembra directa por semillas', 'Sol directo', 'Frecuente y moderado', 'Suelto y bien drenado', 'Mantener humedad y retirar malezas.', 'Pulgones y escarabajos', 'Cuando la raiz alcance buen tamaño'),
(71, 'Remolacha', 'Planta de raiz comestible utilizada en ensaladas y jugos.', 'Hortalizas', 75, NULL, 'Siembra directa por semillas', 'Sol directo', 'Regular sin encharcar', 'Profundo y suelto', 'Controlar malezas y conservar la humedad.', 'Pulgones y minadores', 'Cuando la raiz tenga tamaño adecuado'),
(72, 'Calabacin', 'Hortaliza de fruto verde utilizada en diferentes preparaciones.', 'Hortalizas', 50, NULL, 'Siembra directa por semillas', 'Sol directo', 'Frecuente dirigido al suelo', 'Fertil y bien drenado', 'Dejar espacio entre plantas.', 'Mosca blanca y pulgones', 'Cuando los frutos esten tiernos'),
(73, 'Repollo morado', 'Hortaliza de hojas compactas de color morado.', 'Hortalizas', 100, NULL, 'Semillero y trasplante', 'Sol directo', 'Regular', 'Fertil y rico en materia organica', 'Vigilar el desarrollo de la cabeza.', 'Orugas y pulgones', 'Cuando la cabeza este firme'),
(74, 'Quimbombo', 'Planta de clima calido que produce vainas comestibles.', 'Hortalizas', 60, NULL, 'Siembra directa por semillas', 'Sol directo', 'Moderado y constante', 'Fertil y bien drenado', 'Controlar malezas y proteger plantas jovenes.', 'Pulgones y mosca blanca', 'Recolectar las vainas tiernas'),
(75, 'Fresa', 'Planta que produce frutos rojos, dulces y aromaticos.', 'Frutas', 120, NULL, 'Plántulas o estolones', 'Buena iluminacion', 'Frecuente sin encharcar', 'Rico en materia organica y drenado', 'Retirar frutos dañados y controlar humedad.', 'Acaros, pulgones y babosas', 'Cuando los frutos esten rojos y maduros'),
(76, 'Pina', 'Fruta tropical cultivada en regiones calidas.', 'Frutas', 540, NULL, 'Coronas, hijuelos o retoños', 'Sol directo', 'Moderado', 'Acido, suelto y bien drenado', 'Evitar encharcamientos.', 'Cochinillas y acaros', 'Cuando el fruto alcance la madurez'),
(77, 'Guayaba', 'Arbol frutal de clima tropical.', 'Frutas', 1095, NULL, 'Plántulas preferiblemente', 'Sol directo', 'Regular durante el establecimiento', 'Adaptable y bien drenado', 'Podar y retirar frutos afectados.', 'Moscas de la fruta y picudos', 'Cuando los frutos maduren'),
(78, 'Granadilla', 'Fruta andina de pulpa aromatica.', 'Frutas', 300, NULL, 'Semillas o plantulas', 'Buena iluminacion', 'Regular', 'Fertil y bien drenado', 'Instalar soportes para la enredadera.', 'Moscas de la fruta y acaros', 'Cuando los frutos esten maduros'),
(79, 'Aguacate', 'Arbol frutal importante para la agricultura colombiana.', 'Frutas', 1095, NULL, 'Plantulas injertadas', 'Sol directo', 'Regular sin encharcar', 'Profundo y bien drenado', 'Vigilar enfermedades de raiz.', 'Trips y acaros', 'Segun variedad y madurez'),
(80, 'Frijol mungo', 'Leguminosa de ciclo relativamente corto.', 'Leguminosas', 75, NULL, 'Siembra directa por semillas', 'Sol directo', 'Moderado', 'Suelto y bien drenado', 'Evitar exceso de humedad.', 'Pulgones y mosca blanca', 'Cuando las vainas esten maduras'),
(81, 'Garbanzo', 'Leguminosa cultivada por sus semillas comestibles.', 'Leguminosas', 105, NULL, 'Siembra directa por semillas', 'Sol directo', 'Moderado sin exceso', 'Bien drenado', 'Evitar encharcamientos.', 'Orugas y pulgones', 'Cuando las vainas se sequen'),
(82, 'Amaranto', 'Planta cuyas semillas se aprovechan como alimento.', 'Cereales', 105, NULL, 'Siembra directa por semillas', 'Sol directo', 'Moderado durante el establecimiento', 'Suelto y bien drenado', 'Controlar malezas al comienzo.', 'Pulgones y orugas', 'Cuando las semillas maduren'),
(83, 'Cebada', 'Cereal utilizado para alimentacion y otros usos agricolas.', 'Cereales', 120, NULL, 'Siembra directa por semillas', 'Sol directo', 'Segun lluvias y desarrollo', 'Bien drenado', 'Controlar malezas y enfermedades.', 'Pulgones y gusanos cortadores', 'Cuando las espigas esten secas'),
(84, 'Mijo', 'Cereal de ciclo corto que se adapta a condiciones calidas.', 'Cereales', 80, NULL, 'Siembra directa por semillas', 'Sol directo', 'Moderado', 'Bien drenado', 'Controlar malezas jovenes.', 'Aves e insectos', 'Cuando las semillas maduren'),
(85, 'Esparrago', 'Planta perenne cultivada por sus brotes comestibles.', 'Hortalizas', 365, NULL, 'Semillas o coronas', 'Sol directo', 'Regular', 'Profundo, fertil y drenado', 'Mantener el terreno libre de malezas.', 'Escarabajos y pulgones', 'Cortar brotes jovenes desarrollados'),
(86, 'Alcachofa', 'Planta cultivada por sus capitulos florales comestibles.', 'Hortalizas', 150, NULL, 'Semillas o plantulas', 'Sol directo', 'Regular', 'Fertil y bien drenado', 'Aportar materia organica y controlar malezas.', 'Pulgones y orugas', 'Antes de que los capitulos se abran'),
(87, 'Flor de Jamaica', 'Planta cultivada por sus calices rojos usados en bebidas.', 'Aromaticas', 150, NULL, 'Siembra directa o semillero', 'Sol directo', 'Regular', 'Fertil y bien drenado', 'Controlar malezas y humedad.', 'Pulgones y mosca blanca', 'Cuando los calices esten desarrollados'),
(88, 'Manzanilla', 'Planta aromatica utilizada tradicionalmente en infusiones.', 'Aromaticas', 75, NULL, 'Siembra superficial por semillas', 'Sol suave o directo', 'Moderado', 'Ligero y drenado', 'Evitar exceso de humedad.', 'Pulgones y orugas', 'Recolectar las flores abiertas'),
(89, 'Estevia', 'Planta de hojas dulces utilizadas como edulcorante.', 'Aromaticas', 90, NULL, 'Esquejes o plantulas', 'Buena iluminacion', 'Regular sin encharcar', 'Fertil y bien drenado', 'Podar para favorecer la ramificacion.', 'Pulgones y mosca blanca', 'Recolectar las hojas desarrolladas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fases_fenologicas`
--

CREATE TABLE `fases_fenologicas` (
  `id_fase` int(11) NOT NULL,
  `id_cultivo` int(11) NOT NULL,
  `nombre_fase` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `dias_aproximados` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fases_fenologicas`
--

INSERT INTO `fases_fenologicas` (`id_fase`, `id_cultivo`, `nombre_fase`, `descripcion`, `dias_aproximados`) VALUES
(1, 1, 'Germinación', 'Inicio del desarrollo de la semilla.', 7),
(2, 1, 'Crecimiento', 'Desarrollo de tallos y hojas.', 35),
(3, 1, 'Floración', 'Aparición de las flores.', 20),
(4, 1, 'Fructificación', 'Formación y desarrollo de los frutos.', 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id_publicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `foro`
--

INSERT INTO `foro` (`id_publicacion`, `id_usuario`, `titulo`, `contenido`, `fecha_publicacion`) VALUES
(1, 2, 'JHKKHL', 'JHLKJKL', '2026-09-24 21:14:57'),
(2, 2, 'JHKKHL', 'JHLKJKL', '2026-09-24 21:15:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id_mensaje` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendiente','Leído') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id_mensaje`, `nombre`, `correo`, `mensaje`, `fecha_envio`, `estado`) VALUES
(4, 'cindy', 'cindy@example.com', 'yjujhgjhk', '2026-09-24 21:03:23', 'Pendiente'),
(5, 'cindy', 'cindy@example.com', 'fdsfggg', '2026-09-24 21:03:25', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id_noticia` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regiones`
--

CREATE TABLE `regiones` (
  `id_region` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `regiones`
--

INSERT INTO `regiones` (`id_region`, `nombre`, `departamento`, `descripcion`) VALUES
(1, 'Valle de Aburrá', 'Antioquia', 'Región donde se encuentra Medellín y zonas cercanas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_fenologicos`
--

CREATE TABLE `registros_fenologicos` (
  `id_registro` int(11) NOT NULL,
  `id_cultivo` int(11) NOT NULL,
  `id_fase` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Gestiona usuarios, cultivos, noticias y contenido de AgroFuturo'),
(2, 'Usuario', 'Consulta la información disponible en AgroFuturo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `correo`, `clave`, `nombre_completo`, `id_rol`, `activo`, `fecha_registro`) VALUES
(1, 'admin', 'admin@agrofuturo.com', '$2y$10$cx2pJS6ky8zzHVtg37B4luk6cg6nBbMUqqX2KSLbFmeWsMQJkJYmi', 'Administrador AgroFuturo', 1, 1, '2026-09-24 20:28:51'),
(2, 'cindy', 'cindy@example.com', '$2y$10$W.8f3xQysaSH8j41QChu/.EtavgT8yHKfHEzLA0.UizCBIP2z1HWy', 'cindy', 2, 1, '2026-09-24 20:41:15'),
(3, 'martinezhiguitagelentatiana', 'martinezhiguitagelentatiana@ieru.edu.co', '$2y$10$krUVrBBC5H4VuGUf.ukUBedLS0l49ULMek3xQa0u6BUiVt73w.FoS', 'Gelen Tatiana Martinez Higuita', 2, 1, '2026-10-22 19:22:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendario_agricola`
--
ALTER TABLE `calendario_agricola`
  ADD PRIMARY KEY (`id_calendario`),
  ADD KEY `idx_calendario_cultivo` (`id_cultivo`);

--
-- Indices de la tabla `clima`
--
ALTER TABLE `clima`
  ADD PRIMARY KEY (`id_clima`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `idx_comentarios_publicacion` (`id_publicacion`),
  ADD KEY `idx_comentarios_usuario` (`id_usuario`);

--
-- Indices de la tabla `cultivos`
--
ALTER TABLE `cultivos`
  ADD PRIMARY KEY (`id_cultivo`),
  ADD KEY `idx_cultivos_region` (`id_region`);

--
-- Indices de la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  ADD PRIMARY KEY (`id_fase`),
  ADD KEY `idx_fases_cultivo` (`id_cultivo`);

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `idx_foro_usuario` (`id_usuario`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id_mensaje`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id_noticia`),
  ADD KEY `idx_noticias_usuario` (`id_usuario`);

--
-- Indices de la tabla `regiones`
--
ALTER TABLE `regiones`
  ADD PRIMARY KEY (`id_region`);

--
-- Indices de la tabla `registros_fenologicos`
--
ALTER TABLE `registros_fenologicos`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `idx_registros_cultivo` (`id_cultivo`),
  ADD KEY `idx_registros_fase` (`id_fase`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `uk_roles_nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uk_usuarios_usuario` (`usuario`),
  ADD UNIQUE KEY `uk_usuarios_correo` (`correo`),
  ADD KEY `idx_usuarios_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendario_agricola`
--
ALTER TABLE `calendario_agricola`
  MODIFY `id_calendario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clima`
--
ALTER TABLE `clima`
  MODIFY `id_clima` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cultivos`
--
ALTER TABLE `cultivos`
  MODIFY `id_cultivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  MODIFY `id_fase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `regiones`
--
ALTER TABLE `regiones`
  MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registros_fenologicos`
--
ALTER TABLE `registros_fenologicos`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendario_agricola`
--
ALTER TABLE `calendario_agricola`
  ADD CONSTRAINT `fk_calendario_cultivo` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_comentarios_publicacion` FOREIGN KEY (`id_publicacion`) REFERENCES `foro` (`id_publicacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentarios_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cultivos`
--
ALTER TABLE `cultivos`
  ADD CONSTRAINT `fk_cultivos_region` FOREIGN KEY (`id_region`) REFERENCES `regiones` (`id_region`);

--
-- Filtros para la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  ADD CONSTRAINT `fk_fases_cultivo` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `foro`
--
ALTER TABLE `foro`
  ADD CONSTRAINT `fk_foro_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_noticias_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `registros_fenologicos`
--
ALTER TABLE `registros_fenologicos`
  ADD CONSTRAINT `fk_registros_cultivo` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`),
  ADD CONSTRAINT `fk_registros_fase` FOREIGN KEY (`id_fase`) REFERENCES `fases_fenologicas` (`id_fase`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
