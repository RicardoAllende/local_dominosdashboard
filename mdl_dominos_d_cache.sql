-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-10-2019 a las 10:13:02
-- Versión del servidor: 5.7.23
-- Versión de PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `moodle342`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mdl_dominos_d_cache`
--

DROP TABLE IF EXISTS `mdl_dominos_d_cache`;
CREATE TABLE IF NOT EXISTS `mdl_dominos_d_cache` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `courseid` bigint(10) NOT NULL,
  `title` varchar(255) DEFAULT '',
  `enrolled_users` int(9) DEFAULT 0,
  `approved_users` int(9) DEFAULT 0,
  `percentage` int(9) DEFAULT 0,
  `value` int(9) DEFAULT 0,
  `regiones` longtext,
  `distritos` longtext,
  `entrenadores` longtext,
  `tiendas` longtext,
  `puestos` longtext,
  `ccosto` longtext,
  `startdate` bigint(10) DEFAULT 0,
  `enddate` bigint(10) DEFAULT 0,
  `timemodified` bigint(10) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPRESSED;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
