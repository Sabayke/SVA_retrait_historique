-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 04, 2019 at 08:17 PM
-- Server version: 5.7.24
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sva`
--

-- --------------------------------------------------------

--
-- Table structure for table `code_retrait`
--

DROP TABLE IF EXISTS `code_retrait`;
CREATE TABLE IF NOT EXISTS `code_retrait` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Num_Code` int(40) NOT NULL,
  `Id_Client` varchar(40) NOT NULL,
  `Etat` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Montat` int(40) NOT NULL,
  `Date_de_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `code_retrait`
--

INSERT INTO `code_retrait` (`ID`, `Num_Code`, `Id_Client`, `Etat`, `Montat`, `Date_de_creation`) VALUES
(8, 15301357, 'sabayke', 'utilisÃ©', 500, '2019-06-04 18:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `Id_Compte` int(4) NOT NULL,
  `Id_User_Login` varchar(40) NOT NULL,
  `Solde` int(40) NOT NULL,
  `Type_Compte` varchar(40) NOT NULL,
  `code` varchar(40) NOT NULL,
  PRIMARY KEY (`Id_Compte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table pour le compte d''utilisateur';

--
-- Dumping data for table `compte`
--

INSERT INTO `compte` (`Id_Compte`, `Id_User_Login`, `Solde`, `Type_Compte`, `code`) VALUES
(1, 'sabayke', 2000, 'client', '1');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `Id_Transaction` int(4) NOT NULL AUTO_INCREMENT,
  `Id_Compte1` varchar(40) NOT NULL,
  `Id_compte2` varchar(40) NOT NULL,
  `Type_Transaction` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `frais_Transaction` int(40) NOT NULL,
  `montant` int(40) NOT NULL,
  `date_de_transaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_Transaction`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`Id_Transaction`, `Id_Compte1`, `Id_compte2`, `Type_Transaction`, `frais_Transaction`, `montant`, `date_de_transaction`) VALUES
(20, 'sabayke', 'bremso', 'retrait', 25, 500, '2019-06-04 18:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `Prenom` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Login_Id` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Nom` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `NumTel` int(4) NOT NULL,
  `Etat_Type` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `MDP` varchar(40) NOT NULL,
  `Type` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table des utilisateurs';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Prenom`, `Login_Id`, `Nom`, `NumTel`, `Etat_Type`, `MDP`, `Type`) VALUES
('elmoctar', 'sabayke', 'brahim', 773244176, 'Actif', '482f7629a2511d23ef4e958b13a5ba54bdba06f2', 'client');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
