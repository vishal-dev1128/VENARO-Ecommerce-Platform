-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 04:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `VÉNARO_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address_type` enum('Shipping','Billing') NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) DEFAULT 'India',
  `phone` varchar(20) NOT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Super Admin','Product Manager','Order Manager','Marketing Manager') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `full_name`, `email`, `password_hash`, `role`, `status`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Super Admin', 'admin@VÉNARO.com', '$2y$10$tryWdCRsDn/ieQjR.jM9D.pPghR7hg5R2/5xLGS4ABIoPCAh4Py26', 'Super Admin', 'Active', '2026-02-11 09:51:17', '2026-02-25 17:37:17', '2026-02-25 17:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `category_name` varchar(100) NOT NULL,
  `gender` varchar(20) DEFAULT 'Men',
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `parent_id`, `category_name`, `gender`, `slug`, `description`, `image`, `parent_category_id`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(49, NULL, 'BOTTOMWEAR', 'Men', 'bottomwear', '', 'cat_49_69945ee95e109.png', NULL, 9, 'Active', '2026-02-17 12:28:25', '2026-02-20 10:23:17'),
(50, NULL, 'WINTER WEAR', 'Men', 'winter-wear', '', 'cat_50_6994612ab71ae.jpg', NULL, 13, 'Active', '2026-02-17 12:30:36', '2026-02-20 10:23:17'),
(51, NULL, 'SHIRTS', 'Men', 'shirts', '', 'cat_51_699460a08642c.jpg', NULL, 6, 'Active', '2026-02-17 12:33:25', '2026-02-20 10:23:17'),
(52, NULL, 'T-SHIRTS', 'Men', 't-shirts', '', 'cat_52_69946027cc8ab.jpeg', NULL, 0, 'Active', '2026-02-17 12:33:43', '2026-02-17 12:33:43'),
(53, 52, 'New Arrival', 'Men', 'new-arrival', '', 'cat_53_6997381bdb208.jpeg', NULL, 1, 'Active', '2026-02-19 16:19:39', '2026-02-19 16:40:29'),
(54, 52, 'Oversized Fit', 'Men', 'oversized-fit', '', 'cat_54_6997d0e52e0e4.jpeg', NULL, 2, 'Active', '2026-02-19 16:20:12', '2026-02-20 03:11:33'),
(55, 52, 'Relaxed Fit', 'Men', 'relaxed-fit', '', 'cat_55_69973dc394b7c.jpeg', NULL, 3, 'Active', '2026-02-19 16:43:47', '2026-02-19 16:43:52'),
(58, 51, 'New Arrival', 'Men', 'new-arrival-1', '', 'cat_58_69973e6a417f7.jpg', NULL, 7, 'Active', '2026-02-19 16:46:34', '2026-02-20 10:23:17'),
(59, 51, 'Oversized Fit', 'Men', 'oversized-fit-1', '', 'cat_59_69973e78a2e7f.jpg', NULL, 8, 'Active', '2026-02-19 16:46:48', '2026-02-20 10:23:17'),
(61, 49, 'Jogger', 'Men', 'jogger', '', 'cat_61_6997423793d4f.jpg', NULL, 11, 'Active', '2026-02-19 16:54:03', '2026-02-20 10:23:17'),
(62, 49, 'Sweatpants', 'Men', 'sweatpants', '', NULL, NULL, 10, 'Active', '2026-02-19 16:54:35', '2026-02-20 10:23:17'),
(63, 52, 'Super Essential', 'Men', 'super-essential', '', 'cat_63_6997406cb597c.jpeg', NULL, 4, 'Active', '2026-02-19 16:55:08', '2026-02-19 16:55:12'),
(65, 52, 'Full Sleeve Tshirt', 'Men', 'full-sleeve-tshirt', '', 'cat_65_6997d0c5bcd23.jpg', NULL, 5, 'Active', '2026-02-19 16:58:22', '2026-02-20 03:11:01'),
(67, 50, 'Versity Jacket', 'Men', 'versity-jacket', '', NULL, NULL, 16, 'Active', '2026-02-20 08:50:42', '2026-02-20 10:23:17'),
(68, 50, 'Sweatshirt', 'Men', 'sweatshirt', '', NULL, NULL, 14, 'Active', '2026-02-20 08:50:56', '2026-02-20 10:23:17'),
(69, 50, 'Hoodies', 'Men', 'hoodies', '', NULL, NULL, 15, 'Active', '2026-02-20 08:51:08', '2026-02-20 10:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `collection_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`collection_id`, `collection_name`, `slug`, `description`, `image`, `is_featured`, `start_date`, `end_date`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'New Arrival', 'new-arrival', 'Discover the latest additions to our premium collection. Fresh styles crafted with exceptional attention to detail.', NULL, 1, NULL, NULL, 1, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(2, 'Essentials', 'essentials', 'Timeless pieces that form the foundation of a refined wardrobe. Designed for everyday luxury.', NULL, 1, NULL, NULL, 2, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(3, 'Premium Hoodies', 'premium-hoodies', 'Elevated comfort meets sophisticated design. Crafted from the finest materials for superior warmth and style.', NULL, 1, NULL, NULL, 3, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(4, 'Signature Tees', 'signature-tees', 'Our iconic t-shirt collection. Premium Supima cotton, perfect fit, uncompromising quality.', NULL, 1, NULL, NULL, 4, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(5, 'Varsity Collection', 'varsity-collection', 'Classic varsity jackets reimagined with modern luxury. Heritage meets contemporary craftsmanship.', NULL, 1, NULL, NULL, 5, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(6, 'Sweatpants', 'sweatpants', 'Refined comfort for the modern gentleman. Structured sweatpants that elevate casual sophistication.', NULL, 1, NULL, NULL, 6, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(7, 'Winter Collection', 'winter-collection', 'Essential pieces for the colder months. Warmth, style, and premium construction in perfect harmony.', NULL, 1, NULL, NULL, 7, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37'),
(8, 'Limited Edition', 'limited-edition', 'Exclusive designs in limited quantities. Rare pieces for the discerning individual.', NULL, 1, NULL, NULL, 8, 'Active', '2026-02-16 02:44:37', '2026-02-16 02:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL DEFAULT 'General Inquiry',
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'vishal', 'info@VÉNARO.com', 'General Inquiry', 'Nothing', 1, '2026-02-20 11:20:24'),
(2, 'VÉNARO', 'info@VÉNARO.com', 'General Inquiry', 'nothing', 1, '2026-02-20 11:20:32'),
(3, 'VÉNARO', 'info@VÉNARO.com', 'General Inquiry', 'nothing', 1, '2026-02-20 11:21:10'),
(4, 'VÉNARO', 'info@VÉNARO.com', 'General Inquiry', 'nothing', 1, '2026-02-20 11:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('Percentage','Flat','Free Shipping') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount_cap` decimal(10,2) DEFAULT NULL,
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `usage_limit_total` int(11) DEFAULT NULL,
  `usage_limit_per_user` int(11) DEFAULT 1,
  `first_time_only` tinyint(1) DEFAULT 0,
  `exclude_sale_items` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('Active','Disabled','Expired') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `coupon_code`, `description`, `discount_type`, `discount_value`, `max_discount_cap`, `min_order_value`, `usage_limit_total`, `usage_limit_per_user`, `first_time_only`, `exclude_sale_items`, `start_date`, `expiry_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'NEW08', NULL, 'Flat', 499.00, NULL, 0.00, NULL, 1, 0, 0, NULL, '2026-02-21', 'Active', '2026-02-20 10:39:12', '2026-02-20 10:59:43');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `usage_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('Active','Draft') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `category`, `question`, `answer`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ordering & Payment', 'What payment methods do you accept?', 'We accept Credit/Debit Cards, UPI, Net Banking, Wallets, and Cash on Delivery.', 1, 'Active', '2026-02-11 09:51:17', '2026-02-11 09:51:17'),
(2, 'Shipping & Delivery', 'How long does delivery take?', 'Standard delivery takes 5-7 business days. Express delivery is available in 2-3 days.', 1, 'Active', '2026-02-11 09:51:17', '2026-02-11 09:51:17'),
(3, 'Returns & Exchanges', 'What is your return policy?', 'We offer easy 30-day returns on all products. Items must be unused and in original packaging.', 1, 'Active', '2026-02-11 09:51:17', '2026-02-11 09:51:17'),
(4, 'Size & Fit', 'How do I find my size?', 'Please refer to our comprehensive size guide available on each product page.', 1, 'Active', '2026-02-11 09:51:17', '2026-02-11 09:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `subscriber_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Active','Unsubscribed') DEFAULT 'Active',
  `source` varchar(100) DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unsubscribed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_charge` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `order_status` enum('Order Placed','Processing','Shipped','Delivered','Cancelled','Returned') DEFAULT 'Order Placed',
  `tracking_number` varchar(100) DEFAULT NULL,
  `carrier` varchar(100) DEFAULT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `user_id`, `guest_email`, `shipping_address_id`, `billing_address_id`, `subtotal`, `discount_amount`, `shipping_charge`, `tax_amount`, `total_amount`, `payment_method`, `payment_status`, `transaction_id`, `order_status`, `tracking_number`, `carrier`, `estimated_delivery_date`, `actual_delivery_date`, `coupon_code`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-6991B40204D85', NULL, NULL, NULL, NULL, 3398.00, 0.00, 0.00, 364.07, 3398.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-15 11:54:42', '2026-02-15 12:18:19'),
(2, 'ORD-699286EBCD0E7', NULL, NULL, NULL, NULL, 3398.00, 0.00, 0.00, 364.07, 3398.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-16 02:54:35', '2026-02-20 10:38:36'),
(3, 'ORD-699288D2C55DF', NULL, NULL, NULL, NULL, 1699.00, 0.00, 0.00, 182.04, 1699.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-16 03:02:42', '2026-02-16 03:03:08'),
(4, 'ORD-6992909E76C52', NULL, NULL, NULL, NULL, 1699.00, 0.00, 0.00, 182.04, 1699.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-16 03:35:58', '2026-02-20 10:38:30'),
(5, 'ORD-6997CD3E6A31B', NULL, NULL, NULL, NULL, 999.00, 0.00, 0.00, 107.04, 999.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-20 02:55:58', '2026-02-20 10:38:21'),
(6, 'ORD-699839674B57A', NULL, NULL, NULL, NULL, 7695.00, 0.00, 0.00, 824.46, 7695.00, 'COD', 'Paid', NULL, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-20 10:37:27', '2026-02-20 10:38:11'),
(7, 'ORD-69983EBEB4D3E', NULL, NULL, NULL, NULL, 1699.00, 0.00, 0.00, 182.04, 1200.00, 'COD', 'Pending', NULL, 'Order Placed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-20 11:00:14', '2026-02-20 11:00:14'),
(8, 'ORD-699D4790AEE50', NULL, NULL, NULL, NULL, 1998.00, 0.00, 0.00, 214.07, 1998.00, 'COD', 'Pending', NULL, 'Order Placed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-24 06:39:12', '2026-02-24 06:39:12'),
(9, 'ORD-699EC44F3BFCE', NULL, NULL, NULL, NULL, 1798.00, 0.00, 0.00, 192.64, 1798.00, 'COD', 'Pending', NULL, 'Order Placed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-25 09:43:43', '2026-02-25 09:43:43'),
(10, 'ORD-699FB808E4F39', NULL, NULL, NULL, NULL, 1699.00, 0.00, 0.00, 182.04, 1699.00, 'COD', 'Pending', NULL, 'Order Placed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-26 03:03:36', '2026-02-26 03:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `variant_id`, `product_name`, `size`, `color`, `quantity`, `unit_price`, `subtotal`, `created_at`) VALUES
(1, 1, 21, NULL, 'VÉNARO “VERDANT EAGLE” Oversized T-Shirt – Forest Green', 'M', NULL, 1, 1699.00, 1699.00, '2026-02-15 11:54:42'),
(2, 1, 21, NULL, 'VÉNARO “VERDANT EAGLE” Oversized T-Shirt – Forest Green', 'L', NULL, 1, 1699.00, 1699.00, '2026-02-15 11:54:42'),
(3, 2, 24, NULL, 'VÉNARO “NEVER STOP DREAMING” Oversized T-Shirt – Black', 'S', NULL, 1, 1699.00, 1699.00, '2026-02-16 02:54:35'),
(4, 2, 21, NULL, 'VÉNARO “VERDANT EAGLE” Oversized T-Shirt – Forest Green', 'L', NULL, 1, 1699.00, 1699.00, '2026-02-16 02:54:35'),
(5, 3, 23, NULL, 'VÉNARO “SLOWER SUNDAY VIBES” Oversized T-Shirt – Black', 'S', NULL, 1, 1699.00, 1699.00, '2026-02-16 03:02:42'),
(6, 4, 25, NULL, 'VÉNARO “BLOOM” Oversized T-Shirt – Grey Melange', 'S', NULL, 1, 1699.00, 1699.00, '2026-02-16 03:35:58'),
(7, 5, 66, NULL, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'Standard', NULL, 1, 999.00, 999.00, '2026-02-20 02:55:58'),
(8, 6, 25, NULL, 'VÉNARO “BLOOM” Oversized T-Shirt – Grey Melange', 'XS', NULL, 1, 1699.00, 1699.00, '2026-02-20 10:37:27'),
(9, 6, 23, NULL, 'VÉNARO “SLOWER SUNDAY VIBES” Oversized T-Shirt – Black', 'XS', NULL, 1, 1699.00, 1699.00, '2026-02-20 10:37:27'),
(10, 6, 88, NULL, 'VÉNARO Phoenix Oversized Hoodie | 400 GSM Heavyweight', 'XS', NULL, 1, 2499.00, 2499.00, '2026-02-20 10:37:27'),
(11, 6, 51, NULL, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'XS', NULL, 1, 799.00, 799.00, '2026-02-20 10:37:27'),
(12, 6, 66, NULL, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'XS', NULL, 1, 999.00, 999.00, '2026-02-20 10:37:27'),
(13, 7, 100, NULL, 'VÉNARO “Aquila” Eagle Varsity Jacket – Green & Ivory', 'XS', NULL, 1, 1699.00, 1699.00, '2026-02-20 11:00:14'),
(14, 8, 66, NULL, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'XS', NULL, 2, 999.00, 1998.00, '2026-02-24 06:39:12'),
(15, 9, 64, NULL, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'XS', NULL, 1, 999.00, 999.00, '2026-02-25 09:43:43'),
(16, 9, 73, NULL, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'XS', NULL, 1, 799.00, 799.00, '2026-02-25 09:43:43'),
(17, 10, 28, NULL, 'VÉNARO “VAMPIRE” Oversized T-Shirt – Black', 'XS', NULL, 1, 1699.00, 1699.00, '2026-02-26 03:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('Order Placed','Processing','Shipped','Delivered','Cancelled','Returned') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`history_id`, `order_id`, `status`, `notes`, `created_by`, `created_at`) VALUES
(1, 2, 'Cancelled', 'Cancelled by user', 1, '2026-02-16 03:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `regular_price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sale_start_date` date DEFAULT NULL,
  `sale_end_date` date DEFAULT NULL,
  `tax_rate` decimal(5,2) DEFAULT 18.00,
  `weight` decimal(8,2) DEFAULT NULL,
  `length` decimal(8,2) DEFAULT NULL,
  `width` decimal(8,2) DEFAULT NULL,
  `height` decimal(8,2) DEFAULT NULL,
  `fabric_composition` varchar(255) DEFAULT NULL,
  `gsm_weight` int(11) DEFAULT NULL,
  `care_instructions` text DEFAULT NULL,
  `track_inventory` tinyint(1) DEFAULT 1,
  `stock_quantity` int(11) DEFAULT 0,
  `low_stock_threshold` int(11) DEFAULT 10,
  `allow_backorders` tinyint(1) DEFAULT 0,
  `stock_status` enum('In Stock','Out of Stock','On Backorder') DEFAULT 'In Stock',
  `status` enum('Active','Draft','Archived') DEFAULT 'Draft',
  `featured` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `sku`, `slug`, `short_description`, `long_description`, `regular_price`, `sale_price`, `sale_start_date`, `sale_end_date`, `tax_rate`, `weight`, `length`, `width`, `height`, `fabric_composition`, `gsm_weight`, `care_instructions`, `track_inventory`, `stock_quantity`, `low_stock_threshold`, `allow_backorders`, `stock_status`, `status`, `featured`, `views`, `created_at`, `updated_at`) VALUES
(21, 'VÉNARO “VERDANT EAGLE” Oversized T-Shirt – Forest Green', 'VN-6991B21CBD90F', 'v-naro-verdant-eagle-oversized-t-shirt-forest-green', '', 'Inspired by nature’s raw elegance and unshakable strength, the VÉNARO “VERDANT EAGLE” Oversized T-Shirt embodies freedom, vigilance, and renewal. The clean VÉNARO logo on the front keeps the look refined and minimal, while the back reveals a powerful eagle illustration formed from layered green leaves—symbolizing harmony between strength and nature.\r\n\r\nSet against a deep forest-green base, the design commands attention without excess. The oversized silhouette delivers contemporary streetwear appeal while maintaining everyday comfort and versatility.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a soft, breathable feel\r\n\r\nOversized, relaxed streetwear fit\r\n\r\nMinimal front logo branding\r\n\r\nDetailed leaf-textured eagle artwork on the back\r\n\r\nNature-inspired green-on-green color palette\r\n\r\nDurable print with long-lasting color depth\r\n\r\nMeaning Behind the Design:\r\nThe eagle represents vision, freedom, and authority, while the leaf composition reflects growth, balance, and renewal—made for those who lead with clarity and purpose.\r\n\r\nStyle Tip: Pair with black cargos, olive pants, or neutral sneakers for a clean, elevated street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 22, '2026-02-15 11:46:36', '2026-02-20 10:39:17'),
(22, 'VÉNARO “WILD ROAR” Oversized T-Shirt – Black', 'VN-6991B24A9B7F9', 'v-naro-wild-roar-oversized-t-shirt-black', '', 'Unfiltered. Untamed. Unstoppable.\r\nThe VÉNARO “WILD ROAR” Oversized T-Shirt is built for those who refuse to be controlled. Featuring a clean VÉNARO logo on the front and a powerful back graphic of a roaring lion breaking through bold WILD brush typography, this piece captures raw instinct and fearless energy.\r\n\r\nSet on a deep black base, the design delivers maximum contrast and attitude. The oversized fit amplifies its streetwear edge while ensuring all-day comfort and confidence.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable finish\r\n\r\nOversized streetwear silhouette\r\n\r\nMinimal front branding with bold back statement\r\n\r\nRoaring lion artwork symbolizing raw power and dominance\r\n\r\nHigh-contrast brush typography for an aggressive visual impact\r\n\r\nBreathable, fade-resistant, and built to last\r\n\r\nMeaning Behind the Design:\r\nWILD represents freedom beyond rules, strength beyond limits, and the courage to embrace your true nature.\r\n\r\nStyle Tip: Pair with distressed denim, black cargos, or combat sneakers for a raw, unapologetic street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-15 11:47:22', '2026-02-15 11:47:22'),
(23, 'VÉNARO “SLOWER SUNDAY VIBES” Oversized T-Shirt – Black', 'VN-6991B26E4DB6E', 'v-naro-slower-sunday-vibes-oversized-t-shirt-black', '', 'Slow down. Breathe deep. Feel the moment.\r\nThe VÉNARO “SLOWER SUNDAY VIBES” Oversized T-Shirt is designed for calm minds and effortless style. Featuring a minimalist VÉNARO logo on the front and a detailed sunflower artwork on the back paired with bold typography, this piece blends warmth, positivity, and modern streetwear.\r\n\r\nSet against a deep black base, the sunflower graphic symbolizes growth, optimism, and balance—perfect for relaxed days and expressive fits. The oversized silhouette delivers a laid-back look with premium comfort.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric, soft and breathable\r\n\r\nOversized relaxed streetwear fit\r\n\r\nClean front logo branding\r\n\r\nVintage-inspired sunflower artwork on the back\r\n\r\n“SLOWER SUNDAY VIBES” typography with artistic texture\r\n\r\nDurable, high-quality print\r\n\r\nMeaning Behind the Design:\r\nThe sunflower represents positivity and renewal, while Slower Sunday Vibes reminds you to pause, recharge, and enjoy the moment.\r\n\r\nStyle Tip: Pair with light denim, beige cargos, or sneakers for a chill, effortless vibe.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 4, '2026-02-15 11:47:58', '2026-02-20 10:36:32'),
(24, 'VÉNARO “NEVER STOP DREAMING” Oversized T-Shirt – Black', 'VN-6991B2BF14D08', 'v-naro-never-stop-dreaming-oversized-t-shirt-black', '', 'Built for the relentless. Designed for the driven.\r\nThe VÉNARO “NEVER STOP DREAMING” Oversized T-Shirt is a bold expression of ambition and hustle. Featuring a clean VÉNARO logo on the front, the back delivers a powerful red typographic composition layered with Hustle Habit, No Exit Way, and Never Stop Dreaming—a visual manifesto for those who refuse to quit.\r\n\r\nThe striking red-on-black contrast symbolizes urgency, passion, and focus, while the oversized silhouette brings modern streetwear attitude with everyday comfort.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable feel\r\n\r\nOversized streetwear fit for a confident silhouette\r\n\r\nMinimal front logo branding\r\n\r\nBold red typography with layered graphic elements on the back\r\n\r\nHigh-contrast design for maximum visual impact\r\n\r\nBreathable, fade-resistant, and long-lasting print\r\n\r\nMeaning Behind the Design:\r\nNever Stop Dreaming is a mindset. This piece represents discipline, consistency, and the hunger to keep moving forward—no exit, no excuses.\r\n\r\nStyle Tip: Pair with black cargos, joggers, or high-top sneakers for a sharp, driven street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-15 11:49:19', '2026-02-16 02:53:54'),
(25, 'VÉNARO “BLOOM” Oversized T-Shirt – Grey Melange', 'VN-6991B4609E5E6', 'v-naro-bloom-oversized-t-shirt-grey-melange', '', 'Growth finds a way—even in the concrete jungle.\r\nThe VÉNARO “BLOOM” Oversized T-Shirt is a symbol of resilience, hope, and quiet strength. Featuring a clean VÉNARO logo on the front and a beautifully layered back graphic with blooming flowers, a butterfly, and bold BLOOM typography, this piece celebrates life’s ability to flourish against all odds.\r\n\r\nSet on a calm grey melange base, the pastel florals and butterfly bring a sense of balance and positivity, while the oversized fit delivers modern streetwear comfort with an artistic edge.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a soft, breathable feel\r\n\r\nOversized relaxed fit for everyday comfort\r\n\r\nMinimal front logo branding\r\n\r\nFloral and butterfly artwork with bold “BLOOM” typography on the back\r\n\r\nMuted pastel tones on a versatile grey base\r\n\r\nDurable, high-quality print\r\n\r\nMeaning Behind the Design:\r\nBloom represents growth through struggle—beauty, strength, and individuality rising even in the harshest environments.\r\n\r\nStyle Tip: Pair with light denim, beige cargos, or white sneakers for a clean, calm, and expressive look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 5, '2026-02-15 11:56:16', '2026-02-20 10:36:23'),
(26, 'VÉNARO “DESPAIR” Oversized T-Shirt – Black', 'VN-6991B48F78FEE', 'v-naro-despair-oversized-t-shirt-black', '', 'Strength isn’t always loud—sometimes it’s stitched together through pain.\r\nThe VÉNARO “DESPAIR” Oversized T-Shirt captures the reality of struggle and survival. Featuring a minimal VÉNARO logo on the front, the back showcases a stitched teddy bear graphic surrounded by dark web elements and bold DESPAIR typography, paired with the message “Struggling But Strong.”\r\n\r\nSet on a deep black base, the design reflects emotional depth, resilience, and inner battles—turning vulnerability into power. The oversized silhouette adds a modern streetwear edge while keeping comfort at the core.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a soft yet durable feel\r\n\r\nOversized streetwear fit\r\n\r\nClean front logo branding\r\n\r\nStitched teddy bear artwork symbolizing pain, healing, and resilience\r\n\r\nBold “DESPAIR” typography with dark graphic elements\r\n\r\nHigh-quality, long-lasting print\r\n\r\nMeaning Behind the Design:\r\nDespair represents hardship, while Struggling But Strong is a reminder that survival itself is strength. This piece speaks for those who keep going, even when it’s hard.\r\n\r\nStyle Tip: Pair with black cargos, distressed denim, or boots for a raw, expressive street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-15 11:57:03', '2026-02-16 03:01:32'),
(27, 'VÉNARO “DON’T SLEEP ON YOUR DREAM” Oversized T-Shirt – Burgundy', 'VN-6991B6145875F', 'v-naro-don-t-sleep-on-your-dream-oversized-t-shirt-burgundy', '', 'Dreams don’t wait—and neither should you.\r\nThe VÉNARO “DON’T SLEEP ON YOUR DREAM” Oversized T-Shirt blends grit with beauty, ambition with emotion. Featuring a clean VÉNARO logo on the front, the back showcases two skulls intertwined with deep red roses, paired with the powerful message “DON’T SLEEP ON YOUR DREAM.”\r\n\r\nSet on a rich burgundy base, the contrast of skulls and roses symbolizes life, death, passion, and purpose—reminding you that dreams are fragile, but the will to chase them must be relentless. The oversized fit adds a bold streetwear edge while keeping comfort effortless.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable feel\r\n\r\nOversized streetwear silhouette\r\n\r\nMinimal front logo branding\r\n\r\nSkull and rose artwork symbolizing struggle, passion, and ambition\r\n\r\nBold motivational typography on the back\r\n\r\nLong-lasting, high-quality print\r\n\r\nMeaning Behind the Design:\r\nSkulls represent mortality. Roses represent desire. Together, they remind you that time is limited—so don’t sleep on what you’re meant to become.\r\n\r\nStyle Tip: Pair with black denim, boots, or layered accessories for a bold, expressive street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-15 12:03:32', '2026-02-15 12:03:32'),
(28, 'VÉNARO “VAMPIRE” Oversized T-Shirt – Black', 'VN-6991B643E6AE7', 'v-naro-vampire-oversized-t-shirt-black', '', 'Fear has a signature—and it bites back.\r\nThe VÉNARO “VAMPIRE” Oversized T-Shirt dives into dark mythology and underground street culture. Featuring a clean VÉNARO logo on the front, the back unleashes a striking vampire fang graphic layered with distorted typography, electric accents, and gothic detailing that radiates menace and mystery.\r\n\r\nSet on a deep black base, the design captures the essence of immortality, rebellion, and forbidden power. The oversized silhouette delivers a bold streetwear presence with uncompromising comfort.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable feel\r\n\r\nOversized streetwear fit\r\n\r\nMinimal front logo branding\r\n\r\nVampire fang artwork with gothic, horror-inspired typography\r\n\r\nHigh-contrast dark aesthetic with electric accents\r\n\r\nLong-lasting, high-quality print\r\n\r\nMeaning Behind the Design:\r\nThe vampire symbolizes immortality, hunger, and power beyond limits—an icon of darkness that refuses to fade. Made for those who embrace the night and stand apart from the ordinary.\r\n\r\nStyle Tip: Pair with black cargos, silver chains, or combat sneakers for a hard, underground street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-15 12:04:19', '2026-02-26 03:03:29'),
(29, 'VÉNARO “GOOD IS THE NEW REBELLION” Oversized T-Shirt – Black', 'VN-6991B9F23B96C', 'v-naro-good-is-the-new-rebellion-oversized-t-shirt-black', '', 'Good isn’t weak. Good is defiant.\r\nThe VÉNARO “GOOD IS THE NEW REBELLION” Oversized T-Shirt flips the script on conformity. Featuring a minimal VÉNARO logo on the front, the back makes a powerful statement with a detailed skull illustration wrapped in a bandana, layered over bold GOOD typography and manifesto-style text.\r\n\r\nSet on a deep black base, the monochrome artwork represents strength, individuality, and fearless self-belief. This oversized silhouette delivers raw streetwear energy while keeping comfort uncompromised.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable feel\r\n\r\nOversized streetwear fit\r\n\r\nClean front logo branding\r\n\r\nSkull with bandana artwork symbolizing rebellion and identity\r\n\r\nBold “GOOD” typography with statement text\r\n\r\nHigh-contrast black & white graphic print\r\n\r\nMeaning Behind the Design:\r\nGood is the new rebellion. In a chaotic world, standing tall, staying true, and living with fire is the boldest move of all. This piece is for those who don’t follow noise—they create meaning.\r\n\r\nStyle Tip: Pair with black cargos, denim, or sneakers for a fearless, no-compromise street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-15 12:20:02', '2026-02-15 12:20:02'),
(30, 'VÉNARO “EMBRACE THE DARK” Oversized T-Shirt – Deep Purple', 'VN-6992884575C61', 'v-naro-embrace-the-dark-oversized-t-shirt-deep-purple', '', 'Power doesn’t come from the light—it’s forged in the shadows.\r\nThe VÉNARO “EMBRACE THE DARK” Oversized T-Shirt explores inner strength, mystery, and control. Featuring a clean VÉNARO logo on the front, the back reveals a striking purple skull illustration with melting details, paired with a reflective message about mastering darkness rather than fearing it.\r\n\r\nSet on a rich deep-purple base, the artwork blends menace with elegance, making this piece both bold and refined. The oversized silhouette delivers contemporary streetwear appeal with all-day comfort.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, breathable feel\r\n\r\nOversized streetwear fit\r\n\r\nMinimal front logo branding\r\n\r\nPurple skull artwork symbolizing inner power and transformation\r\n\r\nDark, moody aesthetic with high visual impact\r\n\r\nDurable, long-lasting print quality\r\n\r\nMeaning Behind the Design:\r\nThis piece represents self-mastery—true power comes when you stop running from darkness and learn to command it.\r\n\r\nStyle Tip: Pair with black cargos, layered silver accessories, or dark sneakers for a powerful, underground street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-16 03:00:21', '2026-02-16 03:00:21'),
(31, 'VÉNARO “THE ROBOTIC ANGEL” Oversized T-Shirt – Black', 'VN-69928F43C5EFB', 'v-naro-the-robotic-angel-oversized-t-shirt-black', '', 'Where divinity meets machinery.\r\nThe VÉNARO “THE ROBOTIC ANGEL” Oversized T-Shirt reimagines faith, power, and evolution through a futuristic lens. Featuring a clean VÉNARO logo on the front, the back reveals a striking robotic angel figure layered with gothic typography, chrome-like details, and tech-inspired elements—blending heaven, steel, and rebellion.\r\n\r\nSet on a deep black base, the design represents balance between soul and system, belief and innovation. The oversized silhouette delivers bold streetwear presence with refined comfort.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a smooth, durable feel\r\n\r\nOversized modern streetwear fit\r\n\r\nMinimal front logo branding\r\n\r\nRobotic angel artwork with gothic typography\r\n\r\nFuturistic + divine visual aesthetic\r\n\r\nHigh-quality, long-lasting print\r\n\r\nMeaning Behind the Design:\r\nThe Robotic Angel symbolizes evolution—where faith adapts, power transforms, and identity rises beyond tradition. A statement for those who believe in both destiny and design.\r\n\r\nStyle Tip: Pair with black cargos, techwear pants, or metallic accessories for a futuristic, high-impact street look.', 1499.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-16 03:30:11', '2026-02-16 03:30:11'),
(39, 'VÉNARO “911 PORSCHE” Performance T-Shirt – Black', 'VN-699293FD276EA', 'v-naro-911-porsche-performance-t-shirt-black', '', 'Precision isn’t loud. It’s legendary.\r\nThe VÉNARO “911 PORSCHE” Performance T-Shirt is inspired by one of the most iconic silhouettes in automotive history—the Porsche 911. Featuring a sleek monochrome car graphic with bold 911 PORSCHE typography, this design celebrates speed, engineering excellence, and timeless performance.\r\n\r\nSet on a deep black base, the graphic delivers a clean motorsport aesthetic that blends perfectly with modern streetwear. Crafted for comfort and everyday wear, this tee is made for those who appreciate design, detail, and driving culture.\r\n\r\nKey Features:\r\n\r\nPremium-quality cotton fabric\r\n\r\nSoft, breathable, and durable feel\r\n\r\nRegular unisex fit for a classic silhouette\r\n\r\nHigh-detail 911 Porsche graphic on the front\r\n\r\nMinimal, performance-inspired design language\r\n\r\nIdeal for car enthusiasts and streetwear styling\r\n\r\nDesign Inspiration:\r\nThe Porsche 911 represents balance—power without excess, design without compromise. This piece captures that same philosophy in wearable form.\r\n\r\nStyle Tip: Pair with black denim, racing sneakers, or a jacket for a clean motorsport-inspired look.', 1399.00, 1499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-16 03:50:21', '2026-02-16 03:50:21'),
(40, 'VÉNARO “UNLIMITED” Motivation T-Shirt – Black', 'VN-6992944CD9579', 'v-naro-unlimited-motivation-t-shirt-black', '', 'Limits exist only where you accept them.\r\nThe VÉNARO “UNLIMITED” Motivation T-Shirt is designed to inspire progress, confidence, and forward motion. Featuring bold UNLIMITED typography with a striking red accent across the center and the message “Make Everything Possible”, this piece delivers a powerful statement through clean, modern design.\r\n\r\nSet on a deep black base, the contrast of white and red creates a sharp, high-impact look while keeping the aesthetic minimal and versatile. The silhouette is timeless and easy to style, making it an everyday essential with meaning.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric for comfort and durability\r\n\r\nRegular unisex fit with a classic silhouette\r\n\r\nBold “UNLIMITED” front typography with red highlight\r\n\r\nMinimal, motivational design language\r\n\r\nLightweight, breathable, and ideal for daily wear\r\n\r\nMeaning Behind the Design:\r\nUnlimited represents mindset over limits—believing in possibility, growth, and constant evolution.\r\n\r\nStyle Tip: Pair with black or grey denim and sneakers for a clean, confident street look that speaks without shouting.', 1399.00, 1599.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-16 03:51:40', '2026-02-16 03:51:40'),
(41, 'VÉNARO “NOT WORTH IT” Statement T-Shirt – Black', 'VN-699294808FBEF', 'v-naro-not-worth-it-statement-t-shirt-black', '', 'Not everything has a price—and some things cost too much.\r\nThe VÉNARO “NOT WORTH IT” Statement T-Shirt challenges the idea that everything can be bought. Featuring bold NOT WORTH IT typography layered with falling currency graphics and the message “Not Everything Has a Price”, this piece delivers a strong cultural and streetwear statement.\r\n\r\nSet on a deep black base, the design contrasts wealth symbolism with meaning and values—making it more than a graphic, it’s a mindset. Clean, impactful, and easy to style, this tee fits seamlessly into modern street culture.\r\n\r\nKey Features:\r\n\r\nPremium cotton fabric with a soft, durable feel\r\n\r\nRegular unisex fit for a classic silhouette\r\n\r\nBold front graphic with money overlay\r\n\r\nHigh-contrast white typography on black\r\n\r\nLightweight, breathable, and comfortable for daily wear\r\n\r\nMeaning Behind the Design:\r\nNot Worth It represents choosing self-respect, peace, and purpose over shortcuts, greed, or empty success.\r\n\r\nStyle Tip: Pair with black denim, cargos, or sneakers for a sharp, no-nonsense street look.', 1399.00, 1499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-16 03:52:32', '2026-02-26 03:03:16'),
(42, 'VÉNARO Premium Solid Oversized Shirt – 100% Cotton | 240 GSM | Relaxed Fit', 'VN-69945BCCC14A3', 'VÉNARO-premium-solid-oversized-shirt-100-cotton-240-gsm-relaxed-fit', '', 'Minimal. Premium. Everyday essential.\r\n\r\nThe VÉNARO Solid Oversized Shirt is crafted from 100% pure cotton (240 GSM), offering a perfect balance of structure, breathability, and all-day comfort. Designed for a relaxed silhouette, this shirt delivers a modern oversized fit that elevates both casual and smart-casual looks.\r\n\r\nIts clean solid finish makes it versatile enough to wear solo or layer effortlessly. Whether you’re stepping out for a casual day, a weekend plan, or a laid-back evening, this shirt adapts to your style without compromise.\r\n\r\nKey Features\r\n\r\nFabric: 100% Cotton, 240 GSM – premium, breathable & durable\r\n\r\nFit: Oversized relaxed fit for a modern streetwear look\r\n\r\nFeel: Soft on skin with a structured drape\r\n\r\nDesign: Clean solid color, timeless and versatile\r\n\r\nSleeves: Short sleeves for everyday comfort\r\n\r\nPerfect For\r\n\r\nCasual outings, everyday wear, travel looks, and effortless street style.\r\n\r\nCare Instructions\r\n\r\nWash inside-out in cold water.\r\nDo not bleach.\r\nTumble dry low.\r\nIron inside-out for long-lasting finish.', 799.00, 649.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-17 12:15:08', '2026-02-17 12:15:08'),
(44, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'VN-69974286D0391', 'v-naro-signature-joggers-elevated-comfort-edition-1', '', 'Designed for movement. Defined by minimal luxury.\r\nThe VÉNARO Signature Joggers are crafted for those who value comfort without compromising on style. Featuring a clean silhouette with subtle branding, these joggers deliver a refined streetwear aesthetic that transitions effortlessly from lounge to lifestyle.\r\n\r\nMade with premium cotton fabric and a tapered structure, they offer an active feel while maintaining a polished, everyday look.\r\n\r\n✦ Key Details\r\n\r\nFabric: 100% premium cotton, 260 GSM — soft, breathable & durable\r\n\r\nFit: Unisex jogger fit with ribbed waistband and cuffs\r\n\r\nDesign: Minimal signature logo for a clean, premium identity\r\n\r\nComfort: Relaxed cut with tapered legs for modern styling\r\n\r\nUtility: Perfect for daily wear, travel, gym warm-ups & casual outings\r\n\r\n✦ Care Instructions\r\n\r\nWash inside-out in cold water\r\nDo not bleach\r\nDry on low heat\r\nIron inside-out only\r\n\r\n✨ VÉNARO represents understated luxury — made to move, built to last.', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:04:06', '2026-02-19 17:04:52'),
(48, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'VN-69974318BC443', 'v-naro-signature-joggers-elevated-comfort-edition', '', 'Designed for movement. Defined by minimal luxury.\r\nThe VÉNARO Signature Joggers are crafted for those who value comfort without compromising on style. Featuring a clean silhouette with subtle branding, these joggers deliver a refined streetwear aesthetic that transitions effortlessly from lounge to lifestyle.\r\n\r\nMade with premium cotton fabric and a tapered structure, they offer an active feel while maintaining a polished, everyday look.\r\n\r\n✦ Key Details\r\n\r\nFabric: 100% premium cotton, 260 GSM — soft, breathable & durable\r\n\r\nFit: Unisex jogger fit with ribbed waistband and cuffs\r\n\r\nDesign: Minimal signature logo for a clean, premium identity\r\n\r\nComfort: Relaxed cut with tapered legs for modern styling\r\n\r\nUtility: Perfect for daily wear, travel, gym warm-ups & casual outings\r\n\r\n✦ Care Instructions\r\n\r\nWash inside-out in cold water\r\nDo not bleach\r\nDry on low heat\r\nIron inside-out only\r\n\r\n✨ VÉNARO represents understated luxury — made to move, built to last.', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(49, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'VN-6997433BBD55D', 'v-naro-signature-joggers-elevated-comfort-edition-2', '', 'Designed for movement. Defined by minimal luxury.\r\nThe VÉNARO Signature Joggers are crafted for those who value comfort without compromising on style. Featuring a clean silhouette with subtle branding, these joggers deliver a refined streetwear aesthetic that transitions effortlessly from lounge to lifestyle.\r\n\r\nMade with premium cotton fabric and a tapered structure, they offer an active feel while maintaining a polished, everyday look.\r\n\r\n✦ Key Details\r\n\r\nFabric: 100% premium cotton, 260 GSM — soft, breathable & durable\r\n\r\nFit: Unisex jogger fit with ribbed waistband and cuffs\r\n\r\nDesign: Minimal signature logo for a clean, premium identity\r\n\r\nComfort: Relaxed cut with tapered legs for modern styling\r\n\r\nUtility: Perfect for daily wear, travel, gym warm-ups & casual outings\r\n\r\n✦ Care Instructions\r\n\r\nWash inside-out in cold water\r\nDo not bleach\r\nDry on low heat\r\nIron inside-out only\r\n\r\n✨ VÉNARO represents understated luxury — made to move, built to last.', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(50, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'VN-6997435909E61', 'v-naro-signature-joggers-elevated-comfort-edition-3', '', 'Designed for movement. Defined by minimal luxury.\r\nThe VÉNARO Signature Joggers are crafted for those who value comfort without compromising on style. Featuring a clean silhouette with subtle branding, these joggers deliver a refined streetwear aesthetic that transitions effortlessly from lounge to lifestyle.\r\n\r\nMade with premium cotton fabric and a tapered structure, they offer an active feel while maintaining a polished, everyday look.\r\n\r\n✦ Key Details\r\n\r\nFabric: 100% premium cotton, 260 GSM — soft, breathable & durable\r\n\r\nFit: Unisex jogger fit with ribbed waistband and cuffs\r\n\r\nDesign: Minimal signature logo for a clean, premium identity\r\n\r\nComfort: Relaxed cut with tapered legs for modern styling\r\n\r\nUtility: Perfect for daily wear, travel, gym warm-ups & casual outings\r\n\r\n✦ Care Instructions\r\n\r\nWash inside-out in cold water\r\nDo not bleach\r\nDry on low heat\r\nIron inside-out only\r\n\r\n✨ VÉNARO represents understated luxury — made to move, built to last.', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(51, 'VÉNARO Signature Joggers – Elevated Comfort Edition', 'VN-69974378C3D79', 'v-naro-signature-joggers-elevated-comfort-edition-4', '', 'Designed for movement. Defined by minimal luxury.\r\nThe VÉNARO Signature Joggers are crafted for those who value comfort without compromising on style. Featuring a clean silhouette with subtle branding, these joggers deliver a refined streetwear aesthetic that transitions effortlessly from lounge to lifestyle.\r\n\r\nMade with premium cotton fabric and a tapered structure, they offer an active feel while maintaining a polished, everyday look.\r\n\r\n✦ Key Details\r\n\r\nFabric: 100% premium cotton, 260 GSM — soft, breathable & durable\r\n\r\nFit: Unisex jogger fit with ribbed waistband and cuffs\r\n\r\nDesign: Minimal signature logo for a clean, premium identity\r\n\r\nComfort: Relaxed cut with tapered legs for modern styling\r\n\r\nUtility: Perfect for daily wear, travel, gym warm-ups & casual outings\r\n\r\n✦ Care Instructions\r\n\r\nWash inside-out in cold water\r\nDo not bleach\r\nDry on low heat\r\nIron inside-out only\r\n\r\n✨ VÉNARO represents understated luxury — made to move, built to last.', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 3, '2026-02-19 17:08:08', '2026-02-20 10:36:46'),
(52, 'VÉNARO Essential Terry Lounge Pants – White', 'VN-69974823D00AE', 'v-naro-essential-terry-lounge-pants-white', '', 'Minimal, versatile, and built for everyday comfort, the VÉNARO Essential Terry Lounge Pants in black are a wardrobe staple designed for effortless wear. Made from premium terry fabric with a soft looped interior, these pants offer breathability, durability, and a relaxed fit that moves with you. Finished with subtle VÉNARO branding, they strike the perfect balance between comfort and understated style.\r\n\r\nFabric: 260 GSM terry fabric crafted from 90% cotton and 10% polyester for softness, durability, and breathability\r\nFit: Relaxed loose fit for unrestricted movement and all-day comfort\r\nDesign: Looped inner texture for a cozy, skin-friendly feel\r\nDetails: Elasticated waistband with adjustable drawstring and two functional front pockets\r\nPerfect For: Casual outings, workouts, travel, or laid-back days at home\r\nCare: Machine wash cold, tumble dry low to maintain shape and finish', 1499.00, 1099.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:28:03', '2026-02-19 17:28:17'),
(53, 'VÉNARO Essential Terry Lounge Pants – Black', 'VN-6997485FE225D', 'v-naro-essential-terry-lounge-pants-black', '', 'Minimal, versatile, and built for everyday comfort, the VÉNARO Essential Terry Lounge Pants in black are a wardrobe staple designed for effortless wear. Made from premium terry fabric with a soft looped interior, these pants offer breathability, durability, and a relaxed fit that moves with you. Finished with subtle VÉNARO branding, they strike the perfect balance between comfort and understated style.\r\n\r\nFabric: 260 GSM terry fabric crafted from 90% cotton and 10% polyester for softness, durability, and breathability\r\nFit: Relaxed loose fit for unrestricted movement and all-day comfort\r\nDesign: Looped inner texture for a cozy, skin-friendly feel\r\nDetails: Elasticated waistband with adjustable drawstring and two functional front pockets\r\nPerfect For: Casual outings, workouts, travel, or laid-back days at home\r\nCare: Machine wash cold, tumble dry low to maintain shape and finish', 1499.00, 1099.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(54, 'VÉNARO Lion Geometry Graphic T-Shirt – White (Relaxed Fit)', 'VN-6997522010501', 'v-naro-lion-geometry-graphic-t-shirt-white-relaxed-fit', '', 'Make a bold statement with the VÉNARO Lion Geometry Graphic T-Shirt, where strength meets minimal design. Featuring a powerful lion illustration blended with sharp geometric line art on the back and a clean VÉNARO logo on the front, this tee is crafted for those who carry confidence without noise.\r\n\r\nMade from 100% Supima cotton (160 GSM), the fabric feels luxuriously soft, breathable, and lightweight—perfect for all-day wear. The relaxed unisex fit offers effortless comfort with a modern silhouette, making it ideal for streetwear styling or everyday casual looks.\r\n\r\nDetails:\r\n\r\nFabric: 100% Supima Cotton, 160 GSM\r\n\r\nFit: Unisex relaxed fit\r\n\r\nDesign: Geometric lion back print, minimal front logo\r\n\r\nFeel: Soft, breathable, premium finish\r\n\r\nCare: Wash inside-out in cold water, dry on low heat, iron inside-out\r\n\r\nStyle it your way—pair it with cargos, denim, or layered fits for a clean yet powerful VÉNARO vibe.', 1399.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(55, 'VÉNARO Moon Warrior Graphic T-Shirt | Relaxed Fit | Premium Cotton', 'VN-699752C33FE26', 'v-naro-moon-warrior-graphic-t-shirt-relaxed-fit-premium-cotton', '', 'Inspired by legends and destiny, the VÉNARO Moon Warrior T-shirt features a dramatic moonlit battle graphic that speaks of courage and purpose. A perfect blend of storytelling art and minimal luxury streetwear.\r\n\r\nDetails:\r\n\r\nFabric: 100% Supima Cotton, 160 GSM\r\n\r\nFit: Relaxed unisex fit\r\n\r\nDesign: Artistic moon & warrior back print\r\n\r\nStyle: Dark, cinematic streetwear\r\n\r\nCare: Gentle wash, low heat dry', 1399.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 18:13:23', '2026-02-19 18:13:23'),
(56, 'VÉNARO Tiger Roar Graphic T-Shirt – Relaxed Fit, Supima Cotton', 'VN-699752FFA46BE', 'v-naro-tiger-roar-graphic-t-shirt-relaxed-fit-supima-cotton', '', 'Unleash raw confidence with the VÉNARO Tiger Roar Graphic T-Shirt. Featuring a bold roaring tiger artwork on the back with layered VÉNARO typography, this tee is designed to represent strength, dominance, and fearless attitude. The clean VÉNARO logo on the front keeps it minimal from the front and powerful from the back.\r\n\r\nCrafted from 100% Supima cotton (160 GSM), this t-shirt delivers a premium hand feel—soft, breathable, and durable enough for everyday wear. The relaxed unisex fit offers effortless comfort and a modern streetwear silhouette, perfect for styling solo or layering.\r\n\r\nFabric:\r\n• 100% Supima Cotton\r\n• 160 GSM – lightweight, soft, and breathable\r\n\r\nFit:\r\n• Relaxed fit\r\n• Unisex silhouette for all-day comfort\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Iron inside-out only\r\n\r\nStyle Tip:\r\nPair it with black cargos, denim, or joggers for a strong street-ready look.', 1399.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(57, 'VÉNARO “VINARO – Explore the Changes” Relaxed Fit Graphic T-Shirt', 'VN-69975350A7612', 'v-naro-vinaro-explore-the-changes-relaxed-fit-graphic-t-shirt', '', 'Make a bold statement with the VÉNARO VINARO – Explore the Changes T-shirt. Designed for everyday wear with a modern streetwear edge, this tee features a minimal VÉNARO logo on the front and a striking typographic graphic on the back that represents growth, movement, and evolution.\r\n\r\nCrafted from 100% Supima cotton (160 GSM), the fabric feels ultra-soft, breathable, and smooth on the skin—perfect for all-day comfort. The relaxed unisex fit offers an effortless silhouette that works for casual outings, layering, or daily wear.\r\n\r\nKey Features:\r\n\r\nPremium 100% Supima cotton for a luxury feel\r\n\r\n160 GSM lightweight yet durable fabric\r\n\r\nRelaxed fit for a comfortable, modern look\r\n\r\nMinimal front branding with bold back graphic\r\n\r\nUnisex design, suitable for everyday styling\r\n\r\nCare Instructions:\r\nWash inside-out in cold water. Tumble dry on low heat. Iron inside-out to protect the print.', 1399.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(58, 'VÉNARO “Chase Your Dreams” Relaxed Fit T-Shirt', 'VN-6997537D7266E', 'v-naro-chase-your-dreams-relaxed-fit-t-shirt', '', 'Make a bold statement with the VÉNARO Chase Your Dreams T-shirt—designed for those who move with purpose and confidence. Featuring a powerful phoenix-inspired back graphic symbolizing ambition and rebirth, paired with a minimal VÉNARO logo on the front, this tee blends streetwear attitude with premium comfort.\r\n\r\nFabric:\r\nMade from 100% Supima Cotton (160 GSM) – ultra-soft, breathable, and smooth with a luxury hand-feel for all-day wear.\r\n\r\nFit:\r\nUnisex relaxed fit – easy on the body with a clean silhouette, perfect for everyday streetwear styling.\r\n\r\nDesign:\r\n\r\nStatement back print: “Chase Your Dreams” with dynamic phoenix artwork\r\n\r\nMinimal front branding for a balanced, premium look\r\n\r\nComfort & Wear:\r\nLightweight yet durable, ideal for daily wear, layering, or casual outings.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water. Dry on low heat. Flip inside-out before ironing to preserve print quality.', 1399.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(59, 'VÉNARO Tie-Dye Oversized T-Shirt | 260 GSM Premium Cotton | Relaxed Unisex Fit', 'VN-6997C9F43C0BF', 'v-naro-tie-dye-oversized-t-shirt-260-gsm-premium-cotton-relaxed-unisex-fit', '', '\r\n✅ Product Description\r\n\r\nUpgrade your everyday streetwear with the VÉNARO Tie-Dye Oversized T-Shirt, designed for comfort, individuality, and effortless style.\r\n\r\nFabric:\r\nCrafted from a premium 260 GSM blend of 90% cotton and 10% polyester, this tee offers a soft, breathable feel with enough structure to maintain its oversized silhouette all day.\r\n\r\nFit:\r\nDesigned in a relaxed unisex oversized fit, providing extra room through the body and sleeves for a laid-back, modern streetwear look.\r\n\r\nDesign:\r\nEach t-shirt features a unique tie-dye pattern, making every piece one-of-a-kind. The subtle VÉNARO branding keeps the look clean while the dye work adds bold personality.\r\n\r\nStyle & Use:\r\nPerfect for casual wear, streetwear styling, travel, lounging, or layering. Pair it with cargos, denim, or joggers for a complete fit.\r\n\r\nCare Instructions:\r\n• Wash separately in cold water\r\n• Tumble dry low\r\n• Do not bleach\r\n• Wash inside-out to preserve dye quality', 1499.00, 1299.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:41:56', '2026-02-20 02:43:06'),
(60, 'VÉNARO Tie-Dye Oversized T-Shirt | 260 GSM Premium Cotton | Relaxed Unisex Fit', 'VN-6997CA2498B74', 'v-naro-tie-dye-oversized-t-shirt-260-gsm-premium-cotton-relaxed-unisex-fit-1', '', 'Upgrade your everyday streetwear with the VÉNARO Tie-Dye Oversized T-Shirt, designed for comfort, individuality, and effortless style.\r\n\r\nFabric:\r\nCrafted from a premium 260 GSM blend of 90% cotton and 10% polyester, this tee offers a soft, breathable feel with enough structure to maintain its oversized silhouette all day.\r\n\r\nFit:\r\nDesigned in a relaxed unisex oversized fit, providing extra room through the body and sleeves for a laid-back, modern streetwear look.\r\n\r\nDesign:\r\nEach t-shirt features a unique tie-dye pattern, making every piece one-of-a-kind. The subtle VÉNARO branding keeps the look clean while the dye work adds bold personality.\r\n\r\nStyle & Use:\r\nPerfect for casual wear, streetwear styling, travel, lounging, or layering. Pair it with cargos, denim, or joggers for a complete fit.\r\n\r\nCare Instructions:\r\n• Wash separately in cold water\r\n• Tumble dry low\r\n• Do not bleach\r\n• Wash inside-out to preserve dye quality', 1499.00, 1299.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:42:44', '2026-02-20 02:42:44'),
(61, 'VÉNARO Tie-Dye Oversized T-Shirt | 260 GSM Premium Cotton | Relaxed Unisex Fit', 'VN-6997CA7329010', 'v-naro-tie-dye-oversized-t-shirt-260-gsm-premium-cotton-relaxed-unisex-fit-2', '', 'Upgrade your everyday streetwear with the VÉNARO Tie-Dye Oversized T-Shirt, designed for comfort, individuality, and effortless style.\r\n\r\nFabric:\r\nCrafted from a premium 260 GSM blend of 90% cotton and 10% polyester, this tee offers a soft, breathable feel with enough structure to maintain its oversized silhouette all day.\r\n\r\nFit:\r\nDesigned in a relaxed unisex oversized fit, providing extra room through the body and sleeves for a laid-back, modern streetwear look.\r\n\r\nDesign:\r\nEach t-shirt features a unique tie-dye pattern, making every piece one-of-a-kind. The subtle VÉNARO branding keeps the look clean while the dye work adds bold personality.\r\n\r\nStyle & Use:\r\nPerfect for casual wear, streetwear styling, travel, lounging, or layering. Pair it with cargos, denim, or joggers for a complete fit.\r\n\r\nCare Instructions:\r\n• Wash separately in cold water\r\n• Tumble dry low\r\n• Do not bleach\r\n• Wash inside-out to preserve dye quality', 1499.00, 1299.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(62, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'VN-6997CB1A85C3D', 'v-naro-acid-wash-oversized-t-shirt-240-gsm-heavyweight-cotton-streetwear-essential', '', 'Fabric:\r\nCrafted from 100% premium cotton, this 240 GSM heavyweight fabric offers durability with breathable comfort. The acid-washed finish gives a naturally worn-in, vintage feel while remaining soft on the skin.\r\n\r\nFit:\r\nDesigned in a unisex oversized fit, featuring relaxed shoulders and roomy sleeves for a bold, streetwear-ready silhouette. Perfect for everyday wear with effortless drape.\r\n\r\nDesign:\r\nFinished with a unique acid wash treatment, each piece has subtle variations, making every t-shirt one-of-a-kind. Minimal VÉNARO branding adds a clean, premium touch.\r\n\r\nStyle & Use:\r\nIdeal for streetwear looks, casual outings, layering, or statement everyday wear. Pairs effortlessly with cargos, denim, or joggers.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nDo not bleach.\r\nIron inside-out for longevity.', 1299.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(63, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'VN-6997CB25BED46', 'v-naro-acid-wash-oversized-t-shirt-240-gsm-heavyweight-cotton-streetwear-essential-1', '', 'Fabric:\r\nCrafted from 100% premium cotton, this 240 GSM heavyweight fabric offers durability with breathable comfort. The acid-washed finish gives a naturally worn-in, vintage feel while remaining soft on the skin.\r\n\r\nFit:\r\nDesigned in a unisex oversized fit, featuring relaxed shoulders and roomy sleeves for a bold, streetwear-ready silhouette. Perfect for everyday wear with effortless drape.\r\n\r\nDesign:\r\nFinished with a unique acid wash treatment, each piece has subtle variations, making every t-shirt one-of-a-kind. Minimal VÉNARO branding adds a clean, premium touch.\r\n\r\nStyle & Use:\r\nIdeal for streetwear looks, casual outings, layering, or statement everyday wear. Pairs effortlessly with cargos, denim, or joggers.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nDo not bleach.\r\nIron inside-out for longevity.', 1299.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:47:01', '2026-02-20 02:47:01'),
(64, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'VN-6997CB2FEAE28', 'v-naro-acid-wash-oversized-t-shirt-240-gsm-heavyweight-cotton-streetwear-essential-2', '', 'Fabric:\r\nCrafted from 100% premium cotton, this 240 GSM heavyweight fabric offers durability with breathable comfort. The acid-washed finish gives a naturally worn-in, vintage feel while remaining soft on the skin.\r\n\r\nFit:\r\nDesigned in a unisex oversized fit, featuring relaxed shoulders and roomy sleeves for a bold, streetwear-ready silhouette. Perfect for everyday wear with effortless drape.\r\n\r\nDesign:\r\nFinished with a unique acid wash treatment, each piece has subtle variations, making every t-shirt one-of-a-kind. Minimal VÉNARO branding adds a clean, premium touch.\r\n\r\nStyle & Use:\r\nIdeal for streetwear looks, casual outings, layering, or statement everyday wear. Pairs effortlessly with cargos, denim, or joggers.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nDo not bleach.\r\nIron inside-out for longevity.', 1299.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-20 02:47:11', '2026-02-25 09:43:00'),
(65, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'VN-6997CB37A5E29', 'v-naro-acid-wash-oversized-t-shirt-240-gsm-heavyweight-cotton-streetwear-essential-3', '', 'Fabric:\r\nCrafted from 100% premium cotton, this 240 GSM heavyweight fabric offers durability with breathable comfort. The acid-washed finish gives a naturally worn-in, vintage feel while remaining soft on the skin.\r\n\r\nFit:\r\nDesigned in a unisex oversized fit, featuring relaxed shoulders and roomy sleeves for a bold, streetwear-ready silhouette. Perfect for everyday wear with effortless drape.\r\n\r\nDesign:\r\nFinished with a unique acid wash treatment, each piece has subtle variations, making every t-shirt one-of-a-kind. Minimal VÉNARO branding adds a clean, premium touch.\r\n\r\nStyle & Use:\r\nIdeal for streetwear looks, casual outings, layering, or statement everyday wear. Pairs effortlessly with cargos, denim, or joggers.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nDo not bleach.\r\nIron inside-out for longevity.', 1299.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 02:47:19', '2026-02-20 02:47:19'),
(66, 'VÉNARO Acid Wash Oversized T-Shirt | 240 GSM Heavyweight Cotton | Streetwear Essential', 'VN-6997CB40F2652', 'v-naro-acid-wash-oversized-t-shirt-240-gsm-heavyweight-cotton-streetwear-essential-4', '', 'Fabric:\r\nCrafted from 100% premium cotton, this 240 GSM heavyweight fabric offers durability with breathable comfort. The acid-washed finish gives a naturally worn-in, vintage feel while remaining soft on the skin.\r\n\r\nFit:\r\nDesigned in a unisex oversized fit, featuring relaxed shoulders and roomy sleeves for a bold, streetwear-ready silhouette. Perfect for everyday wear with effortless drape.\r\n\r\nDesign:\r\nFinished with a unique acid wash treatment, each piece has subtle variations, making every t-shirt one-of-a-kind. Minimal VÉNARO branding adds a clean, premium touch.\r\n\r\nStyle & Use:\r\nIdeal for streetwear looks, casual outings, layering, or statement everyday wear. Pairs effortlessly with cargos, denim, or joggers.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nDo not bleach.\r\nIron inside-out for longevity.', 1299.00, 999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 5, '2026-02-20 02:47:28', '2026-02-24 06:39:03'),
(67, 'VÉNARO Classic Raglan Full Sleeve T-Shirt | 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D1E3E7329', 'v-naro-classic-raglan-full-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit', '', 'Minimal contrast. Maximum style.\r\nVÉNARO Raglan Full Sleeve T-Shirt timeless sporty aesthetics ko everyday comfort ke saath blend karta hai. Contrast sleeves aur clean silhouette is tee ko casual aur streetwear dono ke liye perfect banate hain.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM), bio-washed for a soft, smooth feel and long-lasting durability. Lightweight yet comfortable for all-day wear.\r\n\r\nFit:\r\nClassic unisex raglan fit with relaxed shoulders and contrast sleeves — sporty, breathable, and easy to style.\r\n\r\nDesign:\r\nSignature raglan construction with color-blocked sleeves and minimal VÉNARO branding for a clean, versatile look.\r\n\r\nPerfect For:\r\nCasual outings, daily wear, layering, travel, or relaxed streetwear styling.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry low.\r\nDo not bleach.\r\nIron inside-out if required.', 699.00, 499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:15:47', '2026-02-20 03:15:47');
INSERT INTO `products` (`product_id`, `product_name`, `sku`, `slug`, `short_description`, `long_description`, `regular_price`, `sale_price`, `sale_start_date`, `sale_end_date`, `tax_rate`, `weight`, `length`, `width`, `height`, `fabric_composition`, `gsm_weight`, `care_instructions`, `track_inventory`, `stock_quantity`, `low_stock_threshold`, `allow_backorders`, `stock_status`, `status`, `featured`, `views`, `created_at`, `updated_at`) VALUES
(68, 'VÉNARO Classic Raglan Full Sleeve T-Shirt | 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D1F79B080', 'v-naro-classic-raglan-full-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-1', '', 'Minimal contrast. Maximum style.\r\nVÉNARO Raglan Full Sleeve T-Shirt timeless sporty aesthetics ko everyday comfort ke saath blend karta hai. Contrast sleeves aur clean silhouette is tee ko casual aur streetwear dono ke liye perfect banate hain.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM), bio-washed for a soft, smooth feel and long-lasting durability. Lightweight yet comfortable for all-day wear.\r\n\r\nFit:\r\nClassic unisex raglan fit with relaxed shoulders and contrast sleeves — sporty, breathable, and easy to style.\r\n\r\nDesign:\r\nSignature raglan construction with color-blocked sleeves and minimal VÉNARO branding for a clean, versatile look.\r\n\r\nPerfect For:\r\nCasual outings, daily wear, layering, travel, or relaxed streetwear styling.\r\n\r\nCare Instructions:\r\nWash inside-out in cold water.\r\nTumble dry low.\r\nDo not bleach.\r\nIron inside-out if required.', 699.00, 499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:16:07', '2026-02-20 03:16:07'),
(69, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D2C235AEF', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(70, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D2CE5AE8E', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-1', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:19:42', '2026-02-20 03:19:42'),
(71, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D2DB8BC16', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-2', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:19:55', '2026-02-20 03:19:55'),
(72, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D2E67AD27', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-3', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:20:06', '2026-02-20 03:20:06'),
(73, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D2F45A54F', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-4', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-20 03:20:20', '2026-02-25 09:43:27'),
(74, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D3001D81A', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-5', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:20:32', '2026-02-20 03:20:32'),
(75, 'VÉNARO Essential Long Sleeve T-Shirt – 100% Cotton | 180 GSM | Unisex Fit', 'VN-6997D309828D5', 'v-naro-essential-long-sleeve-t-shirt-100-cotton-180-gsm-unisex-fit-6', '', 'Minimal. Timeless. Everyday essential.\r\nVÉNARO Essential Long Sleeve T-Shirt clean design aur all-season comfort ke liye banayi gayi hai. Front par subtle VÉNARO branding aur smooth cotton finish isse casual se streetwear tak perfect banati hai.\r\n\r\nFabric:\r\nMade from 100% premium cotton (180 GSM) — soft, breathable aur lightweight, jo daily wear ke liye ideal hai.\r\n\r\nFit:\r\nUnisex regular fit with full sleeves — clean silhouette, relaxed feel aur sharp look ke liye balanced cut.\r\n\r\nDesign:\r\nMinimal branding with solid colors — easy to style, versatile aur timeless wardrobe staple.\r\n\r\nUse:\r\nPerfect for everyday wear, layering, casual outings, travel ya light streetwear styling.\r\n\r\nCare Instructions:\r\n• Wash inside-out in cold water\r\n• Tumble dry on low heat\r\n• Flip inside-out before ironing', 999.00, 799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 03:20:41', '2026-02-20 03:20:41'),
(76, 'VÉNARO Essential Pullover Hoodie – 260 GSM Cotton Blend | Regular Fit', 'VN-699823575C7F4', 'v-naro-essential-pullover-hoodie-260-gsm-cotton-blend-regular-fit', '', 'Elevate your everyday style with the VÉNARO Essential Pullover Hoodie, crafted for comfort, durability, and a clean streetwear aesthetic.\r\n\r\nFabric: Premium 90% cotton, 10% polyester blend in 260 GSM terry fabric for a soft, breathable feel with excellent shape retention.\r\nFit: Regular fit with ribbed cuffs and hem, offering a comfortable, structured silhouette for daily wear.\r\nDesign: Minimal VÉNARO chest branding with a classic pullover hoodie design and a functional kangaroo pocket for practicality and style.\r\nComfort: Smooth inner terry finish keeps you comfortable across seasons—ideal for casual wear, travel, or relaxed days.\r\nCare: Wash inside-out in cold water. Tumble dry low. Turn inside out before ironing to maintain fabric quality.', 1999.00, 1799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(77, 'VÉNARO Essential Pullover Hoodie – 260 GSM Cotton Blend | Regular Fit', 'VN-6998236022110', 'v-naro-essential-pullover-hoodie-260-gsm-cotton-blend-regular-fit-1', '', 'Elevate your everyday style with the VÉNARO Essential Pullover Hoodie, crafted for comfort, durability, and a clean streetwear aesthetic.\r\n\r\nFabric: Premium 90% cotton, 10% polyester blend in 260 GSM terry fabric for a soft, breathable feel with excellent shape retention.\r\nFit: Regular fit with ribbed cuffs and hem, offering a comfortable, structured silhouette for daily wear.\r\nDesign: Minimal VÉNARO chest branding with a classic pullover hoodie design and a functional kangaroo pocket for practicality and style.\r\nComfort: Smooth inner terry finish keeps you comfortable across seasons—ideal for casual wear, travel, or relaxed days.\r\nCare: Wash inside-out in cold water. Tumble dry low. Turn inside out before ironing to maintain fabric quality.', 1999.00, 1799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:03:28', '2026-02-20 09:03:28'),
(78, 'VÉNARO Essential Pullover Hoodie – 260 GSM Cotton Blend | Regular Fit', 'VN-699823685F5E6', 'v-naro-essential-pullover-hoodie-260-gsm-cotton-blend-regular-fit-2', '', 'Elevate your everyday style with the VÉNARO Essential Pullover Hoodie, crafted for comfort, durability, and a clean streetwear aesthetic.\r\n\r\nFabric: Premium 90% cotton, 10% polyester blend in 260 GSM terry fabric for a soft, breathable feel with excellent shape retention.\r\nFit: Regular fit with ribbed cuffs and hem, offering a comfortable, structured silhouette for daily wear.\r\nDesign: Minimal VÉNARO chest branding with a classic pullover hoodie design and a functional kangaroo pocket for practicality and style.\r\nComfort: Smooth inner terry finish keeps you comfortable across seasons—ideal for casual wear, travel, or relaxed days.\r\nCare: Wash inside-out in cold water. Tumble dry low. Turn inside out before ironing to maintain fabric quality.', 1999.00, 1799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:03:36', '2026-02-20 09:03:36'),
(79, 'VÉNARO Essential Pullover Hoodie – 260 GSM Cotton Blend | Regular Fit', 'VN-6998237299342', 'v-naro-essential-pullover-hoodie-260-gsm-cotton-blend-regular-fit-3', '', 'Elevate your everyday style with the VÉNARO Essential Pullover Hoodie, crafted for comfort, durability, and a clean streetwear aesthetic.\r\n\r\nFabric: Premium 90% cotton, 10% polyester blend in 260 GSM terry fabric for a soft, breathable feel with excellent shape retention.\r\nFit: Regular fit with ribbed cuffs and hem, offering a comfortable, structured silhouette for daily wear.\r\nDesign: Minimal VÉNARO chest branding with a classic pullover hoodie design and a functional kangaroo pocket for practicality and style.\r\nComfort: Smooth inner terry finish keeps you comfortable across seasons—ideal for casual wear, travel, or relaxed days.\r\nCare: Wash inside-out in cold water. Tumble dry low. Turn inside out before ironing to maintain fabric quality.', 1999.00, 1799.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:03:46', '2026-02-20 09:03:46'),
(80, 'VÉNARO Core Hoodie', 'VN-69982BBB3F456', 'v-naro-core-hoodie', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(81, 'VÉNARO Core Hoodie', 'VN-69982BCDC15F4', 'v-naro-core-hoodie-1', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:39:25', '2026-02-20 09:39:25'),
(82, 'VÉNARO Core Hoodie', 'VN-69982BE177517', 'v-naro-core-hoodie-2', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:39:45', '2026-02-20 09:39:45'),
(83, 'VÉNARO Core Hoodie', 'VN-69982BEBB3FCA', 'v-naro-core-hoodie-3', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:39:55', '2026-02-20 09:39:55'),
(84, 'VÉNARO Core Hoodie', 'VN-69982BF7E9E3F', 'v-naro-core-hoodie-4', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:40:07', '2026-02-20 09:40:07'),
(85, 'VÉNARO Core Hoodie', 'VN-69982C0505A99', 'v-naro-core-hoodie-5', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:40:21', '2026-02-20 09:40:21'),
(86, 'VÉNARO Core Hoodie', 'VN-69982C10A501B', 'v-naro-core-hoodie-6', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-20 09:40:32', '2026-02-21 06:50:19'),
(87, 'VÉNARO Core Hoodie', 'VN-69982C1814E39', 'v-naro-core-hoodie-7', '', 'Built for everyday warmth and effortless style, the VÉNARO Core Pullover Hoodie delivers a clean, premium look with all-day comfort. Crafted from heavyweight brushed fleece, this hoodie is soft on the skin while keeping you warm without feeling bulky. The minimalist VÉNARO branding adds a refined streetwear edge, making it perfect for casual wear or layering in colder weather.\r\n\r\nFabric\r\n\r\n100% Cotton, 300 GSM brushed fleece – heavyweight, soft, and cozy for chilly days.\r\n\r\nFit\r\n\r\nRegular fit with a structured silhouette. Snug around cuffs and hem for a comfortable, relaxed feel.\r\n\r\nDesign\r\n\r\nPullover hoodie with an adjustable hood, kangaroo pocket, and subtle VÉNARO logo for a clean, premium aesthetic.\r\n\r\nCare\r\n\r\nWash inside-out in cold water.\r\nTumble dry on low heat.\r\nFlip inside out before ironing.', 2499.00, 1999.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:40:40', '2026-02-20 09:40:40'),
(88, 'VÉNARO Phoenix Oversized Hoodie | 400 GSM Heavyweight', 'VN-69982C54A80D2', 'v-naro-phoenix-oversized-hoodie-400-gsm-heavyweight', '', 'Fabric:\r\nCrafted from a premium blend of 90% cotton & 10% polyester, this 400 GSM heavyweight fabric delivers exceptional warmth, softness, and durability—perfect for cold-weather comfort without feeling bulky.\r\n\r\nFit:\r\nDesigned specifically for men, this oversized pullover hoodie features dropped shoulders and a relaxed silhouette for a bold, modern streetwear look.\r\n\r\nDesign:\r\nMinimal VÉNARO branding on the front paired with a powerful phoenix graphic on the back, symbolizing strength, rebirth, and fearless energy.\r\n\r\nComfort & Use:\r\nIdeal for winter layering, casual outings, travel, or everyday street style—built to keep you warm while standing out.\r\n\r\nCare:\r\nWash inside-out in cold water.\r\nDry on low heat.\r\nFlip inside-out before ironing to preserve fabric and print quality.', 2699.00, 2499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 3, '2026-02-20 09:41:40', '2026-02-20 10:36:40'),
(89, 'VÉNARO Acid Washed Hoodie', 'VN-69982E38AE448', 'v-naro-acid-washed-hoodie', '', 'Fabric:\r\n100% cotton, 300 GSM brushed fleece with a lived-in acid wash finish. Heavyweight, warm, and comfortable for everyday wear.\r\n\r\nFit:\r\nRelaxed silhouette with dropped shoulders and a spacious hood, delivering a bold and effortless streetwear look.\r\n\r\nDesign:\r\nDistinct acid wash treatment creates subtle variations on every piece, making each hoodie truly unique. Minimal VÉNARO branding keeps the aesthetic clean and premium.\r\n\r\nStyle & Use:\r\nIdeal for casual wear, street styling, and layering during cooler days. Designed to stand out without trying too hard.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Do not bleach. Flip inside out before ironing.', 2999.00, 2499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(90, 'VÉNARO Acid Washed Hoodie', 'VN-69982E889948C', 'v-naro-acid-washed-hoodie-1', '', 'Fabric:\r\n100% cotton, 300 GSM brushed fleece with a lived-in acid wash finish. Heavyweight, warm, and comfortable for everyday wear.\r\n\r\nFit:\r\nRelaxed silhouette with dropped shoulders and a spacious hood, delivering a bold and effortless streetwear look.\r\n\r\nDesign:\r\nDistinct acid wash treatment creates subtle variations on every piece, making each hoodie truly unique. Minimal VÉNARO branding keeps the aesthetic clean and premium.\r\n\r\nStyle & Use:\r\nIdeal for casual wear, street styling, and layering during cooler days. Designed to stand out without trying too hard.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Do not bleach. Flip inside out before ironing.', 2999.00, 2499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:51:04', '2026-02-20 09:51:04'),
(91, 'VÉNARO Acid Washed Hoodie', 'VN-69982E90CE5B4', 'v-naro-acid-washed-hoodie-2', '', 'Fabric:\r\n100% cotton, 300 GSM brushed fleece with a lived-in acid wash finish. Heavyweight, warm, and comfortable for everyday wear.\r\n\r\nFit:\r\nRelaxed silhouette with dropped shoulders and a spacious hood, delivering a bold and effortless streetwear look.\r\n\r\nDesign:\r\nDistinct acid wash treatment creates subtle variations on every piece, making each hoodie truly unique. Minimal VÉNARO branding keeps the aesthetic clean and premium.\r\n\r\nStyle & Use:\r\nIdeal for casual wear, street styling, and layering during cooler days. Designed to stand out without trying too hard.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Do not bleach. Flip inside out before ironing.', 2999.00, 2499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:51:12', '2026-02-20 09:51:12'),
(92, 'VÉNARO Acid Washed Hoodie', 'VN-69982E978BBB8', 'v-naro-acid-washed-hoodie-3', '', 'Fabric:\r\n100% cotton, 300 GSM brushed fleece with a lived-in acid wash finish. Heavyweight, warm, and comfortable for everyday wear.\r\n\r\nFit:\r\nRelaxed silhouette with dropped shoulders and a spacious hood, delivering a bold and effortless streetwear look.\r\n\r\nDesign:\r\nDistinct acid wash treatment creates subtle variations on every piece, making each hoodie truly unique. Minimal VÉNARO branding keeps the aesthetic clean and premium.\r\n\r\nStyle & Use:\r\nIdeal for casual wear, street styling, and layering during cooler days. Designed to stand out without trying too hard.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Do not bleach. Flip inside out before ironing.', 2999.00, 2499.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 09:51:19', '2026-02-20 09:51:19'),
(93, 'VÉNARO 300 GSM Classic Winter Sweatshirt', 'VN-699830BB76828', 'v-naro-300-gsm-classic-winter-sweatshirt', '', 'Fabric:\r\nCrafted from a premium blend of 90% cotton and 10% polyester, featuring 300 GSM fleece for a soft, warm, and breathable feel suitable for daily wear.\r\n\r\nFit:\r\nDesigned with a relaxed silhouette, offering balanced structure with ribbed cuffs and hem for a clean, comfortable finish.\r\n\r\nDesign:\r\nMinimal front branding with a timeless aesthetic that pairs effortlessly with casual and street-ready outfits.\r\n\r\nComfort:\r\nThick yet breathable fabric keeps you warm without feeling heavy, making it ideal for all-day wear.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Turn inside-out before ironing to maintain fabric quality and finish.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(94, 'VÉNARO 300 GSM Classic Winter Sweatshirt', 'VN-699830C4796F6', 'v-naro-300-gsm-classic-winter-sweatshirt-1', '', 'Fabric:\r\nCrafted from a premium blend of 90% cotton and 10% polyester, featuring 300 GSM fleece for a soft, warm, and breathable feel suitable for daily wear.\r\n\r\nFit:\r\nDesigned with a relaxed silhouette, offering balanced structure with ribbed cuffs and hem for a clean, comfortable finish.\r\n\r\nDesign:\r\nMinimal front branding with a timeless aesthetic that pairs effortlessly with casual and street-ready outfits.\r\n\r\nComfort:\r\nThick yet breathable fabric keeps you warm without feeling heavy, making it ideal for all-day wear.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Turn inside-out before ironing to maintain fabric quality and finish.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:00:36', '2026-02-20 10:00:36'),
(95, 'VÉNARO 300 GSM Classic Winter Sweatshirt', 'VN-699830CC48277', 'v-naro-300-gsm-classic-winter-sweatshirt-2', '', 'Fabric:\r\nCrafted from a premium blend of 90% cotton and 10% polyester, featuring 300 GSM fleece for a soft, warm, and breathable feel suitable for daily wear.\r\n\r\nFit:\r\nDesigned with a relaxed silhouette, offering balanced structure with ribbed cuffs and hem for a clean, comfortable finish.\r\n\r\nDesign:\r\nMinimal front branding with a timeless aesthetic that pairs effortlessly with casual and street-ready outfits.\r\n\r\nComfort:\r\nThick yet breathable fabric keeps you warm without feeling heavy, making it ideal for all-day wear.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Turn inside-out before ironing to maintain fabric quality and finish.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:00:44', '2026-02-20 10:00:44'),
(96, 'Eagle Graphic Oversized Sweatshirt – 400 GSM Heavyweight', 'VN-699833DADA269', 'eagle-graphic-oversized-sweatshirt-400-gsm-heavyweight', '', 'Fabric:\r\nCrafted from a high-quality cotton–polyester blend with 400 GSM heavyweight fleece, offering a soft touch, durability, and warmth suitable for everyday wear.\r\n\r\nFit:\r\nDesigned with an oversized silhouette that delivers a relaxed, modern drape. Ribbed cuffs and hem provide structure while maintaining comfort and ease of movement.\r\n\r\nDesign:\r\nFeatures a striking eagle graphic on the back, symbolizing power, vision, and freedom. The detailed artwork adds a bold statement while keeping the front minimal and clean.\r\n\r\nComfort & Wear:\r\nIdeal for casual styling, streetwear looks, or layering during cooler days. The heavyweight fabric ensures a cozy feel without compromising breathability.\r\n\r\nCare:\r\nWash inside-out in cold water. Dry on low heat. Turn inside-out before ironing to preserve fabric quality and print detail.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:13:46', '2026-02-20 10:13:46'),
(97, '300 GSM Heavyweight Oversized Sweatshirt', 'VN-6998343A2EB01', '300-gsm-heavyweight-oversized-sweatshirt', '', 'Fabric: Premium cotton-blend fleece with 300 GSM weight — soft on the skin, breathable, and built for everyday comfort.\r\nFit: Oversized silhouette with relaxed structure, offering an easy, laid-back feel and effortless streetwear appeal.\r\nDesign: Bold typographic back graphic with a clean, minimal front — strong visual impact without being loud.\r\nComfort: Ribbed cuffs and hem for a secure yet comfortable finish that holds shape wear after wear.\r\nCare: Wash inside-out in cold water. Tumble dry on low heat. Turn inside-out before ironing.\r\n\r\nA statement sweatshirt designed for comfort, presence, and daily wear.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:15:22', '2026-02-20 10:15:22'),
(98, 'Eagle Graphic Oversized Sweatshirt | 400 GSM Heavyweight Fleece', 'VN-6998347BB832F', 'eagle-graphic-oversized-sweatshirt-400-gsm-heavyweight-fleece', '', 'Fabric: Premium cotton–polyester blend with 400 GSM fleece, thick, soft, and durable for everyday wear. Provides warmth without feeling bulky.\r\n\r\nFit: Oversized silhouette with relaxed structure, allowing easy movement and a modern street-style look.\r\n\r\nDesign: Bold eagle artwork on the back symbolizing strength, freedom, and power. High-quality print with rich detail that stands out.\r\n\r\nComfort: Soft brushed interior for a cozy feel, suitable for long wear across seasons.\r\n\r\nCare: Wash inside-out in cold water. Dry on low heat. Do not bleach. Iron inside-out to maintain print quality.', 999.00, 899.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:16:27', '2026-02-20 10:16:27'),
(99, 'VÉNARO “Warrior” Varsity Jacket', 'VN-699835850A8D7', 'v-naro-warrior-varsity-jacket', '', 'Command attention with the VÉNARO Warrior Varsity Jacket — a statement piece inspired by strength, resilience, and timeless dominance. Featuring a bold WARRIOR graphic on the back paired with a classic varsity silhouette, this jacket blends old-school athletic heritage with modern streetwear attitude.\r\n\r\nCrafted from premium 300 GSM cotton, it delivers a structured yet comfortable feel, making it perfect for everyday wear or standout layering. The contrasting sleeves, ribbed collar, cuffs, and hem add an authentic varsity finish, while the subtle VÉNARO branding on the front keeps it refined and balanced.\r\n\r\nDesigned for those who lead from the front — not follow.\r\n\r\nDetails\r\n\r\nFabric: 100% Cotton, 300 GSM – premium weight with a soft, durable feel\r\n\r\nFit: Unisex regular fit with derby ribbed collar and hem\r\n\r\nDesign: Bold “Warrior” back graphic with classic varsity contrast sleeves\r\n\r\nStyle: Streetwear meets heritage varsity aesthetics\r\n\r\nCare: Wash inside-out in cold water, tumble dry low, iron inside-out', 1999.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:20:53', '2026-02-20 10:20:53'),
(100, 'VÉNARO “Aquila” Eagle Varsity Jacket – Green & Ivory', 'VN-699835BA12059', 'v-naro-aquila-eagle-varsity-jacket-green-ivory', '', 'Make a powerful statement with the VÉNARO Aquila Varsity Jacket, where strength, freedom, and craftsmanship come together. Featuring a striking eagle illustration on the back, this piece symbolizes dominance, vision, and resilience—designed for those who move with confidence and purpose.\r\n\r\nThe deep green body paired with contrasting ivory sleeves delivers a classic varsity aesthetic, while premium cotton construction ensures comfort without compromising structure. Ribbed collar, cuffs, and hem complete the timeless silhouette, making it a versatile layer for both streetwear and elevated casual looks.\r\n\r\nBuilt to stand out. Designed to endure.\r\n\r\nDetails:\r\n\r\nFabric: 100% Cotton, 300 GSM – premium, breathable, and durable\r\n\r\nFit: Unisex regular fit with derby ribbed hem and collar\r\n\r\nDesign: Bold eagle artwork on the back, minimal VÉNARO branding on the front\r\n\r\nStyle: Classic varsity jacket with a modern edge\r\n\r\nCare: Wash inside-out in cold water, dry on low heat. Iron inside-out only\r\n\r\nA symbol of power. A statement of identity.\r\nVÉNARO – Rise Above.', 1999.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 5, '2026-02-20 10:21:46', '2026-02-20 10:59:02'),
(101, 'Sunflower Sunday Vibes Varsity Jacket', 'VN-699835F3C02BF', 'sunflower-sunday-vibes-varsity-jacket', '', 'Bring calm energy and vintage streetwear together with the Sunflower Sunday Vibes Varsity Jacket by VÉNARO. Designed around a bold sunflower graphic, this jacket represents warmth, optimism, and effortless confidence—perfect for laid-back days and standout evenings.\r\n\r\nCrafted from premium 300 GSM cotton, it delivers a structured yet comfortable feel, while the classic varsity silhouette adds a timeless edge. The contrast sleeves and ribbed hem complete the look, making it easy to style with both casual and elevated outfits.\r\n\r\nWhether you’re chasing slow Sundays or making everyday moments feel special, this jacket is built to express relaxed confidence with unmistakable character.\r\n\r\nDetails:\r\n\r\nFabric: 100% cotton, 300 GSM – premium, breathable, and durable\r\n\r\nFit: Unisex varsity fit with derby ribbed collar and hem\r\n\r\nDesign: Vintage sunflower artwork with bold typography\r\n\r\nStyle: Streetwear-inspired, all-season layering essential\r\n\r\nCare: Wash inside-out in cold water, dry on low heat, iron inside-out', 1999.00, 1699.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:22:43', '2026-02-20 10:22:43'),
(102, 'VÉNARO Premium Solid Oversized Shirt – 100% Cotton | 240 GSM | Relaxed Fit', 'VN-699837A05137F', 'VÉNARO-premium-solid-oversized-shirt-100-cotton-240-gsm-relaxed-fit-1', '', 'Minimal. Premium. Everyday essential.\r\n\r\nThe VÉNARO Solid Oversized Shirt is crafted from 100% pure cotton (240 GSM), offering a perfect balance of structure, breathability, and all-day comfort. Designed for a relaxed silhouette, this shirt delivers a modern oversized fit that elevates both casual and smart-casual looks.\r\n\r\nIts clean solid finish makes it versatile enough to wear solo or layer effortlessly. Whether you’re stepping out for a casual day, a weekend plan, or a laid-back evening, this shirt adapts to your style without compromise.\r\n\r\nKey Features\r\n\r\nFabric: 100% Cotton, 240 GSM – premium, breathable & durable\r\n\r\nFit: Oversized relaxed fit for a modern streetwear look\r\n\r\nFeel: Soft on skin with a structured drape\r\n\r\nDesign: Clean solid color, timeless and versatile\r\n\r\nSleeves: Short sleeves for everyday comfort\r\n\r\nPerfect For\r\n\r\nCasual outings, everyday wear, travel looks, and effortless street style.\r\n\r\nCare Instructions\r\n\r\nWash inside-out in cold water.\r\nDo not bleach.\r\nTumble dry low.\r\nIron inside-out for long-lasting finish.', 899.00, 599.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:29:52', '2026-02-20 10:29:52'),
(103, 'VÉNARO Premium Solid Oversized Shirt – 100% Cotton | 240 GSM | Relaxed Fit', 'VN-699837AF005F2', 'VÉNARO-premium-solid-oversized-shirt-100-cotton-240-gsm-relaxed-fit-2', '', 'Minimal. Premium. Everyday essential.\r\n\r\nThe VÉNARO Solid Oversized Shirt is crafted from 100% pure cotton (240 GSM), offering a perfect balance of structure, breathability, and all-day comfort. Designed for a relaxed silhouette, this shirt delivers a modern oversized fit that elevates both casual and smart-casual looks.\r\n\r\nIts clean solid finish makes it versatile enough to wear solo or layer effortlessly. Whether you’re stepping out for a casual day, a weekend plan, or a laid-back evening, this shirt adapts to your style without compromise.\r\n\r\nKey Features\r\n\r\nFabric: 100% Cotton, 240 GSM – premium, breathable & durable\r\n\r\nFit: Oversized relaxed fit for a modern streetwear look\r\n\r\nFeel: Soft on skin with a structured drape\r\n\r\nDesign: Clean solid color, timeless and versatile\r\n\r\nSleeves: Short sleeves for everyday comfort\r\n\r\nPerfect For\r\n\r\nCasual outings, everyday wear, travel looks, and effortless street style.\r\n\r\nCare Instructions\r\n\r\nWash inside-out in cold water.\r\nDo not bleach.\r\nTumble dry low.\r\nIron inside-out for long-lasting finish.', 899.00, 599.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 0, '2026-02-20 10:30:07', '2026-02-20 10:30:07'),
(104, 'VÉNARO Premium Solid Oversized Shirt – 100% Cotton | 240 GSM | Relaxed Fit', 'VN-699837C030572', 'VÉNARO-premium-solid-oversized-shirt-100-cotton-240-gsm-relaxed-fit-3', '', 'Minimal. Premium. Everyday essential.\r\n\r\nThe VÉNARO Solid Oversized Shirt is crafted from 100% pure cotton (240 GSM), offering a perfect balance of structure, breathability, and all-day comfort. Designed for a relaxed silhouette, this shirt delivers a modern oversized fit that elevates both casual and smart-casual looks.\r\n\r\nIts clean solid finish makes it versatile enough to wear solo or layer effortlessly. Whether you’re stepping out for a casual day, a weekend plan, or a laid-back evening, this shirt adapts to your style without compromise.\r\n\r\nKey Features\r\n\r\nFabric: 100% Cotton, 240 GSM – premium, breathable & durable\r\n\r\nFit: Oversized relaxed fit for a modern streetwear look\r\n\r\nFeel: Soft on skin with a structured drape\r\n\r\nDesign: Clean solid color, timeless and versatile\r\n\r\nSleeves: Short sleeves for everyday comfort\r\n\r\nPerfect For\r\n\r\nCasual outings, everyday wear, travel looks, and effortless street style.\r\n\r\nCare Instructions\r\n\r\nWash inside-out in cold water.\r\nDo not bleach.\r\nTumble dry low.\r\nIron inside-out for long-lasting finish.', 899.00, 599.00, NULL, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 100, 10, 0, 'In Stock', 'Active', 0, 1, '2026-02-20 10:30:24', '2026-02-20 10:44:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(21, 52),
(21, 53),
(21, 54),
(22, 52),
(22, 53),
(22, 54),
(23, 52),
(23, 53),
(23, 54),
(24, 52),
(24, 53),
(24, 54),
(25, 52),
(25, 53),
(25, 54),
(26, 52),
(26, 53),
(26, 54),
(27, 52),
(27, 53),
(27, 54),
(28, 52),
(28, 53),
(28, 54),
(29, 52),
(29, 53),
(29, 54),
(30, 52),
(30, 53),
(30, 54),
(31, 52),
(31, 53),
(31, 54),
(39, 52),
(39, 55),
(40, 52),
(40, 55),
(42, 51),
(42, 58),
(42, 59),
(44, 49),
(44, 61),
(48, 49),
(48, 61),
(49, 49),
(49, 61),
(50, 49),
(50, 61),
(51, 49),
(51, 61),
(52, 49),
(52, 62),
(53, 49),
(53, 62),
(54, 52),
(54, 55),
(55, 52),
(55, 55),
(56, 52),
(56, 55),
(57, 52),
(57, 55),
(58, 52),
(58, 55),
(59, 52),
(59, 63),
(60, 52),
(60, 63),
(61, 52),
(61, 63),
(62, 52),
(62, 63),
(63, 52),
(63, 63),
(64, 52),
(64, 63),
(65, 52),
(65, 63),
(66, 52),
(66, 63),
(67, 52),
(67, 65),
(68, 52),
(68, 65),
(69, 52),
(69, 65),
(70, 52),
(70, 65),
(71, 52),
(71, 65),
(72, 52),
(72, 65),
(73, 52),
(73, 65),
(74, 52),
(74, 65),
(75, 52),
(75, 65),
(76, 50),
(76, 69),
(77, 50),
(77, 69),
(78, 50),
(78, 69),
(79, 50),
(79, 69),
(80, 50),
(80, 69),
(81, 50),
(81, 69),
(82, 50),
(82, 69),
(83, 50),
(83, 69),
(84, 50),
(84, 69),
(85, 50),
(85, 69),
(86, 50),
(86, 69),
(87, 50),
(87, 69),
(88, 50),
(88, 69),
(89, 50),
(89, 69),
(90, 50),
(90, 69),
(91, 50),
(91, 69),
(92, 50),
(92, 69),
(93, 50),
(93, 68),
(94, 50),
(94, 68),
(95, 50),
(95, 68),
(96, 50),
(96, 68),
(97, 50),
(97, 68),
(98, 50),
(98, 68),
(99, 50),
(99, 67),
(100, 50),
(100, 67),
(101, 50),
(101, 67),
(102, 51),
(102, 58),
(102, 59),
(103, 51),
(103, 58),
(103, 59),
(104, 51),
(104, 58),
(104, 59);

-- --------------------------------------------------------

--
-- Table structure for table `product_collections`
--

CREATE TABLE `product_collections` (
  `product_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_collections`
--

INSERT INTO `product_collections` (`product_id`, `collection_id`) VALUES
(21, 1),
(30, 1),
(31, 1),
(42, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`, `alt_text`, `display_order`, `is_primary`, `created_at`) VALUES
(2, 21, 'prod_21_6991b21cbede5_0.jpg', NULL, 0, 1, '2026-02-15 11:46:36'),
(3, 21, 'prod_21_6991b21cc1228_1.jpg', NULL, 1, 0, '2026-02-15 11:46:36'),
(4, 22, 'prod_22_6991b24a9c145_0.jpg', NULL, 0, 1, '2026-02-15 11:47:22'),
(5, 22, 'prod_22_6991b24a9c82b_1.jpg', NULL, 1, 0, '2026-02-15 11:47:22'),
(6, 23, 'prod_23_6991b26e4e522_0.jpg', NULL, 0, 1, '2026-02-15 11:47:58'),
(7, 23, 'prod_23_6991b26e4feaa_1.jpg', NULL, 1, 0, '2026-02-15 11:47:58'),
(8, 24, 'prod_24_6991b2bf15543_0.jpg', NULL, 0, 1, '2026-02-15 11:49:19'),
(9, 24, 'prod_24_6991b2bf173a2_1.jpg', NULL, 1, 0, '2026-02-15 11:49:19'),
(10, 25, 'prod_25_6991b4609f74d_0.jpg', NULL, 0, 1, '2026-02-15 11:56:16'),
(11, 25, 'prod_25_6991b460a241b_1.jpg', NULL, 1, 0, '2026-02-15 11:56:16'),
(12, 26, 'prod_26_6991b48f798c7_0.jpg', NULL, 0, 1, '2026-02-15 11:57:03'),
(13, 26, 'prod_26_6991b48f7b1cf_1.jpg', NULL, 1, 0, '2026-02-15 11:57:03'),
(14, 27, 'prod_27_6991b61459053_0.jpg', NULL, 0, 1, '2026-02-15 12:03:32'),
(15, 27, 'prod_27_6991b61459a7e_1.jpg', NULL, 1, 0, '2026-02-15 12:03:32'),
(16, 28, 'prod_28_6991b643e7327_0.jpg', NULL, 0, 1, '2026-02-15 12:04:19'),
(17, 28, 'prod_28_6991b643e793e_1.jpg', NULL, 1, 0, '2026-02-15 12:04:19'),
(18, 29, 'prod_29_6991b9f23f76d_0.jpg', NULL, 0, 1, '2026-02-15 12:20:02'),
(19, 29, 'prod_29_6991b9f240285_1.jpg', NULL, 1, 0, '2026-02-15 12:20:02'),
(21, 31, 'prod_31_69928f43c6cd9_0.jpg', NULL, 0, 1, '2026-02-16 03:30:11'),
(22, 31, 'prod_31_69928f43c8f83_1.jpg', NULL, 1, 0, '2026-02-16 03:30:11'),
(35, 39, 'prod_39_699293fd291d4_0.jpg', NULL, 0, 1, '2026-02-16 03:50:21'),
(36, 40, 'prod_40_6992944cdc680_0.jpg', NULL, 0, 1, '2026-02-16 03:51:40'),
(37, 41, 'prod_41_699294809192b_0.jpg', NULL, 0, 1, '2026-02-16 03:52:32'),
(38, 42, 'prod_42_69945bccc4858_0.jpg', NULL, 0, 1, '2026-02-17 12:15:08'),
(45, 48, 'prod_48_69974318bd464_0.jpg', NULL, 0, 1, '2026-02-19 17:06:32'),
(46, 49, 'prod_49_6997433bbe130_0.jpg', NULL, 0, 1, '2026-02-19 17:07:07'),
(47, 50, 'prod_50_699743590aa8d_0.jpg', NULL, 0, 1, '2026-02-19 17:07:37'),
(48, 51, 'prod_51_69974378c5693_0.jpg', NULL, 0, 1, '2026-02-19 17:08:08'),
(49, 44, 'prod_44_69974390363f3_0.jpg', NULL, 0, 1, '2026-02-19 17:08:32'),
(51, 30, 'prod_30_6997448a2c135_0.jpg', NULL, 0, 1, '2026-02-19 17:12:42'),
(52, 30, 'prod_30_6997448a2cbe5_1.jpg', NULL, 0, 0, '2026-02-19 17:12:42'),
(58, 39, 'prod_39_699745ee2c65b_0.jpg', NULL, 0, 0, '2026-02-19 17:18:38'),
(59, 40, 'prod_40_699745f9b4b82_0.jpg', NULL, 0, 0, '2026-02-19 17:18:49'),
(60, 42, 'prod_42_69974678121f4_0.jpg', NULL, 0, 0, '2026-02-19 17:20:56'),
(61, 52, 'prod_52_69974823d11b8_0.jpg', NULL, 0, 1, '2026-02-19 17:28:03'),
(62, 53, 'prod_53_6997485fe2d66_0.jpg', NULL, 0, 1, '2026-02-19 17:29:03'),
(63, 54, 'prod_54_6997522010f39_0.jpg', NULL, 0, 1, '2026-02-19 18:10:40'),
(64, 54, 'prod_54_6997522011677_1.jpg', NULL, 1, 0, '2026-02-19 18:10:40'),
(65, 55, 'prod_55_699752c340a18_0.jpg', NULL, 0, 0, '2026-02-19 18:13:23'),
(66, 55, 'prod_55_699752c341060_1.jpg', NULL, 1, 1, '2026-02-19 18:13:23'),
(67, 56, 'prod_56_699752ffa5059_0.jpg', NULL, 0, 1, '2026-02-19 18:14:23'),
(68, 56, 'prod_56_699752ffa5822_1.jpg', NULL, 1, 0, '2026-02-19 18:14:23'),
(69, 57, 'prod_57_69975350a82ec_0.jpg', NULL, 0, 1, '2026-02-19 18:15:44'),
(70, 57, 'prod_57_69975350a899f_1.jpg', NULL, 1, 0, '2026-02-19 18:15:44'),
(71, 58, 'prod_58_6997537d7300d_0.jpg', NULL, 0, 1, '2026-02-19 18:16:29'),
(72, 58, 'prod_58_6997537d73618_1.jpg', NULL, 1, 0, '2026-02-19 18:16:29'),
(73, 59, 'prod_59_6997c9f440c7d_0.jpg', NULL, 0, 1, '2026-02-20 02:41:56'),
(74, 59, 'prod_59_6997c9f441c4f_1.jpg', NULL, 1, 0, '2026-02-20 02:41:56'),
(75, 60, 'prod_60_6997ca5292ea9_0.jpg', NULL, 0, 1, '2026-02-20 02:43:30'),
(76, 61, 'prod_61_6997ca7329a47_0.jpg', NULL, 0, 1, '2026-02-20 02:44:03'),
(77, 62, 'prod_62_6997cb1a8655c_0.jpg', NULL, 0, 1, '2026-02-20 02:46:50'),
(78, 62, 'prod_62_6997cb1a8788d_1.jpg', NULL, 1, 0, '2026-02-20 02:46:50'),
(79, 63, 'prod_63_6997cb25bff8c_0.jpg', NULL, 0, 1, '2026-02-20 02:47:01'),
(80, 63, 'prod_63_6997cb25c136a_1.jpg', NULL, 1, 0, '2026-02-20 02:47:01'),
(81, 64, 'prod_64_6997cb2feb7c0_0.jpg', NULL, 0, 1, '2026-02-20 02:47:11'),
(82, 64, 'prod_64_6997cb2febd73_1.jpg', NULL, 1, 0, '2026-02-20 02:47:11'),
(83, 65, 'prod_65_6997cb37a681c_0.jpg', NULL, 0, 1, '2026-02-20 02:47:19'),
(84, 65, 'prod_65_6997cb37a7f51_1.jpg', NULL, 1, 0, '2026-02-20 02:47:19'),
(85, 66, 'prod_66_6997cb40f34e3_0.jpg', NULL, 0, 1, '2026-02-20 02:47:28'),
(86, 66, 'prod_66_6997cb41004aa_1.jpg', NULL, 1, 0, '2026-02-20 02:47:29'),
(87, 67, 'prod_67_6997d1e3e8425_0.jpg', NULL, 0, 1, '2026-02-20 03:15:47'),
(88, 67, 'prod_67_6997d1e3e8e60_1.jpg', NULL, 1, 0, '2026-02-20 03:15:47'),
(89, 68, 'prod_68_6997d1f79c8eb_0.jpg', NULL, 0, 1, '2026-02-20 03:16:07'),
(90, 68, 'prod_68_6997d1f79d32a_1.jpg', NULL, 1, 0, '2026-02-20 03:16:07'),
(91, 69, 'prod_69_6997d2c2363fa_0.jpg', NULL, 0, 1, '2026-02-20 03:19:30'),
(92, 69, 'prod_69_6997d2c236c03_1.jpg', NULL, 1, 0, '2026-02-20 03:19:30'),
(93, 70, 'prod_70_6997d2ce5bd32_0.jpg', NULL, 0, 1, '2026-02-20 03:19:42'),
(94, 70, 'prod_70_6997d2ce5d8e1_1.jpg', NULL, 1, 0, '2026-02-20 03:19:42'),
(95, 71, 'prod_71_6997d2db8c8ed_0.jpg', NULL, 0, 1, '2026-02-20 03:19:55'),
(96, 71, 'prod_71_6997d2db8df49_1.jpg', NULL, 1, 0, '2026-02-20 03:19:55'),
(97, 72, 'prod_72_6997d2e67bc9a_0.jpg', NULL, 0, 1, '2026-02-20 03:20:06'),
(98, 72, 'prod_72_6997d2e67c3a7_1.jpg', NULL, 1, 0, '2026-02-20 03:20:06'),
(99, 73, 'prod_73_6997d2f45b731_0.jpg', NULL, 0, 1, '2026-02-20 03:20:20'),
(100, 73, 'prod_73_6997d2f45d8ca_1.jpg', NULL, 1, 0, '2026-02-20 03:20:20'),
(101, 74, 'prod_74_6997d3001e481_0.jpg', NULL, 0, 1, '2026-02-20 03:20:32'),
(102, 74, 'prod_74_6997d30024cb3_1.jpg', NULL, 1, 0, '2026-02-20 03:20:32'),
(103, 75, 'prod_75_6997d30983e5b_0.jpg', NULL, 0, 1, '2026-02-20 03:20:41'),
(104, 75, 'prod_75_6997d309857a1_1.jpg', NULL, 1, 0, '2026-02-20 03:20:41'),
(105, 76, 'prod_76_6998235764dd2_0.jpg', NULL, 0, 1, '2026-02-20 09:03:19'),
(106, 76, 'prod_76_699823576631f_1.jpg', NULL, 1, 0, '2026-02-20 09:03:19'),
(107, 77, 'prod_77_6998236022af6_0.jpg', NULL, 0, 1, '2026-02-20 09:03:28'),
(108, 77, 'prod_77_69982360234d2_1.jpg', NULL, 1, 0, '2026-02-20 09:03:28'),
(109, 78, 'prod_78_6998236860338_0.jpg', NULL, 0, 1, '2026-02-20 09:03:36'),
(110, 78, 'prod_78_699823686097b_1.jpg', NULL, 1, 0, '2026-02-20 09:03:36'),
(111, 79, 'prod_79_699823729bcce_0.jpg', NULL, 0, 1, '2026-02-20 09:03:46'),
(112, 79, 'prod_79_699823729c69f_1.jpg', NULL, 1, 0, '2026-02-20 09:03:46'),
(113, 80, 'prod_80_69982bbb3fd8f_0.jpg', NULL, 0, 1, '2026-02-20 09:39:07'),
(114, 80, 'prod_80_69982bbb40609_1.jpg', NULL, 1, 0, '2026-02-20 09:39:07'),
(115, 81, 'prod_81_69982bcdc1fb5_0.jpg', NULL, 0, 1, '2026-02-20 09:39:25'),
(116, 81, 'prod_81_69982bcdc2625_1.jpg', NULL, 1, 0, '2026-02-20 09:39:25'),
(117, 82, 'prod_82_69982be177d9c_0.jpg', NULL, 0, 1, '2026-02-20 09:39:45'),
(118, 82, 'prod_82_69982be1785d2_1.jpg', NULL, 1, 0, '2026-02-20 09:39:45'),
(119, 83, 'prod_83_69982bebb49f5_0.jpg', NULL, 0, 1, '2026-02-20 09:39:55'),
(120, 83, 'prod_83_69982bebb517f_1.jpg', NULL, 1, 0, '2026-02-20 09:39:55'),
(121, 84, 'prod_84_69982bf7ebb36_0.jpg', NULL, 0, 1, '2026-02-20 09:40:07'),
(122, 84, 'prod_84_69982bf7ecff7_1.jpg', NULL, 1, 0, '2026-02-20 09:40:07'),
(123, 85, 'prod_85_69982c05086e0_0.jpg', NULL, 0, 1, '2026-02-20 09:40:21'),
(124, 85, 'prod_85_69982c0509300_1.jpg', NULL, 1, 0, '2026-02-20 09:40:21'),
(125, 86, 'prod_86_69982c10a8761_0.jpg', NULL, 0, 1, '2026-02-20 09:40:32'),
(126, 86, 'prod_86_69982c10a9046_1.jpg', NULL, 1, 0, '2026-02-20 09:40:32'),
(127, 87, 'prod_87_69982c1815c57_0.jpg', NULL, 0, 1, '2026-02-20 09:40:40'),
(128, 87, 'prod_87_69982c1816583_1.jpg', NULL, 1, 0, '2026-02-20 09:40:40'),
(129, 88, 'prod_88_69982c54a8a57_0.jpg', NULL, 0, 0, '2026-02-20 09:41:40'),
(130, 88, 'prod_88_69982c54aa8ea_1.jpg', NULL, 1, 1, '2026-02-20 09:41:40'),
(131, 89, 'prod_89_69982e38aedae_0.jpg', NULL, 0, 1, '2026-02-20 09:49:44'),
(132, 89, 'prod_89_69982e38af4d6_1.jpg', NULL, 1, 0, '2026-02-20 09:49:44'),
(133, 90, 'prod_90_69982e8899eee_0.jpg', NULL, 0, 1, '2026-02-20 09:51:04'),
(134, 90, 'prod_90_69982e889bb26_1.jpg', NULL, 1, 0, '2026-02-20 09:51:04'),
(135, 91, 'prod_91_69982e90cf05a_0.jpg', NULL, 0, 1, '2026-02-20 09:51:12'),
(136, 91, 'prod_91_69982e90d0ae7_1.jpg', NULL, 1, 0, '2026-02-20 09:51:12'),
(137, 92, 'prod_92_69982e978ccd6_0.jpg', NULL, 0, 1, '2026-02-20 09:51:19'),
(138, 92, 'prod_92_69982e978d582_1.jpg', NULL, 1, 0, '2026-02-20 09:51:19'),
(139, 93, 'prod_93_699830bb77280_0.jpg', NULL, 0, 1, '2026-02-20 10:00:27'),
(140, 93, 'prod_93_699830bb79fdc_1.jpg', NULL, 1, 0, '2026-02-20 10:00:27'),
(141, 94, 'prod_94_699830c47a26f_0.jpg', NULL, 0, 1, '2026-02-20 10:00:36'),
(142, 94, 'prod_94_699830c47a907_1.jpg', NULL, 1, 0, '2026-02-20 10:00:36'),
(143, 95, 'prod_95_699830cc493e7_0.jpg', NULL, 0, 1, '2026-02-20 10:00:44'),
(144, 95, 'prod_95_699830cc4a26c_1.jpg', NULL, 1, 0, '2026-02-20 10:00:44'),
(145, 96, 'prod_96_699833dadac5b_0.jpg', NULL, 0, 0, '2026-02-20 10:13:46'),
(146, 96, 'prod_96_699833dadb302_1.jpg', NULL, 1, 1, '2026-02-20 10:13:46'),
(147, 97, 'prod_97_6998343a2f6b8_0.jpg', NULL, 0, 0, '2026-02-20 10:15:22'),
(148, 97, 'prod_97_6998343a2fd85_1.jpg', NULL, 1, 1, '2026-02-20 10:15:22'),
(149, 98, 'prod_98_6998347bb8ecd_0.jpg', NULL, 0, 1, '2026-02-20 10:16:27'),
(150, 98, 'prod_98_6998347bb9624_1.jpg', NULL, 1, 0, '2026-02-20 10:16:27'),
(151, 99, 'prod_99_699835850b29a_0.jpg', NULL, 0, 0, '2026-02-20 10:20:53'),
(152, 99, 'prod_99_699835850ba6f_1.jpg', NULL, 1, 1, '2026-02-20 10:20:53'),
(153, 100, 'prod_100_699835ba1293e_0.jpg', NULL, 0, 1, '2026-02-20 10:21:46'),
(154, 100, 'prod_100_699835ba12f83_1.jpg', NULL, 1, 0, '2026-02-20 10:21:46'),
(155, 101, 'prod_101_699835f3c0ed5_0.jpg', NULL, 0, 0, '2026-02-20 10:22:43'),
(156, 101, 'prod_101_699835f3c1534_1.jpg', NULL, 1, 1, '2026-02-20 10:22:43'),
(157, 102, 'prod_102_699837a051fdf_0.jpg', NULL, 0, 0, '2026-02-20 10:29:52'),
(158, 102, 'prod_102_699837a0533f8_1.jpg', NULL, 1, 1, '2026-02-20 10:29:52'),
(159, 103, 'prod_103_699837af01041_0.jpg', NULL, 0, 0, '2026-02-20 10:30:07'),
(160, 103, 'prod_103_699837af01710_1.jpg', NULL, 1, 1, '2026-02-20 10:30:07'),
(161, 104, 'prod_104_699837c030ffe_0.jpg', NULL, 0, 0, '2026-02-20 10:30:24'),
(162, 104, 'prod_104_699837c0322a4_1.jpg', NULL, 1, 1, '2026-02-20 10:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `size` enum('XS','S','M','L','XL','2XL','3XL') DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `price_adjustment` decimal(10,2) DEFAULT 0.00,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `sku`, `size`, `color`, `color_hex`, `price_adjustment`, `stock_quantity`, `image`, `status`, `created_at`, `updated_at`) VALUES
(437, 41, 'VN-699294808FBEF-1', 'XS', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(438, 41, 'VN-699294808FBEF-2', 'S', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(439, 41, 'VN-699294808FBEF-3', 'M', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(440, 41, 'VN-699294808FBEF-4', 'L', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(441, 41, 'VN-699294808FBEF-5', 'XL', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(442, 41, 'VN-699294808FBEF-6', '', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-16 03:52:32', '2026-02-16 03:52:32'),
(489, 21, 'VN-6991B21CBD90F-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 16:30:32', '2026-02-19 16:30:32'),
(490, 21, 'VN-6991B21CBD90F-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 16:30:32', '2026-02-19 16:30:32'),
(491, 21, 'VN-6991B21CBD90F-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 16:30:32', '2026-02-19 16:30:32'),
(492, 21, 'VN-6991B21CBD90F-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 16:30:32', '2026-02-19 16:30:32'),
(493, 21, 'VN-6991B21CBD90F-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 16:30:32', '2026-02-19 16:30:32'),
(535, 48, 'VN-69974318BC443-1', 'XS', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(536, 48, 'VN-69974318BC443-2', 'S', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(537, 48, 'VN-69974318BC443-3', 'M', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(538, 48, 'VN-69974318BC443-4', 'L', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(539, 48, 'VN-69974318BC443-5', 'XL', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(540, 48, 'VN-69974318BC443-6', '', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:06:32', '2026-02-19 17:06:32'),
(541, 49, 'VN-6997433BBD55D-1', 'XS', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(542, 49, 'VN-6997433BBD55D-2', 'S', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(543, 49, 'VN-6997433BBD55D-3', 'M', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(544, 49, 'VN-6997433BBD55D-4', 'L', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(545, 49, 'VN-6997433BBD55D-5', 'XL', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(546, 49, 'VN-6997433BBD55D-6', '', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:07:07', '2026-02-19 17:07:07'),
(547, 50, 'VN-6997435909E61-1', 'XS', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(548, 50, 'VN-6997435909E61-2', 'S', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(549, 50, 'VN-6997435909E61-3', 'M', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(550, 50, 'VN-6997435909E61-4', 'L', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(551, 50, 'VN-6997435909E61-5', 'XL', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(552, 50, 'VN-6997435909E61-6', '', '', '#000000', 0.00, 100, '', 'Active', '2026-02-19 17:07:37', '2026-02-19 17:07:37'),
(553, 51, 'VN-69974378C3D79-1', 'XS', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(554, 51, 'VN-69974378C3D79-2', 'S', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(555, 51, 'VN-69974378C3D79-3', 'M', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(556, 51, 'VN-69974378C3D79-4', 'L', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(557, 51, 'VN-69974378C3D79-5', 'XL', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(558, 51, 'VN-69974378C3D79-6', '', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:08', '2026-02-19 17:08:08'),
(564, 44, 'VN-69974286D0391-1', 'XS', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:32', '2026-02-19 17:08:32'),
(565, 44, 'VN-69974286D0391-2', 'S', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:32', '2026-02-19 17:08:32'),
(566, 44, 'VN-69974286D0391-3', 'M', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:32', '2026-02-19 17:08:32'),
(567, 44, 'VN-69974286D0391-4', 'L', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:32', '2026-02-19 17:08:32'),
(568, 44, 'VN-69974286D0391-5', 'XL', '', '#000000', 799.00, 100, '', 'Active', '2026-02-19 17:08:32', '2026-02-19 17:08:32'),
(569, 22, 'VN-6991B24A9B7F9-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:09:56', '2026-02-19 17:09:56'),
(570, 22, 'VN-6991B24A9B7F9-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:09:56', '2026-02-19 17:09:56'),
(571, 22, 'VN-6991B24A9B7F9-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:09:56', '2026-02-19 17:09:56'),
(572, 22, 'VN-6991B24A9B7F9-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:09:56', '2026-02-19 17:09:56'),
(573, 22, 'VN-6991B24A9B7F9-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:09:56', '2026-02-19 17:09:56'),
(574, 23, 'VN-6991B26E4DB6E-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:05', '2026-02-19 17:10:05'),
(575, 23, 'VN-6991B26E4DB6E-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:05', '2026-02-19 17:10:05'),
(576, 23, 'VN-6991B26E4DB6E-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:05', '2026-02-19 17:10:05'),
(577, 23, 'VN-6991B26E4DB6E-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:05', '2026-02-19 17:10:05'),
(578, 23, 'VN-6991B26E4DB6E-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:05', '2026-02-19 17:10:05'),
(579, 24, 'VN-6991B2BF14D08-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:14', '2026-02-19 17:10:14'),
(580, 24, 'VN-6991B2BF14D08-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:14', '2026-02-19 17:10:14'),
(581, 24, 'VN-6991B2BF14D08-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:14', '2026-02-19 17:10:14'),
(582, 24, 'VN-6991B2BF14D08-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:14', '2026-02-19 17:10:14'),
(583, 24, 'VN-6991B2BF14D08-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:14', '2026-02-19 17:10:14'),
(584, 25, 'VN-6991B4609E5E6-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:22', '2026-02-19 17:10:22'),
(585, 25, 'VN-6991B4609E5E6-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:22', '2026-02-19 17:10:22'),
(586, 25, 'VN-6991B4609E5E6-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:22', '2026-02-19 17:10:22'),
(587, 25, 'VN-6991B4609E5E6-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:22', '2026-02-19 17:10:22'),
(588, 25, 'VN-6991B4609E5E6-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:10:22', '2026-02-19 17:10:22'),
(589, 26, 'VN-6991B48F78FEE-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:39', '2026-02-19 17:11:39'),
(590, 26, 'VN-6991B48F78FEE-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:39', '2026-02-19 17:11:39'),
(591, 26, 'VN-6991B48F78FEE-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:39', '2026-02-19 17:11:39'),
(592, 26, 'VN-6991B48F78FEE-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:39', '2026-02-19 17:11:39'),
(593, 26, 'VN-6991B48F78FEE-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:39', '2026-02-19 17:11:39'),
(594, 27, 'VN-6991B6145875F-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:49', '2026-02-19 17:11:49'),
(595, 27, 'VN-6991B6145875F-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:49', '2026-02-19 17:11:49'),
(596, 27, 'VN-6991B6145875F-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:49', '2026-02-19 17:11:49'),
(597, 27, 'VN-6991B6145875F-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:49', '2026-02-19 17:11:49'),
(598, 27, 'VN-6991B6145875F-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:49', '2026-02-19 17:11:49'),
(599, 28, 'VN-6991B643E6AE7-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:57', '2026-02-19 17:11:57'),
(600, 28, 'VN-6991B643E6AE7-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:57', '2026-02-19 17:11:57'),
(601, 28, 'VN-6991B643E6AE7-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:57', '2026-02-19 17:11:57'),
(602, 28, 'VN-6991B643E6AE7-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:57', '2026-02-19 17:11:57'),
(603, 28, 'VN-6991B643E6AE7-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:11:57', '2026-02-19 17:11:57'),
(604, 29, 'VN-6991B9F23B96C-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:05', '2026-02-19 17:12:05'),
(605, 29, 'VN-6991B9F23B96C-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:05', '2026-02-19 17:12:05'),
(606, 29, 'VN-6991B9F23B96C-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:05', '2026-02-19 17:12:05'),
(607, 29, 'VN-6991B9F23B96C-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:05', '2026-02-19 17:12:05'),
(608, 29, 'VN-6991B9F23B96C-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:05', '2026-02-19 17:12:05'),
(619, 30, 'VN-6992884575C61-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:42', '2026-02-19 17:12:42'),
(620, 30, 'VN-6992884575C61-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:42', '2026-02-19 17:12:42'),
(621, 30, 'VN-6992884575C61-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:42', '2026-02-19 17:12:42'),
(622, 30, 'VN-6992884575C61-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:42', '2026-02-19 17:12:42'),
(623, 30, 'VN-6992884575C61-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:42', '2026-02-19 17:12:42'),
(624, 31, 'VN-69928F43C5EFB-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:50', '2026-02-19 17:12:50'),
(625, 31, 'VN-69928F43C5EFB-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:50', '2026-02-19 17:12:50'),
(626, 31, 'VN-69928F43C5EFB-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:50', '2026-02-19 17:12:50'),
(627, 31, 'VN-69928F43C5EFB-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:50', '2026-02-19 17:12:50'),
(628, 31, 'VN-69928F43C5EFB-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-19 17:12:50', '2026-02-19 17:12:50'),
(654, 39, 'VN-699293FD276EA-1', 'XS', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-19 17:18:38', '2026-02-19 17:18:38'),
(655, 39, 'VN-699293FD276EA-2', 'S', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-19 17:18:38', '2026-02-19 17:18:38'),
(656, 39, 'VN-699293FD276EA-3', 'M', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-19 17:18:38', '2026-02-19 17:18:38'),
(657, 39, 'VN-699293FD276EA-4', 'L', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-19 17:18:38', '2026-02-19 17:18:38'),
(658, 39, 'VN-699293FD276EA-5', 'XL', '', '#000000', 1499.00, 100, '', 'Active', '2026-02-19 17:18:38', '2026-02-19 17:18:38'),
(659, 40, 'VN-6992944CD9579-1', 'XS', '', '#000000', 1599.00, 100, '', 'Active', '2026-02-19 17:18:49', '2026-02-19 17:18:49'),
(660, 40, 'VN-6992944CD9579-2', 'S', '', '#000000', 1599.00, 100, '', 'Active', '2026-02-19 17:18:49', '2026-02-19 17:18:49'),
(661, 40, 'VN-6992944CD9579-3', 'M', '', '#000000', 1599.00, 100, '', 'Active', '2026-02-19 17:18:49', '2026-02-19 17:18:49'),
(662, 40, 'VN-6992944CD9579-4', 'L', '', '#000000', 1599.00, 100, '', 'Active', '2026-02-19 17:18:49', '2026-02-19 17:18:49'),
(663, 40, 'VN-6992944CD9579-5', 'XL', '', '#000000', 1599.00, 100, '', 'Active', '2026-02-19 17:18:49', '2026-02-19 17:18:49'),
(669, 42, 'VN-69945BCCC14A3-1', 'XS', '', '#000000', 649.00, 100, '', 'Active', '2026-02-19 17:22:32', '2026-02-19 17:22:32'),
(670, 42, 'VN-69945BCCC14A3-2', 'S', '', '#000000', 649.00, 100, '', 'Active', '2026-02-19 17:22:32', '2026-02-19 17:22:32'),
(671, 42, 'VN-69945BCCC14A3-3', 'M', '', '#000000', 649.00, 100, '', 'Active', '2026-02-19 17:22:32', '2026-02-19 17:22:32'),
(672, 42, 'VN-69945BCCC14A3-4', 'L', '', '#000000', 649.00, 100, '', 'Active', '2026-02-19 17:22:32', '2026-02-19 17:22:32'),
(673, 42, 'VN-69945BCCC14A3-5', 'XL', '', '#000000', 649.00, 100, '', 'Active', '2026-02-19 17:22:32', '2026-02-19 17:22:32'),
(680, 52, 'VN-69974823D00AE-1', 'XS', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:28:17', '2026-02-19 17:28:17'),
(681, 52, 'VN-69974823D00AE-2', 'S', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:28:17', '2026-02-19 17:28:17'),
(682, 52, 'VN-69974823D00AE-3', 'M', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:28:17', '2026-02-19 17:28:17'),
(683, 52, 'VN-69974823D00AE-4', 'L', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:28:17', '2026-02-19 17:28:17'),
(684, 52, 'VN-69974823D00AE-5', 'XL', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:28:17', '2026-02-19 17:28:17'),
(685, 53, 'VN-6997485FE225D-1', 'XS', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(686, 53, 'VN-6997485FE225D-2', 'S', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(687, 53, 'VN-6997485FE225D-3', 'M', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(688, 53, 'VN-6997485FE225D-4', 'L', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(689, 53, 'VN-6997485FE225D-5', 'XL', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(690, 53, 'VN-6997485FE225D-6', '', '', '#000000', 1099.00, 100, '', 'Active', '2026-02-19 17:29:03', '2026-02-19 17:29:03'),
(691, 54, 'VN-6997522010501-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(692, 54, 'VN-6997522010501-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(693, 54, 'VN-6997522010501-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(694, 54, 'VN-6997522010501-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(695, 54, 'VN-6997522010501-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(696, 54, 'VN-6997522010501-6', '', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:10:40', '2026-02-19 18:10:40'),
(703, 55, 'VN-699752C33FE26-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:13:35', '2026-02-19 18:13:35'),
(704, 55, 'VN-699752C33FE26-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:13:35', '2026-02-19 18:13:35'),
(705, 55, 'VN-699752C33FE26-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:13:35', '2026-02-19 18:13:35'),
(706, 55, 'VN-699752C33FE26-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:13:35', '2026-02-19 18:13:35'),
(707, 55, 'VN-699752C33FE26-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:13:35', '2026-02-19 18:13:35'),
(708, 56, 'VN-699752FFA46BE-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(709, 56, 'VN-699752FFA46BE-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(710, 56, 'VN-699752FFA46BE-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(711, 56, 'VN-699752FFA46BE-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(712, 56, 'VN-699752FFA46BE-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(713, 56, 'VN-699752FFA46BE-6', '', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:14:23', '2026-02-19 18:14:23'),
(714, 57, 'VN-69975350A7612-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(715, 57, 'VN-69975350A7612-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(716, 57, 'VN-69975350A7612-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(717, 57, 'VN-69975350A7612-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(718, 57, 'VN-69975350A7612-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(719, 57, 'VN-69975350A7612-6', '', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:15:44', '2026-02-19 18:15:44'),
(720, 58, 'VN-6997537D7266E-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(721, 58, 'VN-6997537D7266E-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(722, 58, 'VN-6997537D7266E-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(723, 58, 'VN-6997537D7266E-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(724, 58, 'VN-6997537D7266E-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(725, 58, 'VN-6997537D7266E-6', '', '', '#000000', 999.00, 100, '', 'Active', '2026-02-19 18:16:29', '2026-02-19 18:16:29'),
(732, 59, 'VN-6997C9F43C0BF-1', 'XS', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:43:06', '2026-02-20 02:43:06'),
(733, 59, 'VN-6997C9F43C0BF-2', 'S', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:43:06', '2026-02-20 02:43:06'),
(734, 59, 'VN-6997C9F43C0BF-3', 'M', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:43:06', '2026-02-20 02:43:06'),
(735, 59, 'VN-6997C9F43C0BF-4', 'L', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:43:06', '2026-02-20 02:43:06'),
(736, 59, 'VN-6997C9F43C0BF-5', 'XL', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:43:06', '2026-02-20 02:43:06'),
(737, 61, 'VN-6997CA7329010-1', 'XS', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(738, 61, 'VN-6997CA7329010-2', 'S', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(739, 61, 'VN-6997CA7329010-3', 'M', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(740, 61, 'VN-6997CA7329010-4', 'L', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(741, 61, 'VN-6997CA7329010-5', 'XL', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(742, 61, 'VN-6997CA7329010-6', '', '', '#000000', 1299.00, 100, '', 'Active', '2026-02-20 02:44:03', '2026-02-20 02:44:03'),
(743, 62, 'VN-6997CB1A85C3D-1', 'XS', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(744, 62, 'VN-6997CB1A85C3D-2', 'S', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(745, 62, 'VN-6997CB1A85C3D-3', 'M', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(746, 62, 'VN-6997CB1A85C3D-4', 'L', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(747, 62, 'VN-6997CB1A85C3D-5', 'XL', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(748, 62, 'VN-6997CB1A85C3D-6', '', '', '#000000', 999.00, 100, '', 'Active', '2026-02-20 02:46:50', '2026-02-20 02:46:50'),
(749, 67, 'VN-6997D1E3E7329-1', 'XS', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(750, 67, 'VN-6997D1E3E7329-2', 'S', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(751, 67, 'VN-6997D1E3E7329-3', 'M', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(752, 67, 'VN-6997D1E3E7329-4', 'L', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(753, 67, 'VN-6997D1E3E7329-5', 'XL', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(754, 67, 'VN-6997D1E3E7329-6', '', '', '#000000', 499.00, 100, '', 'Active', '2026-02-20 03:15:47', '2026-02-20 03:15:47'),
(755, 69, 'VN-6997D2C235AEF-1', 'XS', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(756, 69, 'VN-6997D2C235AEF-2', 'S', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(757, 69, 'VN-6997D2C235AEF-3', 'M', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(758, 69, 'VN-6997D2C235AEF-4', 'L', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(759, 69, 'VN-6997D2C235AEF-5', 'XL', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(760, 69, 'VN-6997D2C235AEF-6', '', '', '#000000', 799.00, 100, '', 'Active', '2026-02-20 03:19:30', '2026-02-20 03:19:30'),
(761, 76, 'VN-699823575C7F4-1', 'XS', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(762, 76, 'VN-699823575C7F4-2', 'S', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(763, 76, 'VN-699823575C7F4-3', 'M', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(764, 76, 'VN-699823575C7F4-4', 'L', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(765, 76, 'VN-699823575C7F4-5', 'XL', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(766, 76, 'VN-699823575C7F4-6', '', '', '#000000', 1799.00, 100, '', 'Active', '2026-02-20 09:03:19', '2026-02-20 09:03:19'),
(767, 80, 'VN-69982BBB3F456-1', 'XS', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(768, 80, 'VN-69982BBB3F456-2', 'S', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(769, 80, 'VN-69982BBB3F456-3', 'M', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(770, 80, 'VN-69982BBB3F456-4', 'L', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(771, 80, 'VN-69982BBB3F456-5', 'XL', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(772, 80, 'VN-69982BBB3F456-6', '', '', '#000000', 1999.00, 100, '', 'Active', '2026-02-20 09:39:07', '2026-02-20 09:39:07'),
(789, 88, 'VN-69982C54A80D2-1', 'XS', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:43:01', '2026-02-20 09:43:01'),
(790, 88, 'VN-69982C54A80D2-2', 'S', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:43:01', '2026-02-20 09:43:01'),
(791, 88, 'VN-69982C54A80D2-3', 'M', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:43:01', '2026-02-20 09:43:01'),
(792, 88, 'VN-69982C54A80D2-4', 'L', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:43:01', '2026-02-20 09:43:01'),
(793, 88, 'VN-69982C54A80D2-5', 'XL', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:43:01', '2026-02-20 09:43:01'),
(794, 89, 'VN-69982E38AE448-1', 'XS', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(795, 89, 'VN-69982E38AE448-2', 'S', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(796, 89, 'VN-69982E38AE448-3', 'M', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(797, 89, 'VN-69982E38AE448-4', 'L', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(798, 89, 'VN-69982E38AE448-5', 'XL', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(799, 89, 'VN-69982E38AE448-6', '', '', '#000000', 2499.00, 100, '', 'Active', '2026-02-20 09:49:44', '2026-02-20 09:49:44'),
(800, 93, 'VN-699830BB76828-1', 'XS', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(801, 93, 'VN-699830BB76828-2', 'S', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(802, 93, 'VN-699830BB76828-3', 'M', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(803, 93, 'VN-699830BB76828-4', 'L', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(804, 93, 'VN-699830BB76828-5', 'XL', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(805, 93, 'VN-699830BB76828-6', '', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:00:27', '2026-02-20 10:00:27'),
(817, 96, 'VN-699833DADA269-1', 'XS', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:13:54', '2026-02-20 10:13:54'),
(818, 96, 'VN-699833DADA269-2', 'S', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:13:54', '2026-02-20 10:13:54'),
(819, 96, 'VN-699833DADA269-3', 'M', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:13:54', '2026-02-20 10:13:54'),
(820, 96, 'VN-699833DADA269-4', 'L', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:13:54', '2026-02-20 10:13:54'),
(821, 96, 'VN-699833DADA269-5', 'XL', '', '#000000', 899.00, 100, '', 'Active', '2026-02-20 10:13:54', '2026-02-20 10:13:54'),
(833, 99, 'VN-699835850A8D7-1', 'XS', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-20 10:20:59', '2026-02-20 10:20:59'),
(834, 99, 'VN-699835850A8D7-2', 'S', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-20 10:20:59', '2026-02-20 10:20:59'),
(835, 99, 'VN-699835850A8D7-3', 'M', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-20 10:20:59', '2026-02-20 10:20:59'),
(836, 99, 'VN-699835850A8D7-4', 'L', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-20 10:20:59', '2026-02-20 10:20:59'),
(837, 99, 'VN-699835850A8D7-5', 'XL', '', '#000000', 1699.00, 100, '', 'Active', '2026-02-20 10:20:59', '2026-02-20 10:20:59'),
(854, 102, 'VN-699837A05137F-1', 'XS', '', '#000000', 599.00, 100, '', 'Active', '2026-02-20 10:31:42', '2026-02-20 10:31:42'),
(855, 102, 'VN-699837A05137F-2', 'S', '', '#000000', 599.00, 100, '', 'Active', '2026-02-20 10:31:42', '2026-02-20 10:31:42'),
(856, 102, 'VN-699837A05137F-3', 'M', '', '#000000', 599.00, 100, '', 'Active', '2026-02-20 10:31:42', '2026-02-20 10:31:42'),
(857, 102, 'VN-699837A05137F-4', 'L', '', '#000000', 599.00, 100, '', 'Active', '2026-02-20 10:31:42', '2026-02-20 10:31:42'),
(858, 102, 'VN-699837A05137F-5', 'XL', '', '#000000', 599.00, 100, '', 'Active', '2026-02-20 10:31:42', '2026-02-20 10:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_title` varchar(255) DEFAULT NULL,
  `review_text` text NOT NULL,
  `verified_purchase` tinyint(1) DEFAULT 0,
  `helpful_count` int(11) DEFAULT 0,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `user_id`, `order_id`, `rating`, `review_title`, `review_text`, `verified_purchase`, `helpful_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 21, 14, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-10-03 10:42:04', '2026-02-20 10:42:04'),
(2, 21, 18, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-08-09 10:42:04', '2026-02-20 10:42:04'),
(3, 21, 12, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-11-01 10:42:04', '2026-02-20 10:42:04'),
(4, 21, 16, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2026-01-18 10:42:04', '2026-02-20 10:42:04'),
(5, 22, 17, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-10-13 10:42:04', '2026-02-20 10:42:04'),
(6, 22, 13, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-08-11 10:42:04', '2026-02-20 10:42:04'),
(7, 22, 16, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-08-17 10:42:04', '2026-02-20 10:42:04'),
(8, 22, 14, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-07-03 10:42:04', '2026-02-20 10:42:04'),
(9, 23, 17, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2026-01-01 10:42:04', '2026-02-20 10:42:04'),
(10, 23, 13, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-11-21 10:42:04', '2026-02-20 10:42:04'),
(11, 23, 10, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2026-02-05 10:42:04', '2026-02-20 10:42:04'),
(12, 23, 9, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-12-05 10:42:04', '2026-02-20 10:42:04'),
(13, 24, 17, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-10-19 10:42:04', '2026-02-20 10:42:04'),
(14, 24, 13, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2026-01-11 10:42:04', '2026-02-20 10:42:04'),
(15, 24, 18, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-08-09 10:42:04', '2026-02-20 10:42:04'),
(16, 24, 12, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-10-16 10:42:04', '2026-02-20 10:42:04'),
(17, 25, 13, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-10-08 10:42:04', '2026-02-20 10:42:04'),
(18, 25, 7, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-09-05 10:42:04', '2026-02-20 10:42:04'),
(19, 25, 15, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-09-14 10:42:04', '2026-02-20 10:42:04'),
(20, 25, 9, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-11-11 10:42:04', '2026-02-20 10:42:04'),
(21, 26, 15, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-08-22 10:42:04', '2026-02-20 10:42:04'),
(22, 26, 17, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-09-13 10:42:04', '2026-02-20 10:42:04'),
(23, 26, 7, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-11-19 10:42:04', '2026-02-20 10:42:04'),
(24, 26, 12, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-08-30 10:42:04', '2026-02-20 10:42:04'),
(25, 27, 18, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-08-04 10:42:04', '2026-02-20 10:42:04'),
(26, 27, 16, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2026-01-08 10:42:04', '2026-02-20 10:42:04'),
(27, 27, 13, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-10-22 10:42:04', '2026-02-20 10:42:04'),
(28, 27, 14, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-07-02 10:42:04', '2026-02-20 10:42:04'),
(29, 28, 16, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2026-01-30 10:42:04', '2026-02-20 10:42:04'),
(30, 28, 14, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2026-01-23 10:42:04', '2026-02-20 10:42:04'),
(31, 28, 9, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-10-30 10:42:04', '2026-02-20 10:42:04'),
(32, 28, 13, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-07-06 10:42:04', '2026-02-20 10:42:04'),
(33, 29, 8, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-09-20 10:42:04', '2026-02-20 10:42:04'),
(34, 29, 13, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-02-05 10:42:04', '2026-02-20 10:42:04'),
(35, 29, 10, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-07-24 10:42:04', '2026-02-20 10:42:04'),
(36, 29, 15, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-07-19 10:42:04', '2026-02-20 10:42:04'),
(37, 30, 9, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-10-07 10:42:04', '2026-02-20 10:42:04'),
(38, 30, 11, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2026-01-22 10:42:04', '2026-02-20 10:42:04'),
(39, 30, 14, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2026-02-02 10:42:04', '2026-02-20 10:42:04'),
(40, 30, 10, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-01-11 10:42:04', '2026-02-20 10:42:04'),
(41, 31, 15, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-09-17 10:42:04', '2026-02-20 10:42:04'),
(42, 31, 8, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2026-01-10 10:42:04', '2026-02-20 10:42:04'),
(43, 31, 11, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2026-01-14 10:42:04', '2026-02-20 10:42:04'),
(44, 31, 12, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-09-11 10:42:04', '2026-02-20 10:42:04'),
(45, 39, 16, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-08-05 10:42:04', '2026-02-20 10:42:04'),
(46, 39, 17, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2026-01-14 10:42:04', '2026-02-20 10:42:04'),
(47, 39, 12, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-10-29 10:42:04', '2026-02-20 10:42:04'),
(48, 39, 11, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-11-16 10:42:04', '2026-02-20 10:42:04'),
(49, 40, 17, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-08-11 10:42:04', '2026-02-20 10:42:04'),
(50, 40, 8, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2026-01-25 10:42:04', '2026-02-20 10:42:04'),
(51, 40, 7, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2026-01-28 10:42:04', '2026-02-20 10:42:04'),
(52, 40, 18, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-11-02 10:42:04', '2026-02-20 10:42:04'),
(53, 41, 10, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-12-10 10:42:04', '2026-02-20 10:42:04'),
(54, 41, 17, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-12-29 10:42:04', '2026-02-20 10:42:04'),
(55, 41, 16, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-12-26 10:42:04', '2026-02-20 10:42:04'),
(56, 41, 13, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-07-27 10:42:04', '2026-02-20 10:42:04'),
(57, 42, 17, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-07-22 10:42:04', '2026-02-20 10:42:04'),
(58, 42, 9, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-08-15 10:42:04', '2026-02-20 10:42:04'),
(59, 42, 14, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-07-08 10:42:04', '2026-02-20 10:42:04'),
(60, 42, 18, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-07-18 10:42:04', '2026-02-20 10:42:04'),
(61, 44, 7, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-11-08 10:42:04', '2026-02-20 10:42:04'),
(62, 44, 11, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-07-31 10:42:04', '2026-02-20 10:42:04'),
(63, 44, 13, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-08-28 10:42:04', '2026-02-20 10:42:04'),
(64, 44, 15, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-08-11 10:42:04', '2026-02-20 10:42:04'),
(65, 48, 14, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-06-25 10:42:04', '2026-02-20 10:42:04'),
(66, 48, 16, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-12-07 10:42:04', '2026-02-20 10:42:04'),
(67, 48, 7, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-08-11 10:42:04', '2026-02-20 10:42:04'),
(68, 48, 10, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-09-04 10:42:04', '2026-02-20 10:42:04'),
(69, 49, 8, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-12-21 10:42:04', '2026-02-20 10:42:04'),
(70, 49, 9, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-06-27 10:42:04', '2026-02-20 10:42:04'),
(71, 49, 10, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-10-17 10:42:04', '2026-02-20 10:42:04'),
(72, 49, 14, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-11-29 10:42:04', '2026-02-20 10:42:04'),
(73, 50, 12, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-10-19 10:42:04', '2026-02-20 10:42:04'),
(74, 50, 11, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-07-05 10:42:04', '2026-02-20 10:42:04'),
(75, 50, 17, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2026-01-30 10:42:04', '2026-02-20 10:42:04'),
(76, 50, 8, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-11-11 10:42:04', '2026-02-20 10:42:04'),
(77, 51, 15, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-07-14 10:42:04', '2026-02-20 10:42:04'),
(78, 51, 8, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-09-11 10:42:04', '2026-02-20 10:42:04'),
(79, 51, 7, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-01-29 10:42:04', '2026-02-20 10:42:04'),
(80, 51, 13, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-11-23 10:42:04', '2026-02-20 10:42:04'),
(81, 52, 15, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-11-22 10:42:04', '2026-02-20 10:42:04'),
(82, 52, 17, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-11-12 10:42:04', '2026-02-20 10:42:04'),
(83, 52, 9, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-12-13 10:42:04', '2026-02-20 10:42:04'),
(84, 52, 12, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-12-09 10:42:04', '2026-02-20 10:42:04'),
(85, 53, 17, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-11-23 10:42:04', '2026-02-20 10:42:04'),
(86, 53, 10, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-12-09 10:42:04', '2026-02-20 10:42:04'),
(87, 53, 9, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-10-22 10:42:04', '2026-02-20 10:42:04'),
(88, 53, 14, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2026-01-30 10:42:04', '2026-02-20 10:42:04'),
(89, 54, 12, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-07-12 10:42:04', '2026-02-20 10:42:04'),
(90, 54, 16, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-09-23 10:42:04', '2026-02-20 10:42:04'),
(91, 54, 18, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-09-11 10:42:04', '2026-02-20 10:42:04'),
(92, 54, 17, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-09-03 10:42:04', '2026-02-20 10:42:04'),
(93, 55, 8, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-07-07 10:42:04', '2026-02-20 10:42:04'),
(94, 55, 7, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-12-18 10:42:04', '2026-02-20 10:42:04'),
(95, 55, 10, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2026-02-10 10:42:04', '2026-02-20 10:42:04'),
(96, 55, 13, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-07-08 10:42:04', '2026-02-20 10:42:04'),
(97, 56, 13, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-07-27 10:42:04', '2026-02-20 10:42:04'),
(98, 56, 9, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-08-24 10:42:04', '2026-02-20 10:42:04'),
(99, 56, 8, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-10-24 10:42:04', '2026-02-20 10:42:04'),
(100, 56, 16, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2026-01-11 10:42:04', '2026-02-20 10:42:04'),
(101, 57, 8, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-11-05 10:42:04', '2026-02-20 10:42:04'),
(102, 57, 17, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-12-13 10:42:04', '2026-02-20 10:42:04'),
(103, 57, 14, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2026-01-08 10:42:04', '2026-02-20 10:42:04'),
(104, 57, 18, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-12-05 10:42:04', '2026-02-20 10:42:04'),
(105, 58, 7, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-08-29 10:42:04', '2026-02-20 10:42:04'),
(106, 58, 18, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-08-25 10:42:04', '2026-02-20 10:42:04'),
(107, 58, 9, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-10-08 10:42:04', '2026-02-20 10:42:04'),
(108, 58, 17, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-11-02 10:42:04', '2026-02-20 10:42:04'),
(109, 59, 15, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2026-01-29 10:42:04', '2026-02-20 10:42:04'),
(110, 59, 11, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-11-19 10:42:04', '2026-02-20 10:42:04'),
(111, 59, 14, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-10-24 10:42:04', '2026-02-20 10:42:04'),
(112, 59, 7, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-10-27 10:42:04', '2026-02-20 10:42:04'),
(113, 60, 12, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-10-16 10:42:04', '2026-02-20 10:42:04'),
(114, 60, 16, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-09-04 10:42:04', '2026-02-20 10:42:04'),
(115, 60, 15, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-08-12 10:42:04', '2026-02-20 10:42:04'),
(116, 60, 9, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-09-10 10:42:04', '2026-02-20 10:42:04'),
(117, 61, 14, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-08-05 10:42:04', '2026-02-20 10:42:04'),
(118, 61, 16, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-08-19 10:42:04', '2026-02-20 10:42:04'),
(119, 61, 17, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-10-09 10:42:04', '2026-02-20 10:42:04'),
(120, 61, 8, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-09-24 10:42:04', '2026-02-20 10:42:04'),
(121, 62, 18, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-07-16 10:42:04', '2026-02-20 10:42:04'),
(122, 62, 8, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-07-02 10:42:04', '2026-02-20 10:42:04'),
(123, 62, 10, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-09-23 10:42:04', '2026-02-20 10:42:04'),
(124, 62, 15, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-09-02 10:42:04', '2026-02-20 10:42:04'),
(125, 63, 18, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-09-12 10:42:04', '2026-02-20 10:42:04'),
(126, 63, 12, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-11-21 10:42:04', '2026-02-20 10:42:04');
INSERT INTO `reviews` (`review_id`, `product_id`, `user_id`, `order_id`, `rating`, `review_title`, `review_text`, `verified_purchase`, `helpful_count`, `status`, `created_at`, `updated_at`) VALUES
(127, 63, 17, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2026-02-11 10:42:04', '2026-02-20 10:42:04'),
(128, 63, 13, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-10-06 10:42:04', '2026-02-20 10:42:04'),
(129, 64, 15, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-12-06 10:42:04', '2026-02-20 10:42:04'),
(130, 64, 14, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-01-21 10:42:04', '2026-02-20 10:42:04'),
(131, 64, 12, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-09-18 10:42:04', '2026-02-20 10:42:04'),
(132, 64, 8, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-07-04 10:42:04', '2026-02-20 10:42:04'),
(133, 65, 16, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-08-30 10:42:04', '2026-02-20 10:42:04'),
(134, 65, 10, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2026-01-03 10:42:04', '2026-02-20 10:42:04'),
(135, 65, 17, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-12-03 10:42:04', '2026-02-20 10:42:04'),
(136, 65, 11, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-09-16 10:42:04', '2026-02-20 10:42:04'),
(137, 66, 11, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-11-15 10:42:04', '2026-02-20 10:42:04'),
(138, 66, 8, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-08-14 10:42:04', '2026-02-20 10:42:04'),
(139, 66, 7, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-09-04 10:42:04', '2026-02-20 10:42:04'),
(140, 66, 15, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-01-13 10:42:04', '2026-02-20 10:42:04'),
(141, 67, 18, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-11-24 10:42:04', '2026-02-20 10:42:04'),
(142, 67, 16, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-08-29 10:42:04', '2026-02-20 10:42:04'),
(143, 67, 7, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-10-22 10:42:04', '2026-02-20 10:42:04'),
(144, 67, 12, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-08-15 10:42:04', '2026-02-20 10:42:04'),
(145, 68, 16, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-07-30 10:42:04', '2026-02-20 10:42:04'),
(146, 68, 7, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-11-04 10:42:04', '2026-02-20 10:42:04'),
(147, 68, 12, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-02-03 10:42:04', '2026-02-20 10:42:04'),
(148, 68, 13, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-08-28 10:42:04', '2026-02-20 10:42:04'),
(149, 69, 15, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-07-07 10:42:04', '2026-02-20 10:42:04'),
(150, 69, 9, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-10-30 10:42:04', '2026-02-20 10:42:04'),
(151, 69, 8, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-12-27 10:42:04', '2026-02-20 10:42:04'),
(152, 69, 10, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2026-02-12 10:42:04', '2026-02-20 10:42:04'),
(153, 70, 18, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-01-16 10:42:04', '2026-02-20 10:42:04'),
(154, 70, 10, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-08-30 10:42:04', '2026-02-20 10:42:04'),
(155, 70, 12, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-09-25 10:42:04', '2026-02-20 10:42:04'),
(156, 70, 9, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-08-14 10:42:04', '2026-02-20 10:42:04'),
(157, 71, 11, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2026-02-03 10:42:04', '2026-02-20 10:42:04'),
(158, 71, 10, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-07-06 10:42:04', '2026-02-20 10:42:04'),
(159, 71, 14, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-10-01 10:42:04', '2026-02-20 10:42:04'),
(160, 71, 9, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-09-09 10:42:04', '2026-02-20 10:42:04'),
(161, 72, 11, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-07-03 10:42:04', '2026-02-20 10:42:04'),
(162, 72, 15, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-09-16 10:42:04', '2026-02-20 10:42:04'),
(163, 72, 7, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-09-30 10:42:04', '2026-02-20 10:42:04'),
(164, 72, 18, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-07-18 10:42:04', '2026-02-20 10:42:04'),
(165, 73, 10, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2026-01-30 10:42:04', '2026-02-20 10:42:04'),
(166, 73, 13, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-07-21 10:42:04', '2026-02-20 10:42:04'),
(167, 73, 16, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-09-24 10:42:04', '2026-02-20 10:42:04'),
(168, 73, 8, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-12-12 10:42:04', '2026-02-20 10:42:04'),
(169, 74, 18, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-07-19 10:42:04', '2026-02-20 10:42:04'),
(170, 74, 13, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-09-12 10:42:04', '2026-02-20 10:42:04'),
(171, 74, 11, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-06-26 10:42:04', '2026-02-20 10:42:04'),
(172, 74, 16, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2026-02-09 10:42:04', '2026-02-20 10:42:04'),
(173, 75, 9, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-11-19 10:42:04', '2026-02-20 10:42:04'),
(174, 75, 8, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2026-01-01 10:42:04', '2026-02-20 10:42:04'),
(175, 75, 15, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-10-08 10:42:04', '2026-02-20 10:42:04'),
(176, 75, 10, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-08-12 10:42:04', '2026-02-20 10:42:04'),
(177, 76, 10, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-11-20 10:42:04', '2026-02-20 10:42:04'),
(178, 76, 15, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-07-08 10:42:04', '2026-02-20 10:42:04'),
(179, 76, 14, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-10-26 10:42:04', '2026-02-20 10:42:04'),
(180, 76, 12, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2026-01-31 10:42:04', '2026-02-20 10:42:04'),
(181, 77, 12, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-11-19 10:42:04', '2026-02-20 10:42:04'),
(182, 77, 17, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-12-12 10:42:04', '2026-02-20 10:42:04'),
(183, 77, 10, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-11-02 10:42:04', '2026-02-20 10:42:04'),
(184, 77, 15, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-10-20 10:42:04', '2026-02-20 10:42:04'),
(185, 78, 16, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-11-03 10:42:04', '2026-02-20 10:42:04'),
(186, 78, 13, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-07-02 10:42:04', '2026-02-20 10:42:04'),
(187, 78, 12, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-10-13 10:42:04', '2026-02-20 10:42:04'),
(188, 78, 8, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-07-15 10:42:04', '2026-02-20 10:42:04'),
(189, 79, 12, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2026-02-05 10:42:04', '2026-02-20 10:42:04'),
(190, 79, 8, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-10-02 10:42:04', '2026-02-20 10:42:04'),
(191, 79, 15, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-09-08 10:42:04', '2026-02-20 10:42:04'),
(192, 79, 11, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-08-18 10:42:04', '2026-02-20 10:42:04'),
(193, 80, 12, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-08-12 10:42:04', '2026-02-20 10:42:04'),
(194, 80, 13, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-08-31 10:42:04', '2026-02-20 10:42:04'),
(195, 80, 7, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-07-01 10:42:04', '2026-02-20 10:42:04'),
(196, 80, 11, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2026-02-01 10:42:04', '2026-02-20 10:42:04'),
(197, 81, 15, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-08-17 10:42:04', '2026-02-20 10:42:04'),
(198, 81, 10, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-11-18 10:42:04', '2026-02-20 10:42:04'),
(199, 81, 16, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-01-02 10:42:04', '2026-02-20 10:42:04'),
(200, 81, 11, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2026-01-20 10:42:04', '2026-02-20 10:42:04'),
(201, 82, 11, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-01-05 10:42:04', '2026-02-20 10:42:04'),
(202, 82, 13, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-09-29 10:42:04', '2026-02-20 10:42:04'),
(203, 82, 16, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-10-22 10:42:04', '2026-02-20 10:42:04'),
(204, 82, 9, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2026-01-21 10:42:04', '2026-02-20 10:42:04'),
(205, 83, 7, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-09-15 10:42:04', '2026-02-20 10:42:04'),
(206, 83, 17, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-10-21 10:42:04', '2026-02-20 10:42:04'),
(207, 83, 15, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-02-11 10:42:04', '2026-02-20 10:42:04'),
(208, 83, 9, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2026-01-29 10:42:04', '2026-02-20 10:42:04'),
(209, 84, 10, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-11-11 10:42:04', '2026-02-20 10:42:04'),
(210, 84, 12, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-10-11 10:42:04', '2026-02-20 10:42:04'),
(211, 84, 15, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-08-25 10:42:04', '2026-02-20 10:42:04'),
(212, 84, 18, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-07-24 10:42:04', '2026-02-20 10:42:04'),
(213, 85, 9, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-07-22 10:42:04', '2026-02-20 10:42:04'),
(214, 85, 18, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-11-21 10:42:04', '2026-02-20 10:42:04'),
(215, 85, 7, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2026-02-09 10:42:04', '2026-02-20 10:42:04'),
(216, 85, 16, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-07-07 10:42:04', '2026-02-20 10:42:04'),
(217, 86, 10, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-07-21 10:42:04', '2026-02-20 10:42:04'),
(218, 86, 8, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-06-30 10:42:04', '2026-02-20 10:42:04'),
(219, 86, 14, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-08-20 10:42:04', '2026-02-20 10:42:04'),
(220, 86, 9, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-10-16 10:42:04', '2026-02-20 10:42:04'),
(221, 87, 17, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-02-10 10:42:04', '2026-02-20 10:42:04'),
(222, 87, 16, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2026-01-28 10:42:04', '2026-02-20 10:42:04'),
(223, 87, 9, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-09-24 10:42:04', '2026-02-20 10:42:04'),
(224, 87, 11, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-10-11 10:42:04', '2026-02-20 10:42:04'),
(225, 88, 8, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-11-10 10:42:04', '2026-02-20 10:42:04'),
(226, 88, 10, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-11-05 10:42:04', '2026-02-20 10:42:04'),
(227, 88, 14, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-07-09 10:42:04', '2026-02-20 10:42:04'),
(228, 88, 7, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-08-06 10:42:04', '2026-02-20 10:42:04'),
(229, 89, 9, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-07-07 10:42:04', '2026-02-20 10:42:04'),
(230, 89, 14, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-11-16 10:42:04', '2026-02-20 10:42:04'),
(231, 89, 12, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-09-13 10:42:04', '2026-02-20 10:42:04'),
(232, 89, 13, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-08-31 10:42:04', '2026-02-20 10:42:04'),
(233, 90, 7, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-08-13 10:42:04', '2026-02-20 10:42:04'),
(234, 90, 14, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-07-25 10:42:04', '2026-02-20 10:42:04'),
(235, 90, 13, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2026-01-23 10:42:04', '2026-02-20 10:42:04'),
(236, 90, 17, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-09-23 10:42:04', '2026-02-20 10:42:04'),
(237, 91, 12, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2026-02-05 10:42:04', '2026-02-20 10:42:04'),
(238, 91, 16, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-09-08 10:42:04', '2026-02-20 10:42:04'),
(239, 91, 17, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-10-23 10:42:04', '2026-02-20 10:42:04'),
(240, 91, 7, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-10-20 10:42:04', '2026-02-20 10:42:04'),
(241, 92, 18, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-10-14 10:42:04', '2026-02-20 10:42:04'),
(242, 92, 13, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2025-10-07 10:42:04', '2026-02-20 10:42:04'),
(243, 92, 14, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2026-01-01 10:42:04', '2026-02-20 10:42:04'),
(244, 92, 11, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-11-24 10:42:04', '2026-02-20 10:42:04'),
(245, 93, 17, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-09-10 10:42:04', '2026-02-20 10:42:04'),
(246, 93, 14, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-08-06 10:42:04', '2026-02-20 10:42:04'),
(247, 93, 10, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-08-16 10:42:04', '2026-02-20 10:42:04'),
(248, 93, 7, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-09-01 10:42:04', '2026-02-20 10:42:04'),
(249, 94, 9, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-08-03 10:42:04', '2026-02-20 10:42:04'),
(250, 94, 18, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-12-18 10:42:04', '2026-02-20 10:42:04'),
(251, 94, 11, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-11-29 10:42:04', '2026-02-20 10:42:04');
INSERT INTO `reviews` (`review_id`, `product_id`, `user_id`, `order_id`, `rating`, `review_title`, `review_text`, `verified_purchase`, `helpful_count`, `status`, `created_at`, `updated_at`) VALUES
(252, 94, 12, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-11-25 10:42:04', '2026-02-20 10:42:04'),
(253, 95, 10, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2026-01-23 10:42:04', '2026-02-20 10:42:04'),
(254, 95, 16, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-09-27 10:42:04', '2026-02-20 10:42:04'),
(255, 95, 18, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-11-22 10:42:04', '2026-02-20 10:42:04'),
(256, 95, 15, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-07-13 10:42:04', '2026-02-20 10:42:04'),
(257, 96, 9, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-10-01 10:42:04', '2026-02-20 10:42:04'),
(258, 96, 13, NULL, 5, 'Minimal but Impactful', 'There are no unnecessary details here, and that restraint is exactly what makes it work. The construction is clean, the fit is contemporary, and the fabric quality justifies the price. For anyone building a serious capsule wardrobe, this is required.', 1, 0, 'Approved', '2025-10-12 10:42:04', '2026-02-20 10:42:04'),
(259, 96, 11, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-09-08 10:42:04', '2026-02-20 10:42:04'),
(260, 96, 7, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-08-24 10:42:04', '2026-02-20 10:42:04'),
(261, 97, 10, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2026-02-11 10:42:04', '2026-02-20 10:42:04'),
(262, 97, 9, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2026-01-29 10:42:04', '2026-02-20 10:42:04'),
(263, 97, 16, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-08-20 10:42:04', '2026-02-20 10:42:04'),
(264, 97, 13, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-09-29 10:42:04', '2026-02-20 10:42:04'),
(265, 98, 16, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2026-01-28 10:42:04', '2026-02-20 10:42:04'),
(266, 98, 18, NULL, 5, 'Understated Precision', 'What sets this piece apart is the consistency in construction. The hemline is clean, the collar holds its shape, and the colour has not shifted even after repeated machine washes. It wears well whether layered or worn standalone. Exactly the kind of quiet confidence a premium tee should carry.', 1, 0, 'Approved', '2025-06-29 10:42:04', '2026-02-20 10:42:04'),
(267, 98, 10, NULL, 4, 'A Cut Above the Usual', 'The silhouette is refined — slightly relaxed through the body without being oversized. The fabric has a matte finish that looks elevated compared to standard cotton. After three washes, there is zero pilling or warp. This is the kind of piece you reorder in every colour.', 1, 0, 'Approved', '2025-11-02 10:42:04', '2026-02-20 10:42:04'),
(268, 98, 13, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-12-30 10:42:04', '2026-02-20 10:42:04'),
(269, 99, 15, NULL, 5, 'Clean Lines, Premium Material', 'Very impressed by the material composition. The sweatshirt feels like something from a much higher price bracket. The seams are flat and do not irritate the skin. Colour depth is excellent and has not faded after four washes. Would purchase again without hesitation.', 1, 0, 'Approved', '2025-12-31 10:42:04', '2026-02-20 10:42:04'),
(270, 99, 17, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2025-12-28 10:42:04', '2026-02-20 10:42:04'),
(271, 99, 7, NULL, 5, 'Built to Last', 'Worn regularly over the past two months and it has held up exceptionally well. The seams show no signs of stress and the ribbed hem has retained its elasticity. The colorway is as rich in person as it appears in the product imagery. A genuinely durable piece.', 1, 0, 'Approved', '2025-09-23 10:42:04', '2026-02-20 10:42:04'),
(272, 99, 10, NULL, 5, 'Heritage Design, Modern Quality', 'The balance of premium materials here is notable. The jacket does not feel costume-like, which is a common pitfall for varsity designs. Instead, it wears with authority. The snap buttons on the collar are firm, the pockets are deep, and the inner lining is clean satin. Extremely satisfied.', 1, 0, 'Approved', '2025-10-31 10:42:04', '2026-02-20 10:42:04'),
(273, 100, 18, NULL, 5, 'Solid Investment', 'This is the kind of tee that does not announce itself — it simply looks right. The fabric thickness is ideal for year-round wear. No shrinkage after first wash. Clean stitching throughout. Would recommend sizing up one if you prefer a more relaxed silhouette.', 1, 0, 'Approved', '2025-10-12 10:42:04', '2026-02-20 10:42:04'),
(274, 100, 10, NULL, 5, 'Premium Weight, Luxury Feel', 'The hoodie hits a rare balance — structured enough to wear out, yet soft enough for all-day comfort. The fleece interior is exceptionally warm without adding bulk. The drawstring is weighted and lay flat without bunching. One of the finer hoodies I have owned across any price point.', 1, 0, 'Approved', '2026-01-13 10:42:04', '2026-02-20 10:42:04'),
(275, 100, 7, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2025-08-02 10:42:04', '2026-02-20 10:42:04'),
(276, 100, 17, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-08-20 10:42:04', '2026-02-20 10:42:04'),
(277, 101, 17, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-12-24 10:42:04', '2026-02-20 10:42:04'),
(278, 101, 14, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-09-16 10:42:04', '2026-02-20 10:42:04'),
(279, 101, 18, NULL, 4, 'Refined Utility', 'The cut is modern without being trend-dependent. It layered perfectly under an overcoat during winter and looks just as intentional when worn alone. The kangaroo pocket sits at a natural hand level and the hood itself has good depth. Technically well executed.', 1, 0, 'Approved', '2025-12-28 10:42:04', '2026-02-20 10:42:04'),
(280, 101, 8, NULL, 4, 'Exceptional Comfort, Sharp Look', 'The internal brushed fleece provides the right level of warmth without making the pants feel shapeless or heavy. They drape well when standing and retain structure when seated. A rare combination that most sweatpants get wrong. The colour is deep and has not washed out.', 1, 0, 'Approved', '2026-01-08 10:42:04', '2026-02-20 10:42:04'),
(281, 102, 7, NULL, 5, 'Luxury Loungewear Standard', 'These sweatpants have replaced everything else in my daily rotation. The waistband sits perfectly — no rolling, no tightness. The tapered leg gives a sharp silhouette that transitions well from home to street. The fabric weight is substantial and the drawstring is clean cotton. Absolutely worth it.', 1, 0, 'Approved', '2025-12-20 10:42:04', '2026-02-20 10:42:04'),
(282, 102, 9, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-11-12 10:42:04', '2026-02-20 10:42:04'),
(283, 102, 12, NULL, 5, 'Superior Fleece Construction', 'Unlike cheaper alternatives, this hoodie does not thin or bobble after washing. The inner fleece remains intact and plush, and the outer face looks clean and fresh after multiple cycles. The fit is relaxed but still tailored around the shoulders. Very well done.', 1, 0, 'Approved', '2025-08-14 10:42:04', '2026-02-20 10:42:04'),
(284, 102, 13, NULL, 4, 'Sharp Construction, Strong Presence', 'The contrast between the body and sleeves is executed with precision — clean seam lines and no visible puckering. The hardware feels solid and appropriately weighted. Sized up one for a relaxed fit over thicker layers. The jacket holds its structure in both cases. Confident purchase.', 1, 0, 'Approved', '2025-07-29 10:42:04', '2026-02-20 10:42:04'),
(285, 103, 17, NULL, 4, 'Structured Comfort', 'The cotton-blend used here gives the sweatshirt a very structured silhouette that most competitors achieve only through heavy interfacing. It drapes naturally and holds its shape through a full day of wear. The crew neck is reinforced well. Exceptional value relative to the quality delivered.', 1, 0, 'Approved', '2025-08-29 10:42:04', '2026-02-20 10:42:04'),
(286, 103, 18, NULL, 5, 'Tailored Athleisure Done Properly', 'The fit on these is significantly more refined than standard track pants. The taper through the calf is calculated, not aggressive. Pockets sit flush and do not create bulk. The elastic cuff around the ankle holds without constricting. Durable, flattering, and low-maintenance.', 1, 0, 'Approved', '2025-12-03 10:42:04', '2026-02-20 10:42:04'),
(287, 103, 9, NULL, 4, 'Well-Balanced Fit and Fabric', 'The length and proportions are spot on for a modern drop-shoulder fit. The cotton feels premium to the touch and the dye is rich and saturated. Only minor feedback would be that it takes one wash to fully soften, but after that it drapes beautifully.', 1, 0, 'Approved', '2025-07-17 10:42:04', '2026-02-20 10:42:04'),
(288, 103, 7, NULL, 5, 'Craftsmanship You Can Feel', 'The weight and drape of the fabric is immediately apparent — this is not your average tee. The GSM is substantial without being heavy, and the finish retains its structure even after several washes. Sizing runs true, and the shoulder seams sit exactly where they should. A wardrobe essential done right.', 1, 0, 'Approved', '2025-07-03 10:42:04', '2026-02-20 10:42:04'),
(289, 104, 18, NULL, 5, 'Consistent Across Washes', 'After six washes, these pants look and feel the same as day one. No shrinkage, no colour bleed, and the hem has held its shape. The stitching around the crotch and inseam has not shown any signs of stress. Built with clear intention. Highly recommend.', 1, 0, 'Approved', '2026-01-17 10:42:04', '2026-02-20 10:42:04'),
(290, 104, 11, NULL, 5, 'Iconic Silhouette, Premium Execution', 'The jacket carries a classic varsity silhouette but the materials elevate it far beyond nostalgia. The wool-blend body is dense and structured, and the leather-look sleeves are supple without cracking. The ribbed collar, cuffs, and hem all sit tight without being restrictive. This is statement outerwear done credibly.', 1, 0, 'Approved', '2025-09-13 10:42:04', '2026-02-20 10:42:04'),
(291, 104, 14, NULL, 5, 'Effortless Quality', 'The sweatshirt fits the way a great piece of clothing should — without effort. The neckline does not stretch out, the cuffs hold their form, and the body does not ride up. The fabric is mid-weight and appropriate for both casual and semi-formal settings. A genuinely versatile piece.', 1, 0, 'Approved', '2025-11-19 10:42:04', '2026-02-20 10:42:04'),
(292, 104, 16, NULL, 5, 'Outerwear With Character', 'Purchasing a varsity jacket at this quality level requires confidence from the brand, and VÉNARO delivers. The fit is generous through the chest without excess shoulder width. The zip is smooth and the lining adds warmth without compromising the silhouette. A piece that will age well.', 1, 0, 'Approved', '2025-09-23 10:42:04', '2026-02-20 10:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','number','boolean','json') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
(1, 'site_name', 'VÉNARO', 'text', '2026-02-11 09:51:17'),
(2, 'site_tagline', 'Redefining Modern Fashion', 'text', '2026-02-11 09:51:17'),
(3, 'contact_email', 'info@VÉNARO.com', 'text', '2026-02-20 10:41:55'),
(4, 'contact_phone', '+91 98765 43210', 'text', '2026-02-20 10:41:55'),
(5, 'free_shipping_threshold', '999', 'number', '2026-02-11 09:51:17'),
(6, 'tax_rate', '12', 'number', '2026-02-13 02:31:30'),
(7, 'currency', 'INR', 'text', '2026-02-11 09:51:17'),
(8, 'currency_symbol', '₹', 'text', '2026-02-11 09:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `issue_type` enum('Order Issue','Product Question','Returns & Exchanges','Shipping & Delivery','Payment Issue','Website Feedback','Other') NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `status` enum('Open','In Progress','Waiting for Customer','Resolved','Closed') DEFAULT 'Open',
  `priority` enum('Low','Medium','High','Urgent') DEFAULT 'Medium',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `message_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `sender_type` enum('Customer','Admin') NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `is_internal_note` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('Active','Blocked') DEFAULT 'Active',
  `email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `phone`, `date_of_birth`, `gender`, `profile_photo`, `status`, `email_verified`, `created_at`, `updated_at`, `last_login`) VALUES
(2, 'Test User', 'test.user+reg1@example.com', '$2y$12$ewJZlFjJ1FHHMLkFe.77He4.EZSykDF8VjAwxBGH.Q/IeTni0nOIG', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-18 02:43:40', '2026-02-18 02:43:40', NULL),
(3, 'Test User', 'testuser_wishlist@example.com', '$2y$12$hgceN.1UiC.EVMZI3aGX3OKXlOyDYV5Rm2hF7.7.Lh.cnQ85iLC/e', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-18 02:44:55', '2026-02-18 02:44:55', NULL),
(4, 'Test User', 'example@gmail.com', '$2y$12$rYfHjPeGoVbLAteZeZydpOADxdgfJso38UfCzlVpBlLlAplKNw.BW', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-18 02:45:04', '2026-02-18 02:45:27', '2026-02-18 02:45:27'),
(5, 'Test User', 'testuser@example.com', '$2y$12$F.i/UOkAfjRLCRNC5i/NWelkyfjJR2bSfcpkiW3pp1VWb7/bksoGi', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-18 02:45:05', '2026-02-18 02:45:05', NULL),
(7, 'Aryan Mehta', 'aryan.mehta@VÉNARO.com', '$2y$10$sNx9RqGk0Z.UKxJrJL/faeuV77UDSLaupDwz4llD5Tg4OI6isDILe', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(8, 'Kabir Singh', 'kabir.singh@VÉNARO.com', '$2y$10$xx0bL/MItNanKBjHXK7cyOzAev2/UJCa0JiW44IziHqtthurLt056', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(9, 'Rohan Verma', 'rohan.verma@VÉNARO.com', '$2y$10$vQFMr9qA0LD06fK6q738yu3e6gpHBf3sA9xqyUKYiUDRp/2NCFkFK', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(10, 'Aditya Sharma', 'aditya.sharma@VÉNARO.com', '$2y$10$WpER2k5PECNGWmg9HiZgu.YYV7tTX/B1FJ2QjTTIp6WapttGPDbw.', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(11, 'Vikram Nair', 'vikram.nair@VÉNARO.com', '$2y$10$gLiCT3f35k3bavajyGdGcuQsUgq42NRstFlmIEHDg8OfLfMD44xqK', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(12, 'Dhruv Kapoor', 'dhruv.kapoor@VÉNARO.com', '$2y$10$8hOvX41bEOJQhC021mhyXeP6vbS9RNwbd.VCuJHGGMFs7vFiOILWi', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(13, 'Ishaan Joshi', 'ishaan.joshi@VÉNARO.com', '$2y$10$iV.b.cm61dXNfiwl30gUTeXWZoXbRjkQHPbSOLf8Jfz7TnA5wkvX6', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(14, 'Karan Malhotra', 'karan.malhotra@VÉNARO.com', '$2y$10$ULAT9CW4OIMZ5P1Js19AK.hqMrGyzqWdelus4aAYwuyQfkcJR7dlW', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(15, 'Samarth Gupta', 'samarth.gupta@VÉNARO.com', '$2y$10$QX7OvFDKnlIlj6YDftkcfe8ieG0amk6Ks7rq7iTNTzkh8OjVDMqly', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(16, 'Nikhil Reddy', 'nikhil.reddy@VÉNARO.com', '$2y$10$9RucJefk7fO23Imqz2sK0uWfk.iNg22wtw/0Dx76w/YJ0B25szoAm', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:03', '2026-02-20 10:42:03', NULL),
(17, 'Rahul Choudhary', 'rahul.choudhary@VÉNARO.com', '$2y$10$bgc4mMoQx8CAqwxgFT42ReUvSMh3OV6myGgml9P.znblBN29GAwSu', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:04', '2026-02-20 10:42:04', NULL),
(18, 'Arjun Patel', 'arjun.patel@VÉNARO.com', '$2y$10$fsiWilZNXFZX/NHsHZyM7Ognp1ZjeFUyhoJ7ZDzJXdbeAE/ULmC1u', NULL, NULL, NULL, NULL, 'Active', 0, '2026-02-20 10:42:04', '2026-02-20 10:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_default` (`is_default`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_session_id` (`session_id`),
  ADD KEY `idx_cart_session` (`session_id`,`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_parent` (`parent_category_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_category_parent` (`parent_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`),
  ADD KEY `idx_coupon_code` (`coupon_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_expiry_date` (`expiry_date`);

--
-- Indexes for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_coupon_id` (`coupon_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`subscriber_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `shipping_address_id` (`shipping_address_id`),
  ADD KEY `billing_address_id` (`billing_address_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_guest_email` (`guest_email`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_orders_created` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_sku` (`sku`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_stock_status` (`stock_status`),
  ADD KEY `idx_products_price` (`regular_price`),
  ADD KEY `idx_products_created` (`created_at`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_search` (`product_name`,`short_description`,`long_description`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_collections`
--
ALTER TABLE `product_collections`
  ADD PRIMARY KEY (`product_id`,`collection_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_is_primary` (`is_primary`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_sku` (`sku`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_ticket_number` (`ticket_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `idx_ticket_id` (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=859;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`coupon_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupon_usage_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`address_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`address_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_collections`
--
ALTER TABLE `product_collections`
  ADD CONSTRAINT `product_collections_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_collections_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`ticket_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
