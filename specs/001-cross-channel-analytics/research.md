# Research: Cross-Channel Analytics Integration

**Date**: 2026-06-13  
**Feature**: 001-cross-channel-analytics

## 1. Fusio Integration Research

### 1.1 Webhook Endpoint Setup

Fusio provides a flexible routing system for webhook endpoints. Based on documentation and common patterns:

```yaml
# config/routes.yaml
routes:
  - path: /webhook/amocrm
    controller: Analytics\Api\WebhookController
    methods:
      POST:
        - name: payload
          schema:
            type: object
            properties:
              webhook:
                type: string
              payload:
                type: object
```

### 1.2 Fusio Action Development

Fusio uses action classes for request handling. For webhook processing:

```php
<?php
namespace Analytics\Api\Action;

use Fusio\Foundation\ActionAbstract;
use Fusio\Foundation\Context;
use PSR\Http\Message\ServerRequestInterface;
use PSR\Http\Message\ResponseInterface;

class WebhookAction extends ActionAbstract
{
    public function handle(ServerRequestInterface $request, Context $context): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        
        // Process webhook
        $this->container->get(WebhookHandler::class)->process($body);
        
        return $this->response->withStatus(200);
    }
}
```

### 1.3 Rate Limiting Considerations

Fusio has built-in rate limiting, but for webhook processing we need:
- Quick response (200 OK) to amoCRM immediately
- Async processing via queue or immediate DB write
- 5-second SLA per SC-002

**Recommendation**: Direct DB write with async validation.

## 2. PostgreSQL Schema Design

### 2.1 Core Tables

```sql
-- Lead snapshots for historical tracking
CREATE TABLE lead_snapshots (
    id BIGSERIAL PRIMARY KEY,
    lead_id BIGINT NOT NULL,
    pipeline_id BIGINT NOT NULL,
    status_id BIGINT NOT NULL,
    price DECIMAL(15,2),
    source_id BIGINT,
    responsible_user_id BIGINT,
    created_at TIMESTAMP WITH TIME ZONE,
    updated_at TIMESTAMP WITH TIME ZONE,
    snapshot_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    custom_fields JSONB,
    UNIQUE(lead_id, snapshot_at)
);

-- Pipeline stages for funnel
CREATE TABLE pipeline_stages (
    id BIGSERIAL PRIMARY KEY,
    pipeline_id BIGINT NOT NULL,
    status_id BIGINT NOT NULL,
    name VARCHAR(255),
    sort_order INT,
    is_final BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    UNIQUE(pipeline_id, status_id)
);

-- Source attribution
CREATE TABLE source_attribution (
    id BIGSERIAL PRIMARY KEY,
    lead_id BIGINT NOT NULL,
    source_id BIGINT,
    unsorted_id BIGINT,
    unsorted_category VARCHAR(50),
    attribution_type VARCHAR(20) NOT NULL, -- 'first_touch' or 'last_touch'
    attribution_value DECIMAL(15,2),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    UNIQUE(lead_id, attribution_type)
);

-- Customer LTV tracking
CREATE TABLE customer_ltv (
    id BIGSERIAL PRIMARY KEY,
    customer_id BIGINT NOT NULL UNIQUE,
    total_revenue DECIMAL(15,2) DEFAULT 0,
    transaction_count INT DEFAULT 0,
    first_purchase_at TIMESTAMP WITH TIME ZONE,
    last_purchase_at TIMESTAMP WITH TIME ZONE,
    ltv DECIMAL(15,2) GENERATED ALWAYS AS (total_revenue) STORED,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Transaction records
CREATE TABLE transactions (
    id BIGSERIAL PRIMARY KEY,
    transaction_id BIGINT NOT NULL UNIQUE,
    customer_id BIGINT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    catalog_element_id BIGINT,
    created_at TIMESTAMP WITH TIME ZONE,
    completed_at TIMESTAMP WITH TIME ZONE,
    UNIQUE(transaction_id)
);

-- Webhook processing log for idempotency
CREATE TABLE webhook_logs (
    id BIGSERIAL PRIMARY KEY,
    webhook_id VARCHAR(255) NOT NULL UNIQUE,
    event_type VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NOT NULL,
    payload JSONB,
    received_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    processed_at TIMESTAMP WITH TIME ZONE,
    status VARCHAR(20) DEFAULT 'pending', -- pending, processed, failed
    error_message TEXT,
    retry_count INT DEFAULT 0
);

-- Analytics events for audit trail
CREATE TABLE analytics_events (
    id BIGSERIAL PRIMARY KEY,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    payload JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    processed_at TIMESTAMP WITH TIME ZONE
);

-- Indexes for performance
CREATE INDEX idx_lead_snapshots_lead_id ON lead_snapshots(lead_id);
CREATE INDEX idx_lead_snapshots_pipeline ON lead_snapshots(pipeline_id, status_id);
CREATE INDEX idx_lead_snapshots_updated ON lead_snapshots(updated_at);
CREATE INDEX idx_transactions_customer ON transactions(customer_id);
CREATE INDEX idx_webhook_logs_status ON webhook_logs(status, received_at);
CREATE INDEX idx_analytics_events_entity ON analytics_events(entity_type, entity_id);
```

### 2.2 Migration Strategy

Use Doctrine Migrations or custom migration files:

```
migrations/
├── Version20260613_InitialSchema.php
├── Version20260620_AddIndexes.php
└── Version20260627_AddRetentionPolicy.php
```

## 3. Entity Relationship Diagram

```
┌─────────────┐     ┌─────────────────┐     ┌──────────────┐
│  Unsorted   │────▶│ Source          │     │  Pipeline    │
│  (first     │     │ Attribution     │     │  Stages      │
│   touch)    │     │                 │     │              │
└─────────────┘     └─────────────────┘     └──────────────┘
       │                    ▲                       │
       │                    │                       │
       ▼                    │                       ▼
┌─────────────┐     ┌───────┴───────┐     ┌──────────────┐
│   Lead      │────▶│   Contact     │────▶│  Customer    │
│   (deal)    │     │               │     │  (buyer)     │
└─────────────┘     └───────────────┘     └──────────────┘
       │                                          │
       │                                          │
       ▼                                          ▼
┌─────────────┐                           ┌──────────────┐
│   Event     │                           │ Transaction  │
│   (history) │                           │              │
└─────────────┘                           └──────────────┘
       │                                          │
       │                                          │
       ▼                                          ▼
┌─────────────────────────────────────────────────────────┐
│                    WebhookLog                           │
│              (idempotency tracking)                     │
└─────────────────────────────────────────────────────────┘
```

## 4. API Integration Points

### 4.1 amoCRM API Endpoints Used

| Entity | Endpoint | Filters | With |
|--------|----------|---------|------|
| Leads | `api/v4/leads` | updatedAt, pipelineIds, statusIds | contacts, loss_reason |
| Events | `api/v4/events` | entityId, type | — |
| Unsorted | `api/v4/incoming_leads` | status | — |
| Contacts | `api/v4/contacts` | updatedAt | — |
| Customers | `api/v4/customers` | updatedAt | contacts |
| Transactions | `api/v4/transactions` | customerId | catalog_elements |
| Sources | `api/v4/sources` | — | — |
| Pipelines | `api/v4/pipelines` | — | statuses |

### 4.2 Webhook Events to Subscribe

```php
$webhookEvents = [
    'lead_added',           // New deal
    'lead_status_changed',  // Stage change
    'lead_updated',         // Any update
    'lead_deleted',         // Deal deleted
    'contact_added',        // New contact
    'contact_updated',      // Contact update
    'customer_added',       // New customer
    'customer_transaction_added', // Purchase
    'unsorted_added',       // New unsorted
];
```

### 4.3 Fusio Routes Configuration

```php
<?php
// config/fusio_routes.php

return [
    'routes' => [
        [
            'path' => '/api/analytics/funnel',
            'controller' => Analytics\Api\Action\FunnelAction::class,
            'methods' => ['GET'],
        ],
        [
            'path' => '/api/analytics/attribution',
            'controller' => Analytics\Api\Action\AttributionAction::class,
            'methods' => ['GET'],
        ],
        [
            'path' => '/api/analytics/ltv',
            'controller' => Analytics\Api\Action\LTVAction::class,
            'methods' => ['GET'],
        ],
        [
            'path' => '/webhook/amocrm',
            'controller' => Analytics\Api\Action\WebhookAction::class,
            'methods' => ['POST'],
        ],
    ],
];
```

## 5. Risk Analysis

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Rate limit (429) | High | Medium | Exponential backoff, queue |
| Token expiration | Medium | Medium | Auto-refresh callback |
| Large export timeout | Medium | Medium | Chunked processing |
| Webhook replay attack | Low | Low | Idempotency key |
| Schema mismatch | Low | High | Field validation |

## 6. Open Questions

1. **Q**: Should we use ClickHouse for high-volume data?
   - **A**: Start with PostgreSQL, migrate to ClickHouse if needed for scale

2. **Q**: How to handle deleted entities in amoCRM?
   - **A**: Soft delete flag in analytics, retain history

3. **Q**: What retention period for snapshots?
   - **A**: 90 days for detailed, 2 years for aggregated

## 7. Conclusion

The integration is technically feasible with:
- Fusio for API framework
- PostgreSQL for primary storage
- Existing amocrm-api-php library for API access
- Standard ETL patterns for data collection