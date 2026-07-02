-- ============================================================
-- Миграция 012: Измерение — Элементы каталогов
-- Таблицы: amocrm_elements, amocrm_elements_attributes, amocrm_elements_products
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_elements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `catalog_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `article` VARCHAR(255) NULL,
    `price` DECIMAL(15,2) NULL,
    `quantity` DECIMAL(15,4) NULL,
    `external_id` VARCHAR(255) NULL,
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_element` (`account_id`, `element_id`),
    INDEX `idx_account_catalog` (`account_id`, `catalog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_elements_attributes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `field_id` BIGINT UNSIGNED NOT NULL,
    `field_name` VARCHAR(255) NULL,
    `field_type` VARCHAR(50) NULL,
    `field_code` VARCHAR(100) NULL,
    `value` TEXT NULL,
    `enum_id` BIGINT UNSIGNED NULL,
    `enum_code` VARCHAR(100) NULL,
    INDEX `idx_account_element_field` (`account_id`, `element_id`, `field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_elements_products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `quantity` DECIMAL(15,4) NULL,
    `price` DECIMAL(15,2) NULL,
    INDEX `idx_account_element_product` (`account_id`, `element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
