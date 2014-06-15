-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 14, 2014 at 10:33 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `instance`
--

CREATE TABLE IF NOT EXISTS `instance` (
  `instanceID` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Alias` varchar(255) NOT NULL,
  PRIMARY KEY (`instanceID`,`email`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instance_alert`
--

CREATE TABLE IF NOT EXISTS `instance_alert` (
  `instanceID` varchar(255) NOT NULL,
  `option-status` int(11) NOT NULL,
  `proc-r` varchar(11) NOT NULL,
  `proc-b` varchar(11) NOT NULL,
  `mem-swpd` varchar(11) NOT NULL,
  `mem-free` varchar(11) NOT NULL,
  `mem-buff` varchar(11) NOT NULL,
  `mem-cache` varchar(11) NOT NULL,
  `swap-si` varchar(11) NOT NULL,
  `swap-so` varchar(11) NOT NULL,
  `io-bi` varchar(11) NOT NULL,
  `io-bo` varchar(11) NOT NULL,
  `system-in` varchar(11) NOT NULL,
  `system-cs` varchar(11) NOT NULL,
  `cpu-us` varchar(11) NOT NULL,
  `cpu-sy` varchar(11) NOT NULL,
  `cpu-id` varchar(11) NOT NULL,
  `cpu-wa` varchar(11) NOT NULL,
  `net-rxSum` varchar(11) NOT NULL,
  `net-txSum` varchar(11) NOT NULL,
  `process-status` varchar(11) NOT NULL,
  `process-cpu` varchar(11) NOT NULL,
  `process-mem` varchar(11) NOT NULL,
  `fs-used` varchar(11) NOT NULL,
  `op-proc-r` varchar(11) NOT NULL,
  `op-proc-b` varchar(11) NOT NULL,
  `op-mem-swpd` varchar(11) NOT NULL,
  `op-mem-free` varchar(11) NOT NULL,
  `op-mem-buff` varchar(11) NOT NULL,
  `op-mem-cache` varchar(11) NOT NULL,
  `op-swap-si` varchar(11) NOT NULL,
  `op-swap-so` varchar(11) NOT NULL,
  `op-io-bi` varchar(11) NOT NULL,
  `op-io-bo` varchar(11) NOT NULL,
  `op-system-in` varchar(11) NOT NULL,
  `op-system-cs` varchar(11) NOT NULL,
  `op-cpu-us` varchar(11) NOT NULL,
  `op-cpu-sy` varchar(11) NOT NULL,
  `op-cpu-id` varchar(11) NOT NULL,
  `op-cpu-wa` varchar(11) NOT NULL,
  `op-net-rxSum` varchar(11) NOT NULL,
  `op-net-txSum` varchar(11) NOT NULL,
  `op-process-cpu` varchar(11) NOT NULL,
  `op-process-mem` varchar(11) NOT NULL,
  `op-process-status` varchar(255) NOT NULL,
  `op-fs-used` varchar(11) NOT NULL,
  PRIMARY KEY (`instanceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instance_info`
--

CREATE TABLE IF NOT EXISTS `instance_info` (
  `kernelName` varchar(255) NOT NULL,
  `nodeName` varchar(255) NOT NULL,
  `kernelRelease` varchar(255) NOT NULL,
  `kernelVersion` varchar(255) NOT NULL,
  `hardwareName` varchar(255) NOT NULL,
  `operatingSystem` varchar(255) NOT NULL,
  `instanceID` varchar(255) NOT NULL,
  PRIMARY KEY (`instanceID`),
  KEY `instanceID` (`instanceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instance_link`
--

CREATE TABLE IF NOT EXISTS `instance_link` (
  `instanceID_one` varchar(255) NOT NULL,
  `instanceID_two` varchar(255) NOT NULL,
  PRIMARY KEY (`instanceID_one`,`instanceID_two`),
  KEY `instanceID_two` (`instanceID_two`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `email` varchar(255) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `instance`
--
ALTER TABLE `instance`
  ADD CONSTRAINT `instance_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`);

--
-- Constraints for table `instance_info`
--
ALTER TABLE `instance_info`
  ADD CONSTRAINT `instance_info_ibfk_2` FOREIGN KEY (`instanceID`) REFERENCES `instance` (`instanceID`);

--
-- Constraints for table `instance_link`
--
ALTER TABLE `instance_link`
  ADD CONSTRAINT `instance_link_ibfk_1` FOREIGN KEY (`instanceID_one`) REFERENCES `instance` (`instanceID`),
  ADD CONSTRAINT `instance_link_ibfk_2` FOREIGN KEY (`instanceID_two`) REFERENCES `instance` (`instanceID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
