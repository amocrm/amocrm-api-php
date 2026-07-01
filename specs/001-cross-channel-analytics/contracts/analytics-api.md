# Analytics API Contract

**Date**: 2026-06-13  
**Feature**: 001-cross-channel-analytics

## Base URL

```
/api/analytics
```

## Authentication

All endpoints require Bearer token authentication.

```
Authorization: Bearer {token}
```

## Common Headers

| Header | Required | Description |
|--------|----------|-------------|
| `Content-Type` | Yes | `application/json` |
| `Authorization` | Yes | Bearer token |
| `Accept` | No | `application/json` |

## Endpoints

### 1. GET /api/analytics/funnel

Get funnel metrics for a pipeline.

**Query Parameters**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `pipeline_id` | int | No | Filter by pipeline |
| `date_from` | date | No | Start date (YYYY-MM-DD) |
| `date_to` | date | No | End date (YYYY-MM-DD) |
| `group_by` | string | No | `day`, `week`, `month` |

**Response**

```json
{
  "pipeline_id": 1,
  "pipeline_name": "Sales Pipeline",
  "period": {
    "from": "2024-01-01",
    "to": "2024-06-30"
  },
  "stages": [
    {
      "status_id": 1,
      "status_name": "New",
      "sort_order": 1,
      "is_final": false,
      "metrics": {
        "lead_count": 100,
        "total_revenue": 500000,
        "avg_deal_value": 5000,
        "conversion_rate": 1.0,
        "avg_time_days": 2.5
      },
      "next_stage_conversion": {
        "to_status_id": 2,
        "converted_count": 65,
        "conversion_rate": 0.65
      }
    }
  ],
  "summary": {
    "total_leads": 100,
    "total_revenue": 500000,
    "overall_conversion": 0.25,
    "avg_deal_cycle_days": 15.5
  }
}
```

### 2. GET /api/analytics/attribution

Get source attribution report.

**Query Parameters**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `type` | string | Yes | `first_touch` or `last_touch` |
| `date_from` | date | No | Start date |
| `date_to` | date | No | End date |
| `source_id` | int | No | Filter by source |
| `limit` | int | No | Max results (default: 50) |
| `offset` | int | No | Pagination offset |

**Response**

```json
{
  "type": "first_touch",
  "period": {
    "from": "2024-01-01",
    "to": "2024-06-30"
  },
  "sources": [
    {
      "source_id": 1,
      "source_name": "Google Ads",
      "source_external_id": "google_ads_001",
      "metrics": {
        "lead_count": 50,
        "total_revenue": 250000,
        "avg_deal_value": 5000,
        "won_count": 25,
        "conversion_rate": 0.5,
        "avg_time_to_close_days": 12
      },
      "breakdown": {
        "by_pipeline": [
          {
            "pipeline_id": 1,
            "lead_count": 30,
            "revenue": 150000
          }
        ],
        "by_utm_medium": [
          {
            "utm_medium": "cpc",
            "lead_count": 40,
            "revenue": 200000
          }
        ]
      }
    }
  ],
  "summary": {
    "total_leads": 200,
    "total_revenue": 1000000,
    "top_source": {
      "source_id": 1,
      "source_name": "Google Ads",
      "revenue_share": 0.25
    }
  }
}
```

### 3. GET /api/analytics/ltv

Get customer lifetime value metrics.

**Query Parameters**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `segment` | string | No | `all`, `active`, `churned` |
| `min_transactions` | int | No | Minimum transaction count |
| `date_from` | date | No | Cohort start date |
| `date_to` | date | No | Cohort end date |
| `sort_by` | string | No | `ltv`, `revenue`, `transactions` |
| `sort_order` | string | No | `asc`, `desc` |
| `limit` | int | No | Max results |
| `offset` | int | No | Pagination offset |

**Response**

```json
{
  "segment": "all",
  "period": {
    "from": "2024-01-01",
    "to": "2024-06-30"
  },
  "customers": [
    {
      "customer_id": 123,
      "metrics": {
        "total_revenue": 15000,
        "transaction_count": 5,
        "avg_transaction_value": 3000,
        "first_purchase_at": "2024-01-15",
        "last_purchase_at": "2024-06-01",
        "days_since_first_purchase": 167,
        "ltv": 15000
      },
      "contact": {
        "id": 456,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  ],
  "summary": {
    "total_customers": 100,
    "total_revenue": 500000,
    "avg_ltv": 5000,
    "median_ltv": 3500,
    "ltv_percentile_90": 12000,
    "avg_transactions_per_customer": 3.5,
    "avg_customer_lifespan_days": 90
  },
  "cohorts": [
    {
      "cohort_month": "2024-01",
      "customers": 20,
      "revenue_month_0": 10000,
      "revenue_month_1": 8000,
      "revenue_month_2": 6000,
      "retention_rate_1": 0.8,
      "retention_rate_2": 0.6
    }
  ]
}
```

### 4. GET /api/analytics/overview

Get dashboard overview metrics.

**Response**

```json
{
  "generated_at": "2024-06-30T12:00:00Z",
  "period": {
    "from": "2024-06-01",
    "to": "2024-06-30"
  },
  "metrics": {
    "leads": {
      "total": 150,
      "new_this_month": 45,
      "won": 30,
      "lost": 20,
      "avg_deal_value": 5500
    },
    "revenue": {
      "total": 275000,
      "from_won_deals": 165000,
      "from_transactions": 110000
    },
    "customers": {
      "total": 50,
      "new_this_month": 15,
      "active": 35,
      "churned": 5
    },
    "funnel": {
      "avg_conversion_rate": 0.35,
      "avg_deal_cycle_days": 14
    }
  },
  "top_sources": [
    {
      "source_name": "Google Ads",
      "lead_count": 50,
      "revenue": 75000,
      "conversion_rate": 0.4
    }
  ],
  "recent_activity": {
    "last_sync_at": "2024-06-30T11:55:00Z",
    "pending_webhooks": 0,
    "last_lead_at": "2024-06-30T11:30:00Z"
  }
}
```

### 5. GET /api/analytics/leads

Get lead data with analytics enrichment.

**Query Parameters**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `pipeline_id` | int | No | Filter by pipeline |
| `status_id` | int | No | Filter by status |
| `source_id` | int | No | Filter by source |
| `date_from` | date | No | Created after |
| `date_to` | date | No | Created before |
| `has_contact` | bool | No | Has linked contact |
| `min_price` | decimal | No | Minimum deal value |
| `limit` | int | No | Max results |
| `offset` | int | No | Pagination offset |

**Response**

```json
{
  "leads": [
    {
      "lead_id": 123456,
      "name": "Deal Name",
      "price": 50000,
      "pipeline": {
        "id": 1,
        "name": "Sales"
      },
      "status": {
        "id": 2,
        "name": "Qualified"
      },
      "source": {
        "id": 1,
        "name": "Google Ads",
        "attribution_type": "first_touch"
      },
      "contact": {
        "id": 789,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "metrics": {
        "time_in_stage_days": 3,
        "time_total_days": 12,
        "touch_count": 5
      },
      "custom_fields": {
        "utm_source": "google",
        "utm_campaign": "summer_sale",
        "client_segment": "enterprise"
      },
      "timestamps": {
        "created_at": "2024-01-15T10:00:00Z",
        "updated_at": "2024-06-30T14:30:00Z",
        "closed_at": null
      }
    }
  ],
  "pagination": {
    "total": 500,
    "limit": 50,
    "offset": 0,
    "has_more": true
  }
}
```

## Error Responses

All errors follow this format:

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid date range",
    "details": {
      "field": "date_from",
      "reason": "date_from must be before date_to"
    }
  }
}
```

### Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `UNAUTHORIZED` | 401 | Invalid or missing token |
| `FORBIDDEN` | 403 | Insufficient permissions |
| `NOT_FOUND` | 404 | Resource not found |
| `VALIDATION_ERROR` | 400 | Invalid parameters |
| `RATE_LIMITED` | 429 | Too many requests |
| `INTERNAL_ERROR` | 500 | Server error |

## Rate Limits

| Endpoint | Limit | Window |
|----------|-------|--------|
| `GET /funnel` | 100 | 1 minute |
| `GET /attribution` | 100 | 1 minute |
| `GET /ltv` | 50 | 1 minute |
| `GET /leads` | 200 | 1 minute |

## Pagination

For endpoints with pagination:

```json
{
  "pagination": {
    "total": 500,
    "limit": 50,
    "offset": 0,
    "next_offset": 50,
    "has_more": true
  }
}
```

Use `limit` and `offset` parameters to paginate.

## Caching

| Endpoint | Cache TTL |
|----------|----------|
| `GET /funnel` | 5 minutes |
| `GET /attribution` | 15 minutes |
| `GET /ltv` | 15 minutes |
| `GET /overview` | 1 minute |
| `GET /leads` | No cache |

Include `Cache-Control` header in requests to control caching behavior:
- `Cache-Control: no-cache` - Skip cache
- `Cache-Control: max-age=300` - Use cached data if fresher than 5 minutes