-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-09-2019 a las 18:46:47
-- Versión del servidor: 5.7.27-0ubuntu0.16.04.1
-- Versión de PHP: 7.1.30-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `moodle_dominos`
--

--
-- Truncar tablas antes de insertar `mdl_user_info_field`
--

TRUNCATE TABLE `mdl_user_info_field`;
--
-- Volcado de datos para la tabla `mdl_user_info_field`
--

INSERT INTO `mdl_user_info_field` (`id`, `shortname`, `name`, `datatype`, `description`, `descriptionformat`, `categoryid`, `sortorder`, `required`, `locked`, `visible`, `forceunique`, `signup`, `defaultdata`, `defaultdataformat`, `param1`, `param2`, `param3`, `param4`, `param5`) VALUES
(1, 'numempleado', 'numempleado', 'text', '', 1, 1, 1, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(2, 'ingreso', 'ingreso', 'text', '', 1, 1, 2, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(3, 'nacimiento', 'nacimiento', 'text', '', 1, 1, 3, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(4, 'sexo', 'sexo', 'text', '', 1, 1, 4, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(5, 'rfc', 'rfc', 'text', '', 1, 1, 5, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(6, 'curp', 'curp', 'text', '', 1, 1, 6, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(7, 'puesto', 'puesto', 'text', '', 1, 1, 7, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(8, 'idpuesto', 'idpuesto', 'text', '', 1, 1, 8, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(9, 'region', 'region', 'text', '', 1, 1, 9, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(10, 'ccosto', 'ccosto', 'text', '', 1, 1, 10, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(11, 'tienda', 'tienda', 'text', '', 1, 1, 11, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(12, 'distrito', 'distrito', 'text', '', 1, 1, 12, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(13, 'sia', 'sia', 'text', '', 1, 1, 13, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(14, 'regiondp', 'regiondp', 'text', '', 1, 1, 14, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(15, 'distritodp', 'distritodp', 'text', '', 1, 1, 15, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(16, 'ciadp', 'ciadp', 'text', '', 1, 1, 16, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(17, 'barra', 'barra', 'text', '', 1, 1, 17, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(18, 'ubicacionbarra', 'ubicacionbarra', 'text', '', 1, 1, 18, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(19, 'division', 'division', 'text', '', 1, 1, 19, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(20, 'tiendadp', 'tiendadp', 'text', '', 1, 1, 20, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(21, 'bebidafav', 'bebidafav', 'text', '', 1, 1, 21, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(22, 'wbcfav', 'wbcfav', 'text', '', 1, 1, 22, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(23, 'foodsbx', 'foodsbx', 'text', '', 1, 1, 23, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(24, 'Hobbsbx', 'Hobbsbx', 'text', '', 1, 1, 24, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(25, 'InsNom', 'InsNom', 'text', '', 1, 1, 25, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(26, 'coursefav', 'coursefav', 'text', '', 1, 1, 26, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(27, 'marca', 'marca', 'text', '', 1, 1, 27, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(28, 'zona', 'zona', 'text', '', 1, 1, 28, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(29, 'region_alsea', 'region_alsea', 'text', '', 1, 1, 29, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(30, 'distrito_alsea', 'distrito_alsea', 'text', '', 1, 1, 30, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(31, 'turno', 'turno', 'text', '', 1, 1, 31, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(32, 'genero', 'genero', 'text', '', 1, 1, 32, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(33, 'distritalcoachdp', 'distritalcoachdp', 'text', '', 1, 1, 33, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(34, 'entrenadordp', 'entrenadordp', 'text', '', 1, 1, 34, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(35, 'companydp', 'companydp', 'text', '', 1, 1, 35, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(36, 'estadodp', 'estadodp', 'text', '', 1, 1, 36, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(37, 'nomccosto', 'nomccosto', 'text', '', 1, 1, 37, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(38, 'ciudaddp', 'ciudaddp', 'text', '', 1, 1, 38, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', ''),
(39, 'ingresodp', 'ingresodp', 'text', '', 1, 1, 39, 0, 0, 2, 0, 0, '', 0, '30', '2048', '0', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
