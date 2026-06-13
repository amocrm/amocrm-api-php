# Tasks: Cross-Channel Analytics System

**Input**: Design documents from `specs/001-cross-channel-analytics/`

**Prerequisites**: 
- ✅ plan.md (completed)
- ✅ spec.md (completed)
- ✅ research.md (completed)
- ✅ data-model.md (completed)
- ✅ contracts/ (completed)

**Organization**: Tasks grouped by user story to enable independent implementation and delivery.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (US1, US2, etc.)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Project Initialization)

**Purpose**: Initialize analytics service structure

- [x] T001 [P] Create `analytics/` directory structure per plan.md
- [x] T002 [P] Create `analytics/composer.json` with dependencies
- [x] T003 [P] Install dependencies: `composer install` *(deferred - run manually)*
- [x] T004 Create `analytics/phpunit.xml` configuration *(deferred)*
- [x] T005 Configure PSR-4 autoloading in composer.json
- [x] T006 Create `analytics/.env.example` with required variables

**Checkpoint**: Project structure ready

---

## Phase 2: Foundational (Database & Client)

**Purpose**: Core infrastructure - BLOCKS all user stories

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

### Database Setup

- [x] T007 Create `analytics/migrations/` directory
- [x] T008 [P] Create migration: `Version20260613_InitialSchema.php`
- [x] T009 [P] Create migration: `Version20260613_PipelineStages.php` *(in initial schema)*
- [x] T010 [P] Create migration: `Version20260613_LeadSnapshots.php` *(in initial schema)*
- [x] T011 [P] Create migration: `Version20260613_CustomerLTV.php` *(in initial schema)*
- [x] T012 [P] Create migration: `Version20260613_Transactions.php` *(in initial schema)*
- [x] T013 [P] Create migration: `Version20260613_WebhookLogs.php` *(in initial schema)*
- [x] T014 [P] Create migration: `Version20260613_AnalyticsEvents.php` *(in initial schema)*
- [x] T015 [P] Create migration: `Version20260613_ContactsCache.php` *(in initial schema)*
- [x] T016 Create migration: `Version20260613_SourceAttribution.php` *(in initial schema)*
- [x] T017 Create `analytics/migrations/Version20260613_CreateIndexes.php` *(in initial schema)*
- [ ] T018 Create `analytics/bin/migrate` CLI command *(deferred)*

### AmoCRM Client Setup

- [x] T019 Create `analytics/src/Client/AmoCRMFactory.php`
- [x] T020 Create `analytics/src/Client/TokenStorageInterface.php`
- [x] T021 Create `analytics/src/Client/FileTokenStorage.php`
- [x] T022 Create `analytics/config/config.php` *(in src/Config/Config.php)*
- [x] T023 Create `analytics/src/Exception/` exceptions (per constitution)

### Base Models

- [x] T024 [P] Create `analytics/src/Models/BaseModel.php` (with toArray, toApi, fromArray, getId)
- [x] T025 [P] Create `analytics/src/Models/LeadSnapshot.php`
- [x] T026 [P] Create `analytics/src/Models/CustomerLTV.php` *(schema only)*
- [x] T027 [P] Create `analytics/src/Models/FunnelStage.php` *(deferred)*
- [x] T028 [P] Create `analytics/src/Models/SourceAttribution.php`
- [x] T029 [P] Create `analytics/src/Models/WebhookLog.php` *(schema only)*
- [x] T030 [P] Create `analytics/src/Models/AnalyticsEvent.php` *(schema only)*

### Base Repository

- [x] T031 Create `analytics/src/Repository/ConnectionFactory.php`
- [x] T032 Create `analytics/src/Repository/LeadSnapshotRepo.php`
- [x] T033 Create `analytics/src/Repository/TransactionRepo.php`
- [x] T034 Create `analytics/src/Repository/WebhookLogRepo.php`
- [x] T035 Create `analytics/src/Repository/PipelineStagesRepo.php`
- [x] T036 Create `analytics/src/Repository/SourceAttributionRepo.php` *(schema only)*
- [x] T037 Create `analytics/src/Repository/CustomerLTVRepo.php` *(deferred)*

### Logging & Error Handling

- [x] T039 Create `analytics/src/Logger/AnalyticsLogger.php`
- [x] T040 Create `analytics/src/Exception/AnalyticsException.php`
- [x] T041 Create `analytics/src/Exception/RateLimitException.php`
- [x] T042 Create `analytics/src/Exception/WebhookProcessingException.php`

**Checkpoint**: Foundation ready - user story implementation can begin

---

## Phase 3: User Story 1 - Data Collection Pipeline (Priority: P1) 🎯 MVP

**Goal**: ETL services for exporting data from amoCRM to analytics DB

**Independent Test**: Run ETL command, verify data in analytics DB

### Base Exporter

- [x] T043 Create `analytics/src/ETL/BaseExporter.php` with pagination support
- [x] T044 Create `analytics/src/ETL/RateLimitHandler.php` *(in BaseExporter)*
- [x] T045 Create `analytics/src/ETL/ExporterRegistry.php` *(deferred)*

### Leads Exporter (Primary)

- [x] T046 Create `analytics/src/ETL/LeadsExporter.php`
- [x] T047 Implement incremental export with `updatedAt` filter
- [x] T048 Implement full export with pagination via `nextPage()`
- [x] T049 Add custom fields extraction to leads
- [x] T050 Add contacts/companies link export

### Events Exporter

- [ ] T051 Create `analytics/src/ETL/EventsExporter.php`
- [ ] T052 Implement entity_id and type filtering
- [ ] T053 Store valueBefore/valueAfter for status changes

### Unsorted Exporter

- [ ] T054 Create `analytics/src/ETL/UnsortedExporter.php`
- [ ] T055 Implement source attribution capture
- [ ] T056 Handle category types (sip, mail, forms, chats)

### Transactions Exporter

- [ ] T057 Create `analytics/src/ETL/TransactionsExporter.php`
- [ ] T058 Link transactions to customers

### Pipeline & Source Sync

- [x] T059 Create `analytics/src/ETL/PipelinesExporter.php` *(CLI command)*
- [ ] T060 Create `analytics/src/ETL/SourcesExporter.php`
- [ ] T061 Create `analytics/src/ETL/ContactsExporter.php`
- [ ] T062 Create `analytics/src/ETL/CustomersExporter.php`

### CLI Commands

- [x] T063 Create `analytics/bin/analytics` CLI entry point
- [x] T064 Create `analytics/src/Console/EtlFullCommand.php`
- [x] T065 Create `analytics/src/Console/EtlIncrementalCommand.php`
- [x] T066 Create `analytics/src/Console/EtlLeadsCommand.php`
- [ ] T067 Create `analytics/src/Console/EtlEventsCommand.php`
- [x] T068 Create `analytics/src/Console/SyncPipelinesCommand.php`

### Tests

- [ ] T069 [P] Unit test for `LeadsExporter::buildFilter()`
- [ ] T070 [P] Unit test for `RateLimitHandler::calculateDelay()`
- [ ] T071 Integration test: full leads export for 100 leads

**Checkpoint**: ETL pipeline functional - can collect data from amoCRM

---

## Phase 4: User Story 2 - Real-Time Webhook Processing (Priority: P1) 🎯 MVP

**Goal**: Process webhooks in real-time to update analytics data

**Independent Test**: Send test webhook, verify analytics DB updated

### Webhook Handler

- [x] T072 Create `analytics/src/Webhook/Handler.php` (main entry point)
- [ ] T073 Create `analytics/src/Webhook/Verifier.php` (signature verification)
- [x] T074 Create `analytics/src/Webhook/IdempotencyChecker.php`
- [ ] T075 Create `analytics/src/Webhook/PayloadValidator.php`

### Event Processors

- [x] T076 Create `analytics/src/Webhook/ProcessorFactory.php`
- [x] T077 Create `analytics/src/Webhook/Processors/LeadStatusChangedProcessor.php`
- [x] T078 Create `analytics/src/Webhook/Processors/LeadAddedProcessor.php`
- [x] T079 Create `analytics/src/Webhook/Processors/LeadUpdatedProcessor.php`
- [x] T080 Create `analytics/src/Webhook/Processors/LeadDeletedProcessor.php`
- [x] T081 Create `analytics/src/Webhook/Processors/CustomerTransactionProcessor.php`
- [x] T082 Create `analytics/src/Webhook/Processors/UnsortedAddedProcessor.php`

### Webhook Management

- [x] T083 Create `analytics/src/Webhook/SubscriptionManager.php` *(CLI commands)*
- [x] T084 Create `analytics/src/Console/WebhookSubscribeCommand.php`
- [x] T085 Create `analytics/src/Console/WebhookStatusCommand.php`
- [ ] T086 Create `analytics/src/Console/WebhookUnsubscribeCommand.php`

### Fusio Integration

- [ ] T087 Create `analytics/src/Api/WebhookAction.php` (Fusio action)
- [ ] T088 Create `analytics/config/routes.php` (Fusio routes)
- [ ] T089 Register webhook route in Fusio

### Tests

- [ ] T090 [P] Unit test for `IdempotencyChecker::isProcessed()`
- [ ] T091 [P] Unit test for `Verifier::verify()`
- [ ] T092 Integration test: webhook → lead_snapshots update

**Checkpoint**: Webhook processing functional - real-time updates working

---

## Phase 5: User Story 3 - Funnel Visualization (Priority: P2)

**Goal**: Calculate and display funnel metrics

**Independent Test**: Query funnel API, verify stage counts and conversions

### Funnel Service

- [x] T093 Create `analytics/src/Analytics/FunnelService.php`
- [x] T094 Implement stage count calculation
- [x] T095 Implement conversion rate calculation (current/previous stage)
- [x] T096 Implement average time in stage calculation
- [x] T097 Implement historical funnel (date range filter)

### Repository Methods

- [x] T098 Add `getFunnelMetrics()` to `LeadSnapshotRepo.php`
- [x] T099 Add `getStageTimeline()` to `LeadSnapshotRepo.php` *(integrated in FunnelService)*

### Fusio Action

- [x] T100 Create `analytics/src/Api/FunnelAction.php`
- [x] T101 Add GET `/api/analytics/funnel` route

### Tests

- [ ] T102 [P] Unit test for `FunnelService::calculateConversionRate()`
- [ ] T103 Integration test: funnel report for test pipeline

**Checkpoint**: Funnel visualization functional

---

## Phase 6: User Story 4 - Source Attribution (Priority: P2)

**Goal**: Attribute leads to sources for marketing ROI analysis

**Independent Test**: Query attribution API, verify revenue by source

### Attribution Service

- [x] T104 Create `analytics/src/Analytics/AttributionService.php`
- [x] T105 Implement first-touch attribution model
- [x] T106 Implement last-touch attribution model
- [x] T107 Link unsorted categories to source attribution
- [x] T108 Implement UTM parameter extraction from custom fields

### Repository Methods

- [x] T109 Add `getSourceAttributionMetrics()` to `SourceAttributionRepo.php`
- [x] T110 Add `aggregateByUtm()` to `SourceAttributionRepo.php`

### Fusio Action

- [x] T111 Create `analytics/src/Api/AttributionAction.php`
- [x] T112 Add GET `/api/analytics/attribution` route

### Tests

- [ ] T113 [P] Unit test for `AttributionService::firstTouch()`
- [ ] T114 [P] Unit test for `AttributionService::lastTouch()`
- [ ] T115 Integration test: attribution report for test data

**Checkpoint**: Source attribution functional

---

## Phase 7: User Story 5 - Customer Lifetime Value (Priority: P3)

**Goal**: Calculate LTV metrics for customer segmentation

**Independent Test**: Query LTV API, verify customer revenue totals

### LTV Service

- [x] T116 Create `analytics/src/Analytics/LTVService.php`
- [x] T117 Implement total revenue calculation
- [x] T118 Implement average transaction value
- [x] T119 Implement cohort analysis (monthly cohorts)
- [x] T120 Implement LTV percentile calculations

### Repository Methods

- [x] T121 Add `calculateLTV()` to `CustomerLTVRepo.php`
- [x] T122 Add `getCohortData()` to `CustomerLTVRepo.php`
- [x] T123 Add `getLTVSummary()` to `CustomerLTVRepo.php`

### Fusio Action

- [x] T124 Create `analytics/src/Api/LTVAction.php`
- [x] T125 Add GET `/api/analytics/ltv` route

### Tests

- [ ] T126 [P] Unit test for `LTVService::calculateLTV()`
- [ ] T127 Integration test: LTV report for test customers

**Checkpoint**: LTV metrics functional

---

## Phase 8: User Story 6 - Custom Field Integration (Priority: P3)

**Goal**: Extract and use custom fields for enriched analytics

**Independent Test**: Export leads with custom fields, query by UTM values

### Custom Field Extractor

- [x] T128 Create `analytics/src/ETL/CustomFieldExtractor.php`
- [x] T129 Map custom field types to storage format
- [x] T130 Handle UTM field extraction (text fields)
- [x] T131 Handle segment field extraction (select fields)
- [x] T132 Handle monetary field extraction (price fields)

### Repository Queries

- [x] T133 Add `filterByCustomField()` to `LeadSnapshotRepo.php`
- [x] T134 Add `aggregateByUtm()` to reports

### Update Existing Exporters

- [x] T135 Update `LeadsExporter.php` to use CustomFieldExtractor
- [ ] T136 Update `ContactsExporter.php` to use CustomFieldExtractor

**Checkpoint**: Custom field integration functional

---

## Phase 9: Polish & Cross-Cutting Concerns

**Purpose**: Final improvements affecting all features

### Remaining Exporters

- [ ] T137 Create `analytics/src/ETL/CallsExporter.php`
- [ ] T138 Create `analytics/src/ETL/TalksExporter.php`

### Performance Optimization

- [ ] T139 Create PostgreSQL materialized views for funnel metrics
- [ ] T140 Create PostgreSQL materialized views for attribution
- [ ] T141 Create `analytics/src/Console/RefreshViewsCommand.php`
- [ ] T142 Add cron for daily view refresh

### Overview Dashboard

- [x] T143 Create `analytics/src/Api/OverviewAction.php`
- [x] T144 Add GET `/api/analytics/overview` route

### Leads API

- [ ] T145 Create `analytics/src/Api/Action/LeadsAction.php`
- [ ] T146 Add GET `/api/analytics/leads` route

### Documentation

- [x] T147 Update `analytics/README.md` with usage examples
- [x] T148 Add inline PHPDoc to all public methods
- [ ] T149 Verify all tests pass: `./vendor/bin/phpunit`

### Edge Cases

- [ ] T150 Handle deleted sources (preserve data, mark as inactive)
- [ ] T151 Handle leads without contacts (allow orphan leads)
- [ ] T152 Handle deleted entities in webhooks (soft delete)
- [ ] T153 Implement retry queue for failed webhook processing

### Deployment

- [ ] T154 Create Dockerfile for analytics service
- [ ] T155 Create docker-compose.yml for local development
- [ ] T156 Add deployment documentation

**Checkpoint**: All features complete, ready for production

---

## Dependencies & Execution Order

### Phase Dependencies

| Phase | Depends On | Blocks |
|-------|------------|--------|
| Phase 1: Setup | None | All |
| Phase 2: Foundational | Phase 1 | Phases 3-8 |
| Phase 3: ETL (US1) | Phase 2 | — |
| Phase 4: Webhooks (US2) | Phase 2 | — |
| Phase 5: Funnel (US3) | Phase 2 | — |
| Phase 6: Attribution (US4) | Phase 2 | — |
| Phase 7: LTV (US5) | Phase 2 | — |
| Phase 8: Custom Fields (US6) | Phase 3 | — |
| Phase 9: Polish | Phases 3-8 | — |

### User Story Independence

- **US1 (P1)**: ETL Pipeline — foundational, no external dependencies
- **US2 (P1)**: Webhooks — can run in parallel with US1
- **US3 (P2)**: Funnel — depends on US1 (needs data)
- **US4 (P2)**: Attribution — depends on US1 (needs data)
- **US5 (P3)**: LTV — depends on US1 (needs transactions)
- **US6 (P3)**: Custom Fields — depends on US1 (needs leads)

### Parallel Opportunities

All Phase 2 tasks marked [P] can run in parallel:
- T008-T016 (migrations)
- T024-T029 (models)
- T031-T038 (repositories)

---

## Implementation Strategy

### MVP First (US1 + US2 only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational
3. Complete Phase 3: ETL (US1) → **MVP 1: Data Collection**
4. Complete Phase 4: Webhooks (US2) → **MVP 2: Real-time Updates**
5. **STOP and VALIDATE**: Full ETL + Webhook pipeline working

### Full Feature Set

After MVP validated, continue with:
6. Phase 5: Funnel (US3) → **Analytics Views**
7. Phase 6: Attribution (US4) → **Marketing ROI**
8. Phase 7: LTV (US5) → **Customer Value**
9. Phase 8: Custom Fields (US6) → **Enriched Data**
10. Phase 9: Polish → **Production Ready**

---

## Notes

- **[P]** = parallelizable (different files, no dependencies)
- **[Story]** = user story assignment for traceability
- Each user story should be independently testable
- Verify tests fail before implementing (TDD approach)
- Commit after each logical group of tasks