-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 07 Gru 2015, 15:11
-- Wersja serwera: 5.6.25
-- Wersja PHP: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `blog`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `historia_zdarzen`
--

CREATE TABLE IF NOT EXISTS `historia_zdarzen` (
  `id` int(11) NOT NULL,
  `fk_uzytkownik` int(11) NOT NULL,
  `fk_zdarzenie` int(11) NOT NULL,
  `data_zdarzenia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adres_ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `komentarze`
--

CREATE TABLE IF NOT EXISTS `komentarze` (
  `id` int(11) NOT NULL,
  `fk_uzytkownik` int(11) NOT NULL,
  `fk_status` int(11) NOT NULL,
  `fk_wpis` int(11) NOT NULL,
  `data_komentarza` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adres_ip` varchar(15) NOT NULL,
  `tresc` varchar(130) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tagi`
--

CREATE TABLE IF NOT EXISTS `tagi` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE IF NOT EXISTS `uzytkownicy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(45) NOT NULL,
  `pass` varchar(45) NOT NULL,
  `email` varchar(65) NOT NULL,
  `data_rejestracji` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wpisy`
--

CREATE TABLE IF NOT EXISTS `wpisy` (
  `id` int(11) NOT NULL,
  `fk_uzytkownik` int(11) NOT NULL,
  `fk_status` int(11) NOT NULL,
  `temat` varchar(100) NOT NULL,
  `data_wpisu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tresc` varchar(2048) DEFAULT NULL,
  `adres_ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wpisy_tagi`
--

CREATE TABLE IF NOT EXISTS `wpisy_tagi` (
  `fk_wpis` int(11) NOT NULL,
  `fk_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdarzenia`
--

CREATE TABLE IF NOT EXISTS `zdarzenia` (
  `id` int(11) NOT NULL,
  `rodzaj` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `zdarzenia`
--

INSERT INTO `zdarzenia` (`id`, `rodzaj`) VALUES
(1, 'rejestracja'),
(2, 'zmiana danych'),
(3, 'zalogowanie'),
(4, 'wylogowanie'),
(5, 'dodanie komentarza'),
(6, 'edycja komentarza'),
(7, 'dodanie wpisu'),
(8, 'edycja wpisu'),
(9, 'dodanie taga do wpisu'),
(10, 'usuniecie taga z wpisu');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `historia_zdarzen`
--
ALTER TABLE `historia_zdarzen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uzytkownik` (`fk_uzytkownik`),
  ADD KEY `fk_zdarzenie` (`fk_zdarzenie`);

--
-- Indexes for table `komentarze`
--
ALTER TABLE `komentarze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wpis` (`fk_wpis`),
  ADD KEY `fk_uzytkownik` (`fk_uzytkownik`),
  ADD KEY `fk_status` (`fk_status`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tagi`
--
ALTER TABLE `tagi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wpisy`
--
ALTER TABLE `wpisy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uzytkownik` (`fk_uzytkownik`),
  ADD KEY `fk_status` (`fk_status`);

--
-- Indexes for table `wpisy_tagi`
--
ALTER TABLE `wpisy_tagi`
  ADD KEY `fk_wpis` (`fk_wpis`),
  ADD KEY `fk_tag` (`fk_tag`);

--
-- Indexes for table `zdarzenia`
--
ALTER TABLE `zdarzenia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `historia_zdarzen`
--
ALTER TABLE `historia_zdarzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `komentarze`
--
ALTER TABLE `komentarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `tagi`
--
ALTER TABLE `tagi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `wpisy`
--
ALTER TABLE `wpisy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `zdarzenia`
--
ALTER TABLE `zdarzenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `historia_zdarzen`
--
ALTER TABLE `historia_zdarzen`
  ADD CONSTRAINT `historia_zdarzen_ibfk_1` FOREIGN KEY (`fk_uzytkownik`) REFERENCES `uzytkownicy` (`id`),
  ADD CONSTRAINT `historia_zdarzen_ibfk_2` FOREIGN KEY (`fk_zdarzenie`) REFERENCES `zdarzenia` (`id`);

--
-- Ograniczenia dla tabeli `komentarze`
--
ALTER TABLE `komentarze`
  ADD CONSTRAINT `komentarze_ibfk_1` FOREIGN KEY (`fk_wpis`) REFERENCES `wpisy` (`id`),
  ADD CONSTRAINT `komentarze_ibfk_2` FOREIGN KEY (`fk_uzytkownik`) REFERENCES `uzytkownicy` (`id`),
  ADD CONSTRAINT `komentarze_ibfk_3` FOREIGN KEY (`fk_status`) REFERENCES `status` (`id`);

--
-- Ograniczenia dla tabeli `wpisy`
--
ALTER TABLE `wpisy`
  ADD CONSTRAINT `wpisy_ibfk_1` FOREIGN KEY (`fk_uzytkownik`) REFERENCES `uzytkownicy` (`id`),
  ADD CONSTRAINT `wpisy_ibfk_2` FOREIGN KEY (`fk_status`) REFERENCES `status` (`id`);

--
-- Ograniczenia dla tabeli `wpisy_tagi`
--
ALTER TABLE `wpisy_tagi`
  ADD CONSTRAINT `wpisy_tagi_ibfk_1` FOREIGN KEY (`fk_wpis`) REFERENCES `tagi` (`id`),
  ADD CONSTRAINT `wpisy_tagi_ibfk_2` FOREIGN KEY (`fk_tag`) REFERENCES `tagi` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
