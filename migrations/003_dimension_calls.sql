-- ============================================================
-- Миграция 003: Измерение — Звонки
-- Таблицы: amocrm_calls
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_calls` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `call_id` BIGINT UNSIGNED NOT NULL,
    `entity_type` VARCHAR(50) NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `phone` VARCHAR(50) NULL,
    `direction` VARCHAR(20) NULL,
    `status` VARCHAR(20) NULL,
    `result` VARCHAR(255) NULL,
    `duration` INT UNSIGNED NULL,
    `call_responsible_user_id` BIGINT UNSIGNED NULL,
    `source` VARCHAR(100) NULL,
    `note` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_call` (`account_id`, `call_id`),
    INDEX `idx_account_entity` (`account_id`, `entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
