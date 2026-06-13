# Data Model: Cross-Channel Analytics

**Date**: 2026-06-13  
**Feature**: 001-cross-channel-analytics

## 1. Database Schema Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          ANALYTICS DATABASE                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                    │
│  │lead_snapshots│  │pipeline_stages│  │source_attr   │                    │
│  ├──────────────┤  ├──────────────┤  ├──────────────┤                    │
│  │ BIGINT lead_id│  │ BIGINT pl_id │  │ BIGINT lead_│                    │
│  │ BIGINT pl_id  │  │ BIGINT st_id │  │ ID          │                    │
│  │ BIGINT st_id  │  │ VARCHAR name │  │ BIGINT src_ │                    │
│  │ DECIMAL price │  │ INT sort_ord │  │ ID          │                    │
│  │ JSONB fields │  │ BOOL is_final│  │ VARCHAR type │                    │
│  │ TIMESTAMPTZ  │  └──────────────┘  │ DECIMAL val  │                    │
│  └──────────────┘                     └──────────────┘                    │
│         │                                    │                              │
│         │           ┌──────────────┐        │                              │
│         └──────────▶│analytics_event│◀───────┘                              │
│                     ├──────────────┤                                       │
│                     │ VARCHAR type │                                       │
│                     │ BIGINT ent_id│                                       │
│                     │ JSONB payload│                                       │
│                     │ TIMESTAMPTZ  │                                       │
│                     └──────────────┘                                       │
│                                                                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │ customer_ltv │  │ transactions │  │ webhook_logs │  │contacts_cache│ │
│  ├──────────────┤  ├──────────────┤  ├──────────────┤  ├──────────────┤ │
│  │ BIGINT cust_ │  │ BIGINT txn_ │  │ VARCHAR hook_│  │ BIGINT id   │ │
│  │ ID          │  │ ID          │  │ ID          │  │ VARCHAR name│ │
│  │ DECIMAL rev │  │ BIGINT cust_│  │ VARCHAR type│  │ JSONB data  │ │
│  │ INT txn_cnt │  │ ID          │  │ JSONB payl  │  │ TIMESTAMPTZ │ │
│  │ TIMESTAMPTZ │  │ DECIMAL val │  │ TIMESTAMPTZ │  └──────────────┘ │
│  │ first/last  │  │ TIMESTAMPTZ │  │ VARCHAR stat│                    │
│  └──────────────┘  └──────────────┘  └──────────────┘                    │
└─────────────────────────────────────────────────────────────────────────────┘
```

## 2. Table Definitions

### 2.1 lead_snapshots

Stores historical state of leads/deals for trend analysis.

```sql
CREATE TABLE lead_snapshots (
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
    is_deleted BOOLEAN DEFAULT FALSE,
    
    -- Constraints
    CONSTRAINT uk_lead_snapshot UNIQUE(lead_id, snapshot_at),
    
    -- Indexes
    CONSTRAINT fk_pipeline FOREIGN KEY (pipeline_id) REFERENCES pipeline_stages(pipeline_id) ON DELETE SET NULL,
    CONSTRAINT fk_status FOREIGN KEY (status_id) REFERENCES pipeline_stages(status_id) ON DELETE SET NULL
);

CREATE INDEX idx_lead_snapshots_lead_id ON lead_snapshots(lead_id);
CREATE INDEX idx_lead_snapshots_pipeline ON lead_snapshots(pipeline_id);
CREATE INDEX idx_lead_snapshots_status ON lead_snapshots(status_id);
CREATE INDEX idx_lead_snapshots_updated ON lead_snapshots(updated_at);
CREATE INDEX idx_lead_snapshots_snapshot ON lead_snapshots(snapshot_at DESC);
CREATE INDEX idx_lead_snapshots_price ON lead_snapshots(price) WHERE price IS NOT NULL;
```

### 2.2 pipeline_stages

Denormalized pipeline and stage configuration.

```sql
CREATE TABLE pipeline_stages (
    id BIGSERIAL PRIMARY KEY,
    pipeline_id BIGINT NOT NULL,
    pipeline_name VARCHAR(255) NOT NULL,
    status_id BIGINT NOT NULL,
    status_name VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_final BOOLEAN DEFAULT FALSE, -- Won or Lost status
    final_type VARCHAR(20), -- 'won' or 'lost' or NULL
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    CONSTRAINT uk_pipeline_status UNIQUE(pipeline_id, status_id)
);

CREATE INDEX idx_pipeline_stages_pipeline ON pipeline_stages(pipeline_id);
CREATE INDEX idx_pipeline_stages_sort ON pipeline_stages(pipeline_id, sort_order);
CREATE INDEX idx_pipeline_stages_final ON pipeline_stages(is_final) WHERE is_final = TRUE;
```

### 2.3 source_attribution

Tracks attribution for each lead.

```sql
CREATE TABLE source_attribution (
    id BIGSERIAL PRIMARY KEY,
    lead_id BIGINT NOT NULL,
    unsorted_id BIGINT,
    unsorted_category VARCHAR(50), -- sip, mail, forms, chats
    source_id BIGINT,
    source_name VARCHAR(255),
    source_external_id VARCHAR(255),
    first_touch_at TIMESTAMP WITH TIME ZONE,
    last_touch_at TIMESTAMP WITH TIME ZONE,
    -- Attribution values (can be calculated based on deal value)
    first_touch_value DECIMAL(15,2),
    last_touch_value DECIMAL(15,2),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    CONSTRAINT uk_lead_attribution UNIQUE(lead_id)
);

CREATE INDEX idx_source_attribution_lead ON source_attribution(lead_id);
CREATE INDEX idx_source_attribution_source ON source_attribution(source_id);
CREATE INDEX idx_source_attribution_category ON source_attribution(unsorted_category);
```

### 2.4 customer_ltv

Aggregated LTV metrics per customer.

```sql
CREATE TABLE customer_ltv (
    id BIGSERIAL PRIMARY KEY,
    customer_id BIGINT NOT NULL UNIQUE,
    contact_ids JSONB DEFAULT '[]',
    total_revenue DECIMAL(15,2) DEFAULT 0,
    transaction_count INT DEFAULT 0,
    first_purchase_at TIMESTAMP WITH TIME ZONE,
    last_purchase_at TIMESTAMP WITH TIME ZONE,
    average_transaction_value DECIMAL(15,2) GENERATED ALWAYS AS (
        CASE WHEN transaction_count > 0 
             THEN total_revenue / transaction_count 
             ELSE 0 
        END
    ) STORED,
    ltv_90_days DECIMAL(15,2) DEFAULT 0,
    ltv_180_days DECIMAL(15,2) DEFAULT 0,
    ltv_365_days DECIMAL(15,2) DEFAULT 0,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_customer_ltv_revenue ON customer_ltv(total_revenue DESC);
CREATE INDEX idx_customer_ltv_first ON customer_ltv(first_purchase_at);
CREATE INDEX idx_customer_ltv_updated ON customer_ltv(updated_at);
```

### 2.5 transactions

Individual purchase transactions.

```sql
CREATE TABLE transactions (
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

CREATE INDEX idx_transactions_customer ON transactions(customer_id);
CREATE INDEX idx_transactions_created ON transactions(created_at DESC);
CREATE INDEX idx_transactions_completed ON transactions(completed_at DESC) WHERE is_completed = TRUE;
```

### 2.6 webhook_logs

Idempotency and processing status tracking.

```sql
CREATE TABLE webhook_logs (
    id BIGSERIAL PRIMARY KEY,
    webhook_id VARCHAR(255) NOT NULL UNIQUE,
    event_type VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NOT NULL,
    payload JSONB NOT NULL,
    received_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    processed_at TIMESTAMP WITH TIME ZONE,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'processing', 'processed', 'failed')),
    error_message TEXT,
    retry_count INT DEFAULT 0,
    last_retry_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX idx_webhook_logs_webhook ON webhook_logs(webhook_id);
CREATE INDEX idx_webhook_logs_status ON webhook_logs(status, received_at);
CREATE INDEX idx_webhook_logs_entity ON webhook_logs(entity_type, entity_id);
CREATE INDEX idx_webhook_logs_pending ON webhook_logs(received_at) WHERE status = 'pending';
```

### 2.7 analytics_events

Audit trail for all analytics events.

```sql
CREATE TABLE analytics_events (
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

CREATE INDEX idx_analytics_events_entity ON analytics_events(entity_type, entity_id);
CREATE INDEX idx_analytics_events_type ON analytics_events(event_type);
CREATE INDEX idx_analytics_events_created ON analytics_events(created_at DESC);
```

### 2.8 contacts_cache

Denormalized contact data for enrichment.

```sql
CREATE TABLE contacts_cache (
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

CREATE INDEX idx_contacts_cache_id ON contacts_cache(contact_id);
CREATE INDEX idx_contacts_cache_updated ON contacts_cache(updated_at);
```

## 3. Materialized Views

For performance optimization on complex analytics queries.

```sql
-- Funnel metrics by pipeline
CREATE MATERIALIZED VIEW mv_funnel_metrics AS
SELECT 
    ps.pipeline_id,
    ps.pipeline_name,
    ps.status_id,
    ps.status_name,
    ps.sort_order,
    COUNT(DISTINCT ls.lead_id) as lead_count,
    SUM(ls.price) as total_price,
    AVG(EXTRACT(EPOCH FROM (ls.updated_at - ls.created_at))/86400) as avg_days_in_stage
FROM pipeline_stages ps
LEFT JOIN lead_snapshots ls ON ps.pipeline_id = ls.pipeline_id 
    AND ps.status_id = ls.status_id
    AND ls.snapshot_at >= CURRENT_DATE - INTERVAL '30 days'
GROUP BY ps.pipeline_id, ps.pipeline_name, ps.status_id, ps.status_name, ps.sort_order;

CREATE UNIQUE INDEX idx_mv_funnel ON mv_funnel_metrics(pipeline_id, status_id);

-- Source attribution summary
CREATE MATERIALIZED VIEW mv_source_attribution AS
SELECT 
    sa.source_id,
    sa.source_name,
    COUNT(DISTINCT sa.lead_id) as lead_count,
    SUM(ls.price) as total_revenue,
    AVG(ls.price) as avg_deal_value,
    COUNT(DISTINCT CASE WHEN ps.is_final AND ps.final_type = 'won' THEN ls.lead_id END) as won_count
FROM source_attribution sa
LEFT JOIN lead_snapshots ls ON sa.lead_id = ls.lead_id AND ls.is_deleted = FALSE
LEFT JOIN pipeline_stages ps ON ls.pipeline_id = ps.pipeline_id AND ls.status_id = ps.status_id
GROUP BY sa.source_id, sa.source_name;

CREATE UNIQUE INDEX idx_mv_source ON mv_source_attribution(source_id);
```

## 4. Entity Relationships

```
┌──────────────────────────────────────────────────────────────────────┐
│                          ENTITY RELATIONSHIPS                        │
├──────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  pipeline_stages                                                    │
│       │                                                              │
│       │ 1:N (defines stages for)                                    │
│       │                                                              │
│       ▼                                                              │
│  lead_snapshots ────────── source_attribution                       │
│       │                        │                                     │
│       │                        │ 1:1 (attribution for)              │
│       │                        │                                     │
│       │                        ▼                                     │
│       │              ┌─────────────────┐                           │
│       └─────────────▶│ analytics_events │                           │
│                      └─────────────────┘                           │
│                                                                     │
│  contacts_cache ────────── customer_ltv                             │
│       │                        │                                     │
│       │ 1:N                    │ 1:N                                 │
│       │                        │                                     │
│       ▼                        ▼                                     │
│  transactions ◀─────────────────────────────────────────────────── │
│       │                                                              │
│       └──────────────────▶│ (links to customer)                      │
│                                                                     │
│  webhook_logs ─────────────────▶ analytics_events                    │
│       │                                                              │
│       │ (creates)                                                   │
│       │                                                              │
│       ▼                                                              │
└──────────────────────────────────────────────────────────────────────┘
```

## 5. Index Strategy

| Query Pattern | Index | Type |
|--------------|-------|------|
| Get leads by pipeline | `idx_lead_snapshots_pipeline` | B-tree |
| Get latest snapshot | `idx_lead_snapshots_snapshot` | DESC |
| Get pending webhooks | `idx_webhook_logs_pending` | Partial |
| Funnel aggregation | `mv_funnel_metrics` | Materialized |
| LTV by revenue | `idx_customer_ltv_revenue` | DESC |

## 6. Data Retention

| Table | Short-term | Long-term | Archive |
|-------|------------|-----------|---------|
| lead_snapshots | 90 days | 2 years | Delete |
| webhook_logs | 7 days | 30 days | Delete |
| analytics_events | 90 days | 1 year | Aggregate |
| transactions | Indefinite | Indefinite | None |
| customer_ltv | Indefinite | Indefinite | None |