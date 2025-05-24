-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2025 a las 02:09:14
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
-- Base de datos: `judomex`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `id_competencia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('activa','cancelada','completada') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencias`
--

INSERT INTO `competencias` (`id_competencia`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `lugar`, `imagen`, `estado`) VALUES
(1, 'Jornada de Judo', NULL, '2025-04-30', '2025-04-30', 'San Luis Potosí', 'mun.png', 'activa'),
(2, 'Campeonato Nacional de Judo', NULL, '2025-09-05', '2025-09-08', 'Guadalajara', 'nac.png', 'activa'),
(3, 'Torneo International de Judo', NULL, '2025-04-04', '2025-04-05', 'París, Francia', 'inter.jpeg', 'activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones_competencia`
--

CREATE TABLE `inscripciones_competencia` (
  `id_inscripcion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_competencia` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `fecha_inscripcion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','confirmada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente',
  `categoria_peso` varchar(20) NOT NULL,
  `cinta` varchar(20) NOT NULL,
  `tipo_competencia` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones_competencia`
--

INSERT INTO `inscripciones_competencia` (`id_inscripcion`, `id_usuario`, `id_competencia`, `nombre_completo`, `fecha_inscripcion`, `estado`, `categoria_peso`, `cinta`, `tipo_competencia`) VALUES
(2, 1, 1, 'Juan Manuel', '2025-05-23 11:24:31', 'pendiente', '-73kg', 'Blanca', 'Veterano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `estado` varchar(20) DEFAULT 'completado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `usuario_id`, `total`, `fecha_creacion`, `estado`) VALUES
(3, 1, 1970.00, '2025-05-23 13:33:54', 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_items`
--

CREATE TABLE `orden_items` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_items`
--

INSERT INTO `orden_items` (`id`, `orden_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 3, 0, 1, 920.00, 920.00),
(2, 3, 0, 1, 560.00, 560.00),
(3, 3, 0, 1, 490.00, 490.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` enum('judogi','cinta','rodillera') NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `imagen`, `fecha_creacion`) VALUES
(1, 'Judogi Blanco Outshock', 'Judogi de competición profesional', 1200.00, 'judogi', 'assets/jud1.jpg', '2025-05-23 17:49:50'),
(2, 'Judogi Blanco Mizuno', 'Judogi de competición profesional', 7800.00, 'judogi', 'assets/jud2.jpg', '2025-05-23 17:49:50'),
(3, 'Judogi Azul NKL', 'Judogi de competición profesional', 920.00, 'judogi', 'assets/jud3.jpg', '2025-05-23 17:49:50'),
(4, 'Judogi Blanco Adidas', 'Judogi de competición profesional', 920.00, 'judogi', 'assets/jud4.jpg', '2025-05-23 17:49:50'),
(5, 'Judogi Azul NKL', 'Judogi de competición profesional', 860.00, 'judogi', 'assets/jud5.webp', '2025-05-23 17:49:50'),
(6, 'Cinta Azul Fuji', 'Cinta de algodón', 225.00, 'cinta', 'assets/cin1.jpg', '2025-05-23 17:49:50'),
(7, 'Cinta Negra Japan Mizuno', 'Cinta de algodón', 1440.00, 'cinta', 'assets/cin2.jpg', '2025-05-23 17:49:50'),
(8, 'Cinta Azul Fuji', 'Cinta de algodón', 560.00, 'cinta', 'assets/cin3.jpg', '2025-05-23 17:49:50'),
(9, 'Cinta Amarilla Fuji', 'Cinta de algodón', 225.00, 'cinta', 'assets/cin4.jpg', '2025-05-23 17:49:50'),
(10, 'Cinta Roja Mizuno', 'Cinta de algodón', 550.00, 'cinta', 'assets/cin3.jpg', '2025-05-23 17:49:50'),
(11, 'Rodilleras Negras Mizuno', 'Rodillera de competición con protección', 430.00, 'rodillera', 'assets/rod1.jpg', '2025-05-23 17:49:50'),
(12, 'Rodilleras Rojas Mizuno', 'Rodillera de competición con protección', 670.00, 'rodillera', 'assets/rod2.jpg', '2025-05-23 17:49:50'),
(13, 'Rodilleras Azulez Mizuno', 'Rodillera de competición con protección', 490.00, 'rodillera', 'assets/rod3.jpg', '2025-05-23 17:49:50'),
(14, 'Rodilleras Blanca Mizuno', 'Rodillera de competición con protección', 610.00, 'rodillera', 'assets/rod4.jpg', '2025-05-23 17:49:50'),
(15, 'Rodilleras VSI Negras Mizuno', 'Rodillera de competición con protección', 610.00, 'rodillera', 'assets/rod5.jpg', '2025-05-23 17:49:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `talla_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id`, `producto_id`, `talla_id`, `cantidad`) VALUES
(1, 1, 1, 6),
(2, 1, 1, 10),
(3, 1, 1, 15),
(4, 1, 1, 8),
(5, 1, 5, 5),
(6, 2, 1, 6),
(7, 2, 2, 10),
(8, 2, 3, 15),
(9, 2, 4, 8),
(10, 2, 5, 5),
(11, 3, 1, 6),
(12, 3, 2, 10),
(13, 3, 3, 15),
(14, 3, 4, 8),
(15, 3, 5, 5),
(16, 4, 1, 6),
(17, 4, 2, 10),
(18, 4, 3, 15),
(19, 4, 4, 8),
(20, 4, 5, 5),
(21, 5, 1, 6),
(22, 5, 2, 10),
(23, 5, 3, 15),
(24, 5, 4, 8),
(25, 5, 5, 5),
(26, 6, 1, 6),
(27, 6, 1, 10),
(28, 6, 1, 15),
(29, 6, 1, 8),
(30, 6, 5, 5),
(31, 7, 1, 6),
(32, 7, 2, 10),
(33, 7, 3, 15),
(34, 4, 4, 8),
(35, 7, 5, 5),
(36, 8, 1, 6),
(37, 8, 2, 10),
(38, 8, 3, 15),
(39, 8, 4, 8),
(40, 8, 5, 5),
(41, 9, 1, 6),
(42, 9, 2, 10),
(43, 9, 3, 15),
(44, 9, 4, 8),
(45, 9, 5, 5),
(46, 10, 1, 6),
(47, 10, 2, 10),
(48, 10, 3, 15),
(49, 10, 4, 8),
(50, 10, 5, 5),
(51, 11, 1, 6),
(52, 11, 1, 10),
(53, 11, 1, 15),
(54, 11, 1, 8),
(55, 11, 5, 5),
(56, 12, 1, 6),
(57, 12, 2, 10),
(58, 12, 3, 15),
(59, 12, 4, 8),
(60, 12, 5, 5),
(61, 13, 1, 6),
(62, 13, 2, 10),
(63, 13, 3, 15),
(64, 13, 4, 8),
(65, 13, 5, 5),
(66, 14, 1, 6),
(67, 14, 2, 10),
(68, 14, 3, 15),
(69, 14, 4, 8),
(70, 14, 5, 5),
(71, 15, 1, 6),
(72, 15, 2, 10),
(73, 15, 3, 15),
(74, 15, 4, 8),
(75, 15, 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` int(11) NOT NULL,
  `talla` varchar(20) NOT NULL,
  `tipo` enum('ropa','cinta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `talla`, `tipo`) VALUES
(1, 'XS', 'ropa'),
(2, 'S', 'ropa'),
(3, 'M', 'ropa'),
(4, 'L', 'ropa'),
(5, 'XL', 'ropa'),
(6, 'Blanca', 'cinta'),
(7, 'Amarilla', 'cinta'),
(8, 'Naranja', 'cinta'),
(9, 'Verde', 'cinta'),
(10, 'Azul', 'cinta'),
(11, 'Marrón', 'cinta'),
(12, 'Negra', 'cinta'),
(13, 'XS', 'ropa'),
(14, 'S', 'ropa'),
(15, 'M', 'ropa'),
(16, 'L', 'ropa'),
(17, 'XL', 'ropa'),
(18, 'Blanca', 'cinta'),
(19, 'Amarilla', 'cinta'),
(20, 'Naranja', 'cinta'),
(21, 'Verde', 'cinta'),
(22, 'Azul', 'cinta'),
(23, 'Marrón', 'cinta'),
(24, 'Negra', 'cinta'),
(25, 'XS', 'ropa'),
(26, 'S', 'ropa'),
(27, 'M', 'ropa'),
(28, 'L', 'ropa'),
(29, 'XL', 'ropa'),
(30, 'Blanca', 'cinta'),
(31, 'Amarilla', 'cinta'),
(32, 'Naranja', 'cinta'),
(33, 'Verde', 'cinta'),
(34, 'Azul', 'cinta'),
(35, 'Marrón', 'cinta'),
(36, 'Negra', 'cinta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('Hombre','Mujer','Otro') NOT NULL,
  `calle` varchar(150) NOT NULL,
  `no_ext` varchar(20) NOT NULL,
  `no_int` varchar(20) DEFAULT NULL,
  `colonia` varchar(100) NOT NULL,
  `cp` varchar(10) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `telefono`, `fecha_nacimiento`, `genero`, `calle`, `no_ext`, `no_int`, `colonia`, `cp`, `pais`, `estado`, `password`, `fecha_registro`) VALUES
(1, 'Juan Manuel', 'Gonzalez Cabrera', 'jmgca@live.com.mx', '4443835659', '1960-12-18', 'Hombre', 'Azabache', '1717', NULL, 'Jardines del Sur', '78399', 'México', 'San Luis Potosí', '$2y$10$Ilt4NRH0rvE406qvJvBazOXHp2vY1l0jueFrzlkQJxuBtrEdh4w3e', '2025-05-23 11:16:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`id_competencia`);

--
-- Indices de la tabla `inscripciones_competencia`
--
ALTER TABLE `inscripciones_competencia`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_competencia` (`id_competencia`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `talla_id` (`talla_id`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_telefono` (`telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `competencias`
--
ALTER TABLE `competencias`
  MODIFY `id_competencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inscripciones_competencia`
--
ALTER TABLE `inscripciones_competencia`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inscripciones_competencia`
--
ALTER TABLE `inscripciones_competencia`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencias` (`id_competencia`);

--
-- Filtros para la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD CONSTRAINT `orden_items_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
