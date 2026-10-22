-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2026 a las 00:01:42
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
  `id_region` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cultivos`
--

INSERT INTO `cultivos` (`id_cultivo`, `nombre`, `descripcion`, `tipo`, `tiempo_cosecha`, `id_region`) VALUES
(1, 'Tomate', 'Cultivo utilizado para producción de alimentos.', 'Hortaliza', 90, 1),
(2, 'Lechuga', 'Hortaliza de ciclo corto.', 'Hortaliza', 45, 1),
(3, 'Maíz', 'Cultivo de importancia alimentaria y agrícola.', 'Cereal', 120, 1);

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
(1, 'admin', 'admin@agrofuturo.com', 'Admin123', 'Administrador AgroFuturo', 1, 1, '2026-09-24 20:28:51'),
(2, 'cindy', 'cindy@example.com', '$2y$10$W.8f3xQysaSH8j41QChu/.EtavgT8yHKfHEzLA0.UizCBIP2z1HWy', 'cindy', 2, 1, '2026-09-24 20:41:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendario_agricola`
--
ALTER TABLE `calendario_agricola`
  ADD PRIMARY KEY (`id_calendario`),
  ADD KEY `id_cultivo` (`id_cultivo`);

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
  ADD KEY `id_publicacion` (`id_publicacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `cultivos`
--
ALTER TABLE `cultivos`
  ADD PRIMARY KEY (`id_cultivo`),
  ADD KEY `id_region` (`id_region`);

--
-- Indices de la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  ADD PRIMARY KEY (`id_fase`),
  ADD KEY `id_cultivo` (`id_cultivo`);

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `id_cultivo` (`id_cultivo`),
  ADD KEY `id_fase` (`id_fase`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`);

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
  MODIFY `id_cultivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  MODIFY `id_fase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendario_agricola`
--
ALTER TABLE `calendario_agricola`
  ADD CONSTRAINT `calendario_agricola_ibfk_1` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_publicacion`) REFERENCES `foro` (`id_publicacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cultivos`
--
ALTER TABLE `cultivos`
  ADD CONSTRAINT `cultivos_ibfk_1` FOREIGN KEY (`id_region`) REFERENCES `regiones` (`id_region`);

--
-- Filtros para la tabla `fases_fenologicas`
--
ALTER TABLE `fases_fenologicas`
  ADD CONSTRAINT `fases_fenologicas_ibfk_1` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `foro`
--
ALTER TABLE `foro`
  ADD CONSTRAINT `foro_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `registros_fenologicos`
--
ALTER TABLE `registros_fenologicos`
  ADD CONSTRAINT `registros_fenologicos_ibfk_1` FOREIGN KEY (`id_cultivo`) REFERENCES `cultivos` (`id_cultivo`),
  ADD CONSTRAINT `registros_fenologicos_ibfk_2` FOREIGN KEY (`id_fase`) REFERENCES `fases_fenologicas` (`id_fase`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
