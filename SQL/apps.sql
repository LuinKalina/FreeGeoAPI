-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2015 at 12:23 PM
-- Server version: 5.6.17-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `freegeo`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Email` varchar(500) NOT NULL,
  `AppTitle` varchar(255) NOT NULL,
  `AppDescription` varchar(500) NOT NULL,
  `AppWebsite` varchar(500) NOT NULL,
  `PublicKey` varchar(500) NOT NULL,
  `SecretKey` varchar(500) NOT NULL,
  `Status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`id`, `FirstName`, `LastName`, `Email`, `AppTitle`, `AppDescription`, `AppWebsite`, `PublicKey`, `SecretKey`, `Status`) VALUES
(1, 'Zac', 'Brown', 'zbrown@live.com', 'Batman', 'A demo app for the FreeGeoDB API', 'http://www.yupitszac.com', '47e838ad7cef8bb51a503d3c35f74ba8', 'd3e9bb9c59881b16ac460ee23822ba68', 'Active');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
