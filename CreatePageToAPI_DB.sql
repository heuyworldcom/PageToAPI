-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2020 at 02:24 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `pagetoapidb`
--
CREATE DATABASE IF NOT EXISTS `pagetoapidb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pagetoapidb`;

-- --------------------------------------------------------

--
-- Table structure for table `player_stats`
--

CREATE TABLE `player_stats` (
  `player_id` int(11) NOT NULL,
  `player_name` varchar(50) NOT NULL,
  `team` varchar(50) NOT NULL,
  `games_played` int(11) NOT NULL,
  `at_bats` int(11) NOT NULL,
  `runs` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `doubles` int(11) NOT NULL,
  `triples` int(11) NOT NULL,
  `home_runs` int(11) NOT NULL,
  `runs_batted_in` int(11) NOT NULL,
  `walks` int(11) NOT NULL,
  `strike_outs` int(11) NOT NULL,
  `stolen_bases` int(11) NOT NULL,
  `caught_stealing` int(11) NOT NULL,
  `batting_average` decimal(10,5) NOT NULL,
  `on_base_percentage` decimal(10,5) NOT NULL,
  `slugging_percentage` decimal(10,5) NOT NULL,
  `on_base_and_slugging` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `player_stats`
--
ALTER TABLE `player_stats`
  ADD PRIMARY KEY (`player_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `player_stats`
--
ALTER TABLE `player_stats`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
