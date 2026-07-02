-- ============================================================
-- Миграция 007: Измерение — Сделки
-- Таблицы: amocrm_leads, leads_attributes, leads_tags, leads_notes, leads_events
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_leads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `price` DECIMAL(15,2) NULL,
    `pipeline_id` BIGINT UNSIGNED NULL,
    `status_id` BIGINT UNSIGNED NULL,
    `old_status_id` BIGINT UNSIGNED NULL,
    `loss_reason_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `group_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `closed_user_id` BIGINT UNSIGNED NULL,
    `closed_at` DATETIME NULL,
    `closest_task_at` DATETIME NULL,
    `score` INT NULL,
    `is_price_modified_by_robot` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `source_external_id` VARCHAR(255) NULL,
    `visitor_uid` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_lead` (`account_id`, `lead_id`),
    INDEX `idx_account_pipeline_status` (`account_id`, `pipeline_id`, `status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_leads_attributes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `field_id` BIGINT UNSIGNED NOT NULL,
    `field_name` VARCHAR(255) NULL,
    `field_type` VARCHAR(50) NULL,
    `field_code` VARCHAR(100) NULL,
    `value` TEXT NULL,
    `enum_id` BIGINT UNSIGNED NULL,
    `enum_code` VARCHAR(100) NULL,
    INDEX `idx_account_lead_field` (`account_id`, `lead_id`, `field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_leads_tags` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `color` VARCHAR(20) NULL,
    INDEX `idx_account_lead_tag` (`account_id`, `lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_leads_notes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
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
    INDEX `idx_account_lead_note` (`account_id`, `lead_id`, `note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_leads_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `event_id` VARCHAR(100) NULL,
    `event_type` VARCHAR(100) NULL,
    `event_value_before` TEXT NULL,
    `event_value_after` TEXT NULL,
    `event_created_user_id` BIGINT UNSIGNED NULL,
    `event_created_at` DATETIME NULL,
    INDEX `idx_account_lead_event` (`account_id`, `lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
