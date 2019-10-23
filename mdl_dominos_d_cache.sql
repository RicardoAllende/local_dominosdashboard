-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-10-2019 a las 15:30:45
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
  `title` varchar(255) DEFAULT '""',
  `enrolled_users` int(9) DEFAULT '0',
  `approved_users` int(9) DEFAULT '0',
  `percentage` int(9) DEFAULT '0',
  `value` int(9) DEFAULT '0',
  `regiones` longtext,
  `distritos` longtext,
  `entrenadores` longtext,
  `tiendas` longtext,
  `puestos` longtext,
  `ccosto` longtext,
  `startdate` bigint(10) DEFAULT null,
  `enddate` bigint(10) DEFAULT null,
  `timemodified` bigint(10) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPRESSED;

--
-- Volcado de datos para la tabla `mdl_dominos_d_cache`
--

INSERT INTO `mdl_dominos_d_cache` (`id`, `courseid`, `title`, `enrolled_users`, `approved_users`, `percentage`, `value`, `regiones`, `distritos`, `entrenadores`, `tiendas`, `puestos`, `ccosto`, `startdate`, `enddate`, `timemodified`) VALUES
(1, 8, 'WOW Rewards', 0, 0, 0, 0, '', '', '', '', '', '', NULL, NULL, 1571770635),
(2, 8, 'WOW Rewards', 0, 0, 0, 0, 'bajio', '', '', '', '', '', NULL, NULL, 1571770635),
(3, 8, 'WOW Rewards', 0, 0, 0, 0, 'centro norte', '', '', '', '', '', NULL, NULL, 1571770635),
(4, 8, 'WOW Rewards', 0, 0, 0, 0, 'centro sur', '', '', '', '', '', NULL, NULL, 1571770635),
(5, 8, 'WOW Rewards', 0, 0, 0, 0, 'Ciudad de Mexico', '', '', '', '', '', NULL, NULL, 1571770635),
(6, 8, 'WOW Rewards', 0, 0, 0, 0, 'norte - noroeste', '', '', '', '', '', NULL, NULL, 1571770635),
(7, 8, 'WOW Rewards', 0, 0, 0, 0, 'norte noroeste', '', '', '', '', '', NULL, NULL, 1571770635),
(8, 8, 'WOW Rewards', 0, 0, 0, 0, 'norte-noroeste', '', '', '', '', '', NULL, NULL, 1571770635),
(9, 8, 'WOW Rewards', 0, 0, 0, 0, 'occidente', '', '', '', '', '', NULL, NULL, 1571770635),
(10, 8, 'WOW Rewards', 0, 0, 0, 0, 'Occidente Bajío', '', '', '', '', '', NULL, NULL, 1571770635),
(11, 8, 'WOW Rewards', 0, 0, 0, 0, 'occidente-bajio', '', '', '', '', '', NULL, NULL, 1571770636),
(12, 8, 'WOW Rewards', 0, 0, 0, 0, 'puebla veracruz', '', '', '', '', '', NULL, NULL, 1571770636),
(13, 8, 'WOW Rewards', 0, 0, 0, 0, 'puebla-veracruz', '', '', '', '', '', NULL, NULL, 1571770636),
(14, 8, 'WOW Rewards', 0, 0, 0, 0, 'Saraperos', '', '', '', '', '', NULL, NULL, 1571770636),
(15, 8, 'WOW Rewards', 0, 0, 0, 0, 'sureste', '', '', '', '', '', NULL, NULL, 1571770636),
(16, 8, 'WOW Rewards', 0, 0, 0, 0, 't', '', '', '', '', '', NULL, NULL, 1571770636),
(17, 9, 'Examen técnico para convocatoria 2019', 9213, 4605, 50, 50, '', '', '', '', '', '', NULL, NULL, 1571770636),
(18, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'bajio', '', '', '', '', '', NULL, NULL, 1571770636),
(19, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'centro norte', '', '', '', '', '', NULL, NULL, 1571770637),
(20, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'centro sur', '', '', '', '', '', NULL, NULL, 1571770637),
(21, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'Ciudad de Mexico', '', '', '', '', '', NULL, NULL, 1571770637),
(22, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'norte - noroeste', '', '', '', '', '', NULL, NULL, 1571770637),
(23, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'norte noroeste', '', '', '', '', '', NULL, NULL, 1571770637),
(24, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'norte-noroeste', '', '', '', '', '', NULL, NULL, 1571770637),
(25, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'occidente', '', '', '', '', '', NULL, NULL, 1571770638),
(26, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'Occidente Bajío', '', '', '', '', '', NULL, NULL, 1571770638),
(27, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'occidente-bajio', '', '', '', '', '', NULL, NULL, 1571770638),
(28, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'puebla veracruz', '', '', '', '', '', NULL, NULL, 1571770638),
(29, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'puebla-veracruz', '', '', '', '', '', NULL, NULL, 1571770638),
(30, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'Saraperos', '', '', '', '', '', NULL, NULL, 1571770638),
(31, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 'sureste', '', '', '', '', '', NULL, NULL, 1571770639),
(32, 9, 'Examen técnico para convocatoria 2019', 0, 0, 0, 0, 't', '', '', '', '', '', NULL, NULL, 1571770639),
(33, 10, 'Examen Técnico', 0, 0, 0, 0, '', '', '', '', '', '', NULL, NULL, 1571770639),
(34, 10, 'Examen Técnico', 0, 0, 0, 0, 'bajio', '', '', '', '', '', NULL, NULL, 1571770639),
(35, 10, 'Examen Técnico', 0, 0, 0, 0, 'centro norte', '', '', '', '', '', NULL, NULL, 1571770639),
(36, 10, 'Examen Técnico', 0, 0, 0, 0, 'centro sur', '', '', '', '', '', NULL, NULL, 1571770639),
(37, 10, 'Examen Técnico', 0, 0, 0, 0, 'Ciudad de Mexico', '', '', '', '', '', NULL, NULL, 1571770639),
(38, 10, 'Examen Técnico', 0, 0, 0, 0, 'norte - noroeste', '', '', '', '', '', NULL, NULL, 1571770639),
(39, 10, 'Examen Técnico', 0, 0, 0, 0, 'norte noroeste', '', '', '', '', '', NULL, NULL, 1571770640),
(40, 10, 'Examen Técnico', 0, 0, 0, 0, 'norte-noroeste', '', '', '', '', '', NULL, NULL, 1571770640),
(41, 10, 'Examen Técnico', 0, 0, 0, 0, 'occidente', '', '', '', '', '', NULL, NULL, 1571770640),
(42, 10, 'Examen Técnico', 0, 0, 0, 0, 'Occidente Bajío', '', '', '', '', '', NULL, NULL, 1571770640),
(43, 10, 'Examen Técnico', 0, 0, 0, 0, 'occidente-bajio', '', '', '', '', '', NULL, NULL, 1571770640),
(44, 10, 'Examen Técnico', 0, 0, 0, 0, 'puebla veracruz', '', '', '', '', '', NULL, NULL, 1571770640),
(45, 10, 'Examen Técnico', 0, 0, 0, 0, 'puebla-veracruz', '', '', '', '', '', NULL, NULL, 1571770640),
(46, 10, 'Examen Técnico', 0, 0, 0, 0, 'Saraperos', '', '', '', '', '', NULL, NULL, 1571770640),
(47, 10, 'Examen Técnico', 0, 0, 0, 0, 'sureste', '', '', '', '', '', NULL, NULL, 1571770640),
(48, 10, 'Examen Técnico', 0, 0, 0, 0, 't', '', '', '', '', '', NULL, NULL, 1571770640),
(49, 10, 'Examen Técnico', 0, 0, 0, 0, 't', 't', NULL, NULL, NULL, NULL, 0, 0, NULL),
(50, 10, 'Examen Técnico', 0, 0, 0, 0, 't', 't', '', '', '', '', NULL, NULL, 1571770640);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
