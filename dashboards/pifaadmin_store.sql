-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2017 at 08:32 PM
-- Server version: 5.5.31
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pifaadmin_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `attribute_name` text,
  `sortOrder` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `attribute_value` text,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `created_at`, `updated_at`, `attribute_name`, `sortOrder`, `parent_id`, `attribute_value`, `created_by`) VALUES
(1, '2017-03-28 08:17:31', '2017-02-24 00:47:21', 'COLOR', 1, 12, 'RED | BLUE | BLACK', 1),
(2, '2017-03-28 08:17:32', '2017-02-24 00:48:13', 'SIZE ', 1, 1, 'SMALL | LARGE | EXTRA SMALL', 1),
(3, '2017-03-28 08:17:33', '2017-02-24 01:02:34', 'Shape', 0, 0, 'Round | Square | Triangle', 1),
(4, '2017-03-28 08:17:35', '2017-02-24 01:26:26', '颜色', 0, 0, '红 | 蓝色 | 绿色', 1),
(5, '2017-03-28 12:17:40', '2017-03-28 12:17:40', 'Coloor', 2, 0, 'WHITE | GREY | GREEN', 5),
(7, '2017-03-28 13:15:44', '2017-03-28 13:15:44', 'Measure', 12, 0, '3 | 4 | 5', 1),
(8, '2017-03-28 13:16:07', '2017-03-28 13:16:07', 'TEST ATT', 2, 0, '12 | 125 | 65', 1),
(9, '2017-03-28 13:20:19', '2017-03-28 13:20:19', 'final', 0, 0, 'f | E | K | E', 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int(11) NOT NULL,
  `brandDescription` text,
  `supplier_id` int(11) DEFAULT NULL,
  `brand_name` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brandDescription`, `supplier_id`, `brand_name`, `created_at`, `updated_at`) VALUES
(28, '<p><a href="http://pifastore.my/dev/Images/LOGO/logo-png.png"><img alt="" src="http://pifastore.my/dev/Images/LOGO/logo-png.png" style="height:100px; width:100px" /></a>&nbsp;</p>\r\n\r\n<p>testadadsasdadsad</p>\r\n', 1, 'English Brand', '2017-03-27 11:12:03', '2017-03-27 11:12:03'),
(29, '<p>asdasdasdasd</p>\r\n', 1, 'asdadsasd', '2017-03-28 13:01:07', '2017-03-28 13:01:07'),
(30, '<p>My Brand</p>\r\n', 1, 'My Brand', '2017-03-28 13:01:32', '2017-03-28 13:01:32'),
(31, '<p>My Brandasdads</p>\r\n', 1, 'My Brand', '2017-03-28 13:01:55', '2017-03-28 13:01:55'),
(32, '<p>my brand</p>\r\n', 1, 'my brand', '2017-03-28 13:14:41', '2017-03-28 13:14:41'),
(33, '<p>Addidas Official Brand</p>\r\n', 1, 'Addidas', '2017-03-29 13:51:01', '2017-03-29 13:51:01'),
(34, '', 1, 'POLO', '2017-03-31 11:47:34', '2017-03-31 11:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(50) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cat_percentage` float DEFAULT NULL,
  `cat_desc` text,
  `sortOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `parent_id`, `created_at`, `updated_at`, `cat_percentage`, `cat_desc`, `sortOrder`) VALUES
(1, 'Device Accessories', 0, '2017-03-08 06:35:20', '0000-00-00 00:00:00', 12, 'Device Accessories', 1),
(2, 'New', 1, '2017-03-08 06:35:30', '0000-00-00 00:00:00', 12, 'New', 1),
(3, 'Refurbished', 1, '2017-03-08 06:35:51', '0000-00-00 00:00:00', 10, 'Refurbished', 2),
(4, 'Used', 1, '2017-03-08 06:36:06', '0000-00-00 00:00:00', 8, 'Used', 3),
(5, 'Kindle', 0, '2017-03-08 06:48:41', '0000-00-00 00:00:00', 0, 'Kindle Devices Accessories', 2),
(6, ' Used only', 5, '2017-03-08 06:53:15', '0000-00-00 00:00:00', 12, '\r\nUsed only', 1),
(7, 'Automotive & Powersports', 0, '2017-03-08 06:53:37', '0000-00-00 00:00:00', 12, 'Automotive & Powersports', 3),
(8, 'Parts', 7, '2017-03-08 06:54:16', '0000-00-00 00:00:00', 5, 'Parts', 1),
(9, 'Tools ', 7, '2017-03-08 06:56:04', '0000-00-00 00:00:00', 5, 'Tools ', 2),
(10, 'Accessories', 7, '2017-03-08 07:05:18', '0000-00-00 00:00:00', 5, 'Accessories', 3),
(11, 'Baby Products (Excluding Apparel)', 0, '2017-03-08 07:06:19', '0000-00-00 00:00:00', 8, 'Baby Products (Excluding Appar', 4),
(12, 'Nursery', 11, '2017-03-08 07:06:31', '0000-00-00 00:00:00', 8, 'Nursery', 1),
(13, 'Feeding', 11, '2017-03-08 07:06:40', '0000-00-00 00:00:00', 8, 'Feeding', 2),
(14, 'Gear', 11, '2017-03-08 07:06:48', '0000-00-00 00:00:00', 8, 'Gear', 3),
(15, 'Beauty', 0, '2017-03-08 07:07:08', '0000-00-00 00:00:00', 9, 'Beauty', 5),
(16, 'Fragrance', 15, '2017-03-08 07:07:18', '0000-00-00 00:00:00', 9, 'Fragrance', 1),
(17, 'Skincare', 15, '2017-03-08 07:07:27', '0000-00-00 00:00:00', 9, 'Skincare', 2),
(18, 'Makeup', 15, '2017-03-08 07:07:36', '0000-00-00 00:00:00', 9, 'Makeup', 3),
(19, 'Hair Care', 15, '2017-03-08 07:07:53', '0000-00-00 00:00:00', 9, 'Hair Care', 4),
(20, 'Bath & Shower', 15, '2017-03-08 07:08:09', '0000-00-00 00:00:00', 9, 'Bath & Shower', 5),
(21, 'Health & Personal Care.', 15, '2017-03-08 07:08:19', '0000-00-00 00:00:00', 9, 'Health & Personal Care.', 6),
(22, 'Books', 0, '2017-03-08 07:08:34', '0000-00-00 00:00:00', 10, 'Books', 6),
(23, 'Calendars', 22, '2017-03-08 07:08:46', '0000-00-00 00:00:00', 10, 'Calendars', 1),
(24, 'Card Decks', 22, '2017-03-08 07:09:03', '0000-00-00 00:00:00', 10, 'Card Decks', 2),
(25, 'Sheet Music', 22, '2017-03-08 07:09:14', '0000-00-00 00:00:00', 10, 'Sheet Music', 3),
(26, 'Magazines', 22, '2017-03-08 07:09:24', '0000-00-00 00:00:00', 10, 'Magazines', 4),
(27, 'Journals', 22, '2017-03-08 07:09:34', '0000-00-00 00:00:00', 10, 'Journals', 5),
(28, 'Other Publications', 22, '2017-03-08 07:10:03', '0000-00-00 00:00:00', 10, 'Other Publications', 6),
(29, 'New', 23, '2017-03-08 07:10:18', '0000-00-00 00:00:00', 0, 'New', 1),
(30, 'used', 23, '2017-03-08 07:10:28', '0000-00-00 00:00:00', 0, 'used', 2),
(31, 'New', 24, '2017-03-08 07:10:35', '0000-00-00 00:00:00', 0, 'New', 1),
(32, 'used', 24, '2017-03-08 07:10:41', '0000-00-00 00:00:00', 0, 'used', 2),
(33, 'New', 25, '2017-03-08 07:10:49', '0000-00-00 00:00:00', 0, 'New', 1),
(34, 'used', 25, '2017-03-08 07:10:54', '0000-00-00 00:00:00', 0, 'used', 2),
(35, 'New', 26, '2017-03-08 07:11:01', '0000-00-00 00:00:00', 0, 'New', 1),
(36, 'used', 26, '2017-03-08 07:11:07', '0000-00-00 00:00:00', 0, 'used', 2),
(37, 'New', 27, '2017-03-08 07:11:14', '0000-00-00 00:00:00', 0, 'New', 1),
(38, 'used', 27, '2017-03-08 07:11:19', '0000-00-00 00:00:00', 0, 'used', 2),
(39, 'New', 28, '2017-03-08 07:11:26', '0000-00-00 00:00:00', 0, 'New', 1),
(40, 'used', 28, '2017-03-08 07:11:33', '0000-00-00 00:00:00', 0, 'used', 2),
(41, 'Business Products (B2B)', 0, '2017-03-08 07:12:19', '0000-00-00 00:00:00', 0, 'Business-relevant products across multiple categories. Special pricing features to target business customers.', 7),
(42, 'New', 41, '2017-03-08 07:12:33', '0000-00-00 00:00:00', 0, 'New', 1),
(43, 'Refurbished', 41, '2017-03-08 07:12:41', '0000-00-00 00:00:00', 0, 'Refurbished', 2),
(44, 'Used', 41, '2017-03-08 07:12:48', '0000-00-00 00:00:00', 0, 'Used', 3),
(45, 'Camera & Photo', 0, '2017-03-08 07:13:00', '0000-00-00 00:00:00', 11, 'Camera & Photo', 8),
(46, 'Cameras', 45, '2017-03-08 07:13:10', '0000-00-00 00:00:00', 11, 'Cameras', 1),
(47, 'Camcorders', 45, '2017-03-08 07:13:19', '0000-00-00 00:00:00', 11, 'Camcorders', 2),
(48, 'Telescopes', 45, '2017-03-08 07:13:26', '0000-00-00 00:00:00', 11, 'Telescopes', 3),
(49, 'New', 46, '2017-03-08 07:13:36', '0000-00-00 00:00:00', 0, 'New', 1),
(50, 'Refurbished', 46, '2017-03-08 07:13:45', '0000-00-00 00:00:00', 0, 'Refurbished', 2),
(51, 'Used', 46, '2017-03-08 07:13:53', '0000-00-00 00:00:00', 0, 'Used', 3),
(52, 'New', 47, '2017-03-08 07:14:01', '0000-00-00 00:00:00', 0, 'New', 1),
(53, 'Refurbished', 47, '2017-03-08 07:14:15', '0000-00-00 00:00:00', 0, 'Refurbished', 2),
(54, 'Used', 47, '2017-03-08 07:14:22', '0000-00-00 00:00:00', 0, 'Used', 3),
(55, 'New', 48, '2017-03-08 07:14:30', '0000-00-00 00:00:00', 0, 'New', 1),
(56, 'Refurbished', 48, '2017-03-08 07:14:37', '0000-00-00 00:00:00', 0, 'Refurbished', 2),
(57, 'Used', 48, '2017-03-08 07:14:44', '0000-00-00 00:00:00', 0, 'Used', 3),
(58, 'Cell Phones', 0, '2017-03-08 07:18:14', '0000-00-00 00:00:00', 0, 'Cell Phones', 9),
(59, 'New', 58, '2017-03-08 07:18:27', '0000-00-00 00:00:00', 15, 'New', 1),
(60, 'Used', 58, '2017-03-08 07:18:37', '0000-00-00 00:00:00', 14, 'Used', 2),
(61, 'Refurbished', 58, '2017-03-08 07:18:47', '0000-00-00 00:00:00', 13, 'Refurbished', 3),
(62, 'Unlocked', 58, '2017-04-07 13:48:59', '0000-00-00 00:00:00', 10, 'Unlocked', 10),
(63, 'Clothing & Accessories', 0, '2017-04-07 13:48:56', '0000-00-00 00:00:00', 2, 'Clothing & Accessories', 4),
(64, 'Outerwear', 63, '2017-03-08 07:26:53', '0000-00-00 00:00:00', 3, 'Outerwear', 1),
(65, 'Outerwear', 63, '2017-03-08 07:27:04', '0000-00-00 00:00:00', 3, 'Outerwear', 2),
(66, 'Innerwear', 63, '2017-03-08 07:28:24', '0000-00-00 00:00:00', 3, 'Innerwear', 11),
(67, 'Belts', 63, '2017-03-08 07:27:30', '0000-00-00 00:00:00', 3, 'Belts', 3),
(68, 'Wallets', 63, '2017-03-08 07:27:42', '0000-00-00 00:00:00', 1, 'Wallets', 4),
(69, 'Home & Living', 0, '2017-03-10 05:37:13', '0000-00-00 00:00:00', 10, 'Home & Living', 11),
(70, 'Furniture', 69, '2017-03-10 05:37:30', '0000-00-00 00:00:00', 8, 'Furniture', 1),
(71, 'Game Room Furniture', 70, '2017-03-10 05:37:43', '0000-00-00 00:00:00', 7, 'Game Room Furniture', 1),
(72, 'Billiard Tables', 71, '2017-03-10 05:38:00', '0000-00-00 00:00:00', 8, 'Billiard Tables', 1),
(73, 'Game Tables ', 71, '2017-03-10 05:38:15', '0000-00-00 00:00:00', 8, 'Game Tables ', 2),
(74, 'Poker Tables', 71, '2017-03-10 05:38:27', '0000-00-00 00:00:00', 0, 'Poker Tables', 3),
(75, 'Foosball Tables', 71, '2017-03-10 05:38:38', '0000-00-00 00:00:00', 8, 'Foosball Tables', 4),
(76, 'Video Game Chairs', 71, '2017-03-10 05:38:52', '0000-00-00 00:00:00', 8, 'Video Game Chairs', 5),
(77, 'New', 72, '2017-03-10 05:39:17', '0000-00-00 00:00:00', 5, 'New', 1),
(78, 'Used', 72, '2017-03-10 05:39:38', '0000-00-00 00:00:00', 8, 'Used', 2),
(79, 'test', 0, '2017-03-13 11:10:37', '0000-00-00 00:00:00', 12, 'test', 12),
(80, '3', 0, '2017-03-13 11:12:56', '0000-00-00 00:00:00', 0, '3', 13),
(81, 'Ad', 0, '2017-03-28 09:03:43', '0000-00-00 00:00:00', 12, 'Ad', 21),
(82, 'dasda', 81, '2017-03-28 09:04:31', '0000-00-00 00:00:00', 0, 'asdasd', 1),
(83, 'multitest1', 30, '2017-04-10 07:53:24', '0000-00-00 00:00:00', 0, 'multitest1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `imagetable`
--

CREATE TABLE IF NOT EXISTS `imagetable` (
  `id` int(11) NOT NULL,
  `image_path` text NOT NULL,
  `refId` int(11) NOT NULL,
  `table` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` int(11) DEFAULT NULL COMMENT '1 = main , 2 = optional'
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `imagetable`
--

INSERT INTO `imagetable` (`id`, `image_path`, `refId`, `table`, `created_at`, `updated_at`, `type`) VALUES
(1, 'public/Images/Uploads/products/1490359311_today timing.png', 1, 'products', '2017-03-24 16:41:51', '2017-03-24 16:41:51', 1),
(18, 'public/Images/Uploads/brands/1490598723_test1.png', 28, 'brands', '2017-03-27 11:12:03', '2017-03-27 11:12:03', 1),
(21, 'public/Images/Uploads/user_detail/1490688084_valeed.png', 3, 'user_detail', '2017-03-28 12:01:24', '2017-03-28 12:01:24', 1),
(22, 'public/Images/Uploads/brands/1490691667_today timing.png', 29, 'brands', '2017-03-28 13:01:07', '2017-03-28 13:01:07', 1),
(23, 'public/Images/Uploads/brands/1490691692_test2.png', 30, 'brands', '2017-03-28 13:01:32', '2017-03-28 13:01:32', 1),
(24, 'public/Images/Uploads/brands/1490691715_test1.png', 31, 'brands', '2017-03-28 13:01:55', '2017-03-28 13:01:55', 1),
(25, 'public/Images/Uploads/brands/1490692481_today timing.png', 32, 'brands', '2017-03-28 13:14:41', '2017-03-28 13:14:41', 1),
(26, 'public/Images/Uploads/brands/1490781061_images.png', 33, 'brands', '2017-03-29 13:51:01', '2017-03-29 13:51:01', 1),
(27, 'public/Images/Uploads/products/1490781318_BB1302_01_standard.jpg', 2, 'products', '2017-03-29 13:55:18', '2017-03-29 13:55:18', 1),
(28, 'public/Images/Uploads/product_childs/1490870917_test1.png', 4, 'product_childs', '2017-03-30 14:48:37', '2017-03-30 14:48:37', 1),
(29, 'public/Images/Uploads/product_childs/1490870917_test2.png', 5, 'product_childs', '2017-03-30 14:48:37', '2017-03-30 14:48:37', 1),
(30, 'public/Images/Uploads/product_childs/1490870917_today timing.png', 6, 'product_childs', '2017-03-30 14:48:37', '2017-03-30 14:48:37', 1),
(31, 'public/Images/Uploads/products/1490871973_adsadsasd.jpg', 4, 'products', '2017-03-30 15:06:13', '2017-03-30 15:06:13', 1),
(32, 'public/Images/Uploads/product_childs/1490871973_adsadsasd.jpg', 7, 'product_childs', '2017-03-30 15:06:13', '2017-03-30 15:06:13', 1),
(33, 'public/Images/Uploads/brands/1490946454_download.png', 34, 'brands', '2017-03-31 11:47:34', '2017-03-31 11:47:34', 1),
(34, 'public/Images/Uploads/products/1490947885_download (1).jpg', 5, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(35, 'public/Images/Uploads/product_childs/1490947885_polored.jpg', 8, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(36, 'public/Images/Uploads/product_childs/1490947885_bluepolo.jpg', 9, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(37, 'public/Images/Uploads/product_childs/1490947885_download (1).jpg', 10, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(38, 'public/Images/Uploads/product_childs/1490947885_polored.jpg', 11, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(39, 'public/Images/Uploads/product_childs/1490947885_bluepolo.jpg', 12, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(40, 'public/Images/Uploads/product_childs/1490947885_download (1).jpg', 13, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(41, 'public/Images/Uploads/product_childs/1490947885_polored.jpg', 14, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(42, 'public/Images/Uploads/product_childs/1490947885_bluepolo.jpg', 15, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(43, 'public/Images/Uploads/product_childs/1490947885_download (1).jpg', 16, 'product_childs', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(44, 'public/Images/Uploads/products/1490947885_download (1).jpg', 11, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(45, 'public/Images/Uploads/products/1490947885_download (1).jpg', 12, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(46, 'public/Images/Uploads/products/1490947885_download (1).jpg', 13, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(47, 'public/Images/Uploads/products/1490947885_download (1).jpg', 14, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(48, 'public/Images/Uploads/products/1490947885_download (1).jpg', 15, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(49, 'public/Images/Uploads/products/1490947885_download (1).jpg', 16, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1),
(50, 'public/Images/Uploads/products/1490947885_download (1).jpg', 17, 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `languagetable`
--

CREATE TABLE IF NOT EXISTS `languagetable` (
  `id` int(11) NOT NULL,
  `lang` varchar(3) NOT NULL,
  `field` varchar(50) NOT NULL,
  `table` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `refId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languagetable`
--

INSERT INTO `languagetable` (`id`, `lang`, `field`, `table`, `created_at`, `updated_at`, `refId`) VALUES
(1, 'en', '', 'attributes', '2017-02-24 00:47:21', '2017-02-24 00:47:21', 1),
(2, 'en', '', 'attributes', '2017-02-24 00:48:13', '2017-02-24 00:48:13', 2),
(3, 'en', '', 'attributes', '2017-02-24 01:02:34', '2017-02-24 01:02:34', 3),
(4, 'ch', '', 'attributes', '2017-02-24 01:26:26', '2017-02-24 01:26:26', 4),
(5, 'en', 'message', 'settings', '2017-03-07 02:49:38', '2017-03-07 02:49:38', 37),
(6, 'en', 'message', 'settings', '2017-03-07 02:51:33', '2017-03-07 02:51:33', 38),
(7, 'ch', 'message', 'settings', '2017-03-07 02:51:47', '2017-03-07 02:51:47', 39),
(33, 'en', '', 'categories', '2017-03-08 01:35:20', '2017-03-08 01:35:20', 1),
(34, 'en', '', 'categories', '2017-03-08 01:35:30', '2017-03-08 01:35:30', 2),
(35, 'en', '', 'categories', '2017-03-08 01:35:51', '2017-03-08 01:35:51', 3),
(36, 'en', '', 'categories', '2017-03-08 01:36:06', '2017-03-08 01:36:06', 4),
(37, 'en', '', 'categories', '2017-03-08 01:48:41', '2017-03-08 01:48:41', 5),
(38, 'en', '', 'categories', '2017-03-08 01:53:15', '2017-03-08 01:53:15', 6),
(39, 'en', '', 'categories', '2017-03-08 01:53:37', '2017-03-08 01:53:37', 7),
(40, 'en', '', 'categories', '2017-03-08 01:54:16', '2017-03-08 01:54:16', 8),
(41, 'en', '', 'categories', '2017-03-08 01:56:04', '2017-03-08 01:56:04', 9),
(42, 'en', '', 'categories', '2017-03-08 02:05:18', '2017-03-08 02:05:18', 10),
(43, 'en', '', 'categories', '2017-03-08 02:06:19', '2017-03-08 02:06:19', 11),
(44, 'en', '', 'categories', '2017-03-08 02:06:31', '2017-03-08 02:06:31', 12),
(45, 'en', '', 'categories', '2017-03-08 02:06:40', '2017-03-08 02:06:40', 13),
(46, 'en', '', 'categories', '2017-03-08 02:06:48', '2017-03-08 02:06:48', 14),
(47, 'en', '', 'categories', '2017-03-08 02:07:08', '2017-03-08 02:07:08', 15),
(48, 'en', '', 'categories', '2017-03-08 02:07:18', '2017-03-08 02:07:18', 16),
(49, 'en', '', 'categories', '2017-03-08 02:07:27', '2017-03-08 02:07:27', 17),
(50, 'en', '', 'categories', '2017-03-08 02:07:36', '2017-03-08 02:07:36', 18),
(51, 'en', '', 'categories', '2017-03-08 02:07:53', '2017-03-08 02:07:53', 19),
(52, 'en', '', 'categories', '2017-03-08 02:08:09', '2017-03-08 02:08:09', 20),
(53, 'en', '', 'categories', '2017-03-08 02:08:19', '2017-03-08 02:08:19', 21),
(54, 'en', '', 'categories', '2017-03-08 02:08:34', '2017-03-08 02:08:34', 22),
(55, 'en', '', 'categories', '2017-03-08 02:08:46', '2017-03-08 02:08:46', 23),
(56, 'en', '', 'categories', '2017-03-08 02:09:03', '2017-03-08 02:09:03', 24),
(57, 'en', '', 'categories', '2017-03-08 02:09:14', '2017-03-08 02:09:14', 25),
(58, 'en', '', 'categories', '2017-03-08 02:09:24', '2017-03-08 02:09:24', 26),
(59, 'en', '', 'categories', '2017-03-08 02:09:34', '2017-03-08 02:09:34', 27),
(60, 'en', '', 'categories', '2017-03-08 02:10:03', '2017-03-08 02:10:03', 28),
(61, 'en', '', 'categories', '2017-03-08 02:10:18', '2017-03-08 02:10:18', 29),
(62, 'en', '', 'categories', '2017-03-08 02:10:28', '2017-03-08 02:10:28', 30),
(63, 'en', '', 'categories', '2017-03-08 02:10:35', '2017-03-08 02:10:35', 31),
(64, 'en', '', 'categories', '2017-03-08 02:10:41', '2017-03-08 02:10:41', 32),
(65, 'en', '', 'categories', '2017-03-08 02:10:49', '2017-03-08 02:10:49', 33),
(66, 'en', '', 'categories', '2017-03-08 02:10:54', '2017-03-08 02:10:54', 34),
(67, 'en', '', 'categories', '2017-03-08 02:11:01', '2017-03-08 02:11:01', 35),
(68, 'en', '', 'categories', '2017-03-08 02:11:07', '2017-03-08 02:11:07', 36),
(69, 'en', '', 'categories', '2017-03-08 02:11:14', '2017-03-08 02:11:14', 37),
(70, 'en', '', 'categories', '2017-03-08 02:11:19', '2017-03-08 02:11:19', 38),
(71, 'en', '', 'categories', '2017-03-08 02:11:27', '2017-03-08 02:11:27', 39),
(72, 'en', '', 'categories', '2017-03-08 02:11:33', '2017-03-08 02:11:33', 40),
(73, 'en', '', 'categories', '2017-03-08 02:12:19', '2017-03-08 02:12:19', 41),
(74, 'en', '', 'categories', '2017-03-08 02:12:33', '2017-03-08 02:12:33', 42),
(75, 'en', '', 'categories', '2017-03-08 02:12:41', '2017-03-08 02:12:41', 43),
(76, 'en', '', 'categories', '2017-03-08 02:12:48', '2017-03-08 02:12:48', 44),
(77, 'en', '', 'categories', '2017-03-08 02:13:00', '2017-03-08 02:13:00', 45),
(78, 'en', '', 'categories', '2017-03-08 02:13:10', '2017-03-08 02:13:10', 46),
(79, 'en', '', 'categories', '2017-03-08 02:13:19', '2017-03-08 02:13:19', 47),
(80, 'en', '', 'categories', '2017-03-08 02:13:27', '2017-03-08 02:13:27', 48),
(81, 'en', '', 'categories', '2017-03-08 02:13:36', '2017-03-08 02:13:36', 49),
(82, 'en', '', 'categories', '2017-03-08 02:13:46', '2017-03-08 02:13:46', 50),
(83, 'en', '', 'categories', '2017-03-08 02:13:53', '2017-03-08 02:13:53', 51),
(84, 'en', '', 'categories', '2017-03-08 02:14:01', '2017-03-08 02:14:01', 52),
(85, 'en', '', 'categories', '2017-03-08 02:14:15', '2017-03-08 02:14:15', 53),
(86, 'en', '', 'categories', '2017-03-08 02:14:22', '2017-03-08 02:14:22', 54),
(87, 'en', '', 'categories', '2017-03-08 02:14:30', '2017-03-08 02:14:30', 55),
(88, 'en', '', 'categories', '2017-03-08 02:14:37', '2017-03-08 02:14:37', 56),
(89, 'en', '', 'categories', '2017-03-08 02:14:44', '2017-03-08 02:14:44', 57),
(90, 'en', '', 'categories', '2017-03-08 02:18:14', '2017-03-08 02:18:14', 58),
(91, 'en', '', 'categories', '2017-03-08 02:18:27', '2017-03-08 02:18:27', 59),
(92, 'en', '', 'categories', '2017-03-08 02:18:38', '2017-03-08 02:18:38', 60),
(93, 'en', '', 'categories', '2017-03-08 02:18:47', '2017-03-08 02:18:47', 61),
(94, 'en', '', 'categories', '2017-03-08 02:18:54', '2017-03-08 02:18:54', 62),
(95, 'en', '', 'categories', '2017-03-08 02:26:18', '2017-03-08 02:26:18', 63),
(96, 'en', '', 'categories', '2017-03-08 02:26:53', '2017-03-08 02:26:53', 64),
(97, 'en', '', 'categories', '2017-03-08 02:27:04', '2017-03-08 02:27:04', 65),
(98, 'en', '', 'categories', '2017-03-08 02:27:13', '2017-03-08 02:27:13', 66),
(99, 'en', '', 'categories', '2017-03-08 02:27:30', '2017-03-08 02:27:30', 67),
(100, 'en', '', 'categories', '2017-03-08 02:27:42', '2017-03-08 02:27:42', 68),
(101, 'en', '', 'categories', '2017-03-10 00:37:13', '2017-03-10 00:37:13', 69),
(102, 'en', '', 'categories', '2017-03-10 00:37:30', '2017-03-10 00:37:30', 70),
(103, 'en', '', 'categories', '2017-03-10 00:37:43', '2017-03-10 00:37:43', 71),
(104, 'en', '', 'categories', '2017-03-10 00:38:00', '2017-03-10 00:38:00', 72),
(105, 'en', '', 'categories', '2017-03-10 00:38:15', '2017-03-10 00:38:15', 73),
(106, 'en', '', 'categories', '2017-03-10 00:38:27', '2017-03-10 00:38:27', 74),
(107, 'en', '', 'categories', '2017-03-10 00:38:38', '2017-03-10 00:38:38', 75),
(108, 'en', '', 'categories', '2017-03-10 00:38:52', '2017-03-10 00:38:52', 76),
(109, 'en', '', 'categories', '2017-03-10 00:39:17', '2017-03-10 00:39:17', 77),
(110, 'en', '', 'categories', '2017-03-10 00:39:38', '2017-03-10 00:39:38', 78),
(111, 'en', '', 'categories', '2017-03-13 06:10:37', '2017-03-13 06:10:37', 79),
(112, 'en', '', 'categories', '2017-03-13 06:12:56', '2017-03-13 06:12:56', 80),
(133, 'en', '', 'products', '2017-03-24 16:41:51', '2017-03-24 16:41:51', 1),
(161, 'en', '', 'brands', '2017-03-27 11:12:03', '2017-03-27 11:12:03', 28),
(162, 'en', '', 'attributes', '2017-03-28 12:17:40', '2017-03-28 12:17:40', 5),
(163, 'en', '', 'brands', '2017-03-28 13:01:07', '2017-03-28 13:01:07', 29),
(164, 'en', '', 'brands', '2017-03-28 13:01:32', '2017-03-28 13:01:32', 30),
(165, 'en', '', 'brands', '2017-03-28 13:01:55', '2017-03-28 13:01:55', 31),
(166, 'en', '', 'categories', '2017-03-28 13:03:43', '2017-03-28 13:03:43', 81),
(167, 'en', '', 'categories', '2017-03-28 13:04:31', '2017-03-28 13:04:31', 82),
(168, 'en', '', 'brands', '2017-03-28 13:14:41', '2017-03-28 13:14:41', 32),
(169, 'en', '', 'attributes', '2017-03-28 13:15:23', '2017-03-28 13:15:23', 6),
(170, 'en', '', 'attributes', '2017-03-28 13:15:44', '2017-03-28 13:15:44', 7),
(171, 'en', '', 'attributes', '2017-03-28 13:16:07', '2017-03-28 13:16:07', 8),
(172, 'en', '', 'attributes', '2017-03-28 13:20:19', '2017-03-28 13:20:19', 9),
(173, 'en', '', 'brands', '2017-03-29 13:51:01', '2017-03-29 13:51:01', 33),
(174, 'en', '', 'products', '2017-03-29 13:55:18', '2017-03-29 13:55:18', 2),
(175, 'en', '', 'products', '2017-03-30 15:06:13', '2017-03-30 15:06:13', 4),
(176, 'en', '', 'brands', '2017-03-31 11:47:34', '2017-03-31 11:47:34', 34),
(177, 'en', '', 'products', '2017-03-31 12:11:25', '2017-03-31 12:11:25', 5),
(178, 'en', '', 'orders', '2017-04-05 03:17:58', '2017-04-05 03:17:58', 1),
(179, 'en', '', 'orders', '2017-04-05 03:19:34', '2017-04-05 03:19:34', 2),
(180, 'en', '', 'orders', '2017-04-05 03:22:39', '2017-04-05 03:22:39', 3),
(181, 'en', '', 'categories', '2017-04-10 11:53:24', '2017-04-10 11:53:24', 83);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orders_id` int(11) NOT NULL,
  `order_tracking_number` varchar(250) NOT NULL,
  `docDate` date DEFAULT NULL,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 NOT NULL,
  `state` varchar(50) CHARACTER SET utf8 NOT NULL,
  `zip_code` varchar(250) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(50) NOT NULL,
  `card_id` int(11) NOT NULL,
  `card_number` int(19) NOT NULL,
  `card_cvv` int(11) NOT NULL,
  `card_expiration_m` varchar(2) NOT NULL,
  `card_expiration_y` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `country` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orders_id`, `order_tracking_number`, `docDate`, `email`, `first_name`, `last_name`, `address`, `city`, `state`, `zip_code`, `phone`, `card_id`, `card_number`, `card_cvv`, `card_expiration_m`, `card_expiration_y`, `created_at`, `updated_at`, `country`) VALUES
(1, '1491347878_ORDER_1', '2017-04-04', 'yruxyaseen@gmail.com', 'yasen', 'umer', 'test', 'karachi', 'sindh', '0000', '03122197381', 7, 1234567, 12349, '20', 7, '2017-04-04 23:21:17', '2017-04-05 03:17:58', '');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pifa_type`
--

CREATE TABLE IF NOT EXISTS `pifa_type` (
  `type_id` int(11) NOT NULL,
  `type_text` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sortOrder` int(11) DEFAULT NULL,
  `have_parent` bit(1) DEFAULT NULL COMMENT '0 if no parent , 1 if parent',
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pifa_type`
--

INSERT INTO `pifa_type` (`type_id`, `type_text`, `created_at`, `updated_at`, `sortOrder`, `have_parent`, `parent_id`) VALUES
(1, 'CAT_PERM', '2017-03-22 08:38:11', '0000-00-00 00:00:00', 1, b'1', 0),
(5, 'CATEGORY_70', '2017-03-22 11:45:11', '0000-00-00 00:00:00', 0, b'1', 0),
(6, 'CATEGORY_70', '2017-03-22 11:55:38', '0000-00-00 00:00:00', 0, b'1', 0),
(7, 'CATEGORY_1', '2017-03-24 09:45:33', '0000-00-00 00:00:00', 0, b'1', 0),
(12, 'CATEGORY_6', '2017-03-24 10:27:03', '0000-00-00 00:00:00', 0, b'1', 0),
(13, 'CATEGORY_11', '2017-03-24 10:50:25', '0000-00-00 00:00:00', 0, b'1', 0),
(17, 'CATEGORY_0', '2017-03-28 08:54:20', '0000-00-00 00:00:00', 0, b'1', 0),
(26, 'CATEGORY_80', '2017-03-28 09:02:53', '0000-00-00 00:00:00', 0, b'1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pifa_typeTR`
--

CREATE TABLE IF NOT EXISTS `pifa_typeTR` (
  `id` int(11) NOT NULL,
  `type_value` varchar(250) DEFAULT NULL,
  `refId` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sortOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pifa_typeTR`
--

INSERT INTO `pifa_typeTR` (`id`, `type_value`, `refId`, `created_at`, `updated_at`, `sortOrder`) VALUES
(1, 'hideAdditional', 1, '2017-03-22 08:51:51', '0000-00-00 00:00:00', 1),
(2, 'hideInstruction', 1, '2017-03-22 08:51:57', '0000-00-00 00:00:00', 2),
(3, 'hideMeasurements', 1, '2017-03-22 08:52:14', '0000-00-00 00:00:00', 3),
(4, 'hidePackageInfo', 1, '2017-03-22 08:52:21', '0000-00-00 00:00:00', 4),
(5, 'hideVariation', 1, '2017-03-22 08:52:26', '0000-00-00 00:00:00', 5),
(12, '2', 5, '2017-03-22 11:45:11', '0000-00-00 00:00:00', 1),
(13, '3', 5, '2017-03-22 11:45:11', '0000-00-00 00:00:00', 2),
(14, '5', 5, '2017-03-22 11:45:11', '0000-00-00 00:00:00', 3),
(15, '1', 6, '2017-03-22 11:55:38', '0000-00-00 00:00:00', 1),
(16, '3', 6, '2017-03-22 11:55:38', '0000-00-00 00:00:00', 2),
(17, '5', 6, '2017-03-22 11:55:38', '0000-00-00 00:00:00', 3),
(18, '2', 7, '2017-03-24 09:45:33', '0000-00-00 00:00:00', 1),
(19, '3', 7, '2017-03-24 09:45:33', '0000-00-00 00:00:00', 2),
(20, '4', 7, '2017-03-24 09:45:33', '0000-00-00 00:00:00', 3),
(21, '5', 7, '2017-03-24 09:45:33', '0000-00-00 00:00:00', 4),
(28, '1', 12, '2017-03-24 10:27:03', '0000-00-00 00:00:00', 1),
(29, '2', 12, '2017-03-24 10:27:03', '0000-00-00 00:00:00', 2),
(30, '3', 12, '2017-03-24 10:27:03', '0000-00-00 00:00:00', 3),
(31, '4', 12, '2017-03-24 10:27:03', '0000-00-00 00:00:00', 4),
(32, '2', 13, '2017-03-24 10:50:25', '0000-00-00 00:00:00', 1),
(33, '3', 13, '2017-03-24 10:50:25', '0000-00-00 00:00:00', 2),
(34, '4', 13, '2017-03-24 10:50:25', '0000-00-00 00:00:00', 3),
(35, '5', 13, '2017-03-24 10:50:25', '0000-00-00 00:00:00', 4),
(48, '5', 26, '2017-03-28 09:02:53', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `products_id` int(11) NOT NULL,
  `products_name` varchar(150) DEFAULT NULL,
  `short_description` mediumtext,
  `productDescription` mediumtext,
  `supplier_id` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `cat_id` varchar(750) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `discountPer` int(11) DEFAULT NULL,
  `discountAmout` int(11) DEFAULT NULL,
  `taxPer` int(11) DEFAULT NULL,
  `taxAmount` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` bit(1) DEFAULT NULL,
  `featured` bit(1) DEFAULT NULL,
  `tags` varchar(1500) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `brand_id` int(11) DEFAULT NULL,
  `mainColor` varchar(50) DEFAULT NULL,
  `map` float DEFAULT NULL,
  `profitP` float DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `productMeasurement` text,
  `productWeight` float DEFAULT NULL,
  `HTU` text,
  `pkg_height` float DEFAULT NULL,
  `pkg_width` float DEFAULT NULL,
  `pkg_length` float DEFAULT NULL,
  `pkg_weight` float DEFAULT NULL,
  `WINTB` text,
  `HWC` bit(1) DEFAULT NULL,
  `videoLink` text,
  `SKU` varchar(100) DEFAULT NULL,
  `manageStock` bit(1) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `buy_limit` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`products_id`, `products_name`, `short_description`, `productDescription`, `supplier_id`, `createdBy`, `cat_id`, `price`, `discountPer`, `discountAmout`, `taxPer`, `taxAmount`, `quantity`, `status`, `featured`, `tags`, `updated_at`, `created_at`, `brand_id`, `mainColor`, `map`, `profitP`, `cost`, `productMeasurement`, `productWeight`, `HTU`, `pkg_height`, `pkg_width`, `pkg_length`, `pkg_weight`, `WINTB`, `HWC`, `videoLink`, `SKU`, `manageStock`, `model`, `buy_limit`) VALUES
(1, 'Herbal Product', 'SHORT', '<p>LONG</p>\r\n', 1, NULL, '6', 500, NULL, NULL, NULL, NULL, 50, NULL, NULL, NULL, '2017-03-29 09:27:17', '2017-03-24 16:41:51', 1, 'black', NULL, NULL, 500, '', 0, 'MAKE THIS PRODUCT AS USEFULL AS YOU CAN ', 0, 0, 0, 0, '', b'1', 'https://www.youtube.com/embed/XGSy3_Czz8k"', 'SKU-441', b'1', 'MODEL', 5),
(2, 'EQT SUPPORT ADV SHOES', 'Great every day shoe. Is a bit tight on the ankles. Not as light as a primeknit, but better than most. I''m flatfoot but can still walk in these all day. The lining is too thin for winter wear.', '<h2>EQT SUPPORT ADV SHOES</h2>\r\n\r\n<h4>EQT SHOES REMIX PERFORMANCE ELEMENTS INTO STREET STYLE.</h4>\r\n\r\n<p>With roots in the original &#39;90s Equipment running shoes, the new EQT Support ADV series re-imagines the runner-inspired design for today&#39;s urban trendsetter. These shoes feature a two-tone knit upper with bold contrasting colors. A synthetic nubuck overlay at the toe adds texture, while the reflective quarter panel gives them pops of color. Iconic 3-Stripes extend into the cushioned EVA midsole for a true heritage look.</p>\r\n\r\n<ul>\r\n	<li>Signature two-tone knit upper with welded premium synthetic nubuck toe overlay and reflective quarter panel</li>\r\n	<li>Sock-like construction; Breathable mesh lining</li>\r\n	<li>Soft molded TPU support piece on heel; Welded stretchy TPU details on quarter</li>\r\n	<li>Compression molded EVA midsole with integrated 3-Stripes</li>\r\n	<li>Enjoy the comfort and performance of OrthoLite&reg; sockliner</li>\r\n	<li>Rubber outsole</li>\r\n	<li>Imported</li>\r\n</ul>\r\n', 1, NULL, '58', 110, 15, NULL, NULL, NULL, 50, NULL, NULL, NULL, '2017-03-29 10:01:46', '2017-03-29 13:55:18', 33, 'Black', NULL, NULL, 110, '', 0, '', 50, 50, 50, 2, '2x shoes (right,left) ', b'1', 'https://www.youtube.com/embed/mv2wuOxChW0', 'ADD-SKU-125', b'1', 'WHITE SHOE ', 5),
(4, 'SanDisk micro SD Memory Card for Fire Tablets and All-New Fire TV', 'SanDisk  micro SD Memory Card for Fire Tablets and All-New Fire TV', '<p>Would you like to&nbsp;<strong><a href="https://www.amazon.com/SanDisk-micro-Memory-Tablets-All-New/dp/B013TMNKAW/ref=zg_bs_370783011_1?_encoding=UTF8&amp;refRID=QHA0B2BMECK0VT81ZMJ0&amp;th=1#" id="ns_VBHE1RX4JG08YJAZX6E8_24960_1_hmd_pricing_feedback_trigger_product-detail">tell us about a lower price</a></strong>?<br />\r\nIf you are a seller for this product, would you like to&nbsp;<strong><a href="https://sellercentral.amazon.com/cu/contact-us?categoryId=30002&amp;typeId=30005">suggest updates through seller support</a></strong>?</p>\r\n\r\n<ul>\r\n	<li><strong>Product Dimensions:&nbsp;</strong>0.1 x 0.9 x 1.3 inches ; 0.2 ounces</li>\r\n	<li><strong>Shipping Weight:</strong>&nbsp;2.1 ounces (<a href="https://www.amazon.com/gp/help/seller/shipping.html/ref=dp_pd_shipping?ie=UTF8&amp;asin=B013TMNKAW&amp;seller=ATVPDKIKX0DER">View shipping rates and policies</a>)</li>\r\n	<li><strong>Domestic Shipping:&nbsp;</strong>Item can be shipped within U.S.</li>\r\n	<li><strong>International Shipping:&nbsp;</strong>This item is not eligible for international shipping.&nbsp;<a href="https://www.amazon.com/gp/help/customer/display.html?ie=UTF8&amp;nodeId=201117930&amp;pop-up=1" onclick="return amz_js_PopWin(''/gp/help/customer/display.html?ie=UTF8&amp;nodeId=201117930&amp;pop-up=1'',''InternationalShippingDetails'',''width=550,height=550,resizable=1,scrollbars=1,toolbar=0,status=0'')" target="InternationalShippingDetails">Learn More</a></li>\r\n	<li><strong>ASIN:</strong>&nbsp;B013TMNKAW</li>\r\n	<li><strong>Item model number:</strong>&nbsp;SDSQUNB-032G-AZFMN</li>\r\n	<li><strong>Average Customer Review:</strong>&nbsp;<a href="https://www.amazon.com/product-reviews/B013TMNKAW/ref=acr_dpproductdetail_text?ie=UTF8&amp;reviewerType=avp_only_reviews&amp;showViewpoints=1"><em>4.5 out of 5 stars</em>&nbsp;</a>&nbsp;&nbsp;<a href="https://www.amazon.com/product-reviews/B013TMNKAW/ref=acr_dpproductdetail_text?ie=UTF8&amp;reviewerType=avp_only_reviews&amp;showViewpoints=1">6,822 customer reviews</a></li>\r\n	<li><strong>Amazon Best Sellers Rank:</strong>\r\n	<ul>\r\n		<li>#1&nbsp;in&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/ref=pd_zg_hrsr_kstore_1_1">Kindle Store</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/370783011/ref=pd_zg_hrsr_kstore_1_2">Amazon Device Accessories</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/12007552011/ref=pd_zg_hrsr_kstore_1_3">Fire Tablet Accessories</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/15087019011/ref=pd_zg_hrsr_kstore_1_4">Fire HD 8 Accessories (6th Generation)</a>&nbsp;&gt;&nbsp;<strong><a href="https://www.amazon.com/gp/bestsellers/digital-text/10871379011/ref=pd_zg_hrsr_kstore_1_5_last">Memory Cards</a></strong></li>\r\n		<li>#1&nbsp;in&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/ref=pd_zg_hrsr_kstore_2_1">Kindle Store</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/370783011/ref=pd_zg_hrsr_kstore_2_2">Amazon Device Accessories</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/12007552011/ref=pd_zg_hrsr_kstore_2_3">Fire Tablet Accessories</a>&nbsp;&gt;&nbsp;<strong><a href="https://www.amazon.com/gp/bestsellers/digital-text/12516221011/ref=pd_zg_hrsr_kstore_2_4_last">Fire HD 10 (5th Generation) Accessories</a></strong></li>\r\n		<li>#1&nbsp;in&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/ref=pd_zg_hrsr_kstore_3_1">Kindle Store</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/370783011/ref=pd_zg_hrsr_kstore_3_2">Amazon Device Accessories</a>&nbsp;&gt;&nbsp;<a href="https://www.amazon.com/gp/bestsellers/digital-text/12007552011/ref=pd_zg_hrsr_kstore_3_3">Fire Tablet Accessories</a>&nbsp;&gt;&nbsp;<strong><a href="https://www.amazon.com/gp/bestsellers/digital-text/12516220011/ref=pd_zg_hrsr_kstore_3_4_last">Fire HD 8 (5th Generation) Accessories</a></strong></li>\r\n	</ul>\r\n	</li>\r\n	<li><strong>Date first available at Amazon.com:</strong>&nbsp;September 16, 2015</li>\r\n</ul>\r\n', 1, NULL, '2', 64, NULL, NULL, NULL, NULL, 50, NULL, NULL, NULL, '2017-03-30 15:06:13', '2017-03-30 15:06:13', 3, 'Color', NULL, NULL, 63.34, '12', 12, 'About the product\r\nExclusive “Made for Amazon” SD memory card - the only one tested and certified to work with your Fire Tablet and Fire TV\r\nLoad your Fire Tablet with more fun - by adding space for additional photos, music and movies\r\nDownload your apps and games directly to the SD card\r\nClass 10 performance for Full HD (1080p) video recording and playback\r\nDesigned to perform multiple simultaneous activities with no lag or delay\r\nRead speeds up to 48MB/s**', 5, 5, 5, 5, 'NOTHING', b'1', '', 'SKU-4412', b'1', 'SanDisk', 5),
(5, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2>\r\n\r\n<ul>\r\n	<li>AMD Eyefinity multi-display technology</li>\r\n	<li>4x Display Port 1.2</li>\r\n	<li>256-bit memory interface</li>\r\n	<li>160 GB/s memory bandwidth</li>\r\n	<li>1,280 stream processors</li>\r\n	<li>40962160 Pixel Display Port Resolution</li>\r\n	<li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li>\r\n	<li>19201200 Pixel Single Link DVI Resolution</li>\r\n	<li>10% Advance required to confirm the order.</li>\r\n</ul>\r\n', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(11, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2>\r\n\r\n<ul>\r\n	<li>AMD Eyefinity multi-display technology</li>\r\n	<li>4x Display Port 1.2</li>\r\n	<li>256-bit memory interface</li>\r\n	<li>160 GB/s memory bandwidth</li>\r\n	<li>1,280 stream processors</li>\r\n	<li>40962160 Pixel Display Port Resolution</li>\r\n	<li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li>\r\n	<li>19201200 Pixel Single Link DVI Resolution</li>\r\n	<li>10% Advance required to confirm the order.</li>\r\n</ul>\r\n', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(12, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(13, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(14, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(15, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-03-31 12:11:25', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(16, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-04-11 08:27:10', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10),
(17, 'POLO T-shirt', 'Just a t-shirt', '<h2>Sapphire AMD FirePro V7900 2GB GDDR5 Quad DP PCI-Express Graphics Card Graphics Cards 100-505861</h2> <ul> <li>AMD Eyefinity multi-display technology</li> <li>4x Display Port 1.2</li> <li>256-bit memory interface</li> <li>160 GB/s memory bandwidth</li> <li>1,280 stream processors</li> <li>40962160 Pixel Display Port Resolution</li> <li>25601600 Pixel Dual Link DVI Resolution19201200 Pixel Single Link DVI Resolution</li> <li>19201200 Pixel Single Link DVI Resolution</li> <li>10% Advance required to confirm the order.</li> </ul> ', 1, NULL, '66', 12, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, '2017-04-11 08:27:12', '2017-03-31 12:11:25', 34, 'black', NULL, NULL, 12, '', 0, 'JUST WEAR IT AND DON''t Forget to remove the "TAG"', 12, 12, 12, 12, 'A POLO TSHIRT ', b'1', 'https://www.youtube.com/embed/CV_NTwCRwmM', 'POLO-BL-SKU', b'1', 'POLO', 10);

-- --------------------------------------------------------

--
-- Table structure for table `product_childs`
--

CREATE TABLE IF NOT EXISTS `product_childs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `price` float DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `custom_name` text NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 for variable products',
  `SKU` text,
  `cost` float DEFAULT NULL,
  `saleDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `manageStock` bit(1) DEFAULT NULL,
  `variationSelected` text
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_childs`
--

INSERT INTO `product_childs` (`id`, `product_id`, `updated_at`, `created_at`, `price`, `qty`, `custom_name`, `type`, `SKU`, `cost`, `saleDate`, `endDate`, `manageStock`, `variationSelected`) VALUES
(4, 3, '2017-03-30 14:48:36', '2017-03-30 14:48:36', 123, NULL, 'RED ', 1, 'SKU', 123, '0000-00-00', '0000-00-00', b'1', NULL),
(5, 3, '2017-03-30 14:48:37', '2017-03-30 14:48:37', 123, NULL, ' BLUE ', 1, 'SKU', 123, '0000-00-00', '0000-00-00', b'1', NULL),
(6, 3, '2017-03-30 14:48:37', '2017-03-30 14:48:37', 123, NULL, ' BLACK', 1, 'SKU', 123, '0000-00-00', '0000-00-00', b'1', NULL),
(7, 4, '2017-03-30 15:06:13', '2017-03-30 15:06:13', 500, NULL, '32 GB', 1, 'S-32-G', 500, '0000-00-00', '0000-00-00', b'1', NULL),
(8, 5, '2017-04-05 02:32:16', '2017-03-31 12:11:25', 15.11, 10, 'RED ,SMALL ', 2, 'SKU-RS-POLO', 15.11, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(9, 5, '2017-04-05 02:32:19', '2017-03-31 12:11:25', 15.21, 10, ' BLUE ,SMALL ', 2, 'SKU-BS-POLO', 15.21, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(10, 5, '2017-04-05 02:32:22', '2017-03-31 12:11:25', 15.31, 10, ' BLACK,SMALL ', 2, 'SKU-BLS-POLO', 15.31, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(11, 5, '2017-04-05 02:32:24', '2017-03-31 12:11:25', 15.41, 10, 'RED , LARGE ', 2, 'SKU-RL-POLO', 15.41, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(12, 5, '2017-04-05 02:32:26', '2017-03-31 12:11:25', 15.51, 10, ' BLUE , LARGE ', 2, 'SKU-BL-POLO', 15.51, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(13, 5, '2017-04-05 02:32:09', '2017-03-31 12:11:25', 15.61, 10, ' BLACK, LARGE ', 2, 'SKU-BLL-POLO', 15.61, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(14, 5, '2017-04-05 02:32:07', '2017-03-31 12:11:25', 15.71, 10, 'RED , EXTRA SMALL', 2, 'SKU-RES-POLO', 15.71, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(15, 5, '2017-04-05 02:32:05', '2017-03-31 12:11:25', 15.81, 10, ' BLUE , EXTRA SMALL', 2, 'SKU-BES-POLO', 15.81, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE '),
(16, 5, '2017-04-05 02:32:03', '2017-03-31 12:11:25', 15.91, 10, ' BLACK, EXTRA SMALL', 2, 'SKU-BLES-POLO', 15.91, '0000-00-00', '0000-00-00', b'1', 'COLOR,SIZE ');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` mediumtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `created_at`, `updated_at`, `message`) VALUES
(18, '2017-02-21 05:07:36', '2017-02-21 05:07:36', 'chinese entry'),
(19, '2017-02-21 05:07:53', '2017-02-21 05:07:53', 'English entry'),
(20, '2017-02-21 05:08:05', '2017-02-21 05:08:05', 'chinese again'),
(21, '2017-02-21 05:08:16', '2017-02-21 05:08:16', 'English Again'),
(22, '2017-02-21 05:12:32', '2017-02-21 05:12:32', 'tesst Chinese Last'),
(23, '2017-02-21 06:23:42', '2017-02-21 06:23:42', 'test'),
(24, '2017-02-21 06:24:32', '2017-02-21 06:24:32', 'te'),
(25, '2017-02-21 06:25:13', '2017-02-21 06:25:13', 'english last'),
(26, '2017-02-21 06:25:22', '2017-02-21 06:25:22', 'english last 1'),
(27, '2017-02-21 07:56:45', '2017-02-21 07:56:45', 'chinese entry finale '),
(28, '2017-02-21 07:56:56', '2017-02-21 07:56:56', 'english entry finale '),
(29, '2017-02-22 01:26:12', '2017-02-22 01:26:12', 'Pifa??????????????'),
(30, '2017-02-22 01:28:21', '2017-02-22 01:28:21', ''),
(31, '2017-02-22 01:42:43', '2017-02-22 01:42:43', '\nPifa商店即将上线'),
(32, '2017-02-22 01:42:58', '2017-02-22 01:42:58', '\nPifa商店即将上线'),
(33, '2017-02-22 03:32:40', '2017-02-22 03:32:40', '信息'),
(34, '2017-02-22 07:37:05', '2017-02-22 07:37:05', 'te'),
(35, '2017-02-23 00:52:31', '2017-02-23 00:52:31', 'Pifa商店即将上线'),
(36, '2017-03-07 07:48:59', '0000-00-00 00:00:00', 'test'),
(37, '2017-03-07 07:49:38', '0000-00-00 00:00:00', 'test'),
(38, '2017-03-07 07:51:33', '0000-00-00 00:00:00', 'testsss'),
(39, '2017-03-07 07:51:47', '0000-00-00 00:00:00', 'chinese entry');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'yaseen', 'supplier@pifabiz.com', '$2y$10$l7qHd9YEXS9jnvICECE9desI8xYjaAPWKJqNJ0QPAszW89M/nZtgm', 'Kukm1VHMld6p67iAkFnAfcnXtMfnkvgSljDvtycYe9e87wHZ0NpWn8ocHozo', '2017-02-20 08:16:25', '2017-03-31 14:25:58', 2),
(2, 'admin', 'admin@pifabiz.com', '$2y$10$l7qHd9YEXS9jnvICECE9desI8xYjaAPWKJqNJ0QPAszW89M/nZtgm', 'CxQok5P5W9dITZW0i2RitEbcgUlZHxf3bdLUg8SnGeD4U9HHFt4QUiAAjppL', '2017-02-20 08:16:56', '2017-03-28 13:05:28', 1),
(5, 'Valeed', 'valeedmahmood@gmail.com', '$2y$10$XE4CN5jgznIcmhM5uYLajuyh9c8EUUkcvUpt58sxJQA385Xlzljb6', '2igwgV8pJgWMO28bugt53A2ZoNKVVmUG7itfU20Lw6JnLl9IG8tWpHdo0Q0u', NULL, '2017-03-28 12:18:27', 2),
(6, 'yaseen', 'yaseen@gmail.com', '$2y$10$hPwjLJ7FEtwXt9d7Y13taOqLfhliP0HeJyfBEYi6gpcIJQHL/NkGO', 'lFnnRBaP7HbiL2wMZQEEgkN5kZ31GTwtlOPa8IBiDFis54hB9Ps487tJ6qVv', NULL, '2017-03-31 15:35:59', 2),
(7, 'Pifa User', 'pifauser@gmail.com', '$2y$10$6uXEywytwc6pvrxiICtJcuTYGxMrqKO336RACQUawJ9Won.DqyI5u', 'MjToDVqiNl9ppuLE3fJSIEjWCxah0dL0DRkCdHdLKe0tvvcuEqePX9oTnkdF', NULL, '2017-03-31 15:57:20', 3),
(8, 'PIFAUSER', 'pifauser@pifabiz.com', '$2y$10$sft65r/SB0iWxpn9xs1fLelMinfp2v7C1n0fyDEx/BJsbp/iPZKOC', 'jGTG4NdaEjW9RyrK2cK3rHu5OjtdFvPH1ln2ziHhJK34ja9QMJ8eFOs4vJ3v', NULL, '2017-04-04 23:08:41', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_detail`
--

CREATE TABLE IF NOT EXISTS `user_detail` (
  `id` int(11) NOT NULL,
  `refId` int(11) NOT NULL,
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `address` text,
  `buisness_address` text
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_detail`
--

INSERT INTO `user_detail` (`id`, `refId`, `first_name`, `last_name`, `phone`, `address`, `buisness_address`) VALUES
(3, 5, 'Valeed', 'Mehmood', '03122197381', 'GHER PR ', 'GHER PR ');

-- --------------------------------------------------------

--
-- Stand-in structure for view `V_SearchSlug`
--
CREATE TABLE IF NOT EXISTS `V_SearchSlug` (
`combine_id` varchar(22)
,`lower_name` varchar(255)
,`slugged_name` varchar(255)
,`id` int(11)
,`rowName` varchar(255)
,`rowTable` varchar(10)
);

-- --------------------------------------------------------

--
-- Structure for view `V_SearchSlug`
--
DROP TABLE IF EXISTS `V_SearchSlug`;

CREATE ALGORITHM=UNDEFINED DEFINER=`pifaadmin_storeU`@`45.116.%` SQL SECURITY DEFINER VIEW `V_SearchSlug` AS select concat(`brands`.`brand_id`,'_brands') AS `combine_id`,lcase((`brands`.`brand_name` collate utf8_general_ci)) AS `lower_name`,lcase(replace((`brands`.`brand_name` collate utf8_general_ci),' ','-')) AS `slugged_name`,`brands`.`brand_id` AS `id`,(`brands`.`brand_name` collate utf8_general_ci) AS `rowName`,'brands' AS `rowTable` from `brands` union all select concat(`categories`.`cat_id`,'_categories') AS `combine_id`,lcase((`categories`.`cat_name` collate utf8_general_ci)) AS `lower_name`,lcase(replace((`categories`.`cat_name` collate utf8_general_ci),' ','-')) AS `slugged_name`,`categories`.`cat_id` AS `id`,(`categories`.`cat_name` collate utf8_general_ci) AS `rowName`,'categories' AS `rowTable` from `categories` union all select concat(`products`.`products_id`,'_products') AS `combine_id`,lcase((`products`.`products_name` collate utf8_general_ci)) AS `lower_name`,lcase(replace((`products`.`products_name` collate utf8_general_ci),' ','-')) AS `slugged_name`,`products`.`products_id` AS `id`,(`products`.`products_name` collate utf8_general_ci) AS `rowName`,'products' AS `rowTable` from `products` union all select concat(`users`.`id`,'_users') AS `combine_id`,lcase((`users`.`name` collate utf8_general_ci)) AS `lower_name`,lcase(replace((`users`.`name` collate utf8_general_ci),' ','-')) AS `slugged_name`,`users`.`id` AS `id`,(`users`.`name` collate utf8_general_ci) AS `rowName`,'users' AS `rowTable` from `users` where (`users`.`role` = 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `imagetable`
--
ALTER TABLE `imagetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languagetable`
--
ALTER TABLE `languagetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`),
  ADD UNIQUE KEY `order_tracking_unique` (`order_tracking_number`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `pifa_type`
--
ALTER TABLE `pifa_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `pifa_typeTR`
--
ALTER TABLE `pifa_typeTR`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`products_id`);

--
-- Indexes for table `product_childs`
--
ALTER TABLE `product_childs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `imagetable`
--
ALTER TABLE `imagetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `languagetable`
--
ALTER TABLE `languagetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=182;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pifa_type`
--
ALTER TABLE `pifa_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `pifa_typeTR`
--
ALTER TABLE `pifa_typeTR`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `product_childs`
--
ALTER TABLE `product_childs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_detail`
--
ALTER TABLE `user_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
