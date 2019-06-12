-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 12, 2019 at 06:10 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_banking`
--

-- --------------------------------------------------------

--
-- Table structure for table `code_retrait`
--

DROP TABLE IF EXISTS `code_retrait`;
CREATE TABLE IF NOT EXISTS `code_retrait` (
  `Id` varchar(4) COLLATE utf8_bin NOT NULL,
  `Num_Code` int(40) NOT NULL,
  `Id_Client` varchar(40) COLLATE utf8_bin NOT NULL,
  `Etat` varchar(10) COLLATE utf8_bin NOT NULL,
  `Montat` int(40) NOT NULL,
  `Date_de_Creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `Id_Client` (`Id_Client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `code_retrait`
--

INSERT INTO `code_retrait` (`Id`, `Num_Code`, `Id_Client`, `Etat`, `Montat`, `Date_de_Creation`) VALUES
('16', 2263, 'sabayke', 'inutilisÃ©', 200, '2019-06-12 17:57:29'),
('28', 7061, 'sabayke', 'utilisÃ©', 200, '2019-06-12 18:04:40'),
('4', 6692, 'sabayke', 'utilisÃ©', 200, '2019-06-12 18:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `Id_Compte` varchar(40) NOT NULL,
  `Id_User` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Solde` int(40) UNSIGNED NOT NULL,
  `Type_Compte` varchar(40) NOT NULL,
  `code` varchar(40) NOT NULL,
  PRIMARY KEY (`Id_Compte`),
  KEY `Id_User` (`Id_User`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table pour le compte d''utilisateur';

--
-- Dumping data for table `compte`
--

INSERT INTO `compte` (`Id_Compte`, `Id_User`, `Solde`, `Type_Compte`, `code`) VALUES
('1', 'sabayke', 2075, 'client', '1');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `Id_Transaction` int(4) NOT NULL AUTO_INCREMENT,
  `Id_Compte_1` varchar(40) NOT NULL,
  `Id_Compte_2` varchar(40) NOT NULL,
  `Type_Transaction` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Frais_Transaction` int(40) NOT NULL,
  `Montant` int(40) NOT NULL,
  `Date_Transaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_Transaction`),
  KEY `transaction_ibfk_1` (`Id_Compte_1`),
  KEY `transaction_ibfk_2` (`Id_Compte_2`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`Id_Transaction`, `Id_Compte_1`, `Id_Compte_2`, `Type_Transaction`, `Frais_Transaction`, `Montant`, `Date_Transaction`) VALUES
(13, '1', '1', 'retrait', 25, 200, '2019-06-12 18:01:51'),
(60, '1', '1', 'retrait', 25, 500, '2019-06-12 17:29:52'),
(69, '1', '1', 'retrait', 25, 200, '2019-06-12 18:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `Prenom` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Login` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Nom` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Num_Tel` int(4) NOT NULL,
  `Etat_Type` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Mot_de_Passe` varchar(40) NOT NULL,
  `Type` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`Login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table des utilisateurs';

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`Prenom`, `Login`, `Nom`, `Num_Tel`, `Etat_Type`, `Mot_de_Passe`, `Type`) VALUES
('elmoctar', 'sabayke', 'brahim', 773244176, 'Actif', '482f7629a2511d23ef4e958b13a5ba54bdba06f2', 'client');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `code_retrait`
--
ALTER TABLE `code_retrait`
  ADD CONSTRAINT `Id_Client` FOREIGN KEY (`Id_Client`) REFERENCES `compte` (`Id_User`);

--
-- Constraints for table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `utilisateur` (`Login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`Id_Compte_1`) REFERENCES `compte` (`Id_Compte`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`Id_Compte_2`) REFERENCES `compte` (`Id_Compte`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
