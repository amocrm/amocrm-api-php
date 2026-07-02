-- ============================================================
-- Миграция 011: Измерение — Задачи
-- Таблицы: amocrm_tasks, amocrm_tasks_events
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_tasks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `task_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `task_type_id` BIGINT UNSIGNED NULL,
    `entity_type` VARCHAR(50) NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `duration` INT UNSIGNED NULL,
    `complete_till_at` DATETIME NULL,
    `completed_at` DATETIME NULL,
    `result` TEXT NULL,
    `is_completed` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `group_id` BIGINT UNSIGNED NULL,
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_task` (`account_id`, `task_id`),
    INDEX `idx_account_entity` (`account_id`, `entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_tasks_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `task_id` BIGINT UNSIGNED NOT NULL,
    `event_id` VARCHAR(100) NULL,
    `event_type` VARCHAR(100) NULL,
    `event_value_before` TEXT NULL,
    `event_value_after` TEXT NULL,
    `event_created_user_id` BIGINT UNSIGNED NULL,
    `event_created_at` DATETIME NULL,
    INDEX `idx_account_task_event` (`account_id`, `task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
