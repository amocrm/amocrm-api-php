-- ============================================================
-- Миграция 001: Вспомогательные таблицы (справочники)
-- Таблицы: amocrm_pipelines, amocrm_statuses, amocrm_periodicity
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_pipelines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `pipeline_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sort` INT UNSIGNED NOT NULL DEFAULT 0,
    `is_main` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_unsorted_on` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_archive` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_pipeline` (`account_id`, `pipeline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_statuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `status_id` BIGINT UNSIGNED NOT NULL,
    `pipeline_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sort` INT UNSIGNED NOT NULL DEFAULT 0,
    `color` VARCHAR(20) NULL,
    `type` INT UNSIGNED NOT NULL DEFAULT 0,
    `editable` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_status` (`account_id`, `status_id`),
    INDEX `idx_account_pipeline` (`account_id`, `pipeline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_periodicity` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `periodicity_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sort` INT UNSIGNED NOT NULL DEFAULT 0,
    `color` VARCHAR(20) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_periodicity` (`account_id`, `periodicity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
