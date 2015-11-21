-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2015 at 12:26 PM
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
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `Session` varchar(500) NOT NULL,
  `Public` varchar(500) NOT NULL,
  `Secret` varchar(500) NOT NULL,
  `AppId` varchar(255) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `Session`, `Public`, `Secret`, `AppId`, `Timestamp`) VALUES
(4, 'f10820d9fc0f8c7f3fb09b81c504f428', '62146385036f4f208f0550f7a88dad58', 'ef9f0bc594926260c12052c46b0598b8', '1', '2015-10-23 10:13:48'),
(5, 'fb6c4fd77382dbc6d9c2975112d84baa', '62146385036f4f208f0550f7a88dad58', 'ef9f0bc594926260c12052c46b0598b8', '1', '2015-10-27 19:54:20');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
