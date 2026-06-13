# Quickstart: Cross-Channel Analytics

**Date**: 2026-06-13  
**Feature**: 001-cross-channel-analytics

## Prerequisites

- PHP 8.1+
- PostgreSQL 14+
- Composer
- Fusio installation
- amoCRM account with API access

## Installation

### 1. Clone and Install Dependencies

```bash
# Create analytics directory alongside amocrm-api-php
cd /path/to/project
mkdir analytics && cd analytics

# Initialize composer project
composer init --name="analytics/amocrm-analytics"
composer require php:>=8.1 \
  amocrm/amocrm-api-library:^1.17 \
  fusio/fusio:^4.0 \
  doctrine/dbal:^3.0 \
  nesbot/carbon:^2.72 \
  symfony/console:^6.0 \
  monolog/monolog:^3.0

# Dev dependencies
composer require --dev phpunit/phpunit:^9.0
```

### 2. Database Setup

```bash
# Create database
psql -U postgres -c "CREATE DATABASE amocrm_analytics;"

# Run migrations
php bin/analytics migrate
```

### 3. Configuration

Create `config/config.php`:

```php
<?php
return [
    'app' => [
        'env' => 'production',
        'debug' => false,
        'timezone' => 'UTC',
    ],
    
    'database' => [
        'driver' => 'pdo_pgsql',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: 5432,
        'dbname' => getenv('DB_NAME') ?: 'amocrm_analytics',
        'user' => getenv('DB_USER') ?: 'postgres',
        'password' => getenv('DB_PASSWORD') ?: '',
    ],
    
    'amocrm' => [
        'client_id' => getenv('AMOCRM_CLIENT_ID'),
        'client_secret' => getenv('AMOCRM_CLIENT_SECRET'),
        'redirect_uri' => getenv('AMOCRM_REDIRECT_URI'),
        'base_domain' => getenv('AMOCRM_BASE_DOMAIN'),
    ],
    
    'fusio' => [
        'app_public' => getenv('FUSIO_PUBLIC_URL') ?: 'https://your-fusio.com',
        'app_secret' => getenv('FUSIO_APP_SECRET'),
    ],
    
    'analytics' => [
        'batch_size' => 100,
        'webhook_timeout' => 5,
        'max_retries' => 3,
    ],
];
```

### 4. Environment Variables

Create `.env` file:

```bash
# Database
DB_HOST=localhost
DB_PORT=5432
DB_NAME=amocrm_analytics
DB_USER=postgres
DB_PASSWORD=your_password

# amoCRM OAuth
AMOCRM_CLIENT_ID=your_client_id
AMOCRM_CLIENT_SECRET=your_client_secret
AMOCRM_REDIRECT_URI=https://your-app.com/callback
AMOCRM_BASE_DOMAIN=youraccount.amocrm.ru

# Fusio
FUSIO_PUBLIC_URL=https://your-fusio.com
FUSIO_APP_SECRET=your_fusio_secret
```

## Initial Setup

### 1. OAuth Token Initialization

```bash
php bin/analytics amocrm:auth
```

This will:
1. Generate authorization URL
2. Wait for you to authorize
3. Save tokens to secure storage

### 2. Initial Data Load

```bash
# Full export (one-time)
php bin/analytics etl:full

# Or incremental (scheduled)
php bin/analytics etl:incremental
```

### 3. Webhook Subscription

```bash
# Subscribe to events
php bin/analytics webhook:subscribe

# Events subscribed:
# - lead_added
# - lead_status_changed
# - lead_updated
# - contact_added
# - customer_transaction_added
# - unsorted_added
```

## Usage

### CLI Commands

```bash
# ETL operations
php bin/analytics etl:full           # Full data export
php bin/analytics etl:incremental    # Incremental export
php bin/analytics etl:leads         # Export only leads
php bin/analytics etl:events        # Export only events

# Webhook management
php bin/analytics webhook:subscribe  # Subscribe to webhooks
php bin/analytics webhook:status     # Check subscription status
php bin/analytics webhook:unsubscribe # Unsubscribe

# Sync operations
php bin/analytics sync:pipelines    # Sync pipeline stages
php bin/analytics sync:sources      # Sync sources
php bin/analytics sync:contacts      # Sync contacts
```

### API Endpoints

#### Get Funnel Data
```http
GET /api/analytics/funnel?pipeline_id=123&date_from=2024-01-01&date_to=2024-06-30
Authorization: Bearer {token}

Response:
{
  "pipeline_id": 123,
  "pipeline_name": "Sales Pipeline",
  "stages": [
    {
      "status_id": 1,
      "status_name": "New",
      "lead_count": 100,
      "total_revenue": 500000,
      "conversion_rate": 1.0,
      "avg_time_days": 2.5
    },
    {
      "status_id": 2,
      "status_name": "Qualified",
      "lead_count": 65,
      "total_revenue": 380000,
      "conversion_rate": 0.65,
      "avg_time_days": 5.2
    }
  ]
}
```

#### Get Source Attribution
```http
GET /api/analytics/attribution?type=first_touch&date_from=2024-01-01
Authorization: Bearer {token}

Response:
{
  "type": "first_touch",
  "sources": [
    {
      "source_id": 1,
      "source_name": "Google Ads",
      "lead_count": 50,
      "total_revenue": 250000,
      "avg_deal_value": 5000,
      "conversion_rate": 0.45
    }
  ]
}
```

#### Get Customer LTV
```http
GET /api/analytics/ltv?segment=all&min_transactions=1
Authorization: Bearer {token}

Response:
{
  "segment": "all",
  "customers": [
    {
      "customer_id": 123,
      "total_revenue": 15000,
      "transaction_count": 5,
      "avg_transaction_value": 3000,
      "first_purchase_at": "2024-01-15",
      "ltv": 15000
    }
  ],
  "summary": {
    "total_customers": 100,
    "total_revenue": 500000,
    "avg_ltv": 5000,
    "median_ltv": 3500
  }
}
```

#### Webhook Endpoint
```http
POST /webhook/amocrm
Content-Type: application/json

{
  "webhook": {
    "type": "lead_status_changed",
    "timestamp": 1718294400
  },
  "payload": {
    "lead": {
      "id": 12345,
      "status_id": 142,
      "pipeline_id": 1,
      "updated_at": 1718294400
    }
  }
}
```

## Cron Jobs

Add to crontab for scheduled ETL:

```bash
# Every 5 minutes - incremental sync
*/5 * * * * cd /path/to/analytics && php bin/analytics etl:incremental >> /var/log/analytics/etl.log 2>&1

# Every hour - full sync (for backup/consistency)
0 * * * * cd /path/to/analytics && php bin/analytics etl:full --force >> /var/log/analytics/full.log 2>&1

# Daily - refresh materialized views
0 3 * * * cd /path/to/analytics && php bin/analytics db:refresh-views >> /var/log/analytics/views.log 2>&1
```

## Troubleshooting

### Token Expired

```bash
# Refresh token manually
php bin/analytics amocrm:refresh-token
```

### Rate Limiting

The system automatically handles 429 errors with exponential backoff. Check logs:

```bash
tail -f var/log/analytics/error.log | grep "rate.limit"
```

### Webhook Not Receiving

1. Check Fusio endpoint is publicly accessible
2. Verify webhook subscription status: `php bin/analytics webhook:status`
3. Check webhook logs: `php bin/analytics webhook:logs`

## Testing

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit tests/unit/ETL/
./vendor/bin/phpunit tests/integration/

# Generate coverage report
./vendor/bin/phpunit --coverage-html coverage/
```

## Next Steps

1. Review [data-model.md](./data-model.md) for schema details
2. See [research.md](./research.md) for integration notes
3. Proceed to `/speckit.tasks` for implementation tasks