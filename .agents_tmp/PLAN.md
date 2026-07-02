# 1. OBJECTIVE

Реализовать полную схему аналитического хранилища данных (DWH) для выгрузки данных из amoCRM на основе справочника из 48 таблиц:
- **3 вспомогательные таблицы** (справочники: воронки, этапы, статусы покупателей)
- **27 таблиц измерений** (атрибуты сущностей: звонки, неразобранное, компании, контакты, сделки, сегменты, покупатели, транзакции, задачи, элементы, пользователи, SHD-измерения)
- **11 таблиц фактов** (числовые метрики со ссылками на измерения)
- **4 связующие таблицы** (M:N связи: сделки-контакты, покупатели-сегменты и др.)
- **Сервисы ETL** для наполнения таблиц из API amoCRM

# 2. CONTEXT SUMMARY

**Проект:** `amocrm/amocrm-api-library` — официальный PHP-клиент для API amoCRM (OAuth 2.0, API v4).

**Ключевые паттерны кодовой базы:**
- Модели: наследуют `BaseApiModel`, содержат `protected`-свойства, getter/setter, `toArray()`, `toApi()`, `fromArray()`
- Коллекции: наследуют `BaseApiCollection`, реализуют `ArrayAccess`/`IteratorAggregate`, константа `ITEM_CLASS`
- Сервисы: наследуют `BaseEntity`, свойства `$method`, `$collectionClass`, `ITEM_CLASS`, методы `get()`, `addOne()`, `updateOne()`
- Клиент: `AmoCRMApiClient` — точка входа, фабрика сервисов (методы `leads()`, `contacts()`, `companies()`, `customers()`, `calls()`, `tasks()`, `events()`, `unsorted()`, `pipelines()`, `statuses()`, `customersSegments()`, `transactions()`, `products()` и др.)

**Схема DWH (из справочника):**
- Все таблицы содержат поле `account_id` (мультиаккаунтность)
- Фактовые таблицы ссылаются на измерения через внешние ключи `_id`
- События (`*_events`) хранят `value_before` / `value_after` (SCD type 2)
- SHD-таблицы (`general_*`) — общие измерения (трафик, посетители, календарь)
- В `amocrm_segments_facts` опечатка: `created_date` повторяется дважды, второе должно быть `modified_date`

**Зависимости:** PHP >= 7.1, MySQL/MariaDB для DWH-схемы (целевая БД), существующий API-клиент для ETL.

# 3. APPROACH OVERVIEW

**Выбранный подход:** трёхслойная архитектура с новым namespace `AmoCRM\Dwh`:

1. **SQL-слой** — миграции `CREATE TABLE` для всех 48 таблиц. Организованы как сырой SQL в директории `migrations/` с инкрементальными файлами, сгруппированными по типам таблиц.

2. **Модельный слой** (`src/AmoCRM/Dwh/Models/`) — PHP-классы DWH-моделей, наследующие общий `BaseDwhModel`. Каждая модель соответствует одной таблице. Модели используют упрощённый паттерн (только `toArray()` + getter/setter), так как это целевые модели хранилища, а не API-модели.

3. **ETL-слой** (`src/AmoCRM/Dwh/Services/`) — сервисы для каждой группы сущностей, использующие существующий `AmoCRMApiClient` для извлечения данных из API и сохранения их в DWH-таблицы. Каждый ETL-сервис:
   - Принимает `AmoCRMApiClient` (для извлечения) и PDO/DB-адаптер (для сохранения)
   - Реализует метод `sync()` с параметрами фильтрации (период, лимит)
   - Трансформирует API-модели в DWH-модели (mapping API → DWH)
   - Записывает в соответствующие таблицы (основные + attributes + tags + notes + events)

**Почему этот подход:**
- Чёткое разделение: API-клиент не смешивается с DWH-кодом
- Повторяет существующие паттерны библиотеки (Models/Collections/Services)
- ETL-сервисы переиспользуют весь существующий API-клиент без дублирования
- Миграции легко версионировать и накатывать

# 4. IMPLEMENTATION STEPS

## Этап 1: SQL-схема — вспомогательные таблицы и SHD-измерения

**Цель:** Создать миграции для справочников и общих измерений, т.к. они не зависят от API.

**Метод:**
- Создать `migrations/001_auxiliary_tables.sql` — таблицы `amocrm_pipelines`, `amocrm_statuses`, `amocrm_periodicity`
- Создать `migrations/002_shd_tables.sql` — таблицы `general_traffic`, `general_clientids`, `general_dates`
- Все поля — согласно справочнику; `id` — `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`; `account_id` — `INT UNSIGNED NOT NULL`; даты — `DATETIME`; добавлены индексы на FK-поля.

**Ссылки:** справочник таблиц (секции amocrm_pipelines, amocrm_statuses, amocrm_periodicity, general_*)

---

## Этап 2: SQL-схема — таблицы измерений (сущности amoCRM)

**Цель:** Создать миграции для всех 27 таблиц измерений.

**Метод:**
- `migrations/003_dimension_calls.sql` — `amocrm_calls`
- `migrations/004_dimension_unsorted.sql` — `amocrm_unsorted`
- `migrations/005_dimension_companies.sql` — `amocrm_companies`, `amocrm_companies_attributes`, `amocrm_companies_tags`, `amocrm_companies_notes`, `amocrm_companies_events`
- `migrations/006_dimension_contacts.sql` — `amocrm_contacts`, `amocrm_contacts_attributes`, `amocrm_contacts_tags`, `amocrm_contacts_notes`, `amocrm_contacts_events`
- `migrations/007_dimension_leads.sql` — `amocrm_leads`, `amocrm_leads_attributes`, `amocrm_leads_tags`, `amocrm_leads_notes`, `amocrm_leads_events`
- `migrations/008_dimension_segments.sql` — `amocrm_segments`, `amocrm_segments_attributes`
- `migrations/009_dimension_customers.sql` — `amocrm_customers`, `amocrm_customers_attributes`, `amocrm_customers_tags`, `amocrm_customers_notes`
- `migrations/010_dimension_transactions.sql` — `amocrm_transactions`
- `migrations/011_dimension_tasks.sql` — `amocrm_tasks`, `amocrm_tasks_events`
- `migrations/012_dimension_elements.sql` — `amocrm_elements`, `amocrm_elements_attributes`, `amocrm_elements_products`
- `migrations/013_dimension_users.sql` — `amocrm_users`

**Особенности:**
- Для `*_attributes`: поле `value` — `TEXT` (доп. поля могут быть любой длины)
- Для `*_notes`: поле `params` — `JSON` / `TEXT`
- Для `*_events`: поля `value_before`, `value_after` — `TEXT`
- Индексы: `(account_id, <entity>_id)` для всех таблиц

**Ссылки:** справочник, секции amocrm_calls ... amocrm_users

---

## Этап 3: SQL-схема — связующие таблицы и таблицы фактов

**Цель:** Создать миграции для 4 связующих таблиц и 11 таблиц фактов.

**Метод:**
- `migrations/014_linking_tables.sql` — `amocrm_leads_contacts`, `amocrm_customers_segments`
- `migrations/015_fact_tables.sql` — все 11 таблиц фактов:
  - `amocrm_calls_facts`, `amocrm_contacts_facts`, `amocrm_companies_facts`
  - `amocrm_leads_facts`, `amocrm_segments_facts`, `amocrm_customers_facts`
  - `amocrm_transactions_facts`, `amocrm_tasks_facts`
  - `amocrm_transactions_elements_facts`, `amocrm_leads_elements_facts`, `amocrm_customers_elements_facts`

**Особенности:**
- Все FK-поля: `BIGINT UNSIGNED` (ссылаются на id соответствующих таблиц измерений)
- Метрики: `price` — `DECIMAL(15,2)`, `duration` — `INT`, `quantity` — `DECIMAL(15,4)`, `score` — `INT`
- Составной индекс `(account_id, <entity>_id)` + индексы на каждый FK
- Исправить опечатку в `amocrm_segments_facts`: второе поле `created_date` → `modified_date`

**Ссылки:** справочник, секции amocrm_leads_contacts ... amocrm_customers_elements_facts

---

## Этап 4: Базовые классы DWH-моделей

**Цель:** Создать абстрактный `BaseDwhModel`, коллекцию `BaseDwhCollection` и трейты.

**Метод:**
- Создать `src/AmoCRM/Dwh/Models/BaseDwhModel.php`:
  - Аналогичен `BaseApiModel`, но без `toApi()` (DWH-модели не отправляются в API)
  - Содержит `abstract toArray(): array` и `static fromArray(array $data): static`
  - Добавлен трейт `HasAccountId` для общего поля `account_id`
- Создать `src/AmoCRM/Dwh/Collections/BaseDwhCollection.php`:
  - Аналогичен `BaseApiCollection`, но для DWH-моделей
- Создать `src/AmoCRM/Dwh/Enum/TableTypeEnum.php`:
  - Константы: `AUXILIARY`, `DIMENSION`, `FACT`, `LINKING`, `SHD`

**Ссылки:** `src/AmoCRM/Models/BaseApiModel.php`, `src/AmoCRM/Collections/BaseApiCollection.php`

---

## Этап 5: PHP DWH-модели — справочники и SHD

**Цель:** Создать модели для справочников и общих измерений (6 таблиц).

**Метод:** Для каждой таблицы — класс модели в `src/AmoCRM/Dwh/Models/`:
- `PipelineModel.php` → таблица `amocrm_pipelines`
- `StatusModel.php` → таблица `amocrm_statuses` (этапы продаж, не путать с Statuses/StatusModel из Leads)
- `PeriodicityModel.php` → таблица `amocrm_periodicity`
- `TrafficModel.php` → таблица `general_traffic`
- `ClientIdModel.php` → таблица `general_clientids`
- `DateDimensionModel.php` → таблица `general_dates`

Каждая модель содержит:
- `protected` свойства (все поля таблицы)
- Getter/Setter для каждого поля (fluent interface — return `$this`)
- `toArray(): array` — сериализация
- `static fromArray(array $data): static` — десериализация

**Ссылки:** справочник, секции amocrm_pipelines, amocrm_statuses, amocrm_periodicity, general_*

---

## Этап 6: PHP DWH-модели — измерения сущностей amoCRM

**Цель:** Создать модели для всех таблиц измерений (27 таблиц → ~22 класса моделей, т.к. attributes/tags/notes/events — отдельные классы).

**Метод:** Организовать по группам в поддиректориях `src/AmoCRM/Dwh/Models/`:

**Звонки:**
- `Calls/CallDimensionModel.php` → `amocrm_calls`

**Неразобранное:**
- `Unsorted/UnsortedDimensionModel.php` → `amocrm_unsorted`

**Компании:**
- `Companies/CompanyDimensionModel.php` → `amocrm_companies`
- `Companies/CompanyAttributeModel.php` → `amocrm_companies_attributes`
- `Companies/CompanyTagModel.php` → `amocrm_companies_tags`
- `Companies/CompanyNoteModel.php` → `amocrm_companies_notes`
- `Companies/CompanyEventModel.php` → `amocrm_companies_events`

**Контакты:**
- `Contacts/ContactDimensionModel.php` → `amocrm_contacts`
- `Contacts/ContactAttributeModel.php` → `amocrm_contacts_attributes`
- `Contacts/ContactTagModel.php` → `amocrm_contacts_tags`
- `Contacts/ContactNoteModel.php` → `amocrm_contacts_notes`
- `Contacts/ContactEventModel.php` → `amocrm_contacts_events`

**Сделки:**
- `Leads/LeadDimensionModel.php` → `amocrm_leads`
- `Leads/LeadAttributeModel.php` → `amocrm_leads_attributes`
- `Leads/LeadTagModel.php` → `amocrm_leads_tags`
- `Leads/LeadNoteModel.php` → `amocrm_leads_notes`
- `Leads/LeadEventModel.php` → `amocrm_leads_events`

**Сегменты:**
- `Segments/SegmentDimensionModel.php` → `amocrm_segments`
- `Segments/SegmentAttributeModel.php` → `amocrm_segments_attributes`

**Покупатели:**
- `Customers/CustomerDimensionModel.php` → `amocrm_customers`
- `Customers/CustomerAttributeModel.php` → `amocrm_customers_attributes`
- `Customers/CustomerTagModel.php` → `amocrm_customers_tags`
- `Customers/CustomerNoteModel.php` → `amocrm_customers_notes`

**Транзакции:**
- `Transactions/TransactionDimensionModel.php` → `amocrm_transactions`

**Задачи:**
- `Tasks/TaskDimensionModel.php` → `amocrm_tasks`
- `Tasks/TaskEventModel.php` → `amocrm_tasks_events`

**Элементы (каталоги/товары):**
- `Elements/ElementDimensionModel.php` → `amocrm_elements`
- `Elements/ElementAttributeModel.php` → `amocrm_elements_attributes`
- `Elements/ElementProductModel.php` → `amocrm_elements_products`

**Пользователи:**
- `Users/UserDimensionModel.php` → `amocrm_users`

**Ссылки:** справочник, существующие API-модели для mapping'а (`LeadModel`, `ContactModel`, `CompanyModel`, `CustomerModel`, etc.)

---

## Этап 7: PHP DWH-модели — связи и факты

**Цель:** Создать модели для 4 связующих таблиц и 11 таблиц фактов.

**Метод:** Организовать в `src/AmoCRM/Dwh/Models/`:

**Связи:**
- `Links/LeadContactLinkModel.php` → `amocrm_leads_contacts`
- `Links/CustomerSegmentLinkModel.php` → `amocrm_customers_segments`

**Факты:**
- `Facts/CallFactModel.php` → `amocrm_calls_facts`
- `Facts/ContactFactModel.php` → `amocrm_contacts_facts`
- `Facts/CompanyFactModel.php` → `amocrm_companies_facts`
- `Facts/LeadFactModel.php` → `amocrm_leads_facts`
- `Facts/SegmentFactModel.php` → `amocrm_segments_facts`
- `Facts/CustomerFactModel.php` → `amocrm_customers_facts`
- `Facts/TransactionFactModel.php` → `amocrm_transactions_facts`
- `Facts/TaskFactModel.php` → `amocrm_tasks_facts`
- `Facts/TransactionElementFactModel.php` → `amocrm_transactions_elements_facts`
- `Facts/LeadElementFactModel.php` → `amocrm_leads_elements_facts`
- `Facts/CustomerElementFactModel.php` → `amocrm_customers_elements_facts`

**Особенности:**
- Фактовые модели содержат числовые поля (price, quantity, duration, score, ltv и т.д.) и FK-поля на измерения
- Для `SegmentFactModel` учесть исправленную опечатку: `modified_date` вместо дублирующегося `created_date`

**Ссылки:** справочник, секции amocrm_leads_contacts ... amocrm_customers_elements_facts

---

## Этап 8: DWH-коллекции

**Цель:** Создать коллекции DWH-моделей для пакетных операций.

**Метод:** Для каждой группы моделей — коллекция в `src/AmoCRM/Dwh/Collections/`:
- `PipelineCollection.php` (ITEM_CLASS = PipelineModel::class)
- `StatusCollection.php`
- `PeriodicityCollection.php`
- `CallDimensionCollection.php`
- `CompanyDimensionCollection.php`
- `ContactDimensionCollection.php`
- `LeadDimensionCollection.php`
- `CustomerDimensionCollection.php`
- `TransactionDimensionCollection.php`
- `TaskDimensionCollection.php`
- `ElementDimensionCollection.php`
- `UserDimensionCollection.php`
- Коллекции фактов: `LeadFactCollection.php`, `CallFactCollection.php`, ...

Каждая коллекция наследует `BaseDwhCollection` и переопределяет `ITEM_CLASS`.

**Ссылки:** `BaseDwhCollection`

---

## Этап 9: Database adapter (PDO wrapper)

**Цель:** Создать лёгкий адаптер БД для ETL-сервисов.

**Метод:** Создать `src/AmoCRM/Dwh/Database/DwhDbAdapter.php`:
- Конструктор принимает `PDO` или DSN + credentials
- Методы `insert(string $table, array $data): int` (возвращает lastInsertId)
- `batchInsert(string $table, array $rows): array` (возвращает массив ID)
- `upsert(string $table, array $data, array $uniqueKeys): int`
- `delete(string $table, array $conditions): int`
- `beginTransaction()`, `commit()`, `rollback()`

**Почему не ORM:** Минимизация зависимостей, библиотека уже использует лёгкий подход.

**Ссылки:** PDO

---

## Этап 10: Базовый ETL-сервис

**Цель:** Создать абстрактный класс для всех ETL-сервисов.

**Метод:** Создать `src/AmoCRM/Dwh/Services/BaseEtlService.php`:
- Принимает `AmoCRMApiClient` и `DwhDbAdapter` в конструкторе
- Абстрактный метод `sync(array $options = []): array` — возвращает статистику (сколько записей обработано)
- Общие методы:
  - `transformApiToDwh()` — шаблонный метод для маппинга API-модели → DWH-модель
  - `extractCustomFields()` — извлечение доп. полей в attributes-таблицы
  - `extractTags()` — извлечение тегов в tags-таблицы
  - `extractNotes()` — извлечение примечаний c API (через `EntityNotes`)
  - `extractEvents()` — извлечение событий (через `Events` сервис)

**Ссылки:** `AmoCRMApiClient`, `BaseEntity`, `DwhDbAdapter`

---

## Этап 11: ETL-сервисы — справочники (pipelines, statuses, periodicity)

**Цель:** Реализовать ETL-синхронизацию вспомогательных таблиц.

**Метод:** Создать:
- `src/AmoCRM/Dwh/Services/PipelineEtlService.php`:
  - `sync()`: вызывает `$apiClient->pipelines()->get()`, маппит `PipelineModel` → `PipelineDimensionModel`
- `src/AmoCRM/Dwh/Services/StatusEtlService.php`:
  - `sync()`: для каждой воронки вызывает `$apiClient->statuses($pipelineId)->get()`, маппит `StatusModel` → `StatusDimensionModel`
- `src/AmoCRM/Dwh/Services/PeriodicityEtlService.php`:
  - `sync()`: вызывает `$apiClient->customersStatuses()->get()`, маппит `StatusModel` → `PeriodicityDimensionModel`

Каждый сервис: очищает таблицу → вставляет свежие данные (справочники — full refresh).

**Ссылки:** API-сервисы `Pipelines`, `Statuses`, `CustomersStatuses`

---

## Этап 12: ETL-сервисы — основные сущности (companies, contacts, leads)

**Цель:** Реализовать ETL-синхронизацию компаний, контактов и сделок.

**Метод:** Создать:
- `src/AmoCRM/Dwh/Services/CompanyEtlService.php`:
  - `sync()`: вызывает `$apiClient->companies()->get($filter)`
  - Для каждой компании: вставляет в `amocrm_companies`, `amocrm_companies_attributes`, `amocrm_companies_tags`
  - Опционально: подтягивает notes через `$apiClient->notes('companies')`, events через `$apiClient->events()`
- `src/AmoCRM/Dwh/Services/ContactEtlService.php`:
  - Аналогично для контактов → `amocrm_contacts`, `*_attributes`, `*_tags`, `*_notes`, `*_events`
- `src/AmoCRM/Dwh/Services/LeadEtlService.php`:
  - Аналогично для сделок + заполняет `amocrm_leads_contacts` (связи)
  - Для связей: использует `$apiClient->leads()->get($filter, ['contacts'])`

**Параметры `sync()`:** `$filter` (период, лимит), `$withNotes` (bool), `$withEvents` (bool).

**Ссылки:** API-сервисы `Companies`, `Contacts`, `Leads`, `EntityNotes`, `Events`

---

## Этап 13: ETL-сервисы — остальные сущности (customers, segments, calls, tasks, transactions, elements, users)

**Цель:** Реализовать ETL-синхронизацию оставшихся сущностей.

**Метод:** Создать:
- `src/AmoCRM/Dwh/Services/CustomerEtlService.php` — покупатели + segments + attributes + tags + notes
- `src/AmoCRM/Dwh/Services/SegmentEtlService.php` — сегменты + attributes
- `src/AmoCRM/Dwh/Services/CallEtlService.php` — звонки
- `src/AmoCRM/Dwh/Services/TaskEtlService.php` — задачи + events
- `src/AmoCRM/Dwh/Services/TransactionEtlService.php` — транзакции
- `src/AmoCRM/Dwh/Services/ElementEtlService.php` — элементы каталогов + attributes + products
- `src/AmoCRM/Dwh/Services/UserEtlService.php` — пользователи
- `src/AmoCRM/Dwh/Services/UnsortedEtlService.php` — неразобранное

**Ссылки:** API-сервисы `Customers`, `Segments`, `Calls`, `Tasks`, `Transactions`, `CatalogElements`, `Users`, `Unsorted`

---

## Этап 14: ETL-сервисы — фактовые таблицы

**Цель:** Реализовать ETL-сервисы для наполнения таблиц фактов на основе измерений.

**Метод:** Создать в `src/AmoCRM/Dwh/Services/Facts/`:
- `CallFactEtlService.php` — джойнит `amocrm_calls` с API звонков для получения duration, связывает с датами/пользователями/сущностями
- `ContactFactEtlService.php` — агрегирует контакты по дате регистрации
- `CompanyFactEtlService.php` — агрегирует компании по дате регистрации
- `LeadFactEtlService.php` — связывает сделки с clientids, traffic, датами открытия/закрытия; вычисляет price, labor_cost, score
- `SegmentFactEtlService.php` — вычисляет conversion_rate, max_discount, customers_count
- `CustomerFactEtlService.php` — вычисляет purchases, average_check, next_price, ltv, labor_cost
- `TransactionFactEtlService.php` — связывает транзакции с покупателями/датами/сущностями
- `TaskFactEtlService.php` — вычисляет duration задач; связывает с датами создания/завершения
- `TransactionElementFactEtlService.php` — элементы транзакций (quantity)
- `LeadElementFactEtlService.php` — элементы сделок (quantity, price)
- `CustomerElementFactEtlService.php` — элементы покупателей (quantity, price)

**Логика:** Фактовые ETL работают поверх уже заполненных таблиц измерений (читают из DWH + при необходимости дозапрашивают API).

**Ссылки:** справочник таблиц фактов, заполненные таблицы измерений

---

## Этап 15: Оркестратор полной выгрузки

**Цель:** Создать главный сервис, координирующий полный цикл ETL.

**Метод:** Создать `src/AmoCRM/Dwh/DwhSyncOrchestrator.php`:
- Принимает `AmoCRMApiClient`, `DwhDbAdapter`
- Метод `fullSync(int $accountId, array $options = []): array`:
  1. Синхронизирует справочники (pipelines, statuses, periodicity)
  2. Синхронизирует пользователей (нужны для всех остальных таблиц)
  3. Синхронизирует SHD-таблицы (dates, traffic, clientids — могут наполняться отдельно)
  4. Последовательно синхронизирует сущности: companies → contacts → leads → customers → segments → calls → tasks → transactions → elements → unsorted
  5. Заполняет связующие таблицы
  6. Заполняет фактовые таблицы
  7. Возвращает отчёт: `['pipelines' => N, 'leads' => N, ...]`
- Метод `incrementalSync(int $accountId, DateTime $since): array` — инкрементальная синхронизация (только изменённые записи с `$since`)

**Ссылки:** все ETL-сервисы из этапов 11–14

---

## Этап 16: Пример использования и документация

**Цель:** Создать пример полного цикла выгрузки.

**Метод:** Создать `examples/dwh_full_sync.php`:
- Инициализация `AmoCRMApiClient` с OAuth
- Создание `DwhDbAdapter` (PDO к MySQL)
- Запуск `DwhSyncOrchestrator::fullSync()`
- Вывод отчёта

Обновить `README.md`: добавить раздел «Аналитические таблицы (DWH)» с описанием архитектуры и примером использования.

**Ссылки:** `examples/`, `README.md`

# 5. TESTING AND VALIDATION

**Валидация SQL-схемы:**
- Прогнать все миграции на чистой MySQL/MariaDB БД — убедиться, что нет синтаксических ошибок
- Проверить целостность: все FK-ссылки указывают на существующие таблицы
- Проверить индексы: `EXPLAIN` на типовых JOIN-запросах фактов с измерениями

**Валидация моделей:**
- Unit-тесты для каждого DWH-модельного класса: `testToArray()` проверяет, что все поля сериализуются; `testFromArray()` проверяет обратную десериализацию
- Тесты для коллекций: `testAdd()`, `testFromArray()`, `testToArray()`

**Валидация ETL:**
- Интеграционные тесты с тестовым аккаунтом amoCRM:
  - Создать тестовые данные через API (сделку, контакт, компанию)
  - Запустить `fullSync()`
  - Проверить, что данные корректно попали во все соответствующие таблицы (основные + attributes + tags + notes + events + facts)
- Проверить инкрементальную синхронизацию: изменить сущность → `incrementalSync()` → только изменённые записи обновлены
- Проверить идемпотентность: повторный `fullSync()` не дублирует данные

**Критерии успеха:**
- Все 48 таблиц созданы, структура соответствует справочнику
- `fullSync()` на тестовом аккаунте (10+ сущностей каждого типа) завершается без ошибок
- Данные в фактовых таблицах корректно ссылаются на измерения (JOIN дают осмысленный результат)
- Опечатка в `amocrm_segments_facts` исправлена
