-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2020 at 01:13 PM
-- Server version: 5.7.31-log
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tagarakc_tcms_faith`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `no` int(9) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(582) NOT NULL,
  `action` json NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `button`
--

CREATE TABLE `button` (
  `no` int(5) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) NOT NULL,
  `caption` json NOT NULL,
  `content` json NOT NULL,
  `type` json NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `button`
--

INSERT INTO `button` (`no`, `time`, `name`, `caption`, `content`, `type`) VALUES
(1, '2020-07-26 05:51:01', 'start', '[]', '[]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `command`
--

CREATE TABLE `command` (
  `no` int(9) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(58) NOT NULL,
  `type` varchar(25) NOT NULL,
  `keyword` varchar(582) NOT NULL,
  `action` json NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `command`
--

INSERT INTO `command` (`no`, `waktu`, `name`, `type`, `keyword`, `action`) VALUES
(1, '2020-07-26 03:01:18', 'default', 'message', '/////////////////////////////////////////////////', '[\"t\", \"\"]'),
(2, '2020-07-26 03:01:18', 'start', 'message', '/start', '[\"t\", \"\"]');

-- --------------------------------------------------------

--
-- Table structure for table `command-log`
--

CREATE TABLE `command-log` (
  `no` int(9) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` varchar(58) NOT NULL,
  `command` varchar(58) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `command_count`
--

CREATE TABLE `command_count` (
  `no` int(7) NOT NULL,
  `last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(50) NOT NULL,
  `count` int(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `no` int(9) NOT NULL,
  `name` varchar(58) NOT NULL,
  `val` varchar(85) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`no`, `name`, `val`) VALUES
(1, 'token', ''),
(2, 'username', ''),
(3, 'display_name', ''),
(4, 'id', '');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `no` int(9) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` varchar(58) NOT NULL,
  `source` varchar(2) NOT NULL,
  `value` varchar(852) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `no` int(9) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_dm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(25) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

CREATE TABLE `word` (
  `no` int(9) NOT NULL,
  `kata` varchar(58) NOT NULL,
  `jumlah` int(9) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `button`
--
ALTER TABLE `button`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `command`
--
ALTER TABLE `command`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `command-log`
--
ALTER TABLE `command-log`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `command_count`
--
ALTER TABLE `command_count`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `word`
--
ALTER TABLE `word`
  ADD PRIMARY KEY (`no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `button`
--
ALTER TABLE `button`
  MODIFY `no` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `command`
--
ALTER TABLE `command`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `command-log`
--
ALTER TABLE `command-log`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `command_count`
--
ALTER TABLE `command_count`
  MODIFY `no` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `word`
--
ALTER TABLE `word`
  MODIFY `no` int(9) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
