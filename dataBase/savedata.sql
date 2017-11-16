-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 16 nov. 2017 à 14:23
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `savedata`
--

-- --------------------------------------------------------

--
-- Structure de la table `test2`
--

DROP TABLE IF EXISTS `test2`;
CREATE TABLE IF NOT EXISTS `test2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_bin NOT NULL,
  `filename` varchar(255) COLLATE utf8_bin NOT NULL,
  `isSmallFile` int(1) NOT NULL,
  `isHot` int(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `test2`
--

INSERT INTO `test2` (`ID`, `description`, `filename`, `isSmallFile`, `isHot`) VALUES
(74, ' going for the hot', 'ad9n2N2_700b.jpg', 0, 5),
(73, ' going for the hot', 'ad9n2N2_700b.jpg', 0, 5),
(72, ' going for the hot', 'ad9n2N2_700b.jpg', 0, 5),
(71, ' going for the hot', 'ad9n2N2_700b.jpg', 0, 5),
(70, '2 round', '192ab03d142584759512301056_700wa_0.gif', 1, 1),
(69, '2 round', '30cafd78146412696678253242_700wa_0.gif', 0, 1),
(68, '2 round', '8b80ca72145175542292956502_700wa_0.gif', 0, 1),
(67, '1 round', 'ad9n2N2_700b.jpg', 0, 5);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
