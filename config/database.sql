-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Server Version: 5.6.34
-- PHP-Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `[database_name]`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rg_resultate`
--

CREATE TABLE IF NOT EXISTS `rg_resultate` (
  `startnr` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `kat` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `ohg` float NOT NULL DEFAULT '0',
  `seil` float NOT NULL DEFAULT '0',
  `ball` float NOT NULL DEFAULT '0',
  `reif` float NOT NULL DEFAULT '0',
  `keulen` float NOT NULL DEFAULT '0',
  `band` float NOT NULL DEFAULT '0',
  `uebung1` float NOT NULL DEFAULT '0',
  `uebung2` float NOT NULL DEFAULT '0',
  `gf_seil` float NOT NULL DEFAULT '0',
  `gf_ball` float NOT NULL DEFAULT '0',
  `gf_reif` float NOT NULL DEFAULT '0',
  `gf_keulen` float NOT NULL DEFAULT '0',
  `gf_band` float NOT NULL DEFAULT '0',
  `update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `startnr` (`startnr`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tabellenstruktur für Tabelle `rg_resultate_noten`
--

CREATE TABLE IF NOT EXISTS `rg_resultate_noten` (
  `startnr` int(11) NOT NULL,
  `element` varchar(10) NOT NULL,
  `note_d` float NOT NULL,
  `note_e` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
