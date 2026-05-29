-- SQL dump para importar en InfinityFree via phpMyAdmin
-- Base de datos: penca_kp

CREATE TABLE IF NOT EXISTS `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `group_letter` varchar(1) NOT NULL COMMENT 'Grupo A-L',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `teams` (`id`, `name`, `group_letter`, `created_at`, `updated_at`) VALUES
(1, 'Mexico', 'A', NOW(), NOW()),
(2, 'South Africa', 'A', NOW(), NOW()),
(3, 'South Korea', 'A', NOW(), NOW()),
(4, 'Czech Republic', 'A', NOW(), NOW()),
(5, 'Canada', 'B', NOW(), NOW()),
(6, 'Bosnia and Herzegovina', 'B', NOW(), NOW()),
(7, 'Qatar', 'B', NOW(), NOW()),
(8, 'Switzerland', 'B', NOW(), NOW()),
(9, 'Brazil', 'C', NOW(), NOW()),
(10, 'Morocco', 'C', NOW(), NOW()),
(11, 'Haiti', 'C', NOW(), NOW()),
(12, 'Scotland', 'C', NOW(), NOW()),
(13, 'United States', 'D', NOW(), NOW()),
(14, 'Paraguay', 'D', NOW(), NOW()),
(15, 'Australia', 'D', NOW(), NOW()),
(16, 'Turkey', 'D', NOW(), NOW()),
(17, 'Germany', 'E', NOW(), NOW()),
(18, 'Curacao', 'E', NOW(), NOW()),
(19, 'Ivory Coast', 'E', NOW(), NOW()),
(20, 'Ecuador', 'E', NOW(), NOW()),
(21, 'Netherlands', 'F', NOW(), NOW()),
(22, 'Japan', 'F', NOW(), NOW()),
(23, 'Sweden', 'F', NOW(), NOW()),
(24, 'Tunisia', 'F', NOW(), NOW()),
(25, 'Belgium', 'G', NOW(), NOW()),
(26, 'Egypt', 'G', NOW(), NOW()),
(27, 'Iran', 'G', NOW(), NOW()),
(28, 'New Zealand', 'G', NOW(), NOW()),
(29, 'Spain', 'H', NOW(), NOW()),
(30, 'Cape Verde', 'H', NOW(), NOW()),
(31, 'Saudi Arabia', 'H', NOW(), NOW()),
(32, 'Uruguay', 'H', NOW(), NOW()),
(33, 'France', 'I', NOW(), NOW()),
(34, 'Senegal', 'I', NOW(), NOW()),
(35, 'Norway', 'I', NOW(), NOW()),
(36, 'Iraq', 'I', NOW(), NOW()),
(37, 'Argentina', 'J', NOW(), NOW()),
(38, 'Algeria', 'J', NOW(), NOW()),
(39, 'Austria', 'J', NOW(), NOW()),
(40, 'Jordan', 'J', NOW(), NOW()),
(41, 'Portugal', 'K', NOW(), NOW()),
(42, 'DR Congo', 'K', NOW(), NOW()),
(43, 'Uzbekistan', 'K', NOW(), NOW()),
(44, 'Colombia', 'K', NOW(), NOW()),
(45, 'England', 'L', NOW(), NOW()),
(46, 'Croatia', 'L', NOW(), NOW()),
(47, 'Ghana', 'L', NOW(), NOW()),
(48, 'Panama', 'L', NOW(), NOW());

CREATE TABLE IF NOT EXISTS `matches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team1_id` bigint unsigned NOT NULL,
  `team2_id` bigint unsigned NOT NULL,
  `match_date` date NOT NULL,
  `match_time` time NOT NULL,
  `score1` int DEFAULT NULL,
  `score2` int DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matches_team1_id_foreign` (`team1_id`),
  KEY `matches_team2_id_foreign` (`team2_id`),
  CONSTRAINT `matches_team1_id_foreign` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matches_team2_id_foreign` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `predictions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `match_id` bigint unsigned NOT NULL,
  `score1` int NOT NULL,
  `score2` int NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `predictions_user_name_match_id_unique` (`user_name`,`match_id`),
  KEY `predictions_match_id_foreign` (`match_id`),
  CONSTRAINT `predictions_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
