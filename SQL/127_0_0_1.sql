-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Vært: 127.0.0.1
-- Genereringstid: 02. 12 2016 kl. 13:57:40
-- Serverversion: 10.1.9-MariaDB
-- PHP-version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hlparty2018`
--
DROP DATABASE `hlparty2018`;
CREATE DATABASE IF NOT EXISTS `hlparty2018` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `hlparty2018`;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'auto generated',
  `OneallUserToken` int(36) NOT NULL COMMENT 'Oneall login',
  `Nick` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `ZipCode` int(5) NOT NULL,
  `Birthdate` int(15) NOT NULL COMMENT 'Unixtimestamps',
  `Email` varchar(255) NOT NULL,
  `Created` int(15) NOT NULL COMMENT 'Unixtimestamps',
  `LastLogin` int(15) NOT NULL COMMENT 'Unixtimestamps',
  `FK_StatusID` int(11) NOT NULL COMMENT 'new,active,admin.',
  `PW` varchar(128) NOT NULL,
  `Bio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `OneallUserToken` (`OneallUserToken`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto generated';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
