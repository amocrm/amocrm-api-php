# 1. OBJECTIVE

Разработать систему сквозной аналитики (end-to-end analytics) на базе библиотеки amocrm-api-php, которая:
- Собирает данные из amoCRM через API и вебхуки
- Создаёт единый источник правды (SSoT) для аналитики
- Отслеживает полный путь клиента: первое касание → сделка → покупка
- Предоставляет данные для анализа эффективности маркетинговых каналов и воронки продаж

# 2. CONTEXT SUMMARY

## Репозиторий
- PHP-библиотека `amocrm-api-php` для работы с amoCRM API v4
- Расположение: `/workspace/project/amocrm-api-php`
- Composer-based проект с примерами в `/examples`

## Существующие компоненты библиотеки
- **Клиент**: `AmoCRMApiClient` — основной класс для работы с API
- **Сервисы сущностей**: Leads, Events, Unsorted, Transactions, Customers, Webhooks и др.
- **Фильтры**: LeadsFilter, EventsFilter, UnsortedFilter, CustomersFilter и др.
- **Модели**: LeadModel, EventModel, ContactModel, CustomerModel, TransactionModel и др.

## Ключевые сущности для аналитики
| Сущность | Назначение | Источник данных |
|----------|------------|-----------------|
| Unsorted | Первые касания | `apiClient->unsorted()->get()` |
| Leads | Сделки и воронка | `apiClient->leads()->get()` |
| Events | История изменений | `apiClient->events()->get()` |
| Customers | Покупатели | `apiClient->customers()->get()` |
| Transactions | Покупки | `apiClient->transactions()->get()` |
| Webhooks | Real-time уведомления | `apiClient->webhooks()->subscribe()` |

## Архитектурный контекст
- Два режима сбора данных: **Polling** (периодическая выгрузка) и **Real-time** (вебхуки)
- Пагинация через `nextPage()` / `prevPage()` методы
- Инкрементальная выгрузка по `updatedAt` / `createdAt`
- Иерархия исключений для обработки ошибок API

# 3. APPROACH OVERVIEW

## Общая стратегия
Создание модульной системы аналитики с разделением на слои:
1. **Слой сбора данных** — сервисы экспорта (Export Services)
2. **Слой обработки** — вебхук-обработчик (Webhook Handler)
3. **Слой хранения** — модели данных аналитики (Analytics Models)
4. **Слой представления** — аналитические витрины (Analytical Marts)

## Структура каталогов
```
src/AmoCRM/Analytics/
├── Models/                    # Модели для хранения аналитических данных
│   ├── LeadFact.php
│   ├── LeadStatusHistory.php
│   ├── TransactionFact.php
│   ├── FirstTouchFact.php
│   └── CallFact.php
├── Services/                  # Сервисы экспорта данных
│   ├── BaseExportService.php
│   ├── LeadExportService.php
│   ├── EventExportService.php
│   ├── UnsortedExportService.php
│   ├── TransactionExportService.php
│   └── WebhookHandler.php
├── Filters/                   # Фильтры для аналитических выборок
│   └── AnalyticsFilter.php
└── Collections/               # Коллекции моделей аналитики
    └── AnalyticsCollections.php
```

## Обоснование подхода
- **Модульность**: каждый сервис экспорта независим и может использоваться отдельно
- **Расширяемость**: легко добавить новые источники данных или метрики
- **Совместимость**: использует существующие модели и фильтры библиотеки
- **Типобезопасность**: PHPDoc для автокомплита и статического анализа

# 4. IMPLEMENTATION STEPS

## Этап 1: Создание базовой инфраструктуры

### Шаг 1.1: Создать пространство имён Analytics
- **Цель**: Организовать код аналитики в отдельном пространстве имён
- **Метод**: Создать директорию `src/AmoCRM/Analytics/` и базовые файлы
- **Файлы**:
  - `src/AmoCRM/Analytics/AnalyticsServiceInterface.php` — интерфейс для сервисов аналитики
  - `src/AmoCRM/Analytics/BaseAnalyticsModel.php` — базовая модель для фактов

### Шаг 1.2: Создать класс AmoCRMClientFactory
- **Цель**: Упростить инициализацию API-клиента
- **Метод**: Создать фабрику с поддержкой OAuth и Long-Lived токенов
- **Файл**: `src/AmoCRM/Client/AmoCRMClientFactory.php`
- **参考**: Существующий `AmoCRMApiClientFactory` и `LongLivedAccessToken`

---

## Этап 2: Реализация сервисов экспорта данных

### Шаг 2.1: Создать базовый класс экспорта
- **Цель**: Избежать дублирования кода между сервисами экспорта
- **Метод**: Реализовать `BaseExportService` с методами:
  - `fetchAll($filter)` — получение всех страниц данных
  - `fetchSince($timestamp, $entityType)` — инкрементальная выгрузка
  - `getLastSyncTimestamp()` — получение timestamp последней синхронизации
- **Файл**: `src/AmoCRM/Analytics/Services/BaseExportService.php`

### Шаг 2.2: Реализовать LeadExportService
- **Цель**: Выгрузка сделок с связанными сущностями
- **Метод**: 
  - Использовать `LeadsFilter` с фильтром по `updatedAt`
  - Запрашивать связанные данные через `with: contacts, company, source, catalog_elements`
  - Преобразовывать в модель `LeadFact`
- **Файл**: `src/AmoCRM/Analytics/Services/LeadExportService.php`
- **Константы модели**: `LeadModel::WON_STATUS_ID = 142`, `LeadModel::LOST_STATUS_ID = 143`

### Шаг 2.3: Реализовать EventExportService
- **Цель**: Получение истории изменений статусов сделок
- **Метод**:
  - Использовать `EventsFilter` с фильтрами по `entity=leads`, `types=['lead_status_changed']`
  - Извлекать `valueBefore` / `valueAfter` для истории переходов
  - Преобразовывать в модель `LeadStatusHistory`
- **Файл**: `src/AmoCRM/Analytics/Services/EventExportService.php`

### Шаг 2.4: Реализовать UnsortedExportService
- **Цель**: Сбор данных о первых касаниях
- **Метод**:
  - Использовать `UnsortedFilter` с фильтром по `createdAt`
  - Извлекать категорию (`sip|mail|forms|chats`), `sourceName`, `sourceUid`
  - Преобразовывать в модель `FirstTouchFact`
- **Файл**: `src/AmoCRM/Analytics/Services/UnsortedExportService.php`

### Шаг 2.5: Реализовать TransactionExportService
- **Цель**: Выгрузка транзакций покупателей
- **Метод**:
  - Использовать `TransactionsFilter` с фильтром по `completedAt`
  - Учитывать, что требуется предварительная установка `customerId` через `setCustomerId()`
  - Преобразовывать в модель `TransactionFact`
- **Файл**: `src/AmoCRM/Analytics/Services/TransactionExportService.php`

---

## Этап 3: Создание моделей данных аналитики

### Шаг 3.1: Создать LeadFactModel
- **Цель**: Хранение денормализованных данных о сделках
- **Поля**:
  ```php
  'lead_id', 'name', 'pipeline_id', 'status_id', 'price',
  'created_at', 'closed_at', 'updated_at', 'source_id',
  'source_name', 'source_external_id', 'responsible_user_id',
  'contact_id', 'company_id', 'loss_reason_id', 'score',
  'is_deleted', 'is_won', 'is_lost'
  ```
- **Файл**: `src/AmoCRM/Analytics/Models/LeadFactModel.php`

### Шаг 3.2: Создать LeadStatusHistoryModel
- **Цель**: Хранение истории переходов по статусам
- **Поля**:
  ```php
  'id', 'lead_id', 'status_id_from', 'status_id_to',
  'pipeline_id', 'changed_by', 'changed_at', 'duration_seconds'
  ```
- **Файл**: `src/AmoCRM/Analytics/Models/LeadStatusHistoryModel.php`

### Шаг 3.3: Создать TransactionFactModel
- **Цель**: Хранение фактов покупок
- **Поля**:
  ```php
  'transaction_id', 'customer_id', 'price', 'completed_at',
  'created_at', 'external_id', 'receipt_link', 'is_deleted'
  ```
- **Файл**: `src/AmoCRM/Analytics/Models/TransactionFactModel.php`

### Шаг 3.4: Создать FirstTouchFactModel
- **Цель**: Хранение данных о первых касаниях
- **Поля**:
  ```php
  'unsorted_uid', 'category', 'source_name', 'source_uid',
  'pipeline_id', 'created_at', 'lead_id', 'contact_id'
  ```
- **Файл**: `src/AmoCRM/Analytics/Models/FirstTouchFactModel.php`

### Шаг 3.5: Создать CallFactModel
- **Цель**: Хранение данных о звонках (из вебхуков)
- **Поля**:
  ```php
  'call_uniq', 'duration', 'source', 'phone', 'direction',
  'call_status', 'entity_id', 'entity_type', 'responsible_user_id', 'created_at'
  ```
- **Файл**: `src/AmoCRM/Analytics/Models/CallFactModel.php`

---

## Этап 4: Реализация вебхук-обработчика

### Шаг 4.1: Создать WebhookHandler
- **Цель**: Обработка вебхуков в реальном времени
- **Метод**:
  - Создать endpoint для приёма POST-запросов
  - Парсить payload и определять тип события
  - Сохранять изменения в соответствующие модели фактов
- **Файл**: `src/AmoCRM/Analytics/Services/WebhookHandler.php`

### Шаг 4.2: Создать WebhookSubscriptionService
- **Цель**: Управление подписками на вебхуки
- **Метод**:
  - Методы `subscribe()` и `unsubscribe()`
  - Константы для типов событий аналитики
- **Файл**: `src/AmoCRM/Analytics/Services/WebhookSubscriptionService.php`
- **События для подписки**:
  - `add_lead`, `update_lead`, `delete_lead`
  - `add_contact`, `update_contact`, `delete_contact`
  - `add_unsorted`, `update_unsorted`
  - `add_customer`, `update_customer`, `delete_customer`
  - `add_transaction`, `update_transaction`, `delete_transaction`

---

## Этап 5: Создание коллекций и фильтров

### Шаг 5.1: Создать коллекции аналитики
- **Файлы**:
  - `src/AmoCRM/Analytics/Collections/LeadFactsCollection.php`
  - `src/AmoCRM/Analytics/Collections/LeadStatusHistoryCollection.php`
  - `src/AmoCRM/Analytics/Collections/TransactionFactsCollection.php`
  - `src/AmoCRM/Analytics/Collections/FirstTouchFactsCollection.php`
  - `src/AmoCRM/Analytics/Collections/CallFactsCollection.php`

### Шаг 5.2: Создать AnalyticsFilter
- **Цель**: Унифицированный фильтр для аналитических выборок
- **Метод**: Комбинированный фильтр с поддержкой:
  - Диапазонов дат (`created_at`, `updated_at`, `closed_at`)
  - Статусов сделок и воронок
  - Источников и каналов
  - Кастомных полей
- **Файл**: `src/AmoCRM/Analytics/Filters/AnalyticsFilter.php`

---

## Этап 6: Реализация аналитических витрин

### Шаг 6.1: Создать FunnelAnalyticsService
- **Цель**: Аналитика воронки продаж
- **Метрики**:
  - Конверсия из первого касания в сделку
  - Конверсия по этапам воронки
  - Среднее время нахождения на каждом этапе
  - Средний чек по воронкам/источникам
- **Файл**: `src/AmoCRM/Analytics/Services/FunnelAnalyticsService.php`

### Шаг 6.2: Создать SourceAnalyticsService
- **Цель**: Аналитика источников привлечения
- **Метрики**:
  - Количество лидов по источникам
  - Сумма сделок по источникам
  - ROI по источникам
  - Конверсия источника в покупку
- **Файл**: `src/AmoCRM/Analytics/Services/SourceAnalyticsService.php`

### Шаг 6.3: Создать CustomerAnalyticsService
- **Цель**: Аналитика LTV и когорт
- **Метрики**:
  - LTV по когортам
  - Retention rate
  - Среднее количество транзакций на покупателя
  - Средний чек повторной покупки
- **Файл**: `src/AmoCRM/Analytics/Services/CustomerAnalyticsService.php`

---

## Этап 7: Примеры использования

### Шаг 7.1: Создать примеры использования
- **Файлы в `/examples/analytics/`**:
  - `leads_export_example.php` — пример выгрузки сделок
  - `events_export_example.php` — пример выгрузки событий
  - `webhook_handler_example.php` — пример обработчика вебхуков
  - `funnel_analytics_example.php` — пример аналитики воронки
  - `source_analytics_example.php` — пример аналитики источников

---

# 5. TESTING AND VALIDATION

## Модульное тестирование
- **Тесты сервисов экспорта**: Проверка корректности выборки данных
- **Тесты моделей фактов**: Проверка конвертации из API-моделей в аналитические
- **Тесты фильтров**: Проверка корректности построения фильтров

## Интеграционное тестирование
- **Тест полного цикла**: Выгрузка → преобразование → сохранение
- **Тест вебхуков**: Симуляция входящих вебхуков и проверка обработки
- **Тест пагинации**: Проверка корректной обработки больших объёмов данных

## Валидация метрик
- Проверка расчёта конверсии на тестовых данных
- Проверка корректности атрибуции источников
- Проверка расчёта LTV по когортам

## Критерии успеха
- ✅ Все сервисы экспорта корректно получают данные из API
- ✅ Модели фактов правильно преобразуют данные из API-моделей
- ✅ Вебхук-обработчик корректно парсит и сохраняет события
- ✅ Аналитические метрики рассчитываются корректно
- ✅ Пагинация корректно обрабатывает большие объёмы данных
- ✅ Ошибки API корректно обрабатываются (retry, fallback)

---

# 6. IMPLEMENTATION COMPLETED ✅

## Статус реализации (12.06.2026)

### Созданные компоненты:

#### Модели аналитики (5 файлов):
- `src/AmoCRM/Analytics/Models/LeadFactModel.php` - Факты сделок
- `src/AmoCRM/Analytics/Models/LeadStatusHistoryModel.php` - История переходов по статусам
- `src/AmoCRM/Analytics/Models/TransactionFactModel.php` - Факты транзакций
- `src/AmoCRM/Analytics/Models/FirstTouchFactModel.php` - Первые касания
- `src/AmoCRM/Analytics/Models/CallFactModel.php` - Факты звонков

#### Сервисы экспорта (5 файлов):
- `src/AmoCRM/Analytics/Services/BaseExportService.php` - Базовый класс экспорта
- `src/AmoCRM/Analytics/Services/LeadExportService.php` - Экспорт сделок
- `src/AmoCRM/Analytics/Services/EventExportService.php` - Экспорт событий
- `src/AmoCRM/Analytics/Services/UnsortedExportService.php` - Экспорт неразобранного
- `src/AmoCRM/Analytics/Services/TransactionExportService.php` - Экспорт транзакций

#### Вебхук-обработчики (2 файла):
- `src/AmoCRM/Analytics/Services/WebhookHandler.php` - Обработчик вебхуков
- `src/AmoCRM/Analytics/Services/WebhookSubscriptionService.php` - Управление подписками

#### Аналитические витрины (1 файл):
- `src/AmoCRM/Analytics/Services/FunnelAnalyticsService.php` - Аналитика воронки продаж

#### Фильтры (1 файл):
- `src/AmoCRM/Analytics/Filters/AnalyticsFilter.php` - Универсальный аналитический фильтр

#### Базовые классы (2 файла):
- `src/AmoCRM/Analytics/AnalyticsServiceInterface.php` - Интерфейс сервисов
- `src/AmoCRM/Analytics/BaseAnalyticsModel.php` - Базовая модель аналитики

#### Фабрика клиента (1 файл):
- `src/AmoCRM/Client/AmoCRMClientFactory.php` - Фабрика для создания API-клиентов

#### Примеры (3 файла):
- `examples/analytics/leads_export_example.php` - Пример экспорта сделок
- `examples/analytics/webhook_handler_example.php` - Пример обработчика вебхуков
- `examples/analytics/funnel_analytics_example.php` - Пример аналитики воронки

### Статистика:
- Всего файлов: 20
- Моделей: 5
- Сервисов: 8
- Фильтров: 1
- Примеров: 3
- Базовых классов: 2

### Использование:

```php
// Инициализация
use AmoCRM\Client\AmoCRMClientFactory;
use AmoCRM\Analytics\Services\LeadExportService;
use AmoCRM\Analytics\Services\FunnelAnalyticsService;

$apiClient = AmoCRMClientFactory::createWithAccessToken($accessToken, $domain);

// Экспорт сделок
$exportService = new LeadExportService($apiClient);
$leads = $exportService->getLeadsUpdatedSince($timestamp);

// Аналитика воронки
$funnelService = new FunnelAnalyticsService($apiClient);
$stats = $funnelService->calculateFunnelStats($start, $end);
```
