-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Dez 2021 um 14:09
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `blog_v1`
--
CREATE DATABASE IF NOT EXISTS `blog_v1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_v1`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog`
--

CREATE TABLE `blog` (
  `blogID` int(11) NOT NULL,
  `blogHeadline` varchar(255) NOT NULL,
  `blogImagePath` varchar(255) DEFAULT NULL,
  `blogImageAlignment` varchar(20) NOT NULL,
  `blogContent` text NOT NULL,
  `blogDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `catID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `blog`
--

INSERT INTO `blog` (`blogID`, `blogHeadline`, `blogImagePath`, `blogImageAlignment`, `blogContent`, `blogDate`, `catID`, `userID`) VALUES
(13, 'Die verführerische Kunst des Katzenzwinkerns', 'uploads/blogimages/1167868920u_1w7bjmg4nhpqtva-5o6ec8-d2xlr3zf6008_93751i9k2y4s0352752001697988706.jpg', 'fright', 'Das Katzenzwinkern, diese geheimnisvolle Geste, fasziniert Katzenliebhaber auf der ganzen Welt. Wenn Ihre Katze langsam blinzelt und ihre Augenlider sanft schließt und öffnet, können Sie sicher sein, dass eine Botschaft übermittelt wird.\r\n\r\nDieses zwinkernde Verhalten ist ein Zeichen des Vertrauens und der Zuneigung. Ihre Katze sagt Ihnen damit, dass sie sich in Ihrer Gegenwart sicher und wohl fühlt. Das Zurückzwinkern von Ihnen kann diese besondere Verbindung noch verstärken.\r\n\r\nJede Katze hat ihre eigenen Wege, Zuneigung auszudrücken, und nicht alle Katzen zwinkern. Aber wenn Ihre Katze es tut, können Sie sicher sein, dass es ein Augenblick der Verbundenheit und des Vertrauens ist, den Sie in vollen Zügen genießen sollten.', '2023-10-22 15:31:46', 5, 1),
(14, 'Die Faszinierende Bindung: Ein Löwe und ein Mädchen', 'uploads/blogimages/1631736855-13adeuqg4_c6ls3n7korzt2hm16f5ij89bv87-9w4x0y_p0520711627001697988821.jpg', 'fleft', 'In den weiten Ebenen eines abgelegenen Safariparks entstand eine ungewöhnliche Freundschaft. Simba, ein majestätischer Löwe, und Mia, ein lebhaftes Mädchen aus einem nahegelegenen Dorf, teilten eine Verbindung, die alle Erwartungen übertraf. Trotz ihrer offensichtlichen Unterschiede fanden sie Trost und Freundschaft im Miteinander. Ihre Geschichte erinnert uns daran, dass wahre Bindungen keine Grenzen kennen und oft in den unerwartetsten Begegnungen entstehen.', '2023-10-22 15:33:41', 5, 1),
(15, 'Der Meister der Faulheit: Max, der Hund', 'uploads/blogimages/970652465wu_6bon3sy_ef9p05hd4718aizrv7-x48m52t6cq-913kg02lj0975265001697988881.jpg', 'fright', 'Treffen Sie Max, den Faulenzer unter den Hunden. Er hat es perfektioniert, sich nicht zu bewegen, und liegt am liebsten den ganzen Tag auf seinem bequemen Kissen. Max erinnert uns daran, dass es manchmal in Ordnung ist, einfach mal abzuschalten und das Nichtstun zu genießen.', '2023-10-22 15:34:41', 5, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `catID` int(11) NOT NULL,
  `catLabel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`catID`, `catLabel`) VALUES
(1, 'Lifestyle'),
(2, 'Food'),
(3, 'Mobile'),
(4, 'Living'),
(5, 'Tiere');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `userFirstName` varchar(255) NOT NULL,
  `userLastName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userCity` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userID`, `userFirstName`, `userLastName`, `userEmail`, `userCity`, `userPassword`) VALUES
(1, 'Peter', 'Peterson', 'admin@admin.com', 'New York', '$2y$10$tbCYcuHF/flLur6pSSpMheR5DKA2io7T9TcE/Gw3Q/2aulfoQiGD2');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blogID`),
  ADD KEY `blog_ibfk_1` (`userID`),
  ADD KEY `blog_ibfk_2` (`catID`);

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `blog`
--
ALTER TABLE `blog`
  MODIFY `blogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`catID`) REFERENCES `category` (`catID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
