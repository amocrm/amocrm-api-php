<?php

declare(strict_types=1);

namespace Analytics\Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Initial database schema migration
 */
class Version20260613_InitialSchema
{
    private Connection $connection;

    public function __construct(array $dbConfig)
    {
        $this->connection = DriverManager::getConnection($dbConfig);
    }

    public function up(): void
    {
        $this->connection->executeStatement($this->getUpSql());
    }

    public function down(): void
    {
        $this->connection->executeStatement($this->getDownSql());
    }

    private function getUpSql(): string
    {
        return <<<SQL
-- Pipeline stages
CREATE TABLE IF NOT EXISTS pipeline_stages (
    id BIGSERIAL PRIMARY KEY,
    pipeline_id BIGINT NOT NULL,
    pipeline_name VARCHAR(255) NOT NULL,
    status_id BIGINT NOT NULL,
    status_name VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_final BOOLEAN DEFAULT FALSE,
    final_type VARCHAR(20),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    CONSTRAINT uk_pipeline_status UNIQUE(pipeline_id, status_id)
);

CREATE INDEX IF NOT EXISTS idx_pipeline_stages_pipeline ON pipeline_stages(pipeline_id);
CREATE INDEX IF NOT EXISTS idx_pipeline_stages_sort ON pipeline_stages(pipeline_id, sort_order);

-- Lead snapshots
CREATE TABLE IF NOT EXISTS lead_snapshots (
    id BIGSERIAL PRIMARY KEY,
    lead_id BIGINT NOT NULL,
    pipeline_id BIGINT NOT NULL,
    status_id BIGINT NOT NULL,
    status_name VARCHAR(255),
    price DECIMAL(15,2),
    source_id BIGINT,
    responsible_user_id BIGINT,
    contact_ids JSONB DEFAULT '[]',
    company_id BIGINT,
    loss_reason_id BIGINT,
    tags JSONB DEFAULT '[]',
    custom_fields JSONB DEFAULT '{}',
    created_at TIMESTAMP WITH TIME ZONE,
    updated_at TIMESTAMP WITH TIME ZONE,
    closed_at TIMESTAMP WITH TIME ZONE,
    snapshot_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    is_deleted BOOLEAN DEFAULT FALSE
);

CREATE INDEX IF NOT EXISTS idx_lead_snapshots_lead_id ON lead_snapshots(lead_id);
CREATE INDEX IF NOT EXISTS idx_lead_snapshots_pipeline ON lead_snapshots(pipeline_id);
CREATE INDEX IF NOT EXISTS idx_lead_snapshots_status ON lead_snapshots(status_id);
CREATE INDEX IF NOT EXISTS idx_lead_snapshots_updated ON lead_snapshots(updated_at);
CREATE INDEX IF NOT EXISTS idx_lead_snapshots_snapshot ON lead_snapshots(snapshot_at DESC);

-- Transactions
CREATE TABLE IF NOT EXISTS transactions (
    id BIGSERIAL PRIMARY KEY,
    transaction_id BIGINT NOT NULL UNIQUE,
    customer_id BIGINT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    catalog_element_id BIGINT,
    catalog_element_name VARCHAR(255),
    quantity INT DEFAULT 1,
    unit_price DECIMAL(15,2),
    created_at TIMESTAMP WITH TIME ZONE,
    completed_at TIMESTAMP WITH TIME ZONE,
    is_completed BOOLEAN DEFAULT FALSE,
    custom_fields JSONB DEFAULT '{}'
);

CREATE INDEX IF NOT EXISTS idx_transactions_customer ON transactions(customer_id);
CREATE INDEX IF NOT EXISTS idx_transactions_created ON transactions(created_at DESC);

-- Webhook logs
CREATE TABLE IF NOT EXISTS webhook_logs (
    id BIGSERIAL PRIMARY KEY,
    webhook_id VARCHAR(255) NOT NULL UNIQUE,
    event_type VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NOT NULL,
    payload JSONB NOT NULL,
    received_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    processed_at TIMESTAMP WITH TIME ZONE,
    status VARCHAR(20) DEFAULT 'pending',
    error_message TEXT,
    retry_count INT DEFAULT 0,
    last_retry_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX IF NOT EXISTS idx_webhook_logs_webhook ON webhook_logs(webhook_id);
CREATE INDEX IF NOT EXISTS idx_webhook_logs_status ON webhook_logs(status, received_at);
CREATE INDEX IF NOT EXISTS idx_webhook_logs_entity ON webhook_logs(entity_type, entity_id);

-- Analytics events
CREATE TABLE IF NOT EXISTS analytics_events (
    id BIGSERIAL PRIMARY KEY,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    old_value JSONB,
    new_value JSONB,
    payload JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    processed_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX IF NOT EXISTS idx_analytics_events_entity ON analytics_events(entity_type, entity_id);
CREATE INDEX IF NOT EXISTS idx_analytics_events_created ON analytics_events(created_at DESC);

-- Source attribution
CREATE TABLE IF NOT EXISTS source_attribution (
    id BIGSERIAL PRIMARY KEY,
    lead_id BIGINT NOT NULL,
    unsorted_id BIGINT,
    unsorted_category VARCHAR(50),
    source_id BIGINT,
    source_name VARCHAR(255),
    source_external_id VARCHAR(255),
    first_touch_at TIMESTAMP WITH TIME ZONE,
    last_touch_at TIMESTAMP WITH TIME ZONE,
    first_touch_value DECIMAL(15,2),
    last_touch_value DECIMAL(15,2),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_source_attribution_lead ON source_attribution(lead_id);
CREATE INDEX IF NOT EXISTS idx_source_attribution_source ON source_attribution(source_id);

-- Customer LTV
CREATE TABLE IF NOT EXISTS customer_ltv (
    id BIGSERIAL PRIMARY KEY,
    customer_id BIGINT NOT NULL UNIQUE,
    contact_ids JSONB DEFAULT '[]',
    total_revenue DECIMAL(15,2) DEFAULT 0,
    transaction_count INT DEFAULT 0,
    first_purchase_at TIMESTAMP WITH TIME ZONE,
    last_purchase_at TIMESTAMP WITH TIME ZONE,
    ltv_90_days DECIMAL(15,2) DEFAULT 0,
    ltv_180_days DECIMAL(15,2) DEFAULT 0,
    ltv_365_days DECIMAL(15,2) DEFAULT 0,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_customer_ltv_revenue ON customer_ltv(total_revenue DESC);

-- Contacts cache
CREATE TABLE IF NOT EXISTS contacts_cache (
    id BIGSERIAL PRIMARY KEY,
    contact_id BIGINT NOT NULL UNIQUE,
    name VARCHAR(255),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    custom_fields JSONB DEFAULT '{}',
    created_at TIMESTAMP WITH TIME ZONE,
    updated_at TIMESTAMP WITH TIME ZONE,
    synced_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_contacts_cache_contact ON contacts_cache(contact_id);
CREATE INDEX IF NOT EXISTS idx_contacts_cache_updated ON contacts_cache(updated_at);
SQL;
    }

    private function getDownSql(): string
    {
        return <<<SQL
DROP TABLE IF EXISTS contacts_cache CASCADE;
DROP TABLE IF EXISTS customer_ltv CASCADE;
DROP TABLE IF EXISTS source_attribution CASCADE;
DROP TABLE IF EXISTS analytics_events CASCADE;
DROP TABLE IF EXISTS webhook_logs CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS lead_snapshots CASCADE;
DROP TABLE IF EXISTS pipeline_stages CASCADE;
SQL;
    }
}
