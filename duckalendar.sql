-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 04 Mai 2013 à 12:00
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `duckalendar`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `beginTime` time NOT NULL,
  `endTime` time NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`login`,`date`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `events`
--

INSERT INTO `events` (`login`, `date`, `name`, `desc`, `beginTime`, `endTime`, `endDate`) VALUES
('Natsirtt', '2013-05-05', 'lmkm', '', '07:00:00', '09:00:00', '2013-12-31'),
('Natsirtt', '2013-05-06', 'lskk', '', '07:00:00', '09:00:00', '2013-12-31');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `login` varchar(255) NOT NULL,
  `noWorkColor` varchar(7) NOT NULL,
  `hasEventColor` varchar(7) NOT NULL,
  `incomingEventsDaysNb` int(11) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`login`, `noWorkColor`, `hasEventColor`, `incomingEventsDaysNb`) VALUES
('Natsirtt', '#243537', '#410f0f', 7);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`login`, `password`, `salt`, `ip`) VALUES
('Natsirtt', '?pjuUXkq6m8V6', '?p/''.?!5hl;t?3w#.sa6,84o5%.]y!,1', '127.0.0.1');
