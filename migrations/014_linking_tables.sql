-- ============================================================
-- Миграция 014: Связующие таблицы (M:N)
-- Таблицы: amocrm_leads_contacts, amocrm_customers_segments
-- ============================================================

CREATE TABLE IF NOT EXISTS `amocrm_leads_contacts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `is_main` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_account_lead` (`account_id`, `lead_id`),
    INDEX `idx_account_contact` (`account_id`, `contact_id`),
    UNIQUE KEY `uq_account_lead_contact` (`account_id`, `lead_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_customers_segments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `segment_id` BIGINT UNSIGNED NOT NULL,
    INDEX `idx_account_customer` (`account_id`, `customer_id`),
    INDEX `idx_account_segment` (`account_id`, `segment_id`),
    UNIQUE KEY `uq_account_customer_segment` (`account_id`, `customer_id`, `segment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_customers_members` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `member_id` BIGINT UNSIGNED NOT NULL,
    `member_type` VARCHAR(50) NOT NULL,
    INDEX `idx_account_customer` (`account_id`, `customer_id`),
    INDEX `idx_account_member` (`account_id`, `member_id`, `member_type`),
    UNIQUE KEY `uq_account_customer_member` (`account_id`, `customer_id`, `member_id`, `member_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `amocrm_companies_contacts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `company_id` BIGINT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `is_main` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_account_company` (`account_id`, `company_id`),
    INDEX `idx_account_contact` (`account_id`, `contact_id`),
    UNIQUE KEY `uq_account_company_contact` (`account_id`, `company_id`, `contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
