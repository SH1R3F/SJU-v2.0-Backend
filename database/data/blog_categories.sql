-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 01, 2022 at 01:35 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sju`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `title_ar`, `title_en`, `slug`, `description_ar`, `description_en`, `order`, `active`, `created_at`, `updated_at`) VALUES
(0, 'عام', 'All', 'All', '', '', 1, 1, NULL, NULL),
(1, 'حفر الباطن', 'Hafar Al-Batin', 'Hafar-Al-Batin', '', '', 1, 1, NULL, NULL),
(2, 'الأحساء', 'Hasa', 'Hasa', '', '', 1, 1, NULL, NULL),
(3, 'حائل', 'Hail', 'Hail', '', '', 1, 1, NULL, NULL),
(4, 'الجبيل', 'Jubail', 'Jubail', '', '', 1, 1, NULL, NULL),
(5, 'مكة المكرمة', 'Mecca', 'Mecca', '', '', 1, 1, NULL, NULL),
(6, 'المدينة المنورة', 'Medina', 'Medina', '', '', 1, 1, NULL, NULL),
(7, 'الدمام', 'Dammam', 'Dammam', '', '', 1, 1, NULL, NULL),
(8, 'النادي السعودي للصحافة', 'Saudi Press Club', 'Saudi-Press-Club', '', '', 0, 0, NULL, NULL),
(9, 'نادي جدة للصحافة', 'Jeddah Press Club', 'Jeddah-Press-Club', '', '', 0, 0, NULL, NULL),
(10, 'الرياض', 'Riyadh', 'Riyadh', '', '', 1, 1, NULL, NULL),
(11, 'القصيم', 'Qusaim', 'Qusaim', '', '', 1, 1, NULL, NULL),
(12, 'الباحة', 'Baha', 'Baha', '', '', 1, 1, NULL, NULL),
(13, 'الجوف', 'Jawf', 'Jawf', '', '', 1, 1, NULL, NULL),
(14, 'الحدود الشمالية', 'shmaleya', 'shmaleya', '', '', 1, 1, NULL, NULL),
(15, 'عسير', 'asir', 'asir', '', '', 1, 1, NULL, NULL),
(16, 'جازان', 'Jazan', 'Jazan', '', '', 1, 1, NULL, NULL),
(17, 'نجران', 'Najran', 'Najran', '', '', 1, 1, NULL, NULL),
(18, 'تبوك', 'tabouk', 'tabouk', '', '', 1, 1, NULL, NULL),
(19, 'الطائف', 'taef', 'taef', '', '', 1, 1, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
