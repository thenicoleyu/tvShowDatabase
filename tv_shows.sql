-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2016 at 01:07 AM
-- Server version: 5.6.28
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tv_shows`
--

-- --------------------------------------------------------

--
-- Table structure for table `date_aired`
--

CREATE TABLE `date_aired` (
`did` int(4) UNSIGNED NOT NULL,
`date_aired` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `date_aired`
--

INSERT INTO `date_aired` (`did`, `date_aired`) VALUES
(404, 2004),
(405, 2005),
(406, 2006),
(407, 2007),
(408, 2008),
(409, 2009),
(410, 2010),
(411, 2011),
(412, 2012),
(414, 2014),
(415, 2015),
(416, 2016);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
`gid` int(4) UNSIGNED NOT NULL,
`genre` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`gid`, `genre`) VALUES
(501, 'comedy'),
(502, 'political drama'),
(503, 'political thriller'),
(504, 'medical drama'),
(505, 'action'),
(506, 'superhero'),
(507, 'scifi'),
(508, 'period drama'),
(509, 'mystery'),
(510, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `tv_show`
--

CREATE TABLE `tv_show` (
`tvid` int(10) UNSIGNED NOT NULL,
`title` varchar(100) CHARACTER SET latin1 NOT NULL,
`genre` int(4) UNSIGNED NOT NULL,
`date_aired` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tv_show`
--

INSERT INTO `tv_show` (`tvid`, `title`, `genre`, `date_aired`) VALUES
(1, 'battlestar galactica', 507, 404),
(2, 'lost', 507, 404),
(3, 'jessica jones', 506, 415),
(4, 'the good wife', 502, 409),
(5, 'homeland', 503, 411),
(6, 'house', 504, 404),
(7, 'black mirror', 507, 411),
(8, 'greys anatomy', 504, 405),
(9, '30 rock', 501, 406),
(10, 'mad men', 508, 407),
(11, 'fringe', 507, 408),
(12, 'spartacus blood and sand', 505, 410),
(13, 'scandal', 502, 412),
(14, 'agents of shield', 506, 413),
(15, 'the 100', 507, 414),
(16, 'luke cage', 507, 416),
(17, 'how to get away with murder', 509, 414),
(19, 'test', 505, 416),
(20, 'test', 505, 416);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `date_aired`
--
ALTER TABLE `date_aired`
ADD PRIMARY KEY (`did`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `tv_show`
--
ALTER TABLE `tv_show`
ADD PRIMARY KEY (`tvid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `date_aired`
--
ALTER TABLE `date_aired`
MODIFY `did` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=417;
--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
MODIFY `gid` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;
--
-- AUTO_INCREMENT for table `tv_show`
--
ALTER TABLE `tv_show`
MODIFY `tvid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;