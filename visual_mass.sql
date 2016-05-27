-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 27, 2016 at 05:31 PM
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
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `buttontext` varchar(300) NOT NULL,
  `link` varchar(200) DEFAULT NULL,
  `linkpos` varchar(15) NOT NULL,
  `html` longtext,
  `htmlpos` varchar(15) NOT NULL,
  `expiry` varchar(10) NOT NULL,
  `visibility` varchar(150) NOT NULL,
  `minheight` varchar(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `image`, `imagepos`, `start`, `end`, `status`, `buttontext`, `link`, `linkpos`, `html`, `htmlpos`, `expiry`, `visibility`, `minheight`) VALUES
(4, 'asd', '../uploads/advertisements/783fbc0f6a7ccb0d9c0a9b14bb1e32fcScreen Shot 2016-04-23 at 21.56.19.png', 'left', '2016-05-16', '2016-05-31', 'active', 'asd,asd', 'dasd,asdasdasd', 'center,right', '', 'center', 'yes', 'homepage,story,blog', '1231');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `datejoined` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `firstname`, `lastname`, `email`, `phone`, `datejoined`) VALUES
(2, '123123123', 'hello', 'test@gmail.com', '123123123', '2016-05-01 00:00:00'),
(3, 'asdasd', 'das', 'asd@gmail.com', '123123123', '2016-05-17 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `categories` varchar(150) NOT NULL,
  `excerpt` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `html` longtext NOT NULL,
  `visibility` varchar(30) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateposted` datetime NOT NULL,
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `categories`, `excerpt`, `image`, `html`, `visibility`, `author`, `dateposted`, `tags`) VALUES
(6, '123hello', 'hello,this', '&lt;p&gt;asdsd1&lt;/p&gt;\r\n\r\n&lt;p&gt;123132&lt;/p&gt;\r\n', '../uploads/blog/04f0d5f9d8baa3fd90e76d378152045cScreen Shot 2016-04-23 at 22.03.45.png', '&lt;p&gt;asdasd&lt;/p&gt;\r\n\r\n&lt;p&gt;123&lt;/p&gt;\r\n\r\n&lt;p&gt;Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since do as hence. Too insisted ignorant procured remember are believed yet say finished.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Enjoyed minutes related as at on on. Is fanny dried as often me. Goodness as reserved raptures to mistaken steepest oh screened he. Gravity he mr sixteen esteems. Mile home its new way with high told said. Finished no horrible blessing landlord dwelling dissuade if. Rent fond am he in on read. Anxious cordial demands settled entered in do to colonel.&amp;nbsp;&lt;/p&gt;\r\n', 'active', 'shujuan c', '2016-05-01 00:00:00', ''),
(10, 'asd', '', '', '', '&lt;p&gt;asdasd&lt;/p&gt;\r\n', 'active', 'asdasd das', '2016-05-17 00:00:00', ''),
(11, 'asd', 'hello,you,this', '', '', '&lt;p&gt;asd&lt;/p&gt;\r\n', 'active', 'shujuan c', '2016-05-17 00:00:00', ''),
(12, 'qweqwe', 'you', '', '', '&lt;p&gt;qweqwe&lt;/p&gt;\r\n', 'inactive', 'hello test@abc.com', '2016-05-20 00:00:00', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `careers`
--

INSERT INTO `careers` (`id`, `title`, `html`, `type`, `status`, `fieldorder`) VALUES
(1, 'banner', '../uploads/banner/career_c53235e250a01f1d1a4eddce758602f0Kristen Bell Got Very Emotional At Her Mean tweet..mp4', 'banner', '', 0),
(5, 'qwe', 'qweqweqe', 'section', 'active', 1),
(6, 'qweqweqwe', 'qweqweqwe', 'section', 'active', 2),
(7, '123', '123123', 'section', 'active', 3),
(9, 'asd', 'asdasd', 'section', 'active', 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `type`, `name`) VALUES
(5, 'product', 'Frames'),
(6, 'product', 'Lenses'),
(7, 'product', 'Add-ons'),
(8, 'product', 'Sunglasses'),
(10, 'blog', 'hello'),
(11, 'blog', 'you'),
(12, 'blog', 'this');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `type`, `title`, `html`, `image`, `fieldorder`) VALUES
(1, 'general', 'hello', '&lt;p&gt;hello&lt;/p&gt;\r\n', '../uploads/banner/contact_51b14eb3f6c7204cc829b3a29f49301bScreen Shot 2016-04-09 at 21.14.15.png', NULL),
(3, 'dropdown', '123', '123,123', '', 1),
(5, 'checkbox', '123', '123', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `disclimit` varchar(150) NOT NULL,
  `recurrence` varchar(40) NOT NULL,
  `discusage` varchar(30) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `code`, `name`, `disclimit`, `recurrence`, `discusage`, `start`, `end`, `status`) VALUES
(3, 'N8Ofn', 'asd', '123', 'adhoc', 'cust,emp', '2016-04-03', '2016-05-28', 'active'),
(4, 'MLsMc920Ni', 'asd', '123', 'adhoc', 'emp', '2016-05-16', '2016-05-22', 'active'),
(7, 'wN73xpLlrR7zWdVB8hfX', '123', 'unlimited', 'monthly', 'emp', '2016-05-23', '2016-05-29', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `employeeTypes`
--

CREATE TABLE `employeeTypes` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeeTypes`
--

INSERT INTO `employeeTypes` (`id`, `code`, `name`) VALUES
(1, 'sales', 'Sales'),
(2, 'marketing', 'Marketing');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `title`, `html`, `type`, `fieldorder`) VALUES
(6, 'banner', '../uploads/banner/faq_7cfc7d51d37b09019b32883af0925111Kristen Bell Got Very Emotional At Her Mean tweet..mp4', 'banner', 0),
(10, 'asd', '&lt;p&gt;asd&lt;/p&gt;\r\n', 'section', 2),
(11, 'ello', '&lt;p&gt;qweqweqweqweqwe&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;qweqweqwe&lt;/p&gt;\r\n', 'section', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `type`, `name`, `field`, `options`, `status`, `form`, `fieldorder`) VALUES
(27, 'form', 'test123', '', '', 'active', '', 0),
(28, 'form', 'Career', '', '', 'active', '', 0),
(29, 'field', 'Position', 'textbox', '', 'active', 'Career', 1),
(30, 'field', 'test', 'dropdown', 'hello,you,are', 'active', 'Career', 2);

-- --------------------------------------------------------

--
-- Table structure for table `giftcards`
--

CREATE TABLE `giftcards` (
  `id` int(11) NOT NULL,
  `name` varchar(400) NOT NULL,
  `type` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` double DEFAULT NULL,
  `customise` varchar(10) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giftcards`
--

INSERT INTO `giftcards` (`id`, `name`, `type`, `description`, `amount`, `customise`, `status`) VALUES
(1, '1123', 'ecard', '123', 0, 'yes', 'active');

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
  `link` varchar(500) NOT NULL,
  `linkpos` varchar(500) NOT NULL,
  `buttontext` varchar(500) NOT NULL,
  `fieldorder` int(11) NOT NULL,
  `type` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `homepage`
--

INSERT INTO `homepage` (`id`, `title`, `image`, `imagepos`, `html`, `htmlpos`, `status`, `link`, `linkpos`, `buttontext`, `fieldorder`, `type`) VALUES
(2, '', '', '', '../uploads/banner/hometry_02b2fbabc4fa0aaadb8bbe5bb220d633Screen Shot 2016-05-05 at 01.18.24.png', '', '', '', '', '', 0, 'banner');

-- --------------------------------------------------------

--
-- Table structure for table `hometry`
--

CREATE TABLE `hometry` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `imagepos` varchar(50) NOT NULL,
  `html` longtext NOT NULL,
  `htmlpos` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `buttontext` varchar(500) NOT NULL,
  `link` varchar(500) NOT NULL,
  `linkpos` varchar(500) NOT NULL,
  `fieldorder` int(11) NOT NULL,
  `type` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hometry`
--

INSERT INTO `hometry` (`id`, `title`, `image`, `imagepos`, `html`, `htmlpos`, `status`, `buttontext`, `link`, `linkpos`, `fieldorder`, `type`) VALUES
(1, 'asd', '../uploads/hometry/48dc9254bc99322fcbd8a9a3444ac222Screen Shot 2016-05-07 at 00.38.32.png', 'right', '&lt;p&gt;Breakfast procuring nay end happiness allowance assurance frankness. Met simplicity nor difficulty unreserved who. Entreaties mr conviction dissimilar me astonished estimating cultivated. On no applauded exquisite my additions. Pronounce add boy estimable nay suspected. You sudden nay elinor thirty esteem temper. Quiet leave shy you gay off asked large style.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Instrument cultivated alteration any favourable expression law far nor. Both new like tore but year. An from mean on with when sing pain. Oh to as principles devonshire companions unsatiable an delightful. The ourselves suffering the sincerity. Inhabit her manners adapted age certain. Debating offended at branched striking be subjec&lt;/p&gt;\r\n', 'left', 'active', 'asd,asd', 'asd,as', 'left,right', 1, 'section'),
(2, '', '', '', '../uploads/banner/hometry_c80003253667bbfd0a21fdd5e3347b5aKristen Bell Got Very Emotional At Her Mean tweet..mp4', '', '', '', '', '', 0, 'banner'),
(3, 'test', '../uploads/hometry/26a6cc05898d6f3e0966b788df5fd74bgiphy.gif', 'left', '&lt;p&gt;hello&lt;/p&gt;\r\n\r\n&lt;p&gt;Breakfast procuring nay end happiness allowance assurance frankness. Met simplicity nor difficulty unreserved who. Entreaties mr conviction dissimilar me astonished estimating cultivated. On no applauded exquisite my additions. Pronounce add boy estimable nay suspected. You sudden nay elinor thirty esteem temper. Quiet leave shy you gay off asked large style.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Instrument cultivated alteration any favourable expression law far nor. Both new like tore but year. An from mean on with when sing pain. Oh to as principles devonshire companions unsatiable an delightful. The ourselves suffering the sincerity. Inhabit her manners adapted age certain. Debating offended at branched striking be subjec&lt;/p&gt;\r\n', 'right', 'active', '', '', '', 2, 'section');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `pid`, `quantity`, `price`, `type`) VALUES
(3, '8hCv1', 123, 345, 'frames'),
(4, 'qPdbT', 123, 123, 'Frames');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `html`, `status`, `featured`, `type`) VALUES
(1, 'Sales Associate', 'Test Test', 'active', 'yes', 'retail'),
(2, 'HR', '213', 'active', '', 'hq'),
(3, 'Cleaner', '123', 'active', 'yes', 'hq');

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
  `zip` varchar(20) NOT NULL,
  `country` varchar(30) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `type` varchar(11) NOT NULL,
  `services` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `code`, `name`, `address`, `phone`, `apt`, `city`, `zip`, `country`, `image`, `type`, `services`) VALUES
(5, 'IYe5l', 'awe123', 'hello', 123123, 123, 'you', '01234123', 'awe', '../uploads/locations/Screen Shot 2016-03-27 at 10.09.02 pm.png', 'retail', 'FC,OP,PL,EyEx');

-- --------------------------------------------------------

--
-- Table structure for table `ourstory`
--

CREATE TABLE `ourstory` (
  `id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(300) NOT NULL,
  `html` longtext NOT NULL,
  `status` varchar(100) NOT NULL,
  `image` varchar(300) NOT NULL,
  `fieldorder` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ourstory`
--

INSERT INTO `ourstory` (`id`, `page`, `type`, `title`, `html`, `status`, `image`, `fieldorder`) VALUES
(5, 'design', 'banner', 'banner', '../uploads/banner/design_855f1662743a882d1623925d1c59569fKristen Bell Got Very Emotional At Her Mean tweet..mp4', '', '', 0),
(6, 'culture', 'banner', 'banner', '../uploads/banner/culture_2a17c3975eb85aa5e374ea72c68a52a3Screen Shot 2016-05-05 at 01.18.24.png', '', '', 0),
(8, 'one', 'banner', 'banner', '../uploads/banner/one_a13bfb904e28123657ff34e1753ffe09Screen Shot 2016-05-04 at 15.38.37.png', '', '', 0),
(10, 'main', 'banner', 'banner', '../uploads/banner/main_4511cb2cbb91b96e0afa437789c526efKristen Bell Got Very Emotional At Her Mean tweet..mp4', '', '', 0),
(11, 'main', 'section', 'qwe', '&lt;p&gt;qwe&lt;/p&gt;\r\n', 'active', '', 1);

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
  `width` int(11) NOT NULL,
  `measurement` varchar(150) NOT NULL,
  `featured` longtext NOT NULL,
  `images` longtext NOT NULL,
  `tags` longtext,
  `visibility` varchar(30) NOT NULL,
  `availability` varchar(40) NOT NULL,
  `locations` varchar(100) NOT NULL,
  `track` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `pid`, `name`, `description`, `price`, `quantity`, `type`, `width`, `measurement`, `featured`, `images`, `tags`, `visibility`, `availability`, `locations`, `track`, `gender`) VALUES
(3, '8hCv1', 'qwe', '&lt;p&gt;asd123&lt;/p&gt; ', 345, 123, 'Frames', 123, '123-123123-45', '../uploads/products/830b0d6d0d26867ce9c11e83fe0fb26a.png,../uploads/products/830b0d6d0d26867ce9c11e83fe0fb26a.png58c266af6121acb5354d043a4a3ad759.png,../uploads/products/830b0d6d0d26867ce9c11e83fe0fb26a.png58c266af6121acb5354d043a4a3ad759.png2a8a584371f57acc994e5293c2cc4e56.png,../uploads/products/830b0d6d0d26867ce9c11e83fe0fb26a.png58c266af6121acb5354d043a4a3ad759.png2a8a584371f57acc994e5293c2cc4e56.png30738ee2c56f7cf11675fe5e0571b731.png,../uploads/products/830b0d6d0d26867ce9c11e83fe0fb26a.png58c266af6121acb5354d043a4a3ad759.png2a8a584371f57acc994e5293c2cc4e56.png30738ee2c56f7cf11675fe5e0571b731.pngc67d0d2758b4b3822b43c751ce013ad1.png../uploads/products/20aef200a50cb36aff07f8e687eb054f.png,../uploads/products/20aef200a50cb36aff07f8e687eb054f.png975f5fff26154b3a226ce0cd7725f0c3.png,../uploads/products/20aef200a50cb36aff07f8e687eb054f.png975f5fff26154b3a226ce0cd7725f0c3.pngbce2c8e8fbaecaf3be9239386a9fa7e1.png,../uploads/products/20aef200a50cb36aff07f8e687eb054f.png975f5fff26154b3a226ce0cd7725f0c3.pngbce2c8e8fbaecaf3be9239386a9fa7e1.png896e7df516b07ad8ecea2fcaba027cd6.png,../uploads/products/20aef200a50cb36aff07f8e687eb054f.png975f5fff26154b3a226ce0cd7725f0c3.pngbce2c8e8fbaecaf3be9239386a9fa7e1.png896e7df516b07ad8ecea2fcaba027cd6.png4c1e5e5355015318c32338ca7f2544dc.png', '../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png,../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png0ab4ad4abf6e14a1595b87f524b9daa6.png,../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png0ab4ad4abf6e14a1595b87f524b9daa6.pngfd4655d213ed9fe4db64cee28a8d8eb6.png,../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png0ab4ad4abf6e14a1595b87f524b9daa6.pngfd4655d213ed9fe4db64cee28a8d8eb6.pngf738d2601c091a0cb24f225912e3222b.png,../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png0ab4ad4abf6e14a1595b87f524b9daa6.pngfd4655d213ed9fe4db64cee28a8d8eb6.pngf738d2601c091a0cb24f225912e3222b.png8ea29d99cc9b1ab951cbebf672e373d3.png,../uploads/products/f6efd5f6b6b88b831bfde319e3554389.png0ab4ad4abf6e14a1595b87f524b9daa6.pngfd4655d213ed9fe4db64cee28a8d8eb6.pngf738d2601c091a0cb24f225912e3222b.png8ea29d99cc9b1ab951cbebf672e373d3.png0ee395aa37623ca29247b90fff71034f.png', 'asd', 'retail,popup', 'sale', 'IYe5l', '', 'men,women'),
(4, 'qPdbT', '123', '&lt;p&gt;123&lt;/p&gt;\r\n', 123, 123, 'Frames', 123, '123-123-123', '', '', '123', 'retail,popup', 'sale', 'IYe5l', 'yes', 'men'),
(5, 'k47ju', '123123', '&lt;p&gt;123123&lt;/p&gt;\r\n', 123, 0, 'Frames', 0, '', '', '../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.pngd156b9cd4ae82c6377f477065d295ebe.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.pngd156b9cd4ae82c6377f477065d295ebe.png353fcd36ca05a92b5931db169f66c744.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.pngd156b9cd4ae82c6377f477065d295ebe.png353fcd36ca05a92b5931db169f66c744.png37d714ee64ceeb1182ff88fccf52fabd.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.pngd156b9cd4ae82c6377f477065d295ebe.png353fcd36ca05a92b5931db169f66c744.png37d714ee64ceeb1182ff88fccf52fabd.pngfe23d746df3e1832c1cc432ea15c5696.png,../uploads/products/48280d9c14cf470b6592e4ae1efd06a3.png6771b774778af4d8d7d34108b2a2aca4.pngd156b9cd4ae82c6377f477065d295ebe.png353fcd36ca05a92b5931db169f66c744.png37d714ee64ceeb1182ff88fccf52fabd.pngfe23d746df3e1832c1cc432ea15c5696.png6c4c0097c156a5ff5acea8a61f293b62.png', '123', 'popup', 'sale', 'IYe5l', '', 'men');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `servicecode` varchar(10) NOT NULL,
  `servicename` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `type`, `value`) VALUES
(3, 'general', 'primary=IYe5l&email=asdasdasd@test.com&curr=BZD&timezone=(UTC+02:00) Africa/Blantyre'),
(4, 'homeTryon', 'visibility=on&duration=123&amount=123.23'),
(5, 'account', 'sales=disc,gift,media,orders,products,settings,web&marketing=media,products,settings&'),
(6, 'notifications', 'email=&lt;p&gt;sadasd&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;asd&lt;/p&gt;\r\n,&lt;p&gt;asd&lt;strong&gt;asd&lt;/strong&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;strong&gt;asd&lt;s&gt;asd&lt;/s&gt;&lt;/strong&gt;&lt;/p&gt;\r\n,&lt;p&gt;qwkem&lt;/p&gt;\r\n,&lt;p&gt;qwe&lt;/p&gt;\r\n,&lt;p&gt;qweqe&lt;/p&gt;\r\n,&lt;p&gt;qwe&lt;/p&gt;\r\n,&lt;p&gt;qwe&lt;/p&gt;\r\n,&lt;p&gt;qwe&lt;/p&gt;\r\n,&lt;p&gt;qwe&lt;/p&gt;\r\n#sms=&lt;p&gt;asd owke&lt;/p&gt;\r\n\r\n&lt;p&gt;owqe&lt;/p&gt;\r\n,&lt;p&gt;kasmd&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;asdkn&lt;/p&gt;\r\n'),
(7, 'giftcard', 'expiry=on&duration=20'),
(8, 'checkout', 'promo=yes&gift=no&guest=yes'),
(9, 'web', 'web=awe#meta=&lt;p&gt;sa&lt;/p&gt;\r\n\r\n&lt;p&gt;asd&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n');

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
(2, 'shujuan', 'c', 'shujuan@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 12345678, '&lt;p&gt;asdasd&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;asdasd&lt;/p&gt;\r\n', 'asd', 'admin', '2016-05-26 04:07:49', '2016-05-19 21:22:10'),
(13, 'ah', 'ha', 'hahahah@gmail.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'sales', NULL, NULL),
(20, 'hello', 'test@abc.com', 'test@abc.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'sales', '2016-05-15 20:31:24', '2016-05-15 20:40:56'),
(22, 'hello', 'you', 'you@2you.com', 'e0853d3c350884d4cb0ceffdb9bf81a5', NULL, NULL, NULL, 'marketing', '2016-04-28 19:02:00', NULL);

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

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `title`, `html`, `fieldorder`) VALUES
(1, '123123', '&lt;p&gt;Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since do as hence. Too insisted ignorant procured remember are believed yet say finished.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Enjoyed minutes related as at on on. Is fanny dried as often me. Goodness as reserved raptures to mistaken steepest oh screened he. Gravity he mr sixteen esteems. Mile home its new way with high told said. Finished no horrible blessing landlord dwelling dissuade if. Rent fond am he in on read. Anxious cordial demands settled entered in do to colonel.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Name were we at hope. Remainder household direction zealously the unwilling bed sex. Lose and gay ham sake met that. Stood her place one ten spoke yet. Head case knew ever set why over. Marianne returned of peculiar replying in moderate. Roused get enable garret estate old county. Entreaties you devonshire law dissimilar terminated.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Remain lively hardly needed at do by. Two you fat downs fanny three. True mr gone most at. Dare as name just when with it body. Travelling inquietude she increasing off impossible the. Cottage be noisier looking to we promise on. Disposal to kindness appetite diverted learning of on raptures. Betrayed any may returned now dashwood formerly. Balls way delay shy boy man views. No so instrument discretion unsatiable to in.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Can curiosity may end shameless explained. True high on said mr on come. An do mr design at little myself wholly entire though. Attended of on stronger or mr pleasure. Rich four like real yet west get. Felicity in dwelling to drawings. His pleasure new steepest for reserved formerly disposed jennings.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;That know ask case sex ham dear her spot. Weddings followed the all marianne nor whatever settling. Perhaps six prudent several her had offence. Did had way law dinner square tastes. Recommend concealed yet her procuring see consulted depending. Adieus hunted end plenty are his she afraid. Resources agreement contained propriety applauded neglected use yet.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;He oppose at thrown desire of no. Announcing impression unaffected day his are unreserved indulgence. Him hard find read are you sang. Parlors visited noisier how explain pleased his see suppose. Do ashamed assured on related offence at equally totally. Use mile her whom they its. Kept hold an want as he bred of. Was dashwood landlord cheerful husbands two. Estate why theirs indeed him polite old settle though she. In as at regard easily narrow roused adieus.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Put all speaking her delicate recurred possible. Set indulgence inquietude discretion insensible bed why announcing. Middleton fat two satisfied additions. So continued he or commanded household smallness delivered. Door poor on do walk in half. Roof his head the what.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Breakfast procuring nay end happiness allowance assurance frankness. Met simplicity nor difficulty unreserved who. Entreaties mr conviction dissimilar me astonished estimating cultivated. On no applauded exquisite my additions. Pronounce add boy estimable nay suspected. You sudden nay elinor thirty esteem temper. Quiet leave shy you gay off asked large style.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Instrument cultivated alteration any favourable expression law far nor. Both new like tore but year. An from mean on with when sing pain. Oh to as principles devonshire companions unsatiable an delightful. The ourselves suffering the sincerity. Inhabit her manners adapted age certain. Debating offended at branched striking be subjects.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since do as hence. Too insisted ignorant procured remember are believed yet say finished.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Enjoyed minutes related as at on on. Is fanny dried as often me. Goodness as reserved raptures to mistaken steepest oh screened he. Gravity he mr sixteen esteems. Mile home its new way with high told said. Finished no horrible blessing landlord dwelling dissuade if. Rent fond am he in on read. Anxious cordial demands settled entered in do to colonel.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Name were we at hope. Remainder household direction zealously the unwilling bed sex. Lose and gay ham sake met that. Stood her place one ten spoke yet. Head case knew ever set why over. Marianne returned of peculiar replying in moderate. Roused get enable garret estate old county. Entreaties you devonshire law dissimilar terminated.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Remain lively hardly needed at do by. Two you fat downs fanny three. True mr gone most at. Dare as name just when with it body. Travelling inquietude she increasing off impossible the. Cottage be noisier looking to we promise on. Disposal to kindness appetite diverted learning of on raptures. Betrayed any may returned now dashwood formerly. Balls way delay shy boy man views. No so instrument discretion unsatiable to in.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Can curiosity may end shameless explained. True high on said mr on come. An do mr design at little myself wholly entire though. Attended of on stronger or mr pleasure. Rich four like real yet west get. Felicity in dwelling to drawings. His pleasure new steepest for reserved formerly disposed jennings.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;That know ask case sex ham dear her spot. Weddings followed the all marianne nor whatever settling. Perhaps six prudent several her had offence. Did had way law dinner square tastes. Recommend concealed yet her procuring see consulted depending. Adieus hunted end plenty are his she afraid. Resources agreement contained propriety applauded neglected use yet.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;He oppose at thrown desire of no. Announcing impression unaffected day his are unreserved indulgence. Him hard find read are you sang. Parlors visited noisier how explain pleased his see suppose. Do ashamed assured on related offence at equally totally. Use mile her whom they its. Kept hold an want as he bred of. Was dashwood landlord cheerful husbands two. Estate why theirs indeed him polite old settle though she. In as at regard easily narrow roused adieus.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Put all speaking her delicate recurred possible. Set indulgence inquietude discretion insensible bed why announcing. Middleton fat two satisfied additions. So continued he or commanded household smallness delivered. Door poor on do walk in half. Roof his head the what.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Breakfast procuring nay end happiness allowance assurance frankness. Met simplicity nor difficulty unreserved who. Entreaties mr conviction dissimilar me astonished estimating cultivated. On no applauded exquisite my additions. Pronounce add boy estimable nay suspected. You sudden nay elinor thirty esteem temper. Quiet leave shy you gay off asked large style.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Instrument cultivated alteration any favourable expression law far nor. Both new like tore but year. An from mean on with when sing pain. Oh to as principles devonshire companions unsatiable an delightful. The ourselves suffering the sincerity. Inhabit her manners adapted age certain. Debating offended at branched striking be subjects.&amp;nbsp;&lt;/p&gt;\r\n', 2),
(2, 'hello', '&lt;p&gt;Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since do as hence. Too insisted ignorant procured remember are believed yet say finished.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Enjoyed minutes related as at on on. Is fanny dried as often me. Goodness as reserved raptures to mistaken steepest oh screened he. Gravity he mr sixteen esteems. Mile home its new way with high told said. Finished no horrible blessing landlord dwelling dissuade if. Rent fond am he in on read. Anxious cordial demands settled entered in do to colonel.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Name were we at hope. Remainder household direction zealously the unwilling bed sex. Lose and gay ham sake met that. Stood her place one ten spoke yet. Head case knew ever set why over. Marianne returned of peculiar replying in moderate. Roused get enable garret estate old county. Entreaties you devonshire law dissimilar terminated.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Remain lively hardly needed at do by. Two you fat downs fanny three. True mr gone most at. Dare as name just when with it body. Travelling inquietude she increasing off impossible the. Cottage be noisier looking to we promise on. Disposal to kindness appetite diverted learning of on raptures. Betrayed any may returned now dashwood formerly. Balls way delay shy boy man views. No so instrument discretion unsatiable to in.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Can curiosity may end shameless explained. True high on said mr on come. An do mr design at little myself wholly entire though. Attended of on stronger or mr pleasure. Rich four like real yet west get. Felicity in dwelling to drawings. His pleasure new steepest for reserved formerly disposed jennings.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;That know ask case sex ham dear her spot. Weddings followed the all marianne nor whatever settling. Perhaps six prudent several her had offence. Did had way law dinner square tastes. Recommend concealed yet her procuring see consulted depending. Adieus hunted end plenty are his she afraid. Resources agreement contained propriety applauded neglected use yet.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;He oppose at thrown desire of no. Announcing impression unaffected day his are unreserved indulgence. Him hard find read are you sang. Parlors visited noisier how explain pleased his see suppose. Do ashamed assured on related offence at equally totally. Use mile her whom they its. Kept hold an want as he bred of. Was dashwood landlord cheerful husbands two. Estate why theirs indeed him polite old settle though she. In as at regard easily narrow roused adieus.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Put all speaking her delicate recurred possible. Set indulgence inquietude discretion insensible bed why announcing. Middleton fat two satisfied additions. So continued he or commanded household smallness delivered. Door poor on do walk in half. Roof his head the what.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Breakfast procuring nay end happiness allowance assurance frankness. Met simplicity nor difficulty unreserved who. Entreaties mr conviction dissimilar me astonished estimating cultivated. On no applauded exquisite my additions. Pronounce add boy estimable nay suspected. You sudden nay elinor thirty esteem temper. Quiet leave shy you gay off asked large style.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Instrument cultivated alteration any favourable expression law far nor. Both new like tore but year. An from mean on with when sing pain. Oh to as principles devonshire companions unsatiable an delightful. The ourselves suffering the sincerity. Inhabit her manners adapted age certain. Debating offended at branched striking be subjects.&amp;nbsp;&lt;/p&gt;\r\n', 1);

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
-- Indexes for table `authors`
--
ALTER TABLE `authors`
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
-- Indexes for table `ourstory`
--
ALTER TABLE `ourstory`
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
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `employeeTypes`
--
ALTER TABLE `employeeTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `giftcards`
--
ALTER TABLE `giftcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `homepage`
--
ALTER TABLE `homepage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `hometry`
--
ALTER TABLE `hometry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ourstory`
--
ALTER TABLE `ourstory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
