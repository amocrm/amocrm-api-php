-- ============================================================
-- Миграция 009: Измерение — Покупатели
-- Таблицы: amocrm_customers, customers_attributes, customers_tags, customers_notes
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_customers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `contact_name` VARCHAR(500) NULL,
    `company_name` VARCHAR(500) NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `status_id` BIGINT UNSIGNED NULL,
    `periodicity_id` BIGINT UNSIGNED NULL,
    `period_id` INT UNSIGNED NULL,
    `next_price` DECIMAL(15,2) NULL,
    `ltv` DECIMAL(15,2) NULL,
    `purchases` INT UNSIGNED NULL,
    `average_check` DECIMAL(15,2) NULL,
    `next_date` DATETIME NULL,
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_customer` (`account_id`, `customer_id`),
    INDEX `idx_account_contact` (`account_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_customers_attributes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `field_id` BIGINT UNSIGNED NOT NULL,
    `field_name` VARCHAR(255) NULL,
    `field_type` VARCHAR(50) NULL,
    `field_code` VARCHAR(100) NULL,
    `value` TEXT NULL,
    `enum_id` BIGINT UNSIGNED NULL,
    `enum_code` VARCHAR(100) NULL,
    INDEX `idx_account_customer_field` (`account_id`, `customer_id`, `field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_customers_tags` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `color` VARCHAR(20) NULL,
    INDEX `idx_account_customer_tag` (`account_id`, `customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_customers_notes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `note_id` BIGINT UNSIGNED NOT NULL,
    `note_type` VARCHAR(50) NULL,
    `element_id` BIGINT UNSIGNED NULL,
    `element_type` VARCHAR(50) NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `text` TEXT NULL,
    `params` JSON NULL,
    `group_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_customer_note` (`account_id`, `customer_id`, `note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
