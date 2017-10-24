-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 24-Out-2017 às 10:08
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projetox`
--
CREATE DATABASE IF NOT EXISTS `projetox` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `projetox`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lot`
--

CREATE TABLE IF NOT EXISTS `lot` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` int(10) UNSIGNED NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `valuation` decimal(10,0) NOT NULL,
  `creator` int(10) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_activated` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `creator` (`creator`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password`
--

CREATE TABLE IF NOT EXISTS `password` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reset`
--

CREATE TABLE IF NOT EXISTS `reset` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_activated` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lot` int(10) UNSIGNED NOT NULL,
  `seller` int(11) UNSIGNED NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `owner_cpf` varchar(12) NOT NULL,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `seller_2` (`seller`),
  KEY `lot` (`lot`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `password` int(10) UNSIGNED DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_activated` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `password` (`password`),
  KEY `PASSWORD_ID` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `lot`
--
ALTER TABLE `lot`
  ADD CONSTRAINT `lot_user` FOREIGN KEY (`creator`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `reset`
--
ALTER TABLE `reset`
  ADD CONSTRAINT `reset_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `lot_ticket` FOREIGN KEY (`lot`) REFERENCES `lot` (`id`),
  ADD CONSTRAINT `sellet_ticket` FOREIGN KEY (`seller`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_password` FOREIGN KEY (`password`) REFERENCES `password` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
