-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2021 at 12:09 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `streamania2`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `name` varchar(64) NOT NULL,
  `accounts_id` int(8) NOT NULL,
  `users_id` int(11) NOT NULL,
  `images_id` int(11) NOT NULL,
  `friends_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `friends_id` int(8) NOT NULL,
  `accounts_id1` int(11) NOT NULL,
  `accounts_id2` int(11) NOT NULL,
  `status` set('pending','accepted','refused') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genres_id` int(11) NOT NULL,
  `genre` set('horror','horror-thriller','horror-comedy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `genres_relations`
--

CREATE TABLE `genres_relations` (
  `genres_id1` int(11) NOT NULL,
  `genres_id2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `videos_id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `images_id` int(11) NOT NULL,
  `path` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(8) NOT NULL,
  `mail` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `videos_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `type` set('movies','series') NOT NULL,
  `teaser_image` varchar(256) NOT NULL,
  `path` varchar(256) NOT NULL,
  `genres_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accounts_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `friends_id` (`friends_id`),
  ADD KEY `images_id` (`images_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friends_id`),
  ADD KEY `accounts_id1` (`accounts_id1`),
  ADD KEY `accounts_id2` (`accounts_id2`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genres_id`);

--
-- Indexes for table `genres_relations`
--
ALTER TABLE `genres_relations`
  ADD KEY `genres_id1` (`genres_id1`),
  ADD KEY `genres_id2` (`genres_id2`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD KEY `accounts_id` (`accounts_id`),
  ADD KEY `videos_id` (`videos_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`images_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `mail` (`mail`(50));

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`videos_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accounts_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `friends_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genres_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `images_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `videos_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`friends_id`) REFERENCES `friends` (`friends_id`),
  ADD CONSTRAINT `accounts_ibfk_3` FOREIGN KEY (`images_id`) REFERENCES `images` (`images_id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`accounts_id1`) REFERENCES `accounts` (`accounts_id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`accounts_id2`) REFERENCES `accounts` (`accounts_id`);

--
-- Constraints for table `genres_relations`
--
ALTER TABLE `genres_relations`
  ADD CONSTRAINT `genres_relations_ibfk_1` FOREIGN KEY (`genres_id1`) REFERENCES `genres` (`genres_id`),
  ADD CONSTRAINT `genres_relations_ibfk_2` FOREIGN KEY (`genres_id2`) REFERENCES `genres` (`genres_id`);

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`accounts_id`),
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`videos_id`) REFERENCES `videos` (`videos_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
