# Webhook API Contract

**Date**: 2026-06-13  
**Feature**: 001-cross-channel-analytics

## Endpoint

```
POST /webhook/amocrm
Content-Type: application/json
```

## Authentication

- No authentication required (amoCRM signs payloads)
- Verify `X-Signature` header using HMAC-SHA256

## Request Headers

| Header | Required | Description |
|--------|----------|-------------|
| `Content-Type` | Yes | `application/json` |
| `X-Signature` | Yes | HMAC signature for verification |
| `X-Amohook-Version` | No | Webhook version (default: 2.0) |

## Payload Structure

### Base Webhook Payload

```json
{
  "webhook": {
    "type": "string",
    "timestamp": 1718294400
  },
  "payload": {
    // Event-specific payload
  },
  "account": {
    "id": 12345,
    "domain": "yourcompany.amocrm.ru"
  }
}
```

### Event Types

| Type | Description | Frequency |
|------|-------------|------------|
| `lead_added` | New deal created | High |
| `lead_status_changed` | Deal moved to new stage | High |
| `lead_updated` | Deal data changed | Medium |
| `lead_deleted` | Deal deleted | Low |
| `contact_added` | New contact | Medium |
| `contact_updated` | Contact changed | Medium |
| `customer_added` | New customer | Low |
| `customer_transaction_added` | Purchase completed | Medium |
| `unsorted_added` | New unsorted lead | Medium |

### Payload Examples

#### lead_added

```json
{
  "webhook": {
    "type": "lead_added",
    "timestamp": 1718294400
  },
  "payload": {
    "id": 123456,
    "name": "New Deal",
    "price": 50000,
    "pipeline_id": 1,
    "status_id": 1,
    "responsible_user_id": 789,
    "created_by": 789,
    "created_at": 1718294400,
    "updated_at": 1718294400,
    "custom_fields_values": []
  }
}
```

#### lead_status_changed

```json
{
  "webhook": {
    "type": "lead_status_changed",
    "timestamp": 1718294400
  },
  "payload": {
    "id": 123456,
    "name": "Deal Name",
    "old_pipeline_id": 1,
    "old_status_id": 1,
    "new_pipeline_id": 1,
    "new_status_id": 2,
    "responsible_user_id": 789,
    "updated_at": 1718294400
  }
}
```

#### customer_transaction_added

```json
{
  "webhook": {
    "type": "customer_transaction_added",
    "timestamp": 1718294400
  },
  "payload": {
    "id": 789012,
    "customer_id": 456,
    "price": 5000,
    "catalog_id": 1001,
    "catalog_elements": [
      {
        "id": 1001,
        "name": "Product A",
        "quantity": 2,
        "price": 2500
      }
    ],
    "created_at": 1718294400,
    "updated_at": 1718294400
  }
}
```

#### unsorted_added

```json
{
  "webhook": {
    "type": "unsorted_added",
    "timestamp": 1718294400
  },
  "payload": {
    "id": 345678,
    "category": "forms",
    "source": {
      "id": 1,
      "name": "Website Form"
    },
    "data": {
      "name": "John Doe",
      "phone": "+79001234567",
      "email": "john@example.com",
      "message": "I need consultation"
    },
    "created_at": 1718294400
  }
}
```

## Response

### Success (200 OK)

```json
{
  "status": "ok",
  "webhook_id": "abc123-def456"
}
```

### Error (4xx/5xx)

```json
{
  "status": "error",
  "code": "INVALID_SIGNATURE",
  "message": "Webhook signature verification failed"
}
```

## Signature Verification

### PHP Example

```php
<?php
function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
{
    $expected = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expected, $signature);
}

// In webhook handler:
$rawBody = $request->getBody()->getContents();
$signature = $request->getHeaderLine('X-Signature');

if (!verifyWebhookSignature($rawBody, $signature, $clientSecret)) {
    throw new \RuntimeException('Invalid signature');
}
```

## Processing Requirements

| Requirement | SLA |
|------------|-----|
| Max processing time | 5 seconds |
| Response timeout | 30 seconds |
| Retry attempts | 3 |
| Idempotency window | 24 hours |

## Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `INVALID_SIGNATURE` | 401 | Signature verification failed |
| `INVALID_PAYLOAD` | 400 | Payload structure invalid |
| `DUPLICATE_WEBHOOK` | 200 | Webhook already processed (idempotent) |
| `RATE_LIMITED` | 429 | Too many requests |
| `INTERNAL_ERROR` | 500 | Processing error |

## Webhook Log Schema

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
    status VARCHAR(20) DEFAULT 'pending',
    error_message TEXT,
    retry_count INT DEFAULT 0
);
```

## Test Scenarios

1. **Valid payload** → 200 OK, webhook logged
2. **Invalid signature** → 401 Unauthorized
3. **Duplicate webhook** → 200 OK, no-op (idempotent)
4. **Invalid payload** → 400 Bad Request
5. **Processing timeout** → 500 Internal Server Error