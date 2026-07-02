-- ============================================================
-- Миграция 006: Измерение — Контакты
-- Таблицы: amocrm_contacts, contacts_attributes, contacts_tags, contacts_notes, contacts_events
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_contacts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(500) NULL,
    `first_name` VARCHAR(255) NULL,
    `last_name` VARCHAR(255) NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `group_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `company_name` VARCHAR(500) NULL,
    `closest_task_at` DATETIME NULL,
    `score` INT NULL,
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_contact` (`account_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_contacts_attributes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `field_id` BIGINT UNSIGNED NOT NULL,
    `field_name` VARCHAR(255) NULL,
    `field_type` VARCHAR(50) NULL,
    `field_code` VARCHAR(100) NULL,
    `value` TEXT NULL,
    `enum_id` BIGINT UNSIGNED NULL,
    `enum_code` VARCHAR(100) NULL,
    INDEX `idx_account_contact_field` (`account_id`, `contact_id`, `field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_contacts_tags` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `color` VARCHAR(20) NULL,
    INDEX `idx_account_contact_tag` (`account_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_contacts_notes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
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
    INDEX `idx_account_contact_note` (`account_id`, `contact_id`, `note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_contacts_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `event_id` VARCHAR(100) NULL,
    `event_type` VARCHAR(100) NULL,
    `event_value_before` TEXT NULL,
    `event_value_after` TEXT NULL,
    `event_created_user_id` BIGINT UNSIGNED NULL,
    `event_created_at` DATETIME NULL,
    INDEX `idx_account_contact_event` (`account_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
