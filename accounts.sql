-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2014 at 10:03 PM
-- Server version: 5.6.15-log
-- PHP Version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT 'username',
  `password` varchar(255) NOT NULL COMMENT 'password hash',
  `email` varchar(64) NOT NULL,
  `group_id` int(11) NOT NULL COMMENT 'group id',
  `date` int(11) NOT NULL COMMENT 'creation date unix timestamp',
  `last_visit` int(11) NOT NULL COMMENT 'last visit unix timestamp',
  `avatar` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL COMMENT 'ipv4 or v6 address',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `password`, `email`, `group_id`, `date`, `last_visit`, `avatar`, `ip_address`) VALUES
(1, 'test', '$2y$10$oqnL.ymcw6pWPSdoYYl8IednYt1Mh4TWJSV68mesL5zfF1lCbf3.i', 'test@jcink.com', 2, 1414529799, 0, '', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `g_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(64) NOT NULL COMMENT 'group name',
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`g_id`, `group_name`) VALUES
(1, 'Administrator'),
(2, 'User'),
(3, 'Banned');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
