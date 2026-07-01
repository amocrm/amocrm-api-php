# Implementation Plan: Cross-Channel Analytics System

**Branch**: `001-cross-channel-analytics` | **Date**: 2026-06-13 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/001-cross-channel-analytics/spec.md`

## Summary

Система сквозной аналитики (end-to-end analytics) для amoCRM с использованием Fusio. Отслеживает полный путь клиента: от первого касания (источник/неразобранное) → через сделку → до покупки/транзакции и повторных продаж.

**Technical Approach**:
1. ETL-сервисы для инкрементальной и полной выгрузки данных из amoCRM API
2. Webhook-обработчик для real-time обновлений
3. Аналитическая БД (PostgreSQL) для хранения данных
4. REST API через Fusio для запросов аналитики
5. Витрины данных для воронок, атрибуции и LTV

## Technical Context

**Language/Version**: PHP 8.1+

**Primary Dependencies**: 
- `amocrm/amocrm-api-library` — amoCRM API client
- `fusio/fusio` — API framework
- `doctrine/dbal` — Database abstraction (allowed per constitution)
- `nesbot/carbon` — Date/time (existing dependency)

**Storage**: PostgreSQL 14+ (primary analytics DB), ClickHouse (optional for high-volume)

**Testing**: PHPUnit 9+

**Target Platform**: Linux server, PHP-FPM 8.1+

**Project Type**: Backend API service + CLI ETL tools

**Performance Goals**: 
- 10,000 leads export in 10 minutes (SC-001)
- Webhook latency < 5s p95 (SC-002)
- Funnel report < 3s (SC-003)
- 100 concurrent webhooks (SC-004)

**Constraints**: 
- PHP 8.1+ required
- No ORM (Doctrine DBAL only per constitution)
- All code must pass `composer style:check`

**Scale/Scope**: 
- 10k-100k leads per account
- 100 concurrent webhook deliveries
- 8 entity types to collect

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| **I. API Stability** | ✅ PASS | No breaking changes to existing amocrm-api-php library |
| **II. Type Safety** | ✅ PASS | All models use strict types, PHPDoc annotations |
| **III. Model Interface** | ✅ PASS | All analytics models implement toArray(), toApi(), fromArray(), getId() |
| **IV. OAuth Token Mgmt** | ✅ PASS | Reusing existing AmoCRMApiClient token handling |
| **V. Error Handling** | ✅ PASS | Using existing exception hierarchy |

**No violations detected.**

## Project Structure

### Documentation (this feature)

```text
specs/001-cross-channel-analytics/
├── spec.md              # Feature specification
├── quality-checklist.md # Quality validation
├── plan.md              # This file
├── research.md          # Phase 0: Fusio integration research
├── data-model.md        # Phase 1: Analytics DB schema
├── quickstart.md        # Phase 1: Setup guide
├── contracts/           # Phase 1: API contracts
│   ├── webhook-api.md   # Webhook endpoint contract
│   └── analytics-api.md # Analytics query endpoints
└── tasks.md             # Phase 2: Task breakdown (separate command)
```

### Source Code (repository root)

This is a new service, NOT modifying the existing amocrm-api-php library.

```text
# New analytics service (separate from amocrm-api-php library)
analytics/
├── src/
│   ├── Client/                  # API client setup
│   │   └── AmoCRMFactory.php    # Factory for AmoCRMApiClient
│   ├── ETL/                     # Data extraction
│   │   ├── LeadsExporter.php
│   │   ├── EventsExporter.php
│   │   ├── UnsortedExporter.php
│   │   ├── TransactionsExporter.php
│   │   ├── ContactsExporter.php
│   │   ├── CustomersExporter.php
│   │   ├── CallsExporter.php
│   │   └── TalksExporter.php
│   ├── Webhook/                 # Webhook processing
│   │   ├── Handler.php          # Main webhook handler
│   │   ├── Processor.php        # Event processor
│   │   └── Idempotency.php      # Duplicate handling
│   ├── Analytics/               # Analytics calculations
│   │   ├── FunnelService.php    # Funnel metrics
│   │   ├── AttributionService.php # Source attribution
│   │   └── LTVService.php       # Lifetime value
│   ├── Repository/              # Data access
│   │   ├── LeadSnapshotRepo.php
│   │   ├── TransactionRepo.php
│   │   └── WebhookLogRepo.php
│   ├── Models/                  # Analytics models
│   │   ├── AnalyticsEvent.php
│   │   ├── LeadSnapshot.php
│   │   ├── CustomerLTV.php
│   │   ├── FunnelStage.php
│   │   ├── SourceAttribution.php
│   │   └── WebhookLog.php
│   └── Api/                     # Fusio routes
│       ├── Action/
│       │   ├── FunnelAction.php
│       │   ├── AttributionAction.php
│       │   └── LTVAction.php
│       └── webhook.php          # Webhook endpoint
├── config/
│   ├── config.php               # App configuration
│   └── fusio_routes.php         # Fusio route definitions
├── migrations/                  # Database migrations
│   └── Version20260613_InitialSchema.php
├── tests/
│   ├── unit/
│   │   ├── ETL/
│   │   ├── Analytics/
│   │   └── Webhook/
│   └── integration/
│       └── FullPipelineTest.php
├── bin/
│   └── analytics                # CLI tool for ETL
├── composer.json
└── README.md

# Existing amocrm-api-php library (read-only, no modifications)
src/
tests/
examples/
```

**Structure Decision**: New `analytics/` directory alongside existing amocrm-api-php library. No modifications to existing library code. Analytics service is a consumer of amocrm-api-php, not part of it.

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| None | — | — |

No constitution violations. All requirements met with standard patterns.

## Implementation Phases

### Phase 0: Research (Week 1)

**Goal**: Understand Fusio integration and design data model

- [ ] Research Fusio webhook endpoint configuration
- [ ] Research PostgreSQL schema design for analytics
- [ ] Design entity relationship diagram
- [ ] Create `research.md`

### Phase 1: Foundation (Week 2-3)

**Goal**: Core infrastructure and ETL pipeline

- [ ] Setup `analytics/` directory with composer.json
- [ ] Create AmoCRMFactory for client initialization
- [ ] Design and implement PostgreSQL migrations
- [ ] Implement base ETL exporter with pagination
- [ ] Implement LeadsExporter with filters
- [ ] Implement EventsExporter
- [ ] Implement UnsortedExporter
- [ ] Create `data-model.md` with schema documentation
- [ ] Create `quickstart.md` with setup instructions
- [ ] Define API contracts in `contracts/`

### Phase 2: Webhook Processing (Week 4)

**Goal**: Real-time data updates

- [ ] Implement WebhookHandler endpoint
- [ ] Implement idempotency via WebhookLog
- [ ] Implement event processor for lead status changes
- [ ] Add exponential backoff for API errors
- [ ] Create webhook API contract

### Phase 3: Analytics Services (Week 5-6)

**Goal**: Analytics calculations

- [ ] Implement FunnelService with stage metrics
- [ ] Implement AttributionService (first/last touch)
- [ ] Implement LTVService with cohort analysis
- [ ] Add custom field extraction
- [ ] Create Fusio route actions

### Phase 4: Integration & Polish (Week 7)

**Goal**: End-to-end integration

- [ ] Implement remaining exporters (Contacts, Customers, Calls, Talks, Transactions)
- [ ] End-to-end integration tests
- [ ] Performance testing
- [ ] Documentation

## Dependencies

### Composer.json for analytics/

```json
{
  "name": "analytics/amocrm-analytics",
  "description": "Cross-channel analytics for amoCRM",
  "type": "project",
  "require": {
    "php": ">=8.1",
    "amocrm/amocrm-api-library": "^1.17",
    "fusio/fusio": "^4.0",
    "doctrine/dbal": "^3.0",
    "nesbot/carbon": "^2.72",
    "symfony/console": "^6.0",
    "monolog/monolog": "^3.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.0"
  }
}
```

## Key Decisions

1. **Separate Service**: Analytics is a separate service, not part of amocrm-api-php library
2. **PostgreSQL First**: Use PostgreSQL as primary storage (ClickHouse optional for scale)
3. **Fusio for API**: Use Fusio for webhook endpoint and analytics REST API
4. **Doctrine DBAL**: Using DBAL (not full ORM) per constitution constraints
5. **CLI for ETL**: CLI tool for scheduled data exports
6. **Event Sourcing Light**: LeadSnapshot table for historical tracking

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| API rate limiting | High | Exponential backoff, queue-based processing |
| Large data exports timeout | Medium | Chunked processing, progress tracking |
| Webhook duplicate delivery | Medium | Idempotency via WebhookLog |
| Schema changes in amoCRM | Low | Versioned schema, field validation |
| Token expiration during sync | Medium | Auto-refresh via callback |

## Next Steps

1. Execute `/speckit.tasks` to generate detailed task breakdown
2. Start Phase 0 research
3. Review plan with stakeholders