-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 07, 2019 at 10:59 PM
-- Server version: 5.6.24
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reviews`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `_lft` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `_rgt` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `description`, `_lft`, `_rgt`, `parent_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'arts-and-collectibles', '{\"en\":\"Arts and Collectibles\"}', NULL, 41, 42, NULL, '2019-06-26 19:14:41', '2019-06-26 19:14:41', NULL),
(2, 'beauty-and-fragrances', '{\"en\":\"Beauty and fragrances\"}', NULL, 1, 2, NULL, '2019-06-18 13:46:08', '2019-06-26 19:11:53', NULL),
(3, 'books-and-magazines', '{\"en\":\"Books and magazines\"}', NULL, 3, 4, NULL, '2019-06-18 13:46:08', '2019-06-18 13:46:08', NULL),
(4, 'b2b', '{\"en\":\"B2B\"}', NULL, 5, 6, NULL, '2019-06-18 13:46:08', '2019-06-18 13:46:08', NULL),
(5, 'clothing-accessories-and-shoes', '{\"en\":\"Clothing, Accessories and shoes\"}', NULL, 7, 8, NULL, '2019-06-18 13:46:08', '2019-06-18 13:46:08', NULL),
(6, 'computers-accessories-and-services', '{\"en\":\"Computers, accessories, and services.\"}', NULL, 9, 10, NULL, '2019-06-18 13:46:08', '2019-06-18 13:46:08', NULL),
(7, 'education', '{\"en\":\"Education\"}', NULL, 11, 12, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(8, 'electronics-and-telecom', '{\"en\":\"Electronics and telecom\\n\"}', NULL, 13, 14, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(9, 'entertainment-and-media', '{\"en\":\"Entertainment and media\\n\"}', NULL, 15, 16, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(10, 'financial-services-and-products', '{\"en\":\"Financial services and products\"}', NULL, 17, 18, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(11, 'food-retail-and-service', '{\"en\":\"Food retail and service\"}', NULL, 19, 20, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(12, 'gifts-and-flowers', '{\"en\":\"Gifts and flowers\"}', NULL, 21, 22, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(13, 'health-and-personal-care', '{\"en\":\"Health and personal care\"}', NULL, 23, 24, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(14, 'home-and-garden', '{\"en\":\"\\tHome and garden\"}', NULL, 25, 26, NULL, '2019-06-18 13:47:42', '2019-06-18 13:47:42', NULL),
(15, 'nonprofit', '{\"en\":\"Nonprofit\"}', NULL, 27, 28, NULL, '2019-06-18 13:49:17', '2019-06-18 13:49:17', NULL),
(16, 'pets-and-animals', '{\"en\":\"Pets and animals\"}', NULL, 29, 30, NULL, '2019-06-18 13:49:17', '2019-06-18 13:49:17', NULL),
(17, 'others', '{\"en\":\"Others\"}', NULL, 31, 32, NULL, '2019-06-18 13:49:17', '2019-06-18 13:49:17', NULL),
(18, 'sports-and-outdoors', '{\"en\":\"Sports and outdoors\"}', NULL, 33, 34, NULL, '2019-06-18 13:49:17', '2019-06-18 13:49:17', NULL),
(19, 'toys-and-hobbies', '{\"en\":\"Toys and hobbies\"}', NULL, 35, 36, NULL, '2019-06-18 13:49:18', '2019-06-18 13:49:18', NULL),
(20, 'travel', '{\"en\":\"Travel\"}', NULL, 37, 38, NULL, '2019-06-18 13:49:18', '2019-06-18 13:49:18', NULL),
(21, 'cars', '{\"en\":\"Cars\"}', NULL, 39, 40, NULL, '2019-06-18 13:49:18', '2019-06-18 13:49:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorizables`
--

CREATE TABLE `categorizables` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `categorizable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorizable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorizables`
--

INSERT INTO `categorizables` (`category_id`, `categorizable_type`, `categorizable_id`, `created_at`, `updated_at`) VALUES
(6, 'App\\Sites', 4, '2019-08-27 05:59:06', '2019-08-27 05:59:06'),
(6, 'App\\Sites', 44, '2019-06-28 12:20:30', '2019-06-28 12:20:30'),
(6, 'App\\Sites', 45, '2019-06-28 12:21:16', '2019-06-28 12:21:16'),
(6, 'App\\Sites', 46, '2019-06-28 12:36:57', '2019-06-28 12:36:57'),
(6, 'App\\Sites', 47, '2019-09-03 11:05:06', '2019-09-03 11:05:06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_12_17_114551_create_categories_table', 1),
(4, '2016_12_17_119816_create_categorizables_table', 1),
(5, '2019_01_01_000000-create_options_table', 1),
(6, '2019_03_02_131608_create_sites_table', 2),
(7, '2019_03_04_152056_create_reviews_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `options_table`
--

CREATE TABLE `options_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_value` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options_table`
--

INSERT INTO `options_table` (`id`, `option_name`, `option_value`) VALUES
(1, 'adminEmail', 'admin@phptrustedreviews.com'),
(2, 'monthlyPrice', '9'),
(3, '6monthsPrice', '49'),
(4, 'yearlyPrice', '99'),
(5, 'currency_symbol', '$'),
(6, 'currency_code', 'USD'),
(7, 'paypalEnable', 'Yes'),
(8, 'stripeEnable', 'Yes'),
(9, 'PAYPAL_CLIENT_ID', ''),
(10, 'PAYPAL_CLIENT_SECRET', ''),
(11, 'STRIPE_PUBLISHABLE_KEY', ''),
(12, 'STRIPE_SECRET_KEY', ''),
(13, 'seo_title', 'PHP Trusted Reviews'),
(14, 'seo_desc', 'PHP Trusted Reviews description meta tag'),
(15, 'seo_keys', 'php trusted reviews, reviews'),
(16, 'extra_js', NULL),
(17, 'site_title', 'PHP Trusted Reviews'),
(18, 'site_description', '#1 Community Trusted Reviews'),
(19, 'site_belowDescription', 'From People Like You'),
(20, 'paypal_email', 'you@paypal.me'),
(21, 'mapsApiKey', ''),
(22, 'sideAd', 'test sidebar ads'),
(23, 'homeAd', NULL),
(24, 'catAd', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_title`, `page_slug`, `page_content`, `created_at`, `updated_at`) VALUES
(1, 'Terms of Service', 'tos', '<p>Phasellus blandit leo ut odio. Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Fusce a quam. Donec posuere vulputate arcu. Nullam tincidunt adipiscing enim.<br><br>Sed augue ipsum, egestas nec, vestibulum et, malesuada adipiscing, dui. Fusce risus nisl, viverra et, tempor et, pretium in, sapien. Maecenas vestibulum mollis diam. Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Quisque malesuada placerat nisl.<br></p>', '2016-08-21 16:03:03', '2019-06-28 14:33:27'),
(3, 'Privacy Policy', 'privacy-policy', '<p>Aliquam eu nunc. Nullam vel sem. Curabitur at lacus ac velit ornare lobortis. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus.<br><br>Sed hendrerit. Proin faucibus arcu quis ante. Cras id dui. Sed fringilla mauris sit amet nibh.<br></p>', '2016-08-28 05:46:04', '2016-08-28 05:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `review_item_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `review_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_reply` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `publish` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claimedBy` int(11) DEFAULT NULL,
  `submittedBy` int(11) DEFAULT NULL,
  `lati` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci,
  `publish` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `plan` enum('monthly','6months','yearly') NOT NULL,
  `site_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `subscription_id` varchar(255) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `subscription_date` int(10) UNSIGNED NOT NULL,
  `subscription_status` enum('Active','Canceled') NOT NULL,
  `subscription_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE `verify` (
  `id` int(10) UNSIGNED NOT NULL,
  `plan` enum('monthly','6months','yearly') NOT NULL,
  `site_id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `verified` enum('No','Yes') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`);

--
-- Indexes for table `categorizables`
--
ALTER TABLE `categorizables`
  ADD UNIQUE KEY `categorizables_ids_type_unique` (`category_id`,`categorizable_id`,`categorizable_type`),
  ADD KEY `categorizables_categorizable_type_categorizable_id_index` (`categorizable_type`,`categorizable_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options_table`
--
ALTER TABLE `options_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `options_table_option_name_unique` (`option_name`),
  ADD KEY `options_table_option_name_index` (`option_name`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_review_item_id_index` (`review_item_id`),
  ADD KEY `reviews_review_item_id_rating_index` (`review_item_id`,`rating`),
  ADD KEY `reviews_review_item_id_user_id_index` (`review_item_id`,`user_id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sites_url_unique` (`url`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verify`
--
ALTER TABLE `verify`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `options_table`
--
ALTER TABLE `options_table`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verify`
--
ALTER TABLE `verify`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorizables`
--
ALTER TABLE `categorizables`
  ADD CONSTRAINT `categorizables_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
