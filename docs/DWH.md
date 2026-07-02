# Аналитическое хранилище данных (DWH) для amoCRM

## Содержание

- [Обзор](#обзор)
- [Архитектура](#архитектура)
- [Структура таблиц](#структура-таблиц)
- [Установка и настройка](#установка-и-настройка)
- [Миграции](#миграции)
- [Быстрый старт](#быстрый-старт)
- [ETL-сервисы](#etl-сервисы)
- [Фактовые таблицы](#фактовые-таблицы)
- [Оркестратор синхронизации](#оркестратор-синхронизации)
- [Модели и коллекции](#модели-и-коллекции)
- [Обработка ошибок](#обработка-ошибок)
- [Расширение модуля](#расширение-модуля)

---

## Обзор

Модуль `AmoCRM\Dwh` реализует полную схему аналитического хранилища данных для выгрузки информации из amoCRM. Модуль позволяет:

- **Выгружать** все сущности amoCRM (сделки, контакты, компании, покупатели, звонки, задачи и др.) в реляционную БД
- **Сохранять историю изменений** (SCD Type 2) через таблицы событий (`*_events`)
- **Агрегировать метрики** в таблицах фактов для построения отчётов и дашбордов
- **Работать в мультиаккаунтном режиме** — все таблицы содержат поле `account_id`
- **Выполнять инкрементальную синхронизацию** — только изменённые с определённой даты записи

### Ключевые возможности

| Возможность | Описание |
|---|---|
| Полная выгрузка | `fullSync()` — все сущности + атрибуты + теги + примечания + события |
| Инкрементальная выгрузка | `incrementalSync()` — только изменённые записи |
| Атрибуты (custom fields) | Автоматическое извлечение в `*_attributes` таблицы |
| Теги | Автоматическое извлечение в `*_tags` таблицы |
| Примечания | Извлечение через `EntityNotes` API |
| События (events) | SCD Type 2: `value_before` / `value_after` |
| Пагинация | Автоматический обход всех страниц API |

---

## Архитектура

Модуль построен по трёхслойной архитектуре в namespace `AmoCRM\Dwh`:

```
src/AmoCRM/Dwh/
├── Collections/        # Коллекции DWH-моделей (18 классов)
│   └── BaseDwhCollection.php
├── DwhDbAdapter.php    # Адаптер БД на базе PDO
├── DwhSyncOrchestrator.php  # Оркестратор полного цикла синхронизации
├── Enum/
│   └── TableTypeEnum.php    # Типы таблиц (auxiliary, dimension, fact, linking, shd)
├── Models/             # DWH-модели (52 класса)
│   └── BaseDwhModel.php
└── Services/           # ETL-сервисы (26 классов)
    ├── BaseEtlService.php
    ├── Facts/          # Фактовые ETL-сервисы (11 классов)
    └── ...
```

### Слои

1. **SQL-слой** (`migrations/`) — 15 файлов миграций `CREATE TABLE` для всех 48+ таблиц
2. **Модельный слой** (`Models/`) — PHP-классы, каждый соответствует одной таблице DWH. Наследуют `BaseDwhModel`, реализуют `toArray()`, `fromArray()`, `getTableName()`
3. **ETL-слой** (`Services/`) — сервисы извлечения данных из API amoCRM и сохранения в DWH

### Поток данных

```
amoCRM API (v4)
    │
    ▼
AmoCRMApiClient (существующий клиент)
    │
    ▼
EtlService.sync()  ──►  DwhDbAdapter.upsert()  ──►  MySQL/MariaDB
    │
    ├── extractAttributes()  →  *_attributes
    ├── extractTags()        →  *_tags
    └── extractNotes()       →  *_notes (опционально)
```

---

## Структура таблиц

### Вспомогательные таблицы (3)

| Таблица | Назначение | Источник API |
|---|---|---|
| `amocrm_pipelines` | Воронки продаж | `pipelines()->get()` |
| `amocrm_statuses` | Этапы воронок | `statuses($pipelineId)->get()` |
| `amocrm_periodicity` | Статусы покупателей (периодичность) | `customersStatuses()->get()` |

### Общие измерения / SHD (3)

| Таблица | Назначение |
|---|---|
| `general_dates` | Календарь (дата, год, квартал, месяц, неделя, день, выходной) |
| `general_traffic` | Источники трафика (utm-метки) |
| `general_clientids` | Идентификаторы посетителей |

### Таблицы измерений — сущности amoCRM (27)

| Группа | Основная таблица | Дочерние таблицы |
|---|---|---|
| **Звонки** | `amocrm_calls` | — |
| **Неразобранное** | `amocrm_unsorted` | — |
| **Компании** | `amocrm_companies` | `_attributes`, `_tags`, `_notes`, `_events` |
| **Контакты** | `amocrm_contacts` | `_attributes`, `_tags`, `_notes`, `_events` |
| **Сделки** | `amocrm_leads` | `_attributes`, `_tags`, `_notes`, `_events` |
| **Сегменты** | `amocrm_segments` | `_attributes` |
| **Покупатели** | `amocrm_customers` | `_attributes`, `_tags`, `_notes` |
| **Транзакции** | `amocrm_transactions` | — |
| **Задачи** | `amocrm_tasks` | `_events` |
| **Элементы каталогов** | `amocrm_elements` | `_attributes`, `_products` |
| **Пользователи** | `amocrm_users` | — |

### Связующие таблицы (4)

| Таблица | Связь |
|---|---|
| `amocrm_leads_contacts` | Сделка ↔ Контакт (M:N) |
| `amocrm_customers_segments` | Покупатель ↔ Сегмент (M:N) |
| `amocrm_customers_members` | Покупатель ↔ Контакт/Компания |
| `amocrm_companies_contacts` | Компания ↔ Контакт (M:N) |

### Таблицы фактов (11)

| Таблица | Метрики |
|---|---|
| `amocrm_calls_facts` | `duration`, статус звонка |
| `amocrm_contacts_facts` | `score` |
| `amocrm_companies_facts` | `score` |
| `amocrm_leads_facts` | `price`, `labor_cost`, `score`, clientId, trafficId |
| `amocrm_segments_facts` | `customers_count`, `conversion_rate`, `max_discount` |
| `amocrm_customers_facts` | `purchases`, `average_check`, `next_price`, `ltv`, `labor_cost` |
| `amocrm_transactions_facts` | `price` |
| `amocrm_tasks_facts` | `duration`, `is_completed` |
| `amocrm_transactions_elements_facts` | `quantity`, `price` |
| `amocrm_leads_elements_facts` | `quantity`, `price` |
| `amocrm_customers_elements_facts` | `quantity`, `price` |

---

## Установка и настройка

### Требования

- PHP >= 7.1
- MySQL 5.7+ / MariaDB 10.2+
- Установленная библиотека `amocrm/amocrm-api-library`
- Интеграция amoCRM с Client ID, Client Secret и Redirect URI

### Подготовка базы данных

```bash
# Создать базу данных
mysql -u root -p -e "CREATE DATABASE amocrm_dwh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## Миграции

Все миграции находятся в директории `migrations/` и пронумерованы по порядку:

| Файл | Содержание |
|---|---|
| `001_auxiliary_tables.sql` | `amocrm_pipelines`, `amocrm_statuses`, `amocrm_periodicity` |
| `002_shd_tables.sql` | `general_dates`, `general_traffic`, `general_clientids` |
| `003_dimension_calls.sql` | `amocrm_calls` |
| `004_dimension_unsorted.sql` | `amocrm_unsorted` |
| `005_dimension_companies.sql` | `amocrm_companies` + attributes, tags, notes, events |
| `006_dimension_contacts.sql` | `amocrm_contacts` + attributes, tags, notes, events |
| `007_dimension_leads.sql` | `amocrm_leads` + attributes, tags, notes, events |
| `008_dimension_segments.sql` | `amocrm_segments` + attributes |
| `009_dimension_customers.sql` | `amocrm_customers` + attributes, tags, notes |
| `010_dimension_transactions.sql` | `amocrm_transactions` |
| `011_dimension_tasks.sql` | `amocrm_tasks` + events |
| `012_dimension_elements.sql` | `amocrm_elements` + attributes, products |
| `013_dimension_users.sql` | `amocrm_users` |
| `014_linking_tables.sql` | Связующие таблицы (4 шт.) |
| `015_fact_tables.sql` | Фактовые таблицы (11 шт.) |

### Применение миграций

**Способ 1: пакетное применение**
```bash
for f in migrations/*.sql; do
    mysql -u root -p amocrm_dwh < "$f"
done
```

**Способ 2: выборочное применение**
```bash
mysql -u root -p amocrm_dwh < migrations/001_auxiliary_tables.sql
mysql -u root -p amocrm_dwh < migrations/002_shd_tables.sql
# ... и так далее
```

**Способ 3: через DwhDbAdapter (программно)**
```php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=amocrm_dwh', 'user', 'pass');
$migration = file_get_contents('migrations/001_auxiliary_tables.sql');
$pdo->exec($migration);
```

---

## Быстрый старт

### 1. Инициализация API-клиента

```php
use AmoCRM\Client\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;

$apiClient = new AmoCRMApiClient(
    $_ENV['CLIENT_ID'],
    $_ENV['CLIENT_SECRET'],
    $_ENV['CLIENT_REDIRECT_URI']
);

// Установка токена и домена
$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain('example.amocrm.ru')
    ->onAccessTokenRefresh(function (AccessToken $token, string $domain) {
        // Сохранить новый токен в БД/файл
        saveToken($token, $domain);
    });
```

### 2. Подключение к базе данных

```php
use AmoCRM\Dwh\DwhDbAdapter;

$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=amocrm_dwh;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$db = new DwhDbAdapter($pdo);
```

### 3. Полная синхронизация

```php
use AmoCRM\Dwh\DwhSyncOrchestrator;

$orchestrator = new DwhSyncOrchestrator($apiClient, $db, $accountId = 1);
$report = $orchestrator->fullSync(['limit' => 250]);

print_r($report);
// Array (
//     [pipelines] => 3
//     [statuses] => 12
//     [users] => 5
//     [companies] => 42
//     [contacts] => 156
//     [leads] => 89
//     ...
// )
```

### 4. Инкрементальная синхронизация

```php
$since = new DateTime('2024-01-01 00:00:00');
$report = $orchestrator->incrementalSync($since);
```

### 5. Синхронизация только фактовых таблиц

```php
$report = [];
$orchestrator->syncFacts($report);
```

---

## ETL-сервисы

Каждый ETL-сервис отвечает за синхронизацию одной группы сущностей.

### Сервисы справочников

| Сервис | API-метод | Таблица | Режим |
|---|---|---|---|
| `PipelineEtlService` | `pipelines()->get()` | `amocrm_pipelines` | Full refresh |
| `StatusEtlService` | `statuses($id)->get()` | `amocrm_statuses` | Full refresh |
| `PeriodicityEtlService` | `customersStatuses()->get()` | `amocrm_periodicity` | Full refresh |

Справочники синхронизируются в режиме **full refresh**: таблица очищается и заполняется заново.

### Сервисы сущностей

| Сервис | API-метод | Таблицы |
|---|---|---|
| `CompanyEtlService` | `companies()->get()` | `amocrm_companies` + `_attributes` + `_tags` + `_notes` |
| `ContactEtlService` | `contacts()->get()` | `amocrm_contacts` + `_attributes` + `_tags` + `_notes` |
| `LeadEtlService` | `leads()->get()` | `amocrm_leads` + `_attributes` + `_tags` + `_notes` |
| `CustomerEtlService` | `customers()->get()` | `amocrm_customers` + `_attributes` + `_tags` + `_notes` |
| `SegmentEtlService` | `customersSegments()->get()` | `amocrm_segments` + `_attributes` |
| `CallEtlService` | `calls()->get()` | `amocrm_calls` |
| `TaskEtlService` | `tasks()->get()` | `amocrm_tasks` |
| `TransactionEtlService` | `transactions()->get()` | `amocrm_transactions` |
| `ElementEtlService` | `catalogElements()->get()` | `amocrm_elements` + `_attributes` |
| `UserEtlService` | `users()->get()` | `amocrm_users` |
| `UnsortedEtlService` | `unsorted()->get()` | `amocrm_unsorted` |

### Использование отдельного ETL-сервиса

```php
use AmoCRM\Dwh\Services\LeadEtlService;

$leadEtl = new LeadEtlService($apiClient, $db, $accountId);
$stats = $leadEtl->sync(['limit' => 100]);

echo "Обработано сделок: {$stats['processed']}\n";
echo "Атрибутов: {$stats['attributes']}\n";
echo "Тегов: {$stats['tags']}\n";
```

### Принцип работы ETL-сервиса сущности

1. Вызывает API-метод с фильтром и пагинацией
2. Для каждой записи создаёт DWH-модель и сохраняет через `upsert`
3. Извлекает custom fields → сохраняет в `*_attributes`
4. Извлекает теги → сохраняет в `*_tags`
5. Обходит все страницы через `nextPage()`

---

## Фактовые таблицы

Фактовые ETL-сервисы работают **поверх уже заполненных таблиц измерений**: они читают данные из DWH и трансформируют их в фактовые записи.

### Список фактовых сервисов

| Сервис | Читает из | Пишет в |
|---|---|---|
| `CallFactEtlService` | `amocrm_calls` | `amocrm_calls_facts` |
| `ContactFactEtlService` | `amocrm_contacts` | `amocrm_contacts_facts` |
| `CompanyFactEtlService` | `amocrm_companies` | `amocrm_companies_facts` |
| `LeadFactEtlService` | `amocrm_leads` | `amocrm_leads_facts` |
| `SegmentFactEtlService` | `amocrm_segments` | `amocrm_segments_facts` |
| `CustomerFactEtlService` | `amocrm_customers` | `amocrm_customers_facts` |
| `TransactionFactEtlService` | `amocrm_transactions` | `amocrm_transactions_facts` |
| `TaskFactEtlService` | `amocrm_tasks` | `amocrm_tasks_facts` |
| `TransactionElementFactEtlService` | `amocrm_transactions` | `amocrm_transactions_elements_facts` |
| `LeadElementFactEtlService` | `amocrm_leads` | `amocrm_leads_elements_facts` |
| `CustomerElementFactEtlService` | `amocrm_customers` | `amocrm_customers_elements_facts` |

---

## Оркестратор синхронизации

`DwhSyncOrchestrator` управляет порядком и этапами синхронизации.

### Методы

```php
class DwhSyncOrchestrator
{
    // Полная синхронизация всех сущностей и фактов
    public function fullSync(array $options = []): array;

    // Инкрементальная синхронизация (изменения с указанной даты)
    public function incrementalSync(DateTime $since, array $options = []): array;

    // Синхронизация только фактовых таблиц
    public function syncFacts(array &$report, array $options = []): void;
}
```

### Порядок синхронизации (fullSync)

1. **Справочники**: pipelines → statuses → periodicity
2. **Пользователи**: users (нужны для всех остальных таблиц)
3. **Основные сущности**: companies → contacts → leads
4. **Покупатели**: customers → segments
5. **Остальные сущности**: calls → tasks → transactions → elements → unsorted
6. **Фактовые таблицы**: все 11 таблиц

### Опции синхронизации

```php
$options = [
    'limit' => 250,  // Количество записей на страницу (по умолчанию 250)
];
```

---

## Модели и коллекции

### BaseDwhModel

Базовый класс для всех DWH-моделей:

```php
abstract class BaseDwhModel
{
    abstract public function toArray(): array;        // Полный массив
    abstract public static function getTableName(): string;  // Имя таблицы
    public static function fromArray(array $data): self;    // Создать из массива
    public function toInsertArray(): array;           // Массив для INSERT (без id)
}
```

Магические геттеры/сеттеры через `__get`/`__set` (аналогично `BaseApiModel`).

### BaseDwhCollection

Базовый класс коллекций. Реализует `ArrayAccess`, `IteratorAggregate`, `JsonSerializable`.

```php
$collection = LeadDwhCollection::fromArray($rows);
$collection->add($leadModel);
$first = $collection->first();
$all = $collection->all();
$array = $collection->toArray();
$insertArray = $collection->toInsertArray();
```

---

## Обработка ошибок

Все ETL-сервисы перехватывают `AmoCRMApiException` при вызове API. Если API недоступен или возвращает ошибку, синхронизация останавливается и возвращает накопленную статистику.

### DwhDbAdapter

```php
class DwhDbAdapter
{
    public function insert(string $table, array $data): int;
    public function upsert(string $table, array $data, array $uniqueKeys): int;
    public function bulkInsert(string $table, array $rows): int;
    public function deleteBy(string $table, array $conditions): int;
    public function truncate(string $table): void;
    public function fetchAll(string $sql, array $params = []): array;
    public function fetchOne(string $sql, array $params = []): ?array;
    public function count(string $table, array $conditions = []): int;
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function getPdo(): PDO;
}
```

### Пример транзакционного использования

```php
$db->beginTransaction();
try {
    $db->upsert('amocrm_leads', $leadData, ['account_id', 'lead_id']);
    $db->bulkInsert('amocrm_leads_attributes', $attributesRows);
    $db->bulkInsert('amocrm_leads_tags', $tagsRows);
    $db->commit();
} catch (\Exception $e) {
    $db->rollback();
    throw $e;
}
```

---

## Расширение модуля

### Добавление новой сущности

1. **Создать SQL-миграцию** в `migrations/`
2. **Создать DWH-модель** в `src/AmoCRM/Dwh/Models/` (наследовать `BaseDwhModel`)
3. **Создать коллекцию** в `src/AmoCRM/Dwh/Collections/` (наследовать `BaseDwhCollection`)
4. **Создать ETL-сервис** в `src/AmoCRM/Dwh/Services/` (наследовать `BaseEtlService`)
5. **Добавить вызов** в `DwhSyncOrchestrator::fullSync()`

### Пример модели

```php
<?php

namespace AmoCRM\Dwh\Models;

class MyEntityDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $myEntityId;
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'amocrm_my_entities';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'my_entity_id' => $this->myEntityId,
            'name' => $this->name,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getMyEntityId(): int { return $this->myEntityId; }
    public function setMyEntityId(int $v): self { $this->myEntityId = $v; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $v): self { $this->name = $v; return $this; }
}
```

---

## Примеры запросов к DWH

### Сделки по воронкам с контактами

```sql
SELECT
    l.name AS lead_name,
    l.price,
    p.name AS pipeline,
    s.name AS status,
    c.name AS contact_name
FROM amocrm_leads l
LEFT JOIN amocrm_pipelines p ON l.pipeline_id = p.pipeline_id AND l.account_id = p.account_id
LEFT JOIN amocrm_statuses s ON l.status_id = s.status_id AND l.account_id = s.account_id
LEFT JOIN amocrm_leads_contacts lc ON l.lead_id = lc.lead_id AND l.account_id = lc.account_id
LEFT JOIN amocrm_contacts c ON lc.contact_id = c.contact_id AND lc.account_id = c.account_id
WHERE l.account_id = 1
  AND l.is_deleted = 0
ORDER BY l.created_at DESC;
```

### Динамика сделок по месяцам

```sql
SELECT
    d.year,
    d.month,
    COUNT(lf.lead_id) AS leads_count,
    SUM(lf.price) AS total_price
FROM amocrm_leads_facts lf
JOIN general_dates d ON lf.date_create_id = d.id AND lf.account_id = d.account_id
WHERE lf.account_id = 1
GROUP BY d.year, d.month
ORDER BY d.year, d.month;
```

### Покупатели с наибольшим LTV

```sql
SELECT
    c.name,
    cf.purchases,
    cf.average_check,
    cf.ltv
FROM amocrm_customers_facts cf
JOIN amocrm_customers c ON cf.customer_id = c.customer_id AND cf.account_id = c.account_id
WHERE cf.account_id = 1
ORDER BY cf.ltv DESC
LIMIT 20;
```
