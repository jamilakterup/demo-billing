-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2022 at 01:01 PM
-- Server version: 10.3.23-MariaDB-cll-lve
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billingsys_rajbilling`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `ref_number` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `estimate_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `vat` decimal(11,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sent','accepted','rejected','converted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_counters`
--

CREATE TABLE `estimate_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL DEFAULT 1000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimate_counters`
--

INSERT INTO `estimate_counters` (`id`, `number`, `created_at`, `updated_at`) VALUES
(1, 1007, NULL, '2021-08-30 03:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `estimate_details`
--

CREATE TABLE `estimate_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` int(11) NOT NULL DEFAULT 0,
  `number` int(11) NOT NULL,
  `ref_number` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `vat` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `is_recurring` tinyint(4) NOT NULL DEFAULT 0,
  `recurring_interval` int(6) DEFAULT 0,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_counters`
--

CREATE TABLE `invoice_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL DEFAULT 1000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_counters`
--

INSERT INTO `invoice_counters` (`id`, `number`, `created_at`, `updated_at`) VALUES
(1, 1023, NULL, '2021-09-30 15:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2014_10_12_000000_create_users_table', 1),
(6, '2014_10_12_100000_create_password_resets_table', 1),
(7, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2021_07_27_063542_create_permission_tables', 1),
(11, '2021_07_27_110956_create_sessions_table', 1),
(14, '2021_08_04_040301_create_customers_table', 2),
(15, '2021_08_05_123546_create_products_table', 3),
(16, '2021_08_06_194058_create_product_types_table', 4),
(17, '2021_08_09_102553_create_units_table', 5),
(18, '2021_08_09_215916_create_organizations_table', 6),
(19, '2021_08_14_001501_create_invoices_table', 7),
(20, '2021_08_14_003919_create_invoice_details_table', 8),
(21, '2021_08_14_115719_create_invoice_counters_table', 9),
(22, '2021_08_16_060539_create_payments_table', 10),
(23, '2021_08_16_060931_create_payment_types_table', 11),
(31, '2021_08_17_134145_create_estimates_table', 12),
(32, '2021_08_19_143142_create_estimate_details_table', 12),
(33, '2021_08_19_143217_create_estimate_counters_table', 12),
(34, '2021_08_19_170748_create_statuses_table', 13),
(35, '2021_08_20_231348_create_modules_table', 14),
(36, '2021_08_24_224811_create_send_mails_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(7, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 11),
(7, 'App\\Models\\User', 12);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'dashboard', '2021-08-20 17:36:14', '2021-08-20 17:36:14'),
(3, 'setting', '2021-08-20 17:36:23', '2021-08-20 17:36:49'),
(5, 'user', '2021-08-20 17:37:32', '2021-08-20 17:37:32'),
(6, 'module', '2021-08-20 17:37:48', '2021-08-20 17:37:48'),
(7, 'permission', '2021-08-20 17:38:14', '2021-08-20 17:38:14'),
(8, 'role', '2021-08-20 17:38:23', '2021-08-20 17:38:23'),
(9, 'customer', '2021-08-20 17:38:40', '2021-08-20 17:38:40'),
(10, 'unit', '2021-08-20 17:38:55', '2021-08-20 17:38:55'),
(11, 'type', '2021-08-20 17:42:30', '2021-08-20 17:42:30'),
(12, 'product', '2021-08-20 17:42:40', '2021-08-22 01:23:06'),
(13, 'estimate', '2021-08-22 07:34:16', '2021-08-22 07:34:16'),
(14, 'invoice', '2021-08-22 07:34:28', '2021-08-22 07:34:28'),
(15, 'report', '2021-08-22 07:35:00', '2021-08-22 07:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `email`, `mobile`, `phone`, `address`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'raj IT Solution Ltd.', 'rajit@gmail.com', '01740255566', '1212221', 'Enjoy the videos and music you love, upload original content, and share it all with friends, family, and the world on YouTube.', 'HyqQqO5hDg6ylT0CBObkyoEjXfHEZcABD5DSbPzZ.png', '2021-08-02 17:21:56', '2021-08-13 17:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'cash', NULL, NULL),
(2, 'check', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module_id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(8, 2, 'dashboard.view', 'web', '2021-08-20 19:39:28', '2021-08-22 07:35:41'),
(13, 5, 'user.view', 'web', '2021-08-21 04:24:06', '2021-08-22 07:36:01'),
(14, 5, 'user.delete', 'web', '2021-08-21 04:24:20', '2021-08-21 04:27:49'),
(15, 5, 'user.create', 'web', '2021-08-22 07:36:14', '2021-08-22 07:48:47'),
(16, 5, 'user.edit', 'web', '2021-08-22 07:36:50', '2021-08-22 07:49:12'),
(18, 6, 'module.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:47:13'),
(19, 6, 'module.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:46:59'),
(20, 6, 'module.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:46:48'),
(21, 6, 'module.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:46:37'),
(23, 7, 'permission.create', 'web', '2021-08-22 07:38:53', '2021-08-22 07:44:50'),
(24, 7, 'permission.edit', 'web', '2021-08-22 07:39:03', '2021-08-22 07:44:07'),
(25, 7, 'permission.view', 'web', '2021-08-22 07:39:14', '2021-08-22 07:43:52'),
(28, 7, 'permission.delete', 'web', '2021-08-22 07:46:04', '2021-08-22 07:46:23'),
(29, 8, 'view.role', 'web', '2021-08-22 07:50:24', '2021-08-22 07:50:24'),
(30, 8, 'create.role', 'web', '2021-08-22 07:50:37', '2021-08-22 07:50:37'),
(31, 8, 'edit.role', 'web', '2021-08-22 07:50:50', '2021-08-22 07:50:50'),
(32, 8, 'delete.role', 'web', '2021-08-22 07:51:03', '2021-08-22 07:51:03'),
(33, 10, 'unit.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(34, 10, 'unit.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(35, 10, 'unit.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(36, 10, 'unit.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(37, 11, 'type.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(38, 11, 'type.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(39, 11, 'type.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(40, 11, 'type.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(41, 12, 'product.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(42, 12, 'product.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(43, 12, 'product.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(44, 12, 'product.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(45, 13, 'estimate.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(46, 13, 'estimate.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(47, 13, 'estimate.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(48, 13, 'estimate.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(49, 14, 'invoice.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(50, 14, 'invoice.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(51, 14, 'invoice.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(52, 14, 'invoice.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(53, 9, 'customer.view', 'web', '2021-08-22 07:37:37', '2021-08-22 07:59:26'),
(54, 9, 'customer.create', 'web', '2021-08-22 07:37:48', '2021-08-22 07:59:17'),
(55, 9, 'customer.edit', 'web', '2021-08-22 07:38:06', '2021-08-22 07:59:06'),
(56, 9, 'customer.delete', 'web', '2021-08-22 07:38:17', '2021-08-22 07:58:48'),
(57, 9, 'customer.show', 'web', '2021-08-22 10:53:46', '2021-08-22 17:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` int(11) NOT NULL,
  `product_type_id` tinyint(4) NOT NULL,
  `price` double(12,2) NOT NULL,
  `is_visible` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `unit_id`, `product_type_id`, `price`, `is_visible`, `created_at`, `updated_at`) VALUES
(2, 'Domain .bd', 3, 1, 920.00, 1, '2021-10-01 08:28:04', '2021-10-01 08:28:04'),
(3, 'Domain', 3, 1, 1100.00, 1, '2021-10-01 08:28:50', '2021-10-01 08:28:50'),
(4, 'Web Hosting - 2 GB', 3, 1, 2000.00, 1, '2021-10-01 08:47:47', '2021-10-01 09:08:11'),
(5, 'Web Hosting - 5GB', 3, 1, 3000.00, 1, '2021-10-01 08:48:14', '2021-10-01 08:58:55'),
(6, 'Web Hosting - 7 GB', 3, 1, 4000.00, 1, '2021-10-01 08:49:35', '2021-10-01 08:58:40'),
(7, 'Web Hosting -12 GB', 3, 1, 6000.00, 1, '2021-10-01 08:52:15', '2021-10-01 08:58:24'),
(8, 'Web Hosting - 30 GB', 3, 1, 13000.00, 1, '2021-10-01 08:57:02', '2021-10-01 08:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Service', '2021-09-30 14:37:48', '2021-09-30 14:37:48'),
(2, 'Product', '2021-10-01 08:27:24', '2021-10-01 08:27:24'),
(3, 'Design and Development', '2021-10-01 12:51:06', '2021-10-01 12:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'Editor', 'web', '2021-08-21 07:12:39', '2021-08-21 07:12:39'),
(7, 'admin', 'web', '2021-08-22 10:44:48', '2021-08-22 10:44:48');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(8, 7),
(13, 2),
(13, 7),
(14, 2),
(14, 7),
(15, 7),
(16, 7),
(18, 7),
(19, 7),
(20, 7),
(21, 7),
(23, 7),
(24, 7),
(25, 7),
(28, 7),
(29, 7),
(30, 7),
(31, 7),
(32, 7),
(33, 7),
(34, 7),
(35, 7),
(36, 7),
(37, 7),
(38, 7),
(39, 7),
(40, 7),
(41, 7),
(42, 7),
(43, 7),
(44, 7),
(45, 7),
(46, 7),
(47, 7),
(48, 7),
(49, 7),
(50, 7),
(51, 7),
(52, 7),
(53, 7),
(54, 7),
(55, 7),
(56, 7),
(57, 7);

-- --------------------------------------------------------

--
-- Table structure for table `send_mails`
--

CREATE TABLE `send_mails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3FwCCGdjHSsHlIKEpXo5nS4184pUjvzaCpVTorcX', 1, '113.11.86.53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiVUFad0t3UUl2RHh1WTRZOGFSNnROMllKWWNmNlI5OXlqRGpMSHN5UCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vcmFqYmlsbGluZy5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTAkRUNFdGE5MGRCSmF5R0Q5c0NRUVhFLi9KTmtRMGUvV0s2VVB5L2J4bFcwOXFVbVVjYlo1amUiO3M6MjY6IjR5VGxUREt1M29KT2Z6RF9jYXJ0X2l0ZW1zIjtPOjMyOiJEYXJyeWxkZWNvZGVcQ2FydFxDYXJ0Q29sbGVjdGlvbiI6MTp7czo4OiIAKgBpdGVtcyI7YTowOnt9fXM6NToiYWxlcnQiO2E6MDp7fX0=', 1648097971),
('QEPQVd9hxTBwFw7bfVv9FN4f4xWRT3zgA5H6ny0m', NULL, '18.212.150.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNk5XV05xWGlZN0RnZjRXd1gzMVY2U2E3QUhqOE54ZGpVQlp1ckhjdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL3JhamJpbGxpbmcuY29tIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9yYWpiaWxsaW5nLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1648093162),
('sljS8836bmtuOJ7uFFfP26WoccoajsDl0qzV5R9E', NULL, '18.212.150.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzg0RmJrc2UxbE9wUWhpSnMxMjFad2QxSDlycThZVTdZS0I3TUhJeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9yYWpiaWxsaW5nLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1648093167);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Piece', '2021-09-30 14:38:02', '2021-10-01 08:25:13'),
(2, 'Monthly', '2021-10-01 08:25:33', '2021-10-01 08:25:33'),
(3, 'Yearly', '2021-10-01 08:25:51', '2021-10-01 08:25:51'),
(4, 'Meter', '2021-10-01 08:26:02', '2021-10-01 08:26:02'),
(5, 'Number', '2021-10-01 12:50:11', '2021-10-01 12:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recurring_mail` tinyint(4) NOT NULL DEFAULT 0,
  `is_customer` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `is_recurring_mail`, `is_customer`, `created_at`, `updated_at`) VALUES
(1, 'Md. Mominul Haque', 'mdtopu11@gmail.com', '01740255566', NULL, '$2y$10$ECEta90dBJayGD9sCQQXE./JNkQ0e/WK6UPy/bxlW09qUmUcbZ5je', NULL, NULL, NULL, NULL, 'profile_pic_1.jpg', 1, 1, '2021-07-27 05:29:06', '2021-08-30 02:47:47'),
(3, 'Md. Robiul Hasan', 'robiul.stat@gmail.com', '01740255566', NULL, '$2y$10$F6locHsqF7C2yRYPgTXvNenVkhyQaDIxcrjBzGkb57xd9lVJodAn6', NULL, NULL, NULL, NULL, 'profile_pic_3.jpg', 0, 1, '2021-07-29 23:50:52', '2021-08-29 10:07:55'),
(11, 'ASM Akhtaruzzaman', 'akhtaruzzaman@gmail.com', '01755575801', NULL, '$2y$10$hvvYiccRI3FzjjhjcnhEBuozjPPRyIu.IKAInNJzTcw2LGr4NtNE2', NULL, NULL, NULL, NULL, 'profile_pic_11.jpg', 0, 1, '2021-10-01 08:21:21', '2021-10-01 08:21:21'),
(12, 'Abdul Rashid', 'rasid@gmail.com', '01703695842', NULL, '$2y$10$l9S8G/wonxnwt/ZArW7BkeuO8F2tIv24YnfMo0j35N6kPEo1sdZXi', NULL, NULL, NULL, NULL, 'profile_pic_12.jpg', 0, 1, '2022-03-24 02:23:40', '2022-03-24 02:24:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_email_index` (`email`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_counters`
--
ALTER TABLE `estimate_counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_details`
--
ALTER TABLE `estimate_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `send_mails`
--
ALTER TABLE `send_mails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_counters`
--
ALTER TABLE `estimate_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `estimate_details`
--
ALTER TABLE `estimate_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `send_mails`
--
ALTER TABLE `send_mails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
