-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2020 at 12:05 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ventas-suscripcion`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` text NOT NULL,
  `ruta_categoria` text NOT NULL,
  `descripcion_categoria` text NOT NULL,
  `icono_categoria` text NOT NULL,
  `color_categoria` text NOT NULL,
  `fecha_categoria` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `ruta_categoria`, `descripcion_categoria`, `icono_categoria`, `color_categoria`, `fecha_categoria`) VALUES
(1, 'Cuerpo Activo', 'cuerpo-activo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae luctus mauris. Phasellus diam elit, congue interdum velit vitae, aliquet dignissim massa. Nam risus tortor, sagittis eget erat ac, sodales bibendum quam', 'fas fa-heart', 'purple', '2019-07-10 18:46:53'),
(2, 'Mente Sana', 'mente-sana', 'Sed ac vehicula neque, at venenatis nibh. Nullam aliquam odio tempor molestie dignissim. Aenean feugiat porttitor magna, non lobortis magna euismod a. Praesent risus tortor, consectetur et felis ac, accumsan tempor risus.', 'fas fa-puzzle-piece', 'info', '2019-07-10 18:46:53'),
(3, 'Espíritu Libre', 'espiritu-libre', 'Etiam placerat rhoncus pharetra. Fusce dapibus sem ultricies nulla consequat, vel cursus lacus interdum. Nulla ornare iaculis sapien nec faucibus. Nulla condimentum tempus magna, id faucibus nunc egestas ac.', 'fas fa-wind', 'primary', '2019-07-10 18:46:53');

-- --------------------------------------------------------

--
-- Table structure for table `pagos_binaria`
--

CREATE TABLE `pagos_binaria` (
  `id_pago` int(11) NOT NULL,
  `id_pago_paypal` text NOT NULL,
  `usuario_pago` int(11) NOT NULL,
  `periodo` text NOT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pagos_matriz`
--

CREATE TABLE `pagos_matriz` (
  `id_pago` int(11) NOT NULL,
  `id_pago_paypal` text NOT NULL,
  `usuario_pago` int(11) NOT NULL,
  `periodo` text NOT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pagos_uninivel`
--

CREATE TABLE `pagos_uninivel` (
  `id_pago` int(11) NOT NULL,
  `id_pago_paypal` text DEFAULT NULL,
  `usuario_pago` int(11) NOT NULL,
  `periodo` text NOT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pagos_uninivel`
--

INSERT INTO `pagos_uninivel` (`id_pago`, `id_pago_paypal`, `usuario_pago`, `periodo`, `periodo_comision`, `periodo_venta`, `fecha_pago`) VALUES
(7, NULL, 1, '2019-10-05 a 2019-10-06', 30, 30, '2019-10-08 01:13:24'),
(8, '545JE585HW242', 2, '2019-09-06 a 2019-10-06', 8, 20, '2019-10-08 01:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `red_binaria`
--

CREATE TABLE `red_binaria` (
  `id_binaria` int(11) NOT NULL,
  `usuario_red` int(11) NOT NULL,
  `orden_binaria` int(11) NOT NULL,
  `derrame_binaria` int(11) NOT NULL,
  `posicion_binaria` varchar(1) DEFAULT NULL,
  `patrocinador_red` text DEFAULT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_binaria` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `red_binaria`
--

INSERT INTO `red_binaria` (`id_binaria`, `usuario_red`, `orden_binaria`, `derrame_binaria`, `posicion_binaria`, `patrocinador_red`, `periodo_comision`, `periodo_venta`, `fecha_binaria`) VALUES
(1, 1, 1, 0, NULL, NULL, 10, 70, '2019-10-05 15:55:46'),
(10, 2, 2, 1, 'A', 'academy-of-life', 4, 30, '2019-09-27 23:21:45'),
(12, 3, 3, 2, 'A', 'alexander-parra-2', 0, 0, '2019-09-27 21:22:33'),
(13, 4, 4, 1, 'B', 'academy-of-life', 1, 20, '2019-09-27 23:36:01'),
(14, 5, 5, 2, 'B', 'alexander-parra-2', 0, 0, '2019-09-27 22:10:34'),
(15, 6, 6, 3, 'A', 'academy-of-life', 0, 0, '2019-09-27 22:10:34'),
(16, 7, 7, 4, 'A', 'felipe-perez-11', 0, 0, '2019-09-27 22:12:58'),
(17, 8, 8, 4, 'B', 'felipe-perez-11', 0, 0, '2019-09-27 22:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `red_matriz`
--

CREATE TABLE `red_matriz` (
  `id_matriz` int(11) NOT NULL,
  `usuario_red` int(11) NOT NULL,
  `orden_matriz` int(11) NOT NULL,
  `derrame_matriz` int(11) NOT NULL,
  `posicion_matriz` varchar(1) DEFAULT NULL,
  `patrocinador_red` text DEFAULT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_matriz` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `red_matriz`
--

INSERT INTO `red_matriz` (`id_matriz`, `usuario_red`, `orden_matriz`, `derrame_matriz`, `posicion_matriz`, `patrocinador_red`, `periodo_comision`, `periodo_venta`, `fecha_matriz`) VALUES
(1, 1, 1, 0, NULL, NULL, 10, 130, '2019-10-05 15:50:53'),
(2, 2, 2, 1, 'A', 'academy-of-life', 10, 40, '2019-10-07 18:39:54'),
(3, 3, 3, 1, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(4, 4, 4, 1, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(5, 5, 5, 1, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(6, 6, 6, 2, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(7, 7, 7, 2, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(8, 8, 8, 2, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(9, 9, 9, 2, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(10, 10, 10, 3, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(11, 11, 11, 3, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(12, 12, 12, 3, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(13, 13, 13, 3, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(14, 14, 14, 4, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(15, 15, 15, 4, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(16, 16, 16, 4, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(17, 17, 17, 4, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(18, 18, 18, 5, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(19, 19, 19, 5, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(20, 20, 20, 5, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(21, 21, 21, 5, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(22, 22, 22, 6, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(23, 23, 23, 6, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(24, 24, 24, 6, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(25, 25, 25, 6, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(26, 26, 26, 7, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(27, 27, 27, 7, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(28, 28, 28, 7, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(29, 29, 29, 7, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(30, 30, 30, 8, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(31, 31, 31, 8, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(32, 32, 32, 8, 'C', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(33, 33, 33, 8, 'D', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(34, 34, 34, 9, 'A', 'academy-of-life', 0, 0, '2019-10-05 15:50:44'),
(35, 35, 35, 9, 'B', 'academy-of-life', 0, 0, '2019-10-05 15:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `red_uninivel`
--

CREATE TABLE `red_uninivel` (
  `id_uninivel` int(11) NOT NULL,
  `usuario_red` int(11) NOT NULL,
  `patrocinador_red` text DEFAULT NULL,
  `periodo_comision` float NOT NULL,
  `periodo_venta` float NOT NULL,
  `fecha_uninivel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `red_uninivel`
--

INSERT INTO `red_uninivel` (`id_uninivel`, `usuario_red`, `patrocinador_red`, `periodo_comision`, `periodo_venta`, `fecha_uninivel`) VALUES
(1, 2, 'academy-of-life', 10, 10, '2019-10-08 01:13:27'),
(4, 10, 'alexander-parra-2', 4, 10, '2019-10-08 01:47:12'),
(5, 5, 'alexander-parra-2', 4, 10, '2019-10-08 01:47:15'),
(7, 12, 'academy-of-life', 10, 10, '2019-10-08 01:47:19'),
(8, 13, 'academy-of-life', 10, 10, '2019-10-08 01:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `soporte`
--

CREATE TABLE `soporte` (
  `id_soporte` int(11) NOT NULL,
  `remitente` int(11) NOT NULL,
  `receptor` int(11) NOT NULL,
  `asunto` text NOT NULL,
  `mensaje` text NOT NULL,
  `adjuntos` text NOT NULL,
  `tipo` text NOT NULL,
  `papelera` text DEFAULT NULL,
  `fecha_soporte` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `soporte`
--

INSERT INTO `soporte` (`id_soporte`, `remitente`, `receptor`, `asunto`, `mensaje`, `adjuntos`, `tipo`, `papelera`, `fecha_soporte`) VALUES
(1, 2, 1, 'Lorem Ipsum', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sed ex a ante tempus gravida. Etiam iaculis dolor a elit malesuada facilisis. Cras eget risus non leo ornare gravida ac eget ante. Quisque pellentesque mi quam. Suspendisse nisi ex, pellentesque id ligula ac, venenatis venenatis est. Integer ac sem nulla. Cras at nisl nec augue porttitor ullamcorper. Sed dapibus pulvinar libero a vestibulum. Nam ac ullamcorper nisi. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ut rutrum nibh. Donec vel posuere orci. Sed posuere vitae felis et pellentesque. Donec malesuada magna non lorem auctor vehicula. Nulla fermentum maximus venenatis.</p>', '[\"vistas\\/img\\/tickets\\/2\\/147.jpg\",\"vistas\\/img\\/tickets\\/2\\/526.pdf\",\"vistas\\/img\\/tickets\\/2\\/242.xlsx\"]', 'enviado', NULL, '2020-01-22 22:16:06'),
(2, 1, 2, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'papelera', '[2]', '2020-01-23 21:11:21'),
(3, 1, 3, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'papelera', '[\"1\"]', '2020-01-23 20:44:05'),
(4, 1, 4, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'papelera', '[\"1\"]', '2020-01-23 20:44:05'),
(5, 1, 5, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '[]', '2020-01-23 21:09:50'),
(6, 1, 6, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '[]', '2020-01-23 21:06:46'),
(7, 1, 7, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-23 20:42:36'),
(8, 1, 8, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(9, 1, 9, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(10, 1, 10, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(11, 1, 11, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(12, 1, 12, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(13, 1, 13, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(14, 1, 14, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(15, 1, 15, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(16, 1, 16, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:31'),
(17, 1, 17, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(18, 1, 18, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(19, 1, 19, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(20, 1, 20, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(21, 1, 21, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(22, 1, 22, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:32'),
(23, 1, 23, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(24, 1, 24, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(25, 1, 25, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(26, 1, 26, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(27, 1, 27, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(28, 1, 28, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(29, 1, 29, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(30, 1, 30, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(31, 1, 31, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(32, 1, 32, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(33, 1, 33, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(34, 1, 34, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(35, 1, 35, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:33'),
(36, 1, 36, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:34'),
(37, 1, 37, 'Quisque posuere', '<p>In varius leo eros, ac viverra ex ornare sed. Duis tempus sem elit, auctor bibendum lacus viverra sit amet. Curabitur eu turpis nec libero bibendum tincidunt ac vitae risus. Proin auctor ligula id metus accumsan, gravida blandit diam commodo. In est mauris, auctor quis elit vel, lobortis semper augue. Etiam ut imperdiet sapien. Nunc congue tempor tortor, vel dapibus odio mattis luctus. Pellentesque vitae eros sit amet lorem euismod lobortis vitae at diam. Etiam sit amet tellus lobortis, scelerisque nibh vitae, ullamcorper nisi. Curabitur ut luctus turpis. Quisque fermentum leo eu diam iaculis, at tincidunt risus aliquet. Mauris non metus vel eros egestas pretium quis a neque.</p><p>Quisque posuere volutpat lectus sed condimentum. Cras elementum blandit enim nec tincidunt. Mauris aliquet justo sit amet erat sagittis, in faucibus velit eleifend. Integer quis lacus augue. Fusce erat diam, euismod id nisi nec, egestas luctus massa. Integer pulvinar ligula ipsum, at dictum libero porta et. In tincidunt est quis nunc commodo interdum.</p>', '[\"vistas\\/img\\/tickets\\/1\\/943.jpg\",\"vistas\\/img\\/tickets\\/1\\/596.pdf\",\"vistas\\/img\\/tickets\\/1\\/772.png\"]', 'enviado', '', '2020-01-22 15:08:34'),
(38, 1, 2, 'Phasellus non sapien dictum', '<p>Phasellus non sapien dictum, efficitur tellus at, iaculis erat. Quisque fermentum aliquet leo quis maximus. Ut at leo dignissim, mollis nisl a, convallis nisi. Donec et consequat mauris. Nulla facilisis erat at ex porttitor, scelerisque gravida nisi cursus. Sed nec pulvinar turpis, ut vehicula mauris. Nam nec dictum elit. Fusce eleifend sollicitudin quam vitae vestibulum. Nullam auctor mauris a dui porta, eu malesuada turpis finibus. Nam ut commodo tellus. Vivamus vestibulum condimentum neque ac ultrices. Duis sodales nibh at massa fringilla, nec laoreet justo fermentum. Ut consectetur, ipsum vitae scelerisque euismod, nisi sem convallis nibh, id congue est tortor nec sapien. Sed eu ex at erat rutrum posuere. Proin dapibus et sem id aliquam. Mauris nibh nibh, blandit eu augue vitae, faucibus semper diam.</p>', '[\"vistas\\/img\\/tickets\\/1\\/774.png\"]', 'enviado', '', '2020-01-22 15:09:27'),
(39, 1, 3, 'Phasellus non sapien dictum', '<p>Phasellus non sapien dictum, efficitur tellus at, iaculis erat. Quisque fermentum aliquet leo quis maximus. Ut at leo dignissim, mollis nisl a, convallis nisi. Donec et consequat mauris. Nulla facilisis erat at ex porttitor, scelerisque gravida nisi cursus. Sed nec pulvinar turpis, ut vehicula mauris. Nam nec dictum elit. Fusce eleifend sollicitudin quam vitae vestibulum. Nullam auctor mauris a dui porta, eu malesuada turpis finibus. Nam ut commodo tellus. Vivamus vestibulum condimentum neque ac ultrices. Duis sodales nibh at massa fringilla, nec laoreet justo fermentum. Ut consectetur, ipsum vitae scelerisque euismod, nisi sem convallis nibh, id congue est tortor nec sapien. Sed eu ex at erat rutrum posuere. Proin dapibus et sem id aliquam. Mauris nibh nibh, blandit eu augue vitae, faucibus semper diam.</p>', '[\"vistas\\/img\\/tickets\\/1\\/774.png\"]', 'enviado', '', '2020-01-22 15:09:27'),
(40, 1, 4, 'Phasellus non sapien dictum', '<p>Phasellus non sapien dictum, efficitur tellus at, iaculis erat. Quisque fermentum aliquet leo quis maximus. Ut at leo dignissim, mollis nisl a, convallis nisi. Donec et consequat mauris. Nulla facilisis erat at ex porttitor, scelerisque gravida nisi cursus. Sed nec pulvinar turpis, ut vehicula mauris. Nam nec dictum elit. Fusce eleifend sollicitudin quam vitae vestibulum. Nullam auctor mauris a dui porta, eu malesuada turpis finibus. Nam ut commodo tellus. Vivamus vestibulum condimentum neque ac ultrices. Duis sodales nibh at massa fringilla, nec laoreet justo fermentum. Ut consectetur, ipsum vitae scelerisque euismod, nisi sem convallis nibh, id congue est tortor nec sapien. Sed eu ex at erat rutrum posuere. Proin dapibus et sem id aliquam. Mauris nibh nibh, blandit eu augue vitae, faucibus semper diam.</p>', '[\"vistas\\/img\\/tickets\\/1\\/774.png\"]', 'enviado', '', '2020-01-22 15:09:27'),
(41, 2, 1, 'RE:Lorem Ipsum', '<p>Aliquam ligula velit, elementum eu mollis in, feugiat et dui. Suspendisse fringilla sit amet nisi vitae dictum. Aliquam tellus tortor, euismod id lacinia sit amet, mattis et ante. Ut leo elit, rutrum ac elementum a, elementum ut lectus. In eu elementum mi. Vestibulum a elit ac lorem sagittis ultrices vitae quis elit. Nunc pretium libero in diam faucibus, ut egestas mauris egestas. Fusce pellentesque tincidunt turpis, quis pretium turpis ultrices sed. Maecenas placerat dui quis mollis porttitor.</p>', '[]', 'enviado', '', '2020-01-22 19:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `perfil` text NOT NULL,
  `nombre` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `suscripcion` int(11) NOT NULL,
  `id_suscripcion` text DEFAULT NULL,
  `ciclo_pago` int(11) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `verificacion` int(11) NOT NULL,
  `email_encriptado` text DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `enlace_afiliado` text DEFAULT NULL,
  `patrocinador` text DEFAULT NULL,
  `paypal` text DEFAULT NULL,
  `pais` text DEFAULT NULL,
  `codigo_pais` text DEFAULT NULL,
  `telefono_movil` text DEFAULT NULL,
  `firma` text DEFAULT NULL,
  `fecha_contrato` date DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `perfil`, `nombre`, `email`, `password`, `suscripcion`, `id_suscripcion`, `ciclo_pago`, `vencimiento`, `verificacion`, `email_encriptado`, `foto`, `enlace_afiliado`, `patrocinador`, `paypal`, `pais`, `codigo_pais`, `telefono_movil`, `firma`, `fecha_contrato`, `fecha`) VALUES
(1, 'admin', 'virtualearn', 'info@academyoflife.com', '$2a$07$asxx54ahjppf45sd87a5auFL5K1.Cmt9ZheoVVuudOi5BCi10qWly', 1, NULL, NULL, '2019-10-07', 1, NULL, 'vistas/img/usuarios/1/434.jpg', 'academy-of-life', NULL, 'tutorialesatualcance-buyer@hotmail.com', NULL, NULL, NULL, NULL, NULL, '2019-09-27 19:13:02'),
(2, 'usuario', 'Alexander Parra', 'alexander@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auFL5K1.Cmt9ZheoVVuudOi5BCi10qWly', 1, 'I-YN6MGVHWFHD7', 1, '2019-11-01', 1, 'aaf98bbf03aff90b36e5f1343067d5e5', 'vistas/img/usuarios/2/559.png', 'alexander-parra-2', 'academy-of-life', 'tutorialesatualcance-buyer@hotmail.com', 'Austria', 'AT', ' 43 (234) 523-5235', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"175\" height=\"35\"><path stroke-linejoin=\"round\" stroke-linecap=\"round\" stroke-width=\"1\" stroke=\"#333\" fill=\"none\" d=\"M 1 34 c 0.26 -0.1 9.81 -4.69 15 -6 c 18.38 -4.66 37.28 -8.92 56 -12 c 7.53 -1.24 19.58 -0.22 23 -1 c 0.61 -0.14 -0.14 -3.7 -1 -4 c -3.99 -1.4 -12.61 -2.77 -19 -3 c -21.93 -0.77 -64.84 0 -66 0 c -0.52 0 20.02 0.9 30 0 c 16.13 -1.45 46.73 -6.85 48 -7 c 0.14 -0.02 -4.32 1.05 -5 2 c -0.63 0.88 -0.79 4.3 0 5 c 1.5 1.33 5.92 2.37 9 3 c 8.23 1.69 16.64 3.04 25 4 c 3.3 0.38 6.72 -0.42 10 0 c 9.66 1.24 19.59 3.06 29 5 c 1.72 0.35 3.49 2 5 2 c 2.31 0 5.63 -2 8 -2 l 6 2\"/></svg>', '2019-10-01', '2019-09-27 19:13:02'),
(3, 'usuario', 'Alejandra Gomez', 'alejandra@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auFL5K1.Cmt9ZheoVVuudOi5BCi10qWly', 1, 'I-X7VH1S2SXD25', 1, '2019-10-06', 1, '2b7fb2498d971766e7304fd6e90ef330', 'vistas/img/usuarios/3/911.jpg', 'alejandra-gomez-3', 'alexander-parra-2', 'tutorialesatualcance-buyer@hotmail.com', 'Argentina', 'AR', ' 54 (234) 523-5235', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"215\" height=\"55\"><path stroke-linejoin=\"round\" stroke-linecap=\"round\" stroke-width=\"1\" stroke=\"#333\" fill=\"none\" d=\"M 27 6 c -0.07 -0.02 -2.67 -0.93 -4 -1 c -4.83 -0.25 -13.59 -2.29 -15 0 c -2.44 3.97 -5.39 23.86 -1 26 c 13.47 6.56 80.38 12.23 79 12 c -1.48 -0.25 -86.5 -13.13 -85 -13 c 3.74 0.32 179.43 26.86 213 24 c 6.59 -0.56 -0.94 -27.94 -2 -42 c -0.28 -3.74 -1.11 -9.67 -2 -11 c -0.36 -0.53 -3.52 0.98 -4 2 c -1.42 3.05 -2.37 8.61 -3 13 c -0.71 4.94 -0.23 10.18 -1 15 c -0.54 3.35 -1.71 7.2 -3 10 c -0.52 1.14 -1.89 2.9 -3 3 c -8.37 0.72 -21.38 1.24 -32 0 c -46.6 -5.45 -100.4 -13.75 -139 -20 c -1.17 -0.19 -3.37 -2.99 -3 -3 c 7.7 -0.27 58.02 0.3 88 0 l 12 -1\"/></svg>', '2019-10-01', '2019-09-27 19:13:02'),
(4, 'usuario', 'Felipe Perez', 'felipe@hotmail.com', '$2a$07$asxx54ahjppf45sd87a5auFL5K1.Cmt9ZheoVVuudOi5BCi10qWly', 1, 'I-G10RVK3G4VP5', 1, '2019-11-01', 1, '8fe863573a42ae1ec12c4d3c1d591c6d', NULL, 'felipe-perez-4', 'academy-of-life', 'tutorialesatualcance-buyer@hotmail.com', 'Argentina', 'AR', ' 54 (352) 352-3235', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"220\" height=\"29\"><path stroke-linejoin=\"round\" stroke-linecap=\"round\" stroke-width=\"1\" stroke=\"#333\" fill=\"none\" d=\"M 50 28 c 0.14 0 5.34 0.35 8 0 c 53.61 -7.02 153.04 -20.67 160 -22 c 0.85 -0.16 -12.69 -4.7 -19 -5 c -27.28 -1.31 -56.66 -2.28 -85 0 c -38.38 3.09 -114 16 -114 16\"/></svg>', '2019-10-01', '2019-09-27 19:13:02'),
(5, 'usuario', 'Maria Zuluaga', 'maria@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auFL5K1.Cmt9ZheoVVuudOi5BCi10qWly', 1, 'I-2HUG8GXPLNRA', 1, '2019-11-01', 1, 'c3a724f59d3245b0e166b278f809a9c7', NULL, 'maria-zuluaga-5', 'alexander-parra-2', 'tutorialesatualcance-buyer@hotmail.com', 'Colombia', 'CO', ' 57 (352) 352-3523', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"204\" height=\"40\"><path stroke-linejoin=\"round\" stroke-linecap=\"round\" stroke-width=\"1\" stroke=\"#333\" fill=\"none\" d=\"M 15 28 c 0.09 -0.07 3.17 -3.3 5 -4 c 6.23 -2.4 13.77 -4.62 21 -6 c 24.5 -4.69 69.7 -10.96 73 -12 c 0.88 -0.28 -12.73 -4.3 -19 -5 c -8.3 -0.92 -17.58 -1.07 -26 0 c -12.25 1.56 -25.7 4.1 -37 8 c -7.22 2.49 -14.17 7.68 -21 12 c -3.22 2.04 -7.02 4.63 -9 7 c -0.88 1.06 -1.44 3.8 -1 5 c 0.67 1.84 3.04 5.89 5 6 c 20.55 1.18 55.64 2.09 82 -1 c 20.89 -2.45 41.61 -9.98 63 -16 c 11.45 -3.22 21.89 -7.03 33 -11 c 3.15 -1.12 7.03 -4 9 -4 c 1 0 2.36 2.59 3 4 c 0.93 2.05 0.95 5.05 2 7 l 5 6\"/></svg>', '2019-10-01', '2019-09-27 19:13:02'),


--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `pagos_binaria`
--
ALTER TABLE `pagos_binaria`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indexes for table `pagos_matriz`
--
ALTER TABLE `pagos_matriz`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indexes for table `pagos_uninivel`
--
ALTER TABLE `pagos_uninivel`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indexes for table `red_binaria`
--
ALTER TABLE `red_binaria`
  ADD PRIMARY KEY (`id_binaria`);

--
-- Indexes for table `red_matriz`
--
ALTER TABLE `red_matriz`
  ADD PRIMARY KEY (`id_matriz`);

--
-- Indexes for table `red_uninivel`
--
ALTER TABLE `red_uninivel`
  ADD PRIMARY KEY (`id_uninivel`);

--
-- Indexes for table `soporte`
--
ALTER TABLE `soporte`
  ADD PRIMARY KEY (`id_soporte`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id_video`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pagos_binaria`
--
ALTER TABLE `pagos_binaria`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos_matriz`
--
ALTER TABLE `pagos_matriz`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos_uninivel`
--
ALTER TABLE `pagos_uninivel`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `red_binaria`
--
ALTER TABLE `red_binaria`
  MODIFY `id_binaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `red_matriz`
--
ALTER TABLE `red_matriz`
  MODIFY `id_matriz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `red_uninivel`
--
ALTER TABLE `red_uninivel`
  MODIFY `id_uninivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `soporte`
--
ALTER TABLE `soporte`
  MODIFY `id_soporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id_video` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
