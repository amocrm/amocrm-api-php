# Feature Specification: Cross-Channel Analytics System

**Feature Branch**: `001-cross-channel-analytics`

**Created**: 2026-06-13

**Status**: Draft

**Input**: User description: "Создать систему сквозной аналитики (end-to-end analytics) для amoCRM с использованием Fusio, которая отслеживает полный путь клиента: от первого касания (источник/неразобранное) → через сделку → до покупки/транзакции и повторных продаж."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Data Collection Pipeline (Priority: P1)

Как аналитик, я хочу собирать данные из всех источников amoCRM (неразобранное, сделки, события, транзакции, звонки, чаты) в единое хранилище, чтобы иметь полную картину пути клиента.

**Why this priority**: Без сбора данных вся аналитика невозможна. Это базовый фундамент.

**Independent Test**: Можно протестировать полностью, запустив ETL-процесс и проверив наличие данных в целевой БД.

**Acceptance Scenarios**:

1. **Given** amoCRM account with deals and contacts, **When** ETL process runs, **Then** all deals with status changes are stored in analytics database
2. **Given** new unsorted lead appears in amoCRM, **When** next sync runs, **Then** unsorted data is captured with source attribution
3. **Given** customer makes a purchase, **When** transaction is created, **Then** transaction data is stored with customer linkage

---

### User Story 2 - Real-Time Webhook Processing (Priority: P1)

Как аналитик, я хочу получать уведомления о событиях в реальном времени через вебхуки, чтобы мгновенно обновлять аналитические данные при изменении статусов сделок.

**Why this priority**: Real-time данные критичны для актуальности аналитики.

**Independent Test**: Можно протестировать, отправив тестовый webhook и проверив обновление в БД.

**Acceptance Scenarios**:

1. **Given** webhook subscription is active, **When** deal status changes in amoCRM, **Then** webhook payload is received within 5 seconds
2. **Given** webhook handler receives status change event, **When** payload is valid, **Then** analytics database is updated with new status
3. **Given** webhook handler receives invalid payload, **When** processing fails, **Then** error is logged and retry mechanism triggers

---

### User Story 3 - Funnel Visualization (Priority: P2)

Как руководитель отдела продаж, я хочу видеть воронку продаж с конверсией по этапам, чтобы понимать, на каких этапах клиенты теряются.

**Why this priority**: Воронка — ключевая метрика для управления продажами.

**Independent Test**: Можно протестировать, создав тестовые сделки и проверив расчёт конверсии.

**Acceptance Scenarios**:

1. **Given** deals in multiple pipeline stages, **When** funnel report is generated, **Then** each stage shows count and conversion rate from previous stage
2. **Given** deals moved between stages over time, **When** historical funnel is viewed, **Then** historical data shows funnel at any past date

---

### User Story 4 - Source Attribution (Priority: P2)

Как маркетолог, я хочу видеть, какие источники привлечения приводят к сделкам, чтобы оптимизировать маркетинговый бюджет.

**Why this priority**: Атрибуция источников критична для ROI маркетинга.

**Independent Test**: Можно протестировать, создав сделки из разных источников и проверив отчёт по источникам.

**Acceptance Scenarios**:

1. **Given** deals created with different source IDs, **When** source attribution report runs, **Then** revenue is aggregated by source
2. **Given** unsorted leads converted to deals, **When** first-touch attribution calculated, **Then** unsorted category is linked to resulting deal

---

### User Story 5 - Customer Lifetime Value (Priority: P3)

Как аналитик, я хочу рассчитывать LTV (lifetime value) покупателей, чтобы сегментировать клиентов по ценности.

**Why this priority**: LTV критичен для понимания экономики клиента.

**Independent Test**: Можно протестировать с симулированными транзакциями.

**Acceptance Scenarios**:

1. **Given** customer has multiple transactions, **When** LTV is calculated, **Then** sum of all transaction values is returned
2. **Given** customer has no transactions, **When** LTV is queried, **Then** zero or null is returned

---

### User Story 6 - Custom Field Integration (Priority: P3)

Как аналитик, я хочу использовать кастомные поля amoCRM (UTM, сегменты, бюджеты) для обогащения аналитики, чтобы делать более глубокие срезы данных.

**Why this priority**: Обогащение данными повышает ценность аналитики.

**Independent Test**: Можно протестировать с тестовыми кастомными полями.

**Acceptance Scenarios**:

1. **Given** lead has UTM custom fields, **When** lead is exported, **Then** UTM values are stored in analytics database
2. **Given** contact has segment custom field, **When** segment report runs, **Then** contacts are grouped by segment

---

### Edge Cases

- Что происходит, когда источник (source) удалён из amoCRM, но сделки с ним остались?
- Как обрабатывать сделки без привязанных контактов?
- Что если вебхук приходит для уже удалённой сущности?
- Как обрабатывать дубликаты при повторной отправке вебхука?
- Что если транзакция привязана к удалённому покупателю?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST support incremental data export from amoCRM using `updatedAt` filter
- **FR-002**: System MUST support full export (initial load) with pagination via `nextPage()` method
- **FR-003**: System MUST process webhooks and update analytics data within 5 seconds of receipt
- **FR-004**: System MUST store all entity types: unsorted, leads, events, transactions, contacts, customers, calls, talks
- **FR-005**: System MUST maintain entity relationships (lead → contact → customer → transaction)
- **FR-006**: System MUST support first-touch and last-touch attribution models
- **FR-007**: System MUST calculate funnel metrics: stage counts, conversion rates, average time in stage
- **FR-008**: System MUST calculate customer LTV and cohort metrics
- **FR-009**: System MUST handle API rate limiting (429 errors) with exponential backoff
- **FR-010**: System MUST log all API errors with request context for debugging
- **FR-011**: System MUST support idempotent webhook processing (handle duplicates)
- **FR-012**: System MUST extract and store custom field values for enrichment
- **FR-013**: System MUST provide REST API endpoints for analytics queries via Fusio

### Key Entities *(include if feature involves data)*

- **AnalyticsEvent**: Событие аналитики (created_at, entity_type, entity_id, event_type, payload, processed_at)
- **LeadSnapshot**: Снимок сделки в момент времени (lead_id, pipeline_id, status_id, price, source_id, created_at, updated_at)
- **CustomerLTV**: Расчётный LTV покупателя (customer_id, total_revenue, transaction_count, first_purchase_at, last_purchase_at, ltv)
- **FunnelStage**: Этап воронки (pipeline_id, status_id, status_name, position, avg_time_seconds, conversion_rate)
- **SourceAttribution**: Атрибуция по источникам (lead_id, source_id, attribution_type, attribution_value)
- **WebhookLog**: Лог обработки вебхуков (webhook_id, event_type, entity_type, entity_id, received_at, processed_at, status, error_message)

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Data export completes for 10,000 leads within 10 minutes
- **SC-002**: Webhook processing latency is under 5 seconds for 95% of webhooks
- **SC-003**: Funnel report generates within 3 seconds for pipelines with up to 100 stages
- **SC-004**: System handles 100 concurrent webhook deliveries without data loss
- **SC-005**: All API errors include request context (endpoint, parameters, response code) for debugging
- **SC-006**: Initial data load for new amoCRM account completes without manual intervention

## Assumptions

- Пользователи имеют доступ к amoCRM API через OAuth токен
- Fusio установлен и настроен для приёма вебхуков
- Аналитическая БД (PostgreSQL или ClickHouse) доступна для хранения данных
- amoCRM аккаунт имеет активные сделки, контакты и включён модуль покупателей
- Кастомные поля созданы в amoCRM для UTM-меток и сегментов (если требуются)
- Система работает в часовом поясе UTC для консистентности дат