# AmoCRM Cross-Channel Analytics

Сервис сквозной аналитики для amoCRM с интеграцией Fusio.

## Возможности

- 📊 ETL-пайплайн для выгрузки данных из amoCRM
- 🔄 Webhook-обработка в реальном времени
- 📈 Аналитика воронки продаж
- 🎯 Атрибуция по источникам (first/last touch)
- 💰 Расчёт LTV покупателей
- 🏷️ Интеграция с кастомными полями

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
```

## CLI Команды

```bash
# Синхронизация пиплайнов
php bin/analytics sync:pipelines

# Полная выгрузка сделок
php bin/analytics etl:full

# Инкрементальная выгрузка (изменения за последний час)
php bin/analytics etl:incremental

# Инкрементальная выгрузка с указанием даты
php bin/analytics etl:incremental --since="2024-01-01 00:00:00"

# Выгрузка конкретной сделки
php bin/analytics etl:leads 123456

# Статус webhook-подписок
php bin/analytics webhook:status
```

## Webhook Endpoint

POST `/webhook/amocrm`

Принимает callbacks от amoCRM и обновляет аналитику в реальном времени.

## API Endpoints

| Endpoint | Метод | Описание |
|----------|-------|---------|
| `/api/analytics/funnel` | GET | Воронка продаж |
| `/api/analytics/attribution` | GET | Атрибуция по источникам |
| `/api/analytics/ltv` | GET | LTV покупателей |
| `/api/analytics/overview` | GET | Обзор метрик |

## Разработка

```bash
# Установить зависимости
composer install

# Запустить тесты
composer test

# Проверить стиль кода
composer style:check

# Исправить стиль
composer style:fix
```

## Структура кода

```
analytics/
├── src/
│   ├── Client/          # AmoCRM клиент
│   ├── ETL/             # Exporters
│   ├── Webhook/         # Webhook обработчики
│   ├── Analytics/       # Сервисы аналитики
│   ├── Repository/       # Доступ к БД
│   ├── Models/          # Модели
│   └── Console/         # CLI команды
├── migrations/          # Миграции БД
└── tests/              # Тесты
```

## Лицензия

MIT
