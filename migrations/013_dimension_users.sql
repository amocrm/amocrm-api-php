-- ============================================================
-- Миграция 013: Измерение — Пользователи
-- Таблицы: amocrm_users
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `lang` VARCHAR(10) NULL,
    `phone_number` VARCHAR(50) NULL,
    `rights` JSON NULL,
    `role_id` BIGINT UNSIGNED NULL,
    `group_id` BIGINT UNSIGNED NULL,
    `is_admin` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_free` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_user` (`account_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
