-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for suit
CREATE DATABASE IF NOT EXISTS `suit` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `suit`;

-- Dumping structure for table suit.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `address_line_1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.addresses: ~0 rows (approximately)

-- Dumping structure for table suit.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.brands: ~3 rows (approximately)
INSERT INTO `brands` (`id`, `name`, `email`, `phone`, `number`, `address`, `slug`, `description`, `logo`, `banner`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'Gull Ahmad', NULL, NULL, NULL, NULL, 'gull-ahmad', NULL, NULL, NULL, 1, 1, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(2, 'Apna Raza', NULL, NULL, NULL, NULL, 'apna-raza', NULL, NULL, NULL, 1, 2, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(3, 'Eran Strong', NULL, NULL, NULL, NULL, 'eran-strong', NULL, NULL, NULL, 1, 3, '2026-09-10 17:55:17', '2026-09-10 17:55:17');

-- Dumping structure for table suit.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.categories: ~6 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'Cotton', 'cotton', 'Premium cotton fabrics', NULL, 1, 1, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(2, 'Wash & Wear', 'wash-wear', 'Easy care wash and wear fabrics', NULL, 1, 2, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(3, 'Khaddar', 'khaddar', 'Traditional khaddar fabric', NULL, 1, 3, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(4, 'Linen', 'linen', 'Fine linen fabrics', NULL, 1, 4, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(5, 'Boski', 'boski', 'Boski fabric collection', NULL, 1, 5, '2026-09-10 17:55:17', '2026-09-10 17:55:17'),
	(6, 'Dhanak', 'dhanak', 'Dhanak fabric collection', NULL, 1, 6, '2026-09-10 17:55:17', '2026-09-10 17:55:17');

-- Dumping structure for table suit.collection_sections
CREATE TABLE IF NOT EXISTS `collection_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_bg_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gold',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Shop Now',
  `button_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `display_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_sections_section_key_unique` (`section_key`),
  KEY `collection_sections_created_by_foreign` (`created_by`),
  KEY `collection_sections_updated_by_foreign` (`updated_by`),
  CONSTRAINT `collection_sections_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collection_sections_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.collection_sections: ~0 rows (approximately)

-- Dumping structure for table suit.collection_section_images
CREATE TABLE IF NOT EXISTS `collection_section_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `collection_section_id` bigint unsigned NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_alt_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_section_images_collection_section_id_foreign` (`collection_section_id`),
  CONSTRAINT `collection_section_images_collection_section_id_foreign` FOREIGN KEY (`collection_section_id`) REFERENCES `collection_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.collection_section_images: ~0 rows (approximately)

-- Dumping structure for table suit.coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `minimum_amount` decimal(10,2) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.coupons: ~0 rows (approximately)

-- Dumping structure for table suit.customer_measurements
CREATE TABLE IF NOT EXISTS `customer_measurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chest` decimal(8,2) DEFAULT NULL,
  `waist` decimal(8,2) DEFAULT NULL,
  `hips` decimal(8,2) DEFAULT NULL,
  `shoulder` decimal(8,2) DEFAULT NULL,
  `sleeve_length` decimal(8,2) DEFAULT NULL,
  `torso_length` decimal(8,2) DEFAULT NULL,
  `inseam` decimal(8,2) DEFAULT NULL,
  `neck` decimal(8,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_measurements_user_id_foreign` (`user_id`),
  CONSTRAINT `customer_measurements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.customer_measurements: ~0 rows (approximately)

-- Dumping structure for table suit.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `reserved_quantity` int NOT NULL DEFAULT '0',
  `reorder_level` int NOT NULL DEFAULT '10',
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_per_unit` decimal(10,2) DEFAULT '0.00',
  `last_restock_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_id_size_color_unique` (`product_id`,`size`,`color`),
  CONSTRAINT `inventory_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.inventory: ~0 rows (approximately)

-- Dumping structure for table suit.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.migrations: ~44 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2024_07_08_000001_create_categories_table', 1),
	(2, '2024_07_08_000002_create_products_table', 1),
	(3, '2026_07_08_040347_add_new_fields_to_products_table', 1),
	(4, '2026_07_29_000019_create_product_display_sections_table', 2),
	(5, '2026_07_29_000042_create_product_images_table', 2),
	(6, '2026_07_29_000101_add_product_sections_fields_to_products_table', 2),
	(7, '2026_07_29_001543_fix_product_images_table_columns', 2),
	(8, '2026_07_29_002735_make_sku_nullable_in_products_table', 2),
	(9, '2026_07_31_000001_create_brands_table', 2),
	(10, '2026_07_31_000002_add_brand_id_to_products_table', 2),
	(11, '2014_10_12_000000_create_users_table', 3),
	(12, '2014_10_12_100000_create_password_resets_table', 3),
	(13, '2019_12_14_000001_create_personal_access_tokens_table', 3),
	(14, '2023_05_28_090500_add_login_fields_to_users_table', 3),
	(15, '2023_06_12_013333_add_profile_photo_path_column_to_users_table', 3),
	(16, '2026_07_09_032312_add_is_blocked_to_users_table', 3),
	(17, '2026_07_31_012551_create_collection_sections_table', 3),
	(18, '2026_07_31_012623_create_collection_section_images_table', 3),
	(19, '2026_07_31_013000_fix_product_display_sections_enum', 3),
	(20, '2026_07_31_020000_update_categories_to_fabric_types', 3),
	(21, '2026_07_31_030000_migrate_brand_text_to_brand_id', 3),
	(22, '2026_07_31_040000_add_contact_fields_to_brands_table', 3),
	(23, '2026_08_01_100001_update_collection_sections_with_brand_links', 3),
	(24, '2026_07_09_000001_create_website_cms_table', 4),
	(25, '2024_07_01_100049_create_permission_tables', 5),
	(26, '2024_07_01_100050_seed_default_roles', 6),
	(27, '2023_10_09_041104_create_addresses_table', 7),
	(28, '2024_07_08_000003_create_customer_measurements_table', 7),
	(29, '2024_07_08_000004_create_orders_table', 7),
	(30, '2024_07_08_000005_create_order_items_table', 7),
	(31, '2024_07_08_000006_create_stitching_orders_table', 7),
	(32, '2024_07_08_create_coupons_table', 7),
	(33, '2024_07_08_create_inventory_table', 7),
	(34, '2024_07_08_create_payments_table', 7),
	(35, '2024_07_08_create_receptionists_table', 7),
	(36, '2024_07_08_create_tailors_table', 7),
	(37, '2026_07_08_041000_add_service_options_to_stitching_orders_table', 7),
	(38, '2026_07_09_000002_add_inventory_fields', 7),
	(39, '2026_07_09_000003_create_settings_table', 7),
	(40, '2026_09_11_000001_add_contact_number_to_users_table', 8),
	(41, '2026_09_11_000002_sync_super_admin_permissions', 9),
	(42, '2026_09_11_000003_seed_application_permissions', 10),
	(43, '2026_09_11_000004_remove_administrator_role', 11),
	(44, '2026_09_11_000005_create_password_reset_otps_table', 12);

-- Dumping structure for table suit.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table suit.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.model_has_roles: ~3 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(4, 'App\\Models\\User', 3);

-- Dumping structure for table suit.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ready_made','stitching','combined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ready_made',
  `status` enum('pending','confirmed','in_progress','ready','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stitching_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.orders: ~0 rows (approximately)

-- Dumping structure for table suit.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.order_items: ~0 rows (approximately)

-- Dumping structure for table suit.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.password_resets: ~0 rows (approximately)

-- Dumping structure for table suit.password_reset_otps
CREATE TABLE IF NOT EXISTS `password_reset_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_hash` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_otps_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.password_reset_otps: ~0 rows (approximately)

-- Dumping structure for table suit.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('credit_card','debit_card','bank_transfer','cash','wallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount` decimal(10,2) NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.payments: ~0 rows (approximately)

-- Dumping structure for table suit.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.permissions: ~47 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_dashboard', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(2, 'view_users', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(3, 'create_users', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(4, 'edit_users', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(5, 'delete_users', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(6, 'view_roles', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(7, 'create_roles', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(8, 'edit_roles', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(9, 'delete_roles', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(10, 'view_permissions', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(11, 'manage_permissions', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(12, 'view_products', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(13, 'create_products', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(14, 'edit_products', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(15, 'delete_products', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(16, 'view_categories', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(17, 'create_categories', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(18, 'edit_categories', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(19, 'delete_categories', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(20, 'view_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(21, 'create_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(22, 'edit_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(23, 'delete_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(24, 'view_stitching_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(25, 'assign_stitching_orders', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(26, 'update_stitching_status', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(27, 'view_customers', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(28, 'create_customers', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(29, 'edit_customers', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(30, 'delete_customers', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(31, 'block_customers', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(32, 'create_measurements', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(33, 'edit_measurements', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(34, 'delete_measurements', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(35, 'view_tailors', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(36, 'assign_tailors', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(37, 'view_reports', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(38, 'export_reports', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(39, 'view_receptionists', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(40, 'manage_receptionists', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(41, 'view_payments', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(42, 'manage_payments', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(43, 'view_coupons', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(44, 'manage_coupons', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(45, 'view_settings', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(46, 'manage_settings', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27'),
	(47, 'manage_website', 'web', '2026-09-10 19:10:27', '2026-09-10 19:10:27');

-- Dumping structure for table suit.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table suit.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `full_description` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `regular_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_colors` json DEFAULT NULL,
  `material` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fabric_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_sizes` json DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.products: ~0 rows (approximately)

-- Dumping structure for table suit.product_display_sections
CREATE TABLE IF NOT EXISTS `product_display_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `section` enum('home_featured','shop_page','new_in','brands_page','collections','best_sellers','summer_2026') COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_display_sections_product_id_section_unique` (`product_id`,`section`),
  KEY `product_display_sections_section_index` (`section`),
  KEY `product_display_sections_section_is_active_index` (`section`,`is_active`),
  KEY `product_display_sections_section_display_order_index` (`section`,`display_order`),
  CONSTRAINT `product_display_sections_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.product_display_sections: ~0 rows (approximately)

-- Dumping structure for table suit.product_images
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `display_order` int NOT NULL DEFAULT '0',
  `alt_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_index` (`product_id`),
  KEY `product_images_product_id_is_featured_index` (`product_id`,`is_featured`),
  KEY `product_images_product_id_display_order_index` (`product_id`,`display_order`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.product_images: ~0 rows (approximately)

-- Dumping structure for table suit.receptionists
CREATE TABLE IF NOT EXISTS `receptionists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receptionists_user_id_foreign` (`user_id`),
  CONSTRAINT `receptionists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.receptionists: ~0 rows (approximately)

-- Dumping structure for table suit.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'customer', 'web', '2026-09-10 18:12:37', '2026-09-10 18:12:37'),
	(2, 'super_admin', 'web', '2026-09-10 18:12:37', '2026-09-10 18:12:37'),
	(4, 'receptionist', 'web', '2026-09-10 18:12:37', '2026-09-10 18:12:37'),
	(5, 'tailor', 'web', '2026-09-10 18:12:37', '2026-09-10 18:12:37');

-- Dumping structure for table suit.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.role_has_permissions: ~47 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(12, 2),
	(13, 2),
	(14, 2),
	(15, 2),
	(16, 2),
	(17, 2),
	(18, 2),
	(19, 2),
	(20, 2),
	(21, 2),
	(22, 2),
	(23, 2),
	(24, 2),
	(25, 2),
	(26, 2),
	(27, 2),
	(28, 2),
	(29, 2),
	(30, 2),
	(31, 2),
	(32, 2),
	(33, 2),
	(34, 2),
	(35, 2),
	(36, 2),
	(37, 2),
	(38, 2),
	(39, 2),
	(40, 2),
	(41, 2),
	(42, 2),
	(43, 2),
	(44, 2),
	(45, 2),
	(46, 2),
	(47, 2);

-- Dumping structure for table suit.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` enum('string','integer','boolean','json','text') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` enum('shop','payment','email','website','general','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.settings: ~0 rows (approximately)

-- Dumping structure for table suit.stitching_orders
CREATE TABLE IF NOT EXISTS `stitching_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `measurement_id` bigint unsigned DEFAULT NULL,
  `tailor_id` bigint unsigned DEFAULT NULL,
  `garment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_option` enum('cloth_only','cloth_stitching','stitching_only') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cloth_stitching',
  `fabric_details` text COLLATE utf8mb4_unicode_ci,
  `design_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stitching_status` enum('pending','assigned','in_progress','ready_for_fitting','in_fitting','ready','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `special_instructions` text COLLATE utf8mb4_unicode_ci,
  `additional_instructions` text COLLATE utf8mb4_unicode_ci,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `service_request_date` timestamp NULL DEFAULT NULL,
  `assigned_date` timestamp NULL DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `fitting_date` timestamp NULL DEFAULT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `tailor_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stitching_orders_order_id_foreign` (`order_id`),
  KEY `stitching_orders_measurement_id_foreign` (`measurement_id`),
  KEY `stitching_orders_tailor_id_foreign` (`tailor_id`),
  CONSTRAINT `stitching_orders_measurement_id_foreign` FOREIGN KEY (`measurement_id`) REFERENCES `customer_measurements` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stitching_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stitching_orders_tailor_id_foreign` FOREIGN KEY (`tailor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.stitching_orders: ~0 rows (approximately)

-- Dumping structure for table suit.tailors
CREATE TABLE IF NOT EXISTS `tailors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `specialization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `experience_years` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive','on_leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `total_orders` int NOT NULL DEFAULT '0',
  `average_rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tailors_user_id_foreign` (`user_id`),
  CONSTRAINT `tailors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.tailors: ~0 rows (approximately)

-- Dumping structure for table suit.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `contact_number`, `profile_photo_path`, `email_verified_at`, `is_blocked`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`, `last_login_at`, `last_login_ip`) VALUES
	(1, 'Amjid', 'amjidmsd25@gmail.com', NULL, NULL, '2026-09-10 18:18:26', 0, '$2y$10$.mqktvlpg0Sj.N52RNvaveVoyGhW/aEcalQwd5V.SF0KMIEViulea', NULL, '5wf2KCf2MT8BDkPqA0Vw9YvoaYKTH3WlfjlYDriXEbVDWcD4CwIuWH4sqgcQ', '2026-09-10 18:10:24', '2026-09-11 00:29:22', '2026-09-10 23:25:20', '127.0.0.1'),
	(2, 'Super Admin', 'amjidmsd26@gmail.com', '03239123800', NULL, '2026-09-10 18:29:47', 0, '$2y$10$RRfJe1W9AHY2nYt92sfICOTGKpzd7P55aabMZ9h1YZrxczNM78Jh2', NULL, 'tA4DPwW4jXLEXdyd069IY1miRbVdGocdqkrsnR8KyEXJANNa20jrPsTrCgUx', '2026-09-10 18:26:41', '2026-09-11 00:12:33', '2026-09-11 05:12:33', '127.0.0.1'),
	(3, 'Sohail Khan', 'sohailkhan52117@gmail.com', NULL, NULL, NULL, 0, '$2y$10$zgNSfewuCyWfAVstxYLopuxT73Rajy92RWys48p7yJL0wBz0zmKb6', NULL, NULL, '2026-09-10 20:11:33', '2026-09-10 20:11:33', NULL, NULL);

-- Dumping structure for table suit.website_cms
CREATE TABLE IF NOT EXISTS `website_cms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'slider',
  `page_slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `data` json DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `website_cms_created_by_foreign` (`created_by`),
  KEY `website_cms_section_type_index` (`section_type`),
  KEY `website_cms_is_published_index` (`is_published`),
  KEY `website_cms_display_order_index` (`display_order`),
  CONSTRAINT `website_cms_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table suit.website_cms: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
