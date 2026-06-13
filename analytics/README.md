# AmoCRM Cross-Channel Analytics

Сервис сквозной аналитики для amoCRM с интеграцией Fusio.

## Возможности

- 📊 ETL-пайплайн для выгрузки данных из amoCRM (leads, events, contacts, customers)
- 🔄 Webhook-обработка в реальном времени с idempotency
- 📈 Аналитика воронки продаж (конверсия по этапам, время в стадии)
- 🎯 Атрибуция по источникам (first/last touch, UTM параметры)
- 💰 Расчёт LTV покупателей с когортным анализом
- 🏷️ Интеграция с кастомными полями (UTM, сегменты)
- 📊 Dashboard overview с ключевыми метриками

## Требования

- PHP 8.1+
- PostgreSQL 14+
- Composer
- Fusio 4.0+
- amoCRM API ключи

## Установка

```bash
cd analytics
composer install
cp .env.example .env
# Настройте .env с вашими credentials
php bin/analytics migrate
```

## Конфигурация

Отредактируйте `.env` файл:

```env
# amoCRM OAuth
AMOCRM_CLIENT_ID=your_client_id
AMOCRM_CLIENT_SECRET=your_client_secret
AMOCRM_BASE_DOMAIN=youraccount.amocrm.ru

# Database
DB_HOST=localhost
DB_NAME=amocrm_analytics
DB_USER=postgres
DB_PASSWORD=secret

# Token Storage
TOKEN_STORAGE_PATH=./tokens

# Logging
LOGGING_PATH=./logs
LOGGING_LEVEL=info
```

## CLI Команды

```bash
# Синхронизация пиплайнов
php bin/analytics sync:pipelines

# Полная выгрузка (начальная загрузка)
php bin/analytics etl:full

# Инкрементальная выгрузка (изменения за последний час)
php bin/analytics etl:incremental

# Инкрементальная выгрузка с указанием даты
php bin/analytics etl:incremental --since="2024-01-01 00:00:00"

# Выгрузка сделок
php bin/analytics etl:leads

# Выгрузка событий
php bin/analytics etl:events

# Статус webhook-подписок
php bin/analytics webhook:status

# Подписка на webhook
php bin/analytics webhook:subscribe
```

## Webhook Endpoint

```
POST /webhook/amocrm
```

Принимает callbacks от amoCRM и обновляет аналитику в реальном времени.

### Обрабатываемые события

| Событие | Обработчик |
|---------|------------|
| `lead_added` | LeadAddedProcessor |
| `lead_status_changed` | LeadStatusChangedProcessor |
| `lead_updated` | LeadUpdatedProcessor |
| `lead_deleted` | LeadDeletedProcessor |
| `customer_transaction_added` | CustomerTransactionProcessor |
| `unsorted_added` | UnsortedAddedProcessor |

## API Endpoints

| Endpoint | Метод | Описание |
|----------|-------|---------|
| `/webhook/amocrm` | POST | Webhook handler |
| `/api/analytics/overview` | GET | Dashboard overview |
| `/api/analytics/funnel` | GET | Воронка продаж |
| `/api/analytics/attribution` | GET | Атрибуция по источникам |
| `/api/analytics/ltv` | GET | LTV покупателей |

### Параметры запросов

**Funnel API**:
```
GET /api/analytics/funnel?pipeline_id=1&date_from=2024-01-01&date_to=2024-06-30
```

**Attribution API**:
```
GET /api/analytics/attribution?type=first_touch&date_from=2024-01-01
```

**LTV API**:
```
GET /api/analytics/ltv?segment=active&sort_by=ltv&limit=50
```

## Архитектура

```
analytics/
├── src/
│   ├── Analytics/           # Business logic
│   │   ├── FunnelService.php       # Воронка продаж
│   │   ├── AttributionService.php  # Атрибуция
│   │   └── LTVService.php          # LTV расчёт
│   ├── Api/                 # Fusio actions
│   │   ├── WebhookAction.php
│   │   ├── FunnelAction.php
│   │   ├── AttributionAction.php
│   │   └── LTVAction.php
│   ├── Client/              # AmoCRM клиент
│   │   ├── AmoCRMFactory.php
│   │   └── FileTokenStorage.php
│   ├── Config/              # Конфигурация
│   ├── Console/             # CLI команды
│   │   ├── EtlFullCommand.php
│   │   ├── EtlIncrementalCommand.php
│   │   └── ...
│   ├── ETL/                 # Exporters
│   │   ├── BaseExporter.php        # Базовый класс с pagination
│   │   ├── LeadsExporter.php
│   │   ├── EventsExporter.php
│   │   └── CustomFieldExtractor.php
│   ├── Exception/           # Исключения
│   ├── Logger/              # Логирование
│   ├── Models/              # Доменные модели
│   │   ├── LeadSnapshot.php
│   │   ├── Transaction.php
│   │   ├── SourceAttribution.php
│   │   └── CustomerLTV.php
│   ├── Repository/          # Доступ к БД
│   │   ├── LeadSnapshotRepo.php
│   │   ├── TransactionRepo.php
│   │   └── ...
│   └── Webhook/             # Webhook обработчики
│       ├── Handler.php             # Основной обработчик
│       ├── Verifier.php            # HMAC верификация
│       ├── IdempotencyChecker.php  # Дедупликация
│       └── Processors/
├── migrations/              # Миграции БД
└── tests/                   # Тесты
```

## База данных

### Таблицы

| Таблица | Описание |
|---------|----------|
| `lead_snapshots` | Снэпшоты сделок в каждом состоянии |
| `transactions` | Транзакции клиентов |
| `webhook_logs` | Лог обработки webhook (idempotency) |
| `analytics_events` | События из amoCRM |
| `source_attribution` | Атрибуция лидов по источникам |
| `customer_ltv` | Предрассчитанные LTV метрики |
| `contacts_cache` | Кэш контактов |
| `pipeline_stages` | Определения этапов пиплайнов |

## Разработка

```bash
# Установить зависимости
composer install

# Запустить тесты
./vendor/bin/phpunit

# Проверить стиль кода
composer style:check

# Исправить стиль
composer style:fix
```

## Безопасность

### Webhook верификация

Все webhook подписи верифицируются через HMAC-SHA256:
- Подпись из заголовка `X-Signature`
- Timing-safe сравнение предотвращает timing attacks
- Защита от replay через deduplication по webhook ID

### Rate Limiting

ETL пайплайн обрабатывает rate limits amoCRM API:
- Экспоненциальный backoff при 429 ответах
- Автоматический retry с настраиваемым числом попыток
- Задержка ограничена 60 секундами

## Производительность

- **Batch inserts**: `insertBatch()` использует один INSERT для множества записей
- **Индексация**: Все foreign keys и query columns проиндексированы
- **Idempotency**: Дубликаты webhook обнаруживаются и пропускаются

## Лицензия

MIT
