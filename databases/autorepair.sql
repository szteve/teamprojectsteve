-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2016 at 08:29 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autorepair`
--
DROP DATABASE `autorepair`;
CREATE DATABASE IF NOT EXISTS `autorepair` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `autorepair`;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
CREATE TABLE `car` (
  `idcar` int(11) NOT NULL,
  `owner` varchar(64) DEFAULT NULL,
  `make` varchar(64) DEFAULT NULL,
  `model` varchar(64) DEFAULT NULL,
  `colour` varchar(32) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `idlogs` int(11) NOT NULL,
  `type` char(3) DEFAULT NULL,
  `user` varchar(64) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `idorder` int(11) NOT NULL,
  `idpart` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `completed` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

DROP TABLE IF EXISTS `part`;
CREATE TABLE `part` (
  `idpart` int(11) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partreplaced`
--

DROP TABLE IF EXISTS `partreplaced`;
CREATE TABLE `partreplaced` (
  `idpart` int(11) NOT NULL,
  `idrepair` int(11) DEFAULT NULL,
  `partReplacedcol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `idpayment` int(11) NOT NULL,
  `price` double DEFAULT NULL,
  `paid` char(1) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idrepair` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

DROP TABLE IF EXISTS `repair`;
CREATE TABLE `repair` (
  `idrepair` int(11) NOT NULL,
  `idcar` int(11) DEFAULT NULL,
  `mechanicEmail` varchar(64) DEFAULT NULL,
  `status` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `repairqueue`
--

DROP TABLE IF EXISTS `repairqueue`;
CREATE TABLE `repairqueue` (
  `idrepair` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `mechanicEmail` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `email` varchar(64) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `salt` char(16) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` char(3) NOT NULL,
  `addr1` varchar(255) DEFAULT NULL,
  `addr2` varchar(255) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `name`, `password`, `salt`, `create_time`, `type`, `addr1`, `addr2`, `phone`) VALUES
('admin@ars.com', 'Admin', '7947dec0844982836e2e63cf590d73fa218f019fcbe1b30ef1c0dd6c88e49bf5', '6e9e31042370cf9', '2016-03-13 19:07:59', 'ADM', NULL, NULL, NULL),
('booking@ars.com', 'Booking Clerk', '4f24d6088ce52000effde4b4a277b6189e8f05738d1e34d98691af1d7d59abe6', '3edcdfc6330d8bc6', '2016-03-13 19:09:41', 'BKC', NULL, NULL, NULL),
('customer@ars.com', 'Customer', 'b1059eb2e1c10a914be844a096db1aa14b5e3a7c79c8cd7c4987e202665936ca', '4cc7baff171a08c8', '2016-03-13 19:09:23', 'CSM', NULL, NULL, NULL),
('mechanic@ars.com', 'Mechanic', '32adb31aea063e8263f1a0545336cc80f6b57bf85817d820b646e8ad1a59c095', '7608eb4653fc757', '2016-03-13 19:09:51', 'MEC', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workdone`
--

DROP TABLE IF EXISTS `workdone`;
CREATE TABLE `workdone` (
  `idrepair` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`idcar`),
  ADD KEY `owner_idx` (`owner`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`idlogs`),
  ADD KEY `user_idx` (`user`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`idorder`),
  ADD KEY `idpart_idx` (`idpart`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`idpart`);

--
-- Indexes for table `partreplaced`
--
ALTER TABLE `partreplaced`
  ADD KEY `idpart_idx` (`idpart`),
  ADD KEY `idreapir_idx` (`idrepair`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`idpayment`),
  ADD KEY `idrepair_idx` (`idrepair`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`idrepair`),
  ADD KEY `mechanicEmail_idx` (`mechanicEmail`),
  ADD KEY `idcar_idx` (`idcar`);

--
-- Indexes for table `repairqueue`
--
ALTER TABLE `repairqueue`
  ADD PRIMARY KEY (`idrepair`),
  ADD KEY `mechanicEmail_idx` (`mechanicEmail`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `workdone`
--
ALTER TABLE `workdone`
  ADD KEY `idrepair_idx` (`idrepair`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `owner` FOREIGN KEY (`owner`) REFERENCES `user` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `user` FOREIGN KEY (`user`) REFERENCES `user` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `idpart` FOREIGN KEY (`idpart`) REFERENCES `part` (`idpart`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `partreplaced`
--
ALTER TABLE `partreplaced`
  ADD CONSTRAINT `idpart2` FOREIGN KEY (`idpart`) REFERENCES `part` (`idpart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idrepair2` FOREIGN KEY (`idrepair`) REFERENCES `repair` (`idrepair`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `idrepair` FOREIGN KEY (`idrepair`) REFERENCES `repair` (`idrepair`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `repair`
--
ALTER TABLE `repair`
  ADD CONSTRAINT `idcar` FOREIGN KEY (`idcar`) REFERENCES `car` (`idcar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mechanicEmail` FOREIGN KEY (`mechanicEmail`) REFERENCES `user` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `repairqueue`
--
ALTER TABLE `repairqueue`
  ADD CONSTRAINT `idrepair4` FOREIGN KEY (`idrepair`) REFERENCES `repair` (`idrepair`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mechanicEmail2` FOREIGN KEY (`mechanicEmail`) REFERENCES `user` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `workdone`
--
ALTER TABLE `workdone`
  ADD CONSTRAINT `idrepair3` FOREIGN KEY (`idrepair`) REFERENCES `repair` (`idrepair`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
