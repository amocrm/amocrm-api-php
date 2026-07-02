-- ============================================================
-- Миграция 004: Измерение — Неразобранное
-- Таблицы: amocrm_unsorted
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_unsorted` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `unsorted_id` BIGINT UNSIGNED NOT NULL,
    `uid` VARCHAR(255) NULL,
    `source_uid` VARCHAR(255) NULL,
    `source_name` VARCHAR(255) NULL,
    `category` VARCHAR(50) NULL,
    `pipeline_id` BIGINT UNSIGNED NULL,
    `status_id` BIGINT UNSIGNED NULL,
    `form_id` VARCHAR(100) NULL,
    `form_name` VARCHAR(255) NULL,
    `form_page` VARCHAR(500) NULL,
    `form_sent_at` DATETIME NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `group_id` BIGINT UNSIGNED NULL,
    `lead_id` BIGINT UNSIGNED NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `is_processed` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `data_json` JSON NULL,
    `metadata_json` JSON NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_unsorted` (`account_id`, `unsorted_id`),
    INDEX `idx_account_category` (`account_id`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
