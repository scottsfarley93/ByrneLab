-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2015 at 09:30 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Calpalyn`
--

-- --------------------------------------------------------

--
-- Table structure for table `ChronologyFiles`
--

CREATE TABLE IF NOT EXISTS `ChronologyFiles` (
`ChronologyID` int(11) NOT NULL,
  `CoreID` text NOT NULL,
  `NumLevels` int(11) NOT NULL,
  `MinDepth` double NOT NULL,
  `MaxDepth` double NOT NULL,
  `MinAge` double NOT NULL,
  `MaxAge` double NOT NULL,
  `FileReference` longtext NOT NULL,
  `LastModified` date NOT NULL,
  `Uploaded` date NOT NULL,
  `Version` int(11) NOT NULL,
  `User` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ChronologyFiles`
--

INSERT INTO `ChronologyFiles` (`ChronologyID`, `CoreID`, `NumLevels`, `MinDepth`, `MaxDepth`, `MinAge`, `MaxAge`, `FileReference`, `LastModified`, `Uploaded`, `Version`, `User`) VALUES
(4, 'null', 56, 0, 403.5, 5.8, 68.6, '../datafiles/scottsfarley_null_Chronology.csv', '2014-10-27', '2015-01-31', 1, 'scottsfarley'),
(5, 'Test Core 4', 56, 0, 403.5, 5.8, 68.6, '../datafiles/scottsfarley_Test Core 4_Chronology.csv', '2014-10-27', '2015-01-31', 1, 'scottsfarley');

-- --------------------------------------------------------

--
-- Table structure for table `Cores`
--

CREATE TABLE IF NOT EXISTS `Cores` (
`CoreID` int(11) NOT NULL,
  `CoreName` longtext NOT NULL,
  `SiteName` longtext NOT NULL,
  `MinAge` double NOT NULL,
  `MaxAge` double NOT NULL,
  `MinDepth` double NOT NULL,
  `MaxDepth` double NOT NULL,
  `Latitude` double NOT NULL,
  `Longitude` double NOT NULL,
  `WaterDepth` double NOT NULL,
  `DateCored` date NOT NULL,
  `User` longtext NOT NULL,
  `Protect` int(11) NOT NULL,
  `Password` longtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `Cores`
--

INSERT INTO `Cores` (`CoreID`, `CoreName`, `SiteName`, `MinAge`, `MaxAge`, `MinDepth`, `MaxDepth`, `Latitude`, `Longitude`, `WaterDepth`, `DateCored`, `User`, `Protect`, `Password`) VALUES
(9, 'Test Core 3', 'Clear Lake, CA', 0, 5000, 0, 168, 39.0616, -122.8272, 200, '1993-04-06', 'scottsfarley', 0, 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e'),
(10, 'Test Core 4', 'Somewhere in California', -9999, -9999, -9999, -9999, -9999, -9999, -9999, '1900-01-01', 'scottsfarley', 0, 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e');

-- --------------------------------------------------------

--
-- Table structure for table `Datafiles`
--

CREATE TABLE IF NOT EXISTS `Datafiles` (
`DatafileID` int(11) NOT NULL,
  `DatafileName` text NOT NULL,
  `CoreID` text NOT NULL,
  `NumLevels` int(11) NOT NULL,
  `NumTaxa` int(11) NOT NULL,
  `FileReference` text NOT NULL,
  `MinDepth` double NOT NULL,
  `MaxDepth` double NOT NULL,
  `LastModified` date NOT NULL,
  `Uploaded` date NOT NULL,
  `Version` double NOT NULL,
  `User` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `Datafiles`
--

INSERT INTO `Datafiles` (`DatafileID`, `DatafileName`, `CoreID`, `NumLevels`, `NumTaxa`, `FileReference`, `MinDepth`, `MaxDepth`, `LastModified`, `Uploaded`, `Version`, `User`) VALUES
(14, 'Test Core 3 Data 1', 'Test Core 3', 56, 4, '../datafiles/scottsfarley_favre_diatoms.csv', 0, 403.5, '2014-10-27', '2015-01-31', 5, 'scottsfarley'),
(17, 'Core 4 Pollen', 'Test Core 4', 56, 4, '../datafiles/scottsfarley_favre_diatoms.csv', 0, 403.5, '2014-10-27', '2015-01-31', 2, 'scottsfarley'),
(18, 'Test 1C', 'Test Core 4', 56, 4, '../datafiles/scottsfarley_favre_diatoms.csv', 0, 403.5, '2014-10-27', '2015-02-02', 1, 'scottsfarley'),
(19, 'Bolinas Data Test Core 3', 'Test Core 3', 54, 53, '../datafiles/scottsfarley_bolinas.data.csv', 0, 312, '2014-10-29', '2015-02-03', 1, 'scottsfarley');

-- --------------------------------------------------------

--
-- Table structure for table `SavedProjects`
--

CREATE TABLE IF NOT EXISTS `SavedProjects` (
`ProjectIndex` int(11) NOT NULL,
  `User` text NOT NULL,
  `Core` text NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FileReference` longtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `SavedProjects`
--

INSERT INTO `SavedProjects` (`ProjectIndex`, `User`, `Core`, `LastUpdated`, `FileReference`) VALUES
(1, 'scottsfarley', 'Test Core 4', '2015-03-19 19:56:59', '../savedProjects/scottsfarley_Test+Core+4_1426795019.txt'),
(2, 'scottsfarley', 'Test Core 4', '2015-03-19 19:57:29', '../savedProjects/scottsfarley_Test+Core+4_1426795049.txt'),
(3, 'scottsfarley', 'Test Core 4', '2015-03-19 20:07:36', '../savedProjects/scottsfarley_Test+Core+4_1426795656.txt'),
(4, 'scottsfarley', '', '2015-03-19 20:12:39', '../savedProjects/scottsfarley__1426795959.txt'),
(5, 'scottsfarley', 'Test Core 4', '2015-03-19 20:22:17', '../savedProjects/scottsfarley_Test+Core+4_1426796537.txt'),
(6, 'scottsfarley', 'Test Core 4', '2015-03-19 20:22:53', '../savedProjects/scottsfarley_Test+Core+4_1426796573.txt'),
(7, 'scottsfarley', 'Test Core 4', '2015-03-19 20:27:15', '../savedProjects/scottsfarley_Test+Core+4_1426796835.cpn'),
(8, 'scottsfarley', 'Test Core 4', '2015-03-19 20:27:56', '../savedProjects/scottsfarley_Test+Core+4_1426796876.cpn'),
(9, 'scottsfarley', 'Test Core 4', '2015-03-19 20:29:33', '../savedProjects/scottsfarley_Test+Core+4_1426796973.cpn');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
`UserID` int(11) NOT NULL,
  `Email` text NOT NULL,
  `Name` text NOT NULL,
  `Password` longtext NOT NULL,
  `DateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `Email`, `Name`, `Password`, `DateJoined`) VALUES
(1, 'scottsfarley@gmail.com', 'Scott Sherwin Farley', '4e6636904506a07b0eed49473d4688c33a885a7865edc54e9f2be5981d1eed247adf269c1ce4e69e6f535ccaf4164c3245f92d322331fc2837eb039f1992d1ed', '2015-01-24 00:10:13'),
(2, 'scottsfarley@berkeley.edu', 'Scott Farley', '5cf30743142be4b703c07ead9fe79c4dfcfaf4f0d70b78444c8b99f3ef64403f5236239dab7effc1c5e87f6350371234705a6cbce067e36163e1a4bd0079901e', '2015-01-24 00:36:03'),
(3, '', '', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', '2015-01-24 00:38:25'),
(4, 'scottsfarley@yahoo.com', 'Scott Sherwin Farley', '5cf30743142be4b703c07ead9fe79c4dfcfaf4f0d70b78444c8b99f3ef64403f5236239dab7effc1c5e87f6350371234705a6cbce067e36163e1a4bd0079901e', '2015-01-24 00:45:20'),
(5, 'scott@firesphere.org', 'Scott Sherwin Farley', '4e6636904506a07b0eed49473d4688c33a885a7865edc54e9f2be5981d1eed247adf269c1ce4e69e6f535ccaf4164c3245f92d322331fc2837eb039f1992d1ed', '2015-01-26 18:14:51'),
(6, 'test1@calpalyn.edu', 'Scott Sherwin Farley', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', '2015-01-26 18:22:23'),
(7, 'test2@calpalyn.edu', 'Scott Sherwin Farley', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', '2015-01-26 18:24:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ChronologyFiles`
--
ALTER TABLE `ChronologyFiles`
 ADD PRIMARY KEY (`ChronologyID`);

--
-- Indexes for table `Cores`
--
ALTER TABLE `Cores`
 ADD PRIMARY KEY (`CoreID`);

--
-- Indexes for table `Datafiles`
--
ALTER TABLE `Datafiles`
 ADD PRIMARY KEY (`DatafileID`);

--
-- Indexes for table `SavedProjects`
--
ALTER TABLE `SavedProjects`
 ADD PRIMARY KEY (`ProjectIndex`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
 ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ChronologyFiles`
--
ALTER TABLE `ChronologyFiles`
MODIFY `ChronologyID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Cores`
--
ALTER TABLE `Cores`
MODIFY `CoreID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `Datafiles`
--
ALTER TABLE `Datafiles`
MODIFY `DatafileID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `SavedProjects`
--
ALTER TABLE `SavedProjects`
MODIFY `ProjectIndex` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
