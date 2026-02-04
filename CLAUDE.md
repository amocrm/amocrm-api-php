# CLAUDE.md

This file provides guidance for Claude Code (claude.ai/code) when working with this repository.

## Project Overview

This is a **PHP REST API client library** for interacting with the amoCRM CRM system. Despite the directory name containing "js", this is entirely a PHP project. It provides OAuth 2.0 authentication, support for 40+ entity types (leads, contacts, companies, catalogs, etc.), and comprehensive filtering/sorting capabilities.

- **License**: MIT
- **PHP Versions**: 7.1, 7.2, 7.3, 7.4, 8.0, 8.1, 8.2, 8.3, 8.4
- **API Version**: 4

## Key Commands

```bash
# Install dependencies
composer install

# Run tests
composer test

# Check code style (PHP CodeSniffer with PSR-12)
composer style:check

# Auto-fix code style issues
composer style:fix

# Pre-push check (style + tests)
composer run git:prepush

# Start local dev server (localhost:8181)
composer serve
```

## Architecture

### Service-Oriented Pattern

Each entity type has a corresponding service class in `src/AmoCRM/EntitiesServices/`:
- Services extend `BaseEntity` abstract class
- Standard CRUD methods: `get()`, `getOne()`, `add()`, `addOne()`, `update()`, `updateOne()`, `syncOne()`
- Access via `AmoCRMApiClient`: `$client->leads()`, `$client->contacts()`, etc.

### Model/Collection Pattern

- Models in `src/AmoCRM/Models/` extend `BaseApiModel`
- Collections in `src/AmoCRM/Collections/` extend `BaseApiCollection`
- Models have `toArray()` and `toApi()` methods for serialization

### Filter Pattern

- Filters in `src/AmoCRM/Filters/` extend `BaseEntityFilter`
- Support pagination, sorting, and entity-specific filtering
- Fluent interface for building queries

### Custom Fields System

- 30+ field types supported in `src/AmoCRM/Models/CustomFieldsValues/`
- Separate model classes for each field type (text, numeric, checkbox, date, etc.)
- Collection classes for managing field values

## Directory Structure

```
src/AmoCRM/
├── Client/                    # Main API client (entry point: AmoCRMApiClient.php)
├── EntitiesServices/          # Service classes for each entity (40+ files)
├── Models/                    # Data models (294 files)
├── Collections/               # Collection classes
├── Filters/                   # Query filter classes
├── OAuth/                     # OAuth2 implementation
├── Exceptions/                # Custom exceptions (20+ types)
├── Enum/                      # Enumerations
├── Contracts/                 # PHP interfaces
├── Helpers/                   # Utility classes
└── Support/                   # Support utilities

examples/                      # 50+ usage examples
tests/                         # PHPUnit tests
```

## Key Entry Points

- **`src/AmoCRM/Client/AmoCRMApiClient.php`**: Primary API client class
- **`src/AmoCRM/Client/AmoCRMApiRequest.php`**: HTTP request builder
- **`src/AmoCRM/OAuth/AmoCRMOAuth.php`**: OAuth2 client wrapper
- **`src/AmoCRM/EntitiesServices/BaseEntity.php`**: Abstract base for all services
- **`src/AmoCRM/Models/BaseApiModel.php`**: Abstract base for all models

## Coding Standards

- **PSR-12** code style enforced via PHP CodeSniffer
- **4-space indentation** (2-space for JSON/YAML)
- **UTF-8 charset**, LF line endings
- **Short array syntax** required (`[]` not `array()`)
- **PascalCase** for classes, **camelCase** for methods, **UPPER_CASE** for constants
- **PSR-4** autoloading with `AmoCRM` root namespace

## Exception Handling

All exceptions extend `AmoCRMApiException`. Key exceptions:
- `AmoCRMApiErrorResponseException`: API error responses
- `AmoCRMApiNoContentException`: Empty responses
- `AmoCRMoAuthApiException`: OAuth-specific errors
- Validation errors accessible via `getValidationErrors()`

## Supported Entity Services

Core entities: Leads, Contacts, Companies, Catalogs, CatalogElements, Tasks, Notes, Tags, Webhooks, Pipelines, Statuses, Users, Roles, CustomFields, Files, Events, Customers, Transactions, Sources, and 20+ more.

## Configuration

For examples, create `.env` file in `examples/` directory:
```
CLIENT_ID="UUID"
CLIENT_SECRET="secret"
CLIENT_REDIRECT_URI="https://example.com/examples/get_token.php"
```
