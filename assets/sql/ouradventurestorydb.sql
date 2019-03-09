-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 09 mrt 2019 om 16:06
-- Serverversie: 10.1.38-MariaDB
-- PHP-versie: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ouradventurestorydb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `storyinfo`
--

CREATE TABLE `storyinfo` (
  `ID` int(11) NOT NULL,
  `Name` tinytext NOT NULL,
  `Description` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Introduction_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `storyinfo`
--

INSERT INTO `storyinfo` (`ID`, `Name`, `Description`, `Date`, `Introduction_ID`) VALUES
(2, 'name story', 'description story', '2019-03-09 15:53:23', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `storyparts`
--

CREATE TABLE `storyparts` (
  `ID` int(11) NOT NULL,
  `start` tinyint(1) NOT NULL,
  `end` tinyint(1) NOT NULL,
  `option_text` tinytext NOT NULL,
  `content_text` mediumtext NOT NULL,
  `question_text` tinytext NOT NULL,
  `option_IDs` tinytext NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `layer` int(11) NOT NULL,
  `image` tinytext NOT NULL,
  `parentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `storyinfo`
--
ALTER TABLE `storyinfo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexen voor tabel `storyparts`
--
ALTER TABLE `storyparts`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `storyinfo`
--
ALTER TABLE `storyinfo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `storyparts`
--
ALTER TABLE `storyparts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
