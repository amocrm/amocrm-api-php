-- ============================================================
-- Миграция 015: Таблицы фактов
-- 11 таблиц фактов со ссылками на измерения
-- ============================================================

-- Факты по звонкам
CREATE TABLE IF NOT EXISTS `amocrm_calls_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `call_id` BIGINT UNSIGNED NOT NULL,
    `date_id` BIGINT UNSIGNED NULL,
    `entity_type` VARCHAR(50) NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `duration` INT UNSIGNED NULL,
    `status` VARCHAR(20) NULL,
    INDEX `idx_account_call` (`account_id`, `call_id`),
    INDEX `idx_account_date` (`account_id`, `date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по контактам
CREATE TABLE IF NOT EXISTS `amocrm_contacts_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NOT NULL,
    `date_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `score` INT NULL,
    INDEX `idx_account_contact` (`account_id`, `contact_id`),
    INDEX `idx_account_date` (`account_id`, `date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по компаниям
CREATE TABLE IF NOT EXISTS `amocrm_companies_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `company_id` BIGINT UNSIGNED NOT NULL,
    `date_id` BIGINT UNSIGNED NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `score` INT NULL,
    INDEX `idx_account_company` (`account_id`, `company_id`),
    INDEX `idx_account_date` (`account_id`, `date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по сделкам
CREATE TABLE IF NOT EXISTS `amocrm_leads_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `date_create_id` BIGINT UNSIGNED NULL,
    `date_close_id` BIGINT UNSIGNED NULL,
    `client_id` VARCHAR(255) NULL,
    `traffic_id` VARCHAR(255) NULL,
    `pipeline_id` BIGINT UNSIGNED NULL,
    `status_id` BIGINT UNSIGNED NULL,
    `loss_reason_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `price` DECIMAL(15,2) NULL,
    `labor_cost` DECIMAL(15,2) NULL,
    `score` INT NULL,
    INDEX `idx_account_lead` (`account_id`, `lead_id`),
    INDEX `idx_account_date_create` (`account_id`, `date_create_id`),
    INDEX `idx_account_date_close` (`account_id`, `date_close_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по сегментам
CREATE TABLE IF NOT EXISTS `amocrm_segments_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `segment_id` BIGINT UNSIGNED NOT NULL,
    `created_user_id` BIGINT UNSIGNED NULL,
    `modified_user_id` BIGINT UNSIGNED NULL,
    `customers_count` INT UNSIGNED NULL,
    `conversion_rate` DECIMAL(5,2) NULL,
    `max_discount` DECIMAL(5,2) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_account_segment` (`account_id`, `segment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по покупателям
CREATE TABLE IF NOT EXISTS `amocrm_customers_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `periodicity_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `next_date` DATETIME NULL,
    `next_price` DECIMAL(15,2) NULL,
    `purchases` INT UNSIGNED NULL,
    `average_check` DECIMAL(15,2) NULL,
    `ltv` DECIMAL(15,2) NULL,
    `labor_cost` DECIMAL(15,2) NULL,
    INDEX `idx_account_customer` (`account_id`, `customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по транзакциям
CREATE TABLE IF NOT EXISTS `amocrm_transactions_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `company_id` BIGINT UNSIGNED NULL,
    `date_id` BIGINT UNSIGNED NULL,
    `completed_at` DATETIME NULL,
    `price` DECIMAL(15,2) NULL,
    INDEX `idx_account_transaction` (`account_id`, `transaction_id`),
    INDEX `idx_account_customer` (`account_id`, `customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по задачам
CREATE TABLE IF NOT EXISTS `amocrm_tasks_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `task_id` BIGINT UNSIGNED NOT NULL,
    `date_create_id` BIGINT UNSIGNED NULL,
    `date_complete_id` BIGINT UNSIGNED NULL,
    `entity_type` VARCHAR(50) NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `responsible_user_id` BIGINT UNSIGNED NULL,
    `duration` INT UNSIGNED NULL,
    `is_completed` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_account_task` (`account_id`, `task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по элементам транзакций
CREATE TABLE IF NOT EXISTS `amocrm_transactions_elements_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `quantity` DECIMAL(15,4) NULL,
    `price` DECIMAL(15,2) NULL,
    INDEX `idx_account_transaction` (`account_id`, `transaction_id`),
    INDEX `idx_account_element` (`account_id`, `element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по элементам сделок
CREATE TABLE IF NOT EXISTS `amocrm_leads_elements_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `lead_id` BIGINT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `quantity` DECIMAL(15,4) NULL,
    `price` DECIMAL(15,2) NULL,
    INDEX `idx_account_lead` (`account_id`, `lead_id`),
    INDEX `idx_account_element` (`account_id`, `element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Факты по элементам покупателей
CREATE TABLE IF NOT EXISTS `amocrm_customers_elements_facts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `element_id` BIGINT UNSIGNED NOT NULL,
    `quantity` DECIMAL(15,4) NULL,
    `price` DECIMAL(15,2) NULL,
    INDEX `idx_account_customer` (`account_id`, `customer_id`),
    INDEX `idx_account_element` (`account_id`, `element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
