-- ============================================================
-- Миграция 002: Общие измерения (SHD)
-- Таблицы: general_traffic, general_clientids, general_dates
-- ============================================================

CREATE TABLE IF NOT EXISTS `general_dates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `year` SMALLINT UNSIGNED NOT NULL,
    `quarter` TINYINT UNSIGNED NOT NULL,
    `month` TINYINT UNSIGNED NOT NULL,
    `week` TINYINT UNSIGNED NOT NULL,
    `day` TINYINT UNSIGNED NOT NULL,
    `day_of_week` TINYINT UNSIGNED NOT NULL,
    `is_weekend` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_holiday` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_account_date` (`account_id`, `date`),
    UNIQUE KEY `uq_account_date` (`account_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `general_traffic` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `traffic_id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NULL,
    `source` VARCHAR(255) NULL,
    `medium` VARCHAR(255) NULL,
    `campaign` VARCHAR(255) NULL,
    `term` VARCHAR(255) NULL,
    `content` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    INDEX `idx_account_traffic` (`account_id`, `traffic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `general_clientids` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `client_id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NULL,
    `first_seen_at` DATETIME NULL,
    `last_seen_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    INDEX `idx_account_clientid` (`account_id`, `client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
