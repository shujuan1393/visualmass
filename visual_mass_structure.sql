-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 14, 2016 at 09:38 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `image` varchar(300) NOT NULL,
  `imagepos` varchar(15) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(30) NOT NULL,
  `buttontext` longtext NOT NULL,
  `link` longtext,
  `linkpos` longtext NOT NULL,
  `html` longtext,
  `htmlpos` varchar(15) NOT NULL,
  `expiry` varchar(10) NOT NULL,
  `visibility` varchar(500) NOT NULL,
  `minheight` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `html` longtext NOT NULL,
  `categories` varchar(150) NOT NULL,
  `excerpt` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `visibility` varchar(30) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateposted` datetime NOT NULL,
  `tags` varchar(500) NOT NULL,
  `scheduled` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `careers`
--

CREATE TABLE `careers` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `html` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `cartid` varchar(300) NOT NULL,
  `pid` varchar(300) NOT NULL,
  `price` varchar(50) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `type` varchar(300) NOT NULL,
  `details` varchar(500) NOT NULL,
  `lens` varchar(300) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `html` longtext NOT NULL,
  `image` varchar(300) NOT NULL,
  `fieldorder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(11) NOT NULL,
  `orderid` varchar(150) NOT NULL,
  `deliveryid` varchar(500) NOT NULL,
  `trackingnumber` varchar(500) NOT NULL,
  `trackingurl` varchar(500) NOT NULL,
  `cost` varchar(200) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `status` varchar(300) NOT NULL,
  `statename` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discountarchives`
--

CREATE TABLE `discountarchives` (
  `id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `disclimit` int(11) NOT NULL,
  `recurrence` varchar(200) NOT NULL,
  `discusage` int(11) NOT NULL,
  `userlimit` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `disctype` varchar(300) NOT NULL,
  `disccondition` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `disclimit` varchar(150) NOT NULL,
  `userlimit` varchar(100) NOT NULL,
  `recurrence` varchar(40) NOT NULL,
  `discusage` varchar(30) NOT NULL,
  `disctype` varchar(200) NOT NULL,
  `disccondition` varchar(200) NOT NULL,
  `serial` varchar(100) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employeeTypes`
--

CREATE TABLE `employeeTypes` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `html` longtext NOT NULL,
  `type` varchar(80) NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `pid` varchar(50) NOT NULL,
  `email` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `name` varchar(50) NOT NULL,
  `field` varchar(50) NOT NULL,
  `options` varchar(300) NOT NULL,
  `status` varchar(20) NOT NULL,
  `form` varchar(200) NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `giftcards`
--

CREATE TABLE `giftcards` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(400) NOT NULL,
  `type` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` double DEFAULT NULL,
  `customise` varchar(10) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `homepage`
--

CREATE TABLE `homepage` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `imagepos` varchar(50) NOT NULL,
  `html` longtext NOT NULL,
  `htmlpos` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL,
  `link` longtext NOT NULL,
  `linkpos` longtext NOT NULL,
  `buttontext` longtext NOT NULL,
  `fieldorder` int(11) NOT NULL,
  `type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hometry`
--

CREATE TABLE `hometry` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `image` varchar(500) NOT NULL,
  `imagepos` varchar(50) NOT NULL,
  `htmlpos` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `buttontext` longtext NOT NULL,
  `link` longtext NOT NULL,
  `linkpos` longtext NOT NULL,
  `fieldorder` int(11) NOT NULL,
  `type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `pid` varchar(200) NOT NULL,
  `quantity` int(100) NOT NULL,
  `price` double NOT NULL,
  `type` varchar(100) NOT NULL,
  `location` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `status` varchar(50) NOT NULL,
  `featured` varchar(10) DEFAULT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL,
  `address` varchar(150) NOT NULL,
  `phone` int(11) NOT NULL,
  `apt` int(11) DEFAULT NULL,
  `city` varchar(30) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(30) NOT NULL,
  `opening` longtext NOT NULL,
  `description` longtext NOT NULL,
  `featured` longtext NOT NULL,
  `images` longtext,
  `type` varchar(11) NOT NULL,
  `services` varchar(80) NOT NULL,
  `status` varchar(200) NOT NULL,
  `scheduled` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist`
--

CREATE TABLE `mailinglist` (
  `email` varchar(300) NOT NULL,
  `preference` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `details` longtext NOT NULL,
  `image` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `pid` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `details` longtext NOT NULL,
  `type` varchar(100) NOT NULL,
  `payment` varchar(100) NOT NULL,
  `discountcode` varchar(100) NOT NULL,
  `status` varchar(200) NOT NULL,
  `orderedby` varchar(200) NOT NULL,
  `totalcost` double NOT NULL,
  `dateordered` timestamp NULL DEFAULT NULL,
  `datepaid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ourstory`
--

CREATE TABLE `ourstory` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `page` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `image` varchar(300) NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `image` varchar(800) NOT NULL,
  `imagepos` varchar(500) NOT NULL,
  `html` longtext NOT NULL,
  `htmlpos` varchar(500) NOT NULL,
  `status` varchar(100) NOT NULL,
  `scheduled` timestamp NULL DEFAULT NULL,
  `link` longtext NOT NULL,
  `linkpos` longtext NOT NULL,
  `buttontext` longtext NOT NULL,
  `fieldorder` bigint(20) NOT NULL,
  `type` varchar(200) NOT NULL,
  `pageid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `company` varchar(300) NOT NULL,
  `address` varchar(300) NOT NULL,
  `apt` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `contactname` varchar(300) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `type` varchar(150) NOT NULL,
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productbanner`
--

CREATE TABLE `productbanner` (
  `id` int(11) NOT NULL,
  `gender` varchar(150) NOT NULL,
  `categories` varchar(150) NOT NULL,
  `image` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productdescription`
--

CREATE TABLE `productdescription` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `imagepos` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `htmlpos` varchar(300) NOT NULL,
  `status` varchar(500) NOT NULL,
  `link` longtext NOT NULL,
  `linkpos` longtext NOT NULL,
  `buttontext` longtext NOT NULL,
  `fieldorder` bigint(20) NOT NULL,
  `type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `pid` varchar(500) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `price` double NOT NULL,
  `quantity` int(5) NOT NULL,
  `type` varchar(11) NOT NULL,
  `width` int(11) NOT NULL,
  `measurement` varchar(150) NOT NULL,
  `featured` longtext NOT NULL,
  `images` longtext NOT NULL,
  `tags` longtext,
  `visibility` varchar(30) NOT NULL,
  `availability` varchar(40) NOT NULL,
  `locations` longtext NOT NULL,
  `locationqty` longtext NOT NULL,
  `gender` varchar(20) NOT NULL,
  `status` varchar(200) NOT NULL,
  `scheduled` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productstatistics`
--

CREATE TABLE `productstatistics` (
  `id` int(11) NOT NULL,
  `type` varchar(300) NOT NULL,
  `pid` varchar(300) NOT NULL,
  `customer` varchar(500) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `orderid` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `orderid` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `code` varchar(300) NOT NULL,
  `dateused` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `searchstatistics`
--

CREATE TABLE `searchstatistics` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `keyword` varchar(300) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `servicecode` varchar(10) NOT NULL,
  `servicename` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `status` varchar(100) NOT NULL,
  `datejoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `lastlogout` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `keyword` varchar(300) NOT NULL,
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` longtext,
  `zip` varchar(100) NOT NULL,
  `apt` varchar(150) NOT NULL,
  `city` varchar(200) NOT NULL,
  `country` varchar(300) NOT NULL,
  `phone` int(11) NOT NULL,
  `datejoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `prescription` longtext NOT NULL,
  `accountType` varchar(10) NOT NULL,
  `marketing` varchar(50) NOT NULL,
  `code` varchar(100) NOT NULL,
  `credit` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discountarchives`
--
ALTER TABLE `discountarchives`
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
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`pid`,`email`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `giftcards`
--
ALTER TABLE `giftcards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage`
--
ALTER TABLE `homepage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hometry`
--
ALTER TABLE `hometry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailinglist`
--
ALTER TABLE `mailinglist`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ourstory`
--
ALTER TABLE `ourstory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productbanner`
--
ALTER TABLE `productbanner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productdescription`
--
ALTER TABLE `productdescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pid` (`pid`);

--
-- Indexes for table `productstatistics`
--
ALTER TABLE `productstatistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searchstatistics`
--
ALTER TABLE `searchstatistics`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discountarchives`
--
ALTER TABLE `discountarchives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employeeTypes`
--
ALTER TABLE `employeeTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `giftcards`
--
ALTER TABLE `giftcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `homepage`
--
ALTER TABLE `homepage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hometry`
--
ALTER TABLE `hometry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ourstory`
--
ALTER TABLE `ourstory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productbanner`
--
ALTER TABLE `productbanner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productdescription`
--
ALTER TABLE `productdescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productstatistics`
--
ALTER TABLE `productstatistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `searchstatistics`
--
ALTER TABLE `searchstatistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
