-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 09. Apr 2019 um 00:44
-- Server-Version: 10.0.38-MariaDB-0+deb8u1
-- PHP-Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `amu_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_AMU_LOG`
--

CREATE TABLE `TBL_AMU_LOG` (
  `ID` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `log_type` varchar(100) NOT NULL,
  `log_file` varchar(100) NOT NULL,
  `log_message` varchar(500) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_APPOINTMENTS`
--

CREATE TABLE `TBL_APPOINTMENTS` (
  `ID` int(5) NOT NULL,
  `band_id` int(5) NOT NULL,
  `location_id` int(5) DEFAULT NULL,
  `appointment_date` timestamp NULL DEFAULT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_BANDINFO`
--

CREATE TABLE `TBL_BANDINFO` (
  `ID` int(5) NOT NULL,
  `name` varchar(120) NOT NULL,
  `logo_path` varchar(120) DEFAULT 'default.jpg',
  `website_url` varchar(120) DEFAULT NULL,
  `notes` varchar(2500) DEFAULT NULL,
  `leader_id` int(5) DEFAULT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_BANDMEMBERS`
--

CREATE TABLE `TBL_BANDMEMBERS` (
  `ID` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `band_id` int(5) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_LOCATIONS`
--

CREATE TABLE `TBL_LOCATIONS` (
  `ID` int(5) NOT NULL,
  `name` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact_person_id` int(5) DEFAULT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_OFFER`
--

CREATE TABLE `TBL_OFFER` (
  `ID` int(5) NOT NULL,
  `location_id` int(5) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `offer_state` tinyint(1) NOT NULL DEFAULT '0',
  `offer_date` varchar(100) DEFAULT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `record_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text_gage` varchar(1000) DEFAULT NULL,
  `text_paytype` varchar(1000) DEFAULT NULL,
  `text_more_hours` varchar(1000) DEFAULT NULL,
  `text_breakfast` varchar(1000) DEFAULT NULL,
  `text_food` varchar(1000) DEFAULT NULL,
  `text_punitive` varchar(1000) DEFAULT NULL,
  `text_fees` varchar(1000) DEFAULT NULL,
  `text_replacement` varchar(1000) DEFAULT NULL,
  `text_other` varchar(1000) DEFAULT NULL,
  `text_head` varchar(2500) DEFAULT NULL,
  `text_foot` varchar(2500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_OFFER_BANDS`
--

CREATE TABLE `TBL_OFFER_BANDS` (
  `ID` int(5) NOT NULL,
  `offer_id` int(5) NOT NULL,
  `band_id` int(5) NOT NULL,
  `price` double(10,2) NOT NULL,
  `offer_band_chosen` tinyint(1) NOT NULL DEFAULT '0',
  `record_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TBL_USERS`
--

CREATE TABLE `TBL_USERS` (
  `ID` int(5) NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  `phone_number` varchar(120) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `mail` varchar(120) DEFAULT NULL,
  `notes` varchar(2500) DEFAULT NULL,
  `user_type` int(5) DEFAULT NULL,
  `user_description` varchar(200) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `session_key` varchar(50) DEFAULT NULL,
  `session_date` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `TBL_AMU_LOG`
--
ALTER TABLE `TBL_AMU_LOG`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `TBL_APPOINTMENTS`
--
ALTER TABLE `TBL_APPOINTMENTS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_AppointmentBandId` (`band_id`),
  ADD KEY `FK_AppointmentLocId` (`location_id`);

--
-- Indizes für die Tabelle `TBL_BANDINFO`
--
ALTER TABLE `TBL_BANDINFO`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `BANDINFO_unique` (`name`,`logo_path`,`website_url`),
  ADD KEY `FK_LeaderId` (`leader_id`);

--
-- Indizes für die Tabelle `TBL_BANDMEMBERS`
--
ALTER TABLE `TBL_BANDMEMBERS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_BandId` (`band_id`),
  ADD KEY `FK_UserId` (`user_id`);

--
-- Indizes für die Tabelle `TBL_LOCATIONS`
--
ALTER TABLE `TBL_LOCATIONS`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `TBL_OFFER`
--
ALTER TABLE `TBL_OFFER`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_LocationId` (`location_id`),
  ADD KEY `FK_OfferUserId` (`user_id`);

--
-- Indizes für die Tabelle `TBL_OFFER_BANDS`
--
ALTER TABLE `TBL_OFFER_BANDS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_OfferId` (`offer_id`),
  ADD KEY `FK_OfferBandId` (`band_id`);

--
-- Indizes für die Tabelle `TBL_USERS`
--
ALTER TABLE `TBL_USERS`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERS_unique` (`mail`,`username`,`phone_number`) USING BTREE,
  ADD UNIQUE KEY `UC_Users` (`username`,`mail`,`phone_number`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `TBL_AMU_LOG`
--
ALTER TABLE `TBL_AMU_LOG`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_APPOINTMENTS`
--
ALTER TABLE `TBL_APPOINTMENTS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_BANDINFO`
--
ALTER TABLE `TBL_BANDINFO`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_BANDMEMBERS`
--
ALTER TABLE `TBL_BANDMEMBERS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_LOCATIONS`
--
ALTER TABLE `TBL_LOCATIONS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_OFFER`
--
ALTER TABLE `TBL_OFFER`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_OFFER_BANDS`
--
ALTER TABLE `TBL_OFFER_BANDS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `TBL_USERS`
--
ALTER TABLE `TBL_USERS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `TBL_APPOINTMENTS`
--
ALTER TABLE `TBL_APPOINTMENTS`
  ADD CONSTRAINT `FK_AppointmentBandId` FOREIGN KEY (`band_id`) REFERENCES `TBL_BANDINFO` (`ID`),
  ADD CONSTRAINT `FK_AppointmentLocId` FOREIGN KEY (`location_id`) REFERENCES `TBL_LOCATIONS` (`ID`);

--
-- Constraints der Tabelle `TBL_BANDINFO`
--
ALTER TABLE `TBL_BANDINFO`
  ADD CONSTRAINT `FK_LeaderId` FOREIGN KEY (`leader_id`) REFERENCES `TBL_USERS` (`ID`);

--
-- Constraints der Tabelle `TBL_BANDMEMBERS`
--
ALTER TABLE `TBL_BANDMEMBERS`
  ADD CONSTRAINT `FK_BandId` FOREIGN KEY (`band_id`) REFERENCES `TBL_BANDINFO` (`ID`),
  ADD CONSTRAINT `FK_UserId` FOREIGN KEY (`user_id`) REFERENCES `TBL_USERS` (`ID`);

--
-- Constraints der Tabelle `TBL_OFFER`
--
ALTER TABLE `TBL_OFFER`
  ADD CONSTRAINT `FK_LocationId` FOREIGN KEY (`location_id`) REFERENCES `TBL_LOCATIONS` (`ID`),
  ADD CONSTRAINT `FK_OfferUserId` FOREIGN KEY (`user_id`) REFERENCES `TBL_USERS` (`ID`);

--
-- Constraints der Tabelle `TBL_OFFER_BANDS`
--
ALTER TABLE `TBL_OFFER_BANDS`
  ADD CONSTRAINT `FK_OfferBandId` FOREIGN KEY (`band_id`) REFERENCES `TBL_BANDINFO` (`ID`),
  ADD CONSTRAINT `FK_OfferId` FOREIGN KEY (`offer_id`) REFERENCES `TBL_OFFER` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
