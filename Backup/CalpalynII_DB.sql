-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2015 at 10:56 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

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
-- Table structure for table `Bugs`
--

CREATE TABLE IF NOT EXISTS `Bugs` (
`RecordID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Type` text NOT NULL,
  `Description` text NOT NULL,
  `Opened` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Closed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TimeDelta` text NOT NULL,
  `Resolved` tinyint(1) NOT NULL,
  `Votes` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Bugs`
--

INSERT INTO `Bugs` (`RecordID`, `Username`, `Type`, `Description`, `Opened`, `Closed`, `TimeDelta`, `Resolved`, `Votes`) VALUES
(11, '', 'bug', 'Test Bug 9:00', '2015-05-16 04:01:25', '0000-00-00 00:00:00', '0', 0, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Cores`
--

INSERT INTO `Cores` (`CoreID`, `CoreName`, `SiteName`, `MinAge`, `MaxAge`, `MinDepth`, `MaxDepth`, `Latitude`, `Longitude`, `WaterDepth`, `DateCored`, `User`, `Protect`, `Password`) VALUES
(11, 'Core 1', 'California, USA', 0, 0, 0, 0, 37, -121, 102, '2015-05-04', 'scottsfarley', 0, 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e'),
(12, 'Test Core', 'Test Site', -9999, -9999, -9999, -9999, -9999, -9999, -9999, '1900-01-01', 'scottsfarley1', 0, 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Datafiles`
--

INSERT INTO `Datafiles` (`DatafileID`, `DatafileName`, `CoreID`, `NumLevels`, `NumTaxa`, `FileReference`, `MinDepth`, `MaxDepth`, `LastModified`, `Uploaded`, `Version`, `User`) VALUES
(20, 'Core 1 Test Data File', 'Core 1', 54, 53, '../datafiles/scottsfarley_scottsfarley_bolinas.data.csv', 0, 312, '2015-03-20', '2015-05-05', 1, 'scottsfarley'),
(21, 'Test Data', 'Test Core', 54, 53, '../datafiles/scottsfarley1_scottsfarley_scottsfarley_bolinas.data.csv', 0, 312, '2015-05-05', '2015-05-14', 1, 'scottsfarley1');

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE IF NOT EXISTS `Requests` (
`RecordID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Type` text NOT NULL,
  `Description` text NOT NULL,
  `Opened` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Closed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TimeDelta` text NOT NULL,
  `Resolved` tinyint(1) NOT NULL,
  `Votes` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Requests`
--

INSERT INTO `Requests` (`RecordID`, `Username`, `Type`, `Description`, `Opened`, `Closed`, `TimeDelta`, `Resolved`, `Votes`) VALUES
(2, '', 'feature', 'Calpalyn.Listing \n-->file of depth/value/norm tuples for each level and taxon.', '2015-05-16 03:55:30', '0000-00-00 00:00:00', '0', 0, 0),
(3, '', 'feature', 'Above taxa name groups.', '2015-05-16 03:55:47', '0000-00-00 00:00:00', '0', 0, 0),
(4, '', 'feature', 'Stratigraphy column texture boxes', '2015-05-16 03:56:58', '0000-00-00 00:00:00', '0', 0, 0),
(5, '', 'feature', 'APFAC pollen accumulation rate', '2015-05-16 03:57:10', '0000-00-00 00:00:00', '0', 0, 0),
(6, '', 'feature', 'Resolve bugs with button.', '2015-05-16 04:03:11', '0000-00-00 00:00:00', '0', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `SavedProjects`
--

CREATE TABLE IF NOT EXISTS `SavedProjects` (
`ProjectIndex` int(11) NOT NULL,
  `User` text NOT NULL,
  `Core` text NOT NULL,
  `creationTimestamp` bigint(20) NOT NULL,
  `lastDrawnTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FileReference` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SavedProjects`
--

INSERT INTO `SavedProjects` (`ProjectIndex`, `User`, `Core`, `creationTimestamp`, `lastDrawnTimestamp`, `FileReference`) VALUES
(1, 'scottsfarley', 'Core+1', 1430790663, '2015-05-05 01:51:07', '../savedProjects/scottsfarley_Core+1_1430790663.cpn'),
(2, 'scottsfarley', 'Core+1', 1430792655, '2015-05-05 02:24:19', '../savedProjects/scottsfarley_Core+1_1430792655.cpn'),
(3, 'scottsfarley', 'Core+1', 1430793390, '2015-05-05 02:36:38', '../savedProjects/scottsfarley_Core+1_1430793390.cpn'),
(4, 'scottsfarley', 'Core+1', 1430793586, '2015-05-05 02:39:57', '../savedProjects/scottsfarley_Core+1_1430793586.cpn'),
(5, 'scottsfarley', 'Core+1', 1430793759, '2015-05-05 02:42:42', '../savedProjects/scottsfarley_Core+1_1430793759.cpn'),
(6, 'scottsfarley1', 'Test+Core', 1431629891, '2015-05-14 18:58:36', '../savedProjects/scottsfarley1_Test+Core_1431629891.cpn');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
`UserID` int(11) NOT NULL,
  `Email` text NOT NULL,
  `Name` text NOT NULL,
  `Password` longtext NOT NULL,
  `DateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Username` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `Email`, `Name`, `Password`, `DateJoined`, `Username`) VALUES
(8, 'scottsfarley@gmail.com', 'Scott Farley', '4e6636904506a07b0eed49473d4688c33a885a7865edc54e9f2be5981d1eed247adf269c1ce4e69e6f535ccaf4164c3245f92d322331fc2837eb039f1992d1ed', '2015-05-04 22:01:12', 'scottsfarley'),
(9, 'scottsfarley@gmail.com', 'Test', '22210519b480ad65220a52a601de31eb788c0fbbc5aafcdeabd112a8c023fd7fb4954e4723b1ebde90a4e3fc1f5343be69c943453309e64a09cd571d05502fe6', '2015-05-14 18:54:47', 'scottsfarley1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bugs`
--
ALTER TABLE `Bugs`
 ADD PRIMARY KEY (`RecordID`);

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
-- Indexes for table `Requests`
--
ALTER TABLE `Requests`
 ADD PRIMARY KEY (`RecordID`);

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
-- AUTO_INCREMENT for table `Bugs`
--
ALTER TABLE `Bugs`
MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `ChronologyFiles`
--
ALTER TABLE `ChronologyFiles`
MODIFY `ChronologyID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Cores`
--
ALTER TABLE `Cores`
MODIFY `CoreID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `Datafiles`
--
ALTER TABLE `Datafiles`
MODIFY `DatafileID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `Requests`
--
ALTER TABLE `Requests`
MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `SavedProjects`
--
ALTER TABLE `SavedProjects`
MODIFY `ProjectIndex` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
