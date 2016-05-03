-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2016 at 08:28 AM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visual_mass`
--
CREATE DATABASE IF NOT EXISTS `visual_mass` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `visual_mass`;

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `image` varchar(300) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `link` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `image`, `start`, `end`, `status`, `link`) VALUES
(3, 'asd', '../uploads/advertisements/05b0b62912cbed47d74fd0648c9b1cbaScreen Shot 2016-04-07 at 14.18.59.png', '2016-04-30', '2016-05-01', 'active', 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `excerpt` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `html` longtext NOT NULL,
  `visibility` varchar(30) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateposted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `excerpt`, `image`, `html`, `visibility`, `author`, `dateposted`, `tags`) VALUES
(2, 'asdasd', '&lt;p&gt;asdasd&lt;/p&gt;\r\n', '', 'passport_1.jpg', 'inactive', 'shujuan c', '2016-05-02 16:00:00', ''),
(4, 'asd', '', '../uploads/blog/9fa932a569e19e9e216ae491f7afd492passport_1.jpg', '', 'active', 'shujuan c', '2016-05-18 16:00:00', ''),
(5, 'jello', '&lt;p&gt;hehe&lt;/p&gt;\r\n', '../uploads/blog/38cd2c9d66ac3333f51b571924424281passport_1.jpg', '', 'active', 'shujuan c', '2016-05-02 16:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `disclimit` int(11) NOT NULL,
  `recurrence` varchar(40) NOT NULL,
  `discusage` varchar(30) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `code`, `name`, `disclimit`, `recurrence`, `discusage`, `start`, `end`, `status`) VALUES
(3, 'N8Ofn', 'asd', 0, 'adhoc', 'emp', '2016-04-03', '2016-05-28', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `employeeTypes`
--

CREATE TABLE `employeeTypes` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeeTypes`
--

INSERT INTO `employeeTypes` (`id`, `code`, `name`) VALUES
(1, 'sales', 'Sales'),
(2, 'marketing', 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `pid` varchar(10) NOT NULL,
  `quantity` int(100) NOT NULL,
  `price` double NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `pid`, `quantity`, `price`, `type`) VALUES
(3, '8hCv1', 450, 123, 'frames');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `phone` int(11) NOT NULL,
  `apt` int(11) DEFAULT NULL,
  `city` varchar(30) NOT NULL,
  `zip` int(11) NOT NULL,
  `country` varchar(30) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `type` varchar(11) NOT NULL,
  `services` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `code`, `name`, `address`, `phone`, `apt`, `city`, `zip`, `country`, `image`, `type`, `services`) VALUES
(5, 'IYe5l', 'awe', 'qqwe', 5435, 123123123, 'aweqwe', 0, 'awe', '../uploads/locations/Screen Shot 2016-03-27 at 10.09.02 pm.png', 'retail', 'FC,OP,PL');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `pid` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `price` double NOT NULL,
  `quantity` int(5) NOT NULL,
  `type` varchar(11) NOT NULL,
  `images` longtext NOT NULL,
  `tags` longtext,
  `visibility` varchar(10) NOT NULL,
  `availability` varchar(40) NOT NULL,
  `locations` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `pid`, `name`, `description`, `price`, `quantity`, `type`, `images`, `tags`, `visibility`, `availability`, `locations`) VALUES
(3, '8hCv1', 'asdqwe123', 'asd123', 123, 123, 'frames', '../uploads/products/1a64fb39cacd4e23f8ba97076a0fdc2f.png,../uploads/products/78dd081ba60051ce2ab76a79ff4c5170.png,../uploads/products/78dd081ba60051ce2ab76a79ff4c5170.png', 'asd', 'retail', 'sale,tryon', 'IYe5l,pMQJG');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `servicecode` varchar(10) NOT NULL,
  `servicename` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `servicecode`, `servicename`) VALUES
(1, 'FC', 'Full Collection Available'),
(2, 'SNP', 'Same-day Non-Refundable Purchases'),
(3, 'OP', 'Optical Measurements'),
(4, 'FA', 'Frame Adjustments'),
(5, 'PL', 'Progressive Lenses'),
(6, 'R&E', 'Returns & Exchanges'),
(7, 'EyEx', 'Eye Exams');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `type`, `value`) VALUES
(3, 'general', 'primary=IYe5l&email=asdasdasd@test.com&curr=BZD&timezone=(UTC+02:00) Africa/Blantyre'),
(4, 'homeTryon', 'visibility=off&duration=123123&amount=123123'),
(5, 'account', 'sales=disc,media,orders,products&marketing=media,products&'),
(6, 'notifications', 'email=&lt;p&gt;sadasd&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;asd&lt;/p&gt;\n,&lt;p&gt;asd&lt;strong&gt;asd&lt;/strong&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;strong&gt;asd&lt;s&gt;asd&lt;/s&gt;&lt;/strong&gt;&lt;/p&gt;\n,&lt;p&gt;qwkem&lt;/p&gt;\n,&lt;p&gt;qwe&lt;/p&gt;\n,&lt;p&gt;qweqe&lt;/p&gt;\n,&lt;p&gt;qwe&lt;/p&gt;\n,&lt;p&gt;qwe&lt;/p&gt;\n,&lt;p&gt;qwe&lt;/p&gt;\n,&lt;p&gt;qwe&lt;/p&gt;\n#sms=&lt;p&gt;asd owke&lt;/p&gt;\n\n&lt;p&gt;owqe&lt;/p&gt;\n,&lt;p&gt;kasmd&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;asdkn&lt;/p&gt;\n');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `phone` int(8) DEFAULT NULL,
  `biography` longtext,
  `website` varchar(100) DEFAULT NULL,
  `type` varchar(40) NOT NULL,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `lastlogout` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `biography`, `website`, `type`, `lastlogin`, `lastlogout`) VALUES
(2, 'shujuan', 'c', 'shujuan@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 12345678, '&lt;p&gt;asdasd&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;asdasd&lt;/p&gt;\r\n', 'asd', 'admin', '2016-05-02 18:11:37', '2016-05-02 18:10:54'),
(13, 'ah', 'ha', 'hahahah@gmail.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'sales', NULL, NULL),
(20, 'test@abc.com', 'test@abc.com', 'test@abc.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'marketing', '2016-05-03 01:11:38', '2016-05-03 01:11:43'),
(22, 'hello', 'hello', 'you@2you.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'sales', '2016-04-28 19:02:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` longtext,
  `datejoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accountType` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`, `address`, `datejoined`, `accountType`) VALUES
('test', 'test', 'test@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2016-04-25 04:03:39', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `employeeTypes`
--
ALTER TABLE `employeeTypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pid` (`pid`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`,`servicecode`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `Email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `employeeTypes`
--
ALTER TABLE `employeeTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
