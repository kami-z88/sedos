-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2017 at 03:03 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sedos`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL,
  `oid` bigint(20) NOT NULL,
  `name` varchar(1023) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `oid`, `name`) VALUES
(6, 3, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `group_users`
--

CREATE TABLE `group_users` (
  `id` bigint(20) NOT NULL,
  `gid` bigint(20) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `default_phone` varchar(11) DEFAULT NULL,
  `status` enum('member','blocked','invited','rejected','admin') NOT NULL DEFAULT 'member',
  `permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_users`
--

INSERT INTO `group_users` (`id`, `gid`, `uid`, `default_phone`, `status`, `permissions`) VALUES
(5, 6, 1, '09143400953', 'admin', 'VIEW_MEMBER_PHONE,VIEW_INVITATIONS,VIEW_BLOCKED_USERS,INVITE_MEMBER,SEND_MESSAGE,SEND_SMS,REMOVE_GROUP,REMOVE_MEMBERS,UPDATE_GROUP_NAME,BLOCK_USER,VIEW_MEMBER_NAME'),
(7, 6, 4, '09143400950', 'admin', 'VIEW_MEMBER_NAME,VIEW_MEMBER_PHONE,VIEW_INVITATIONS,VIEW_BLOCKED_USERS,INVITE_MEMBER,SEND_MESSAGE,SEND_SMS,REMOVE_GROUP,REMOVE_MEMBERS,UPDATE_GROUP_NAME');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `gid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `text` tinytext NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) NOT NULL,
  `name` varchar(1023) NOT NULL,
  `parent_id` bigint(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `parent_id`) VALUES
(3, 'test', 0),
(6, 'test organization', 3),
(7, 'syamak', 3);

-- --------------------------------------------------------

--
-- Table structure for table `organization_users`
--

CREATE TABLE `organization_users` (
  `id` bigint(20) NOT NULL,
  `oid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organization_users`
--

INSERT INTO `organization_users` (`id`, `oid`, `uid`, `permissions`) VALUES
(1, 3, 1, 'VIEW_ORGANIZION,VIEW_SUB_ORGANIZTION,CREATE_SUB_ORGANIZATIOM,CREATE_ORGANIZATION_GROUP,CREATE_ORGANIZTION_USER,REMOVE_ORGANIZATION,UPDATE_NAME'),
(4, 6, 1, 'VIEW_ORGANIZION,VIEW_SUB_ORGANIZTION,CREATE_SUB_ORGANIZATIOM,CREATE_ORGANIZATION_GROUP,CREATE_ORGANIZTION_USER,REMOVE_ORGANIZATION,UPDATE_NAME'),
(5, 7, 1, 'VIEW_ORGANIZION,VIEW_SUB_ORGANIZTION,CREATE_SUB_ORGANIZATIOM,CREATE_ORGANIZATION_GROUP,CREATE_ORGANIZTION_USER,REMOVE_ORGANIZATION,UPDATE_NAME');

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE `phone` (
  `uid` bigint(20) NOT NULL,
  `phone_num` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registeration`
--

CREATE TABLE `registeration` (
  `uid` bigint(20) NOT NULL,
  `phone_num` varchar(11) CHARACTER SET latin1 NOT NULL,
  `code` smallint(6) NOT NULL,
  `type` int(11) DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `phone_num` varchar(11) DEFAULT NULL,
  `name` varchar(511) DEFAULT NULL,
  `email` varchar(127) DEFAULT NULL,
  `email_verified` tinyint(4) DEFAULT '0',
  `password` varchar(511) NOT NULL,
  `session` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `phone_num`, `name`, `email`, `email_verified`, `password`, `session`, `active`, `permissions`) VALUES
(1, '09143400953', 'Siyamak Ftattahzadeh', 'syaf57@yahoo.com', 0, '25d55ad283aa400af464c76d713c07ad', '', 1, NULL),
(4, '09143400950', 'tester', 'tohid@gmail.com', 0, '25d55ad283aa400af464c76d713c07ad', '', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_index` (`gid`,`uid`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`phone_num`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `phone_num` (`phone_num`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `group_users`
--
ALTER TABLE `group_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `organization_users`
--
ALTER TABLE `organization_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
