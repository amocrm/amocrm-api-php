# Spec-Driven Development for amoCRM API Library

## Project Overview

- **Project**: amocrm-api-php
- **Type**: PHP API Client Library
- **API Version**: amoCRM API v4
- **License**: MIT

## Specification Workflow

This project uses the Speckit specification workflow. All features and changes must go through the specification process.

### Key Commands

| Command | Purpose |
|---------|---------|
| `/speckit.specify` | Create new feature specification |
| `/speckit.plan` | Generate implementation plan |
| `/speckit.tasks` | Create task breakdown |
| `/speckit.constitution` | View/update project constitution |
| `/speckit.checklist` | Generate implementation checklist |

### Speckit Constitution

The project constitution is stored at `.specify/memory/constitution.md` and defines:
- Core development principles
- Code organization rules
- Testing standards
- Documentation requirements
- Versioning policy

## Architecture Summary

### Directory Structure

```
src/AmoCRM/
├── Client/           # AmoCRMApiClient, AmoCRMApiRequest
├── Collections/      # Typed collections (25+ entity types)
├── EntitiesServices/ # CRUD services per entity
├── Enum/            # Type enums, status codes
├── Exceptions/      # Exception hierarchy
├── Filters/         # Query filters per entity
├── Helpers/         # Utilities
├── Models/          # Entity models
└── OAuth/           # OAuth implementation
```

### Key Patterns

1. **Model-Collection-Service**: Each entity has Model, Collection, and Service
2. **toArray/toApi**: Models support two representations
3. **Filter Chain**: Complex queries via Filter classes
4. **Trait Reuse**: Common functionality via traits

## Development Standards

### Code Style

```bash
composer style:check   # Check code style
composer style:fix     # Fix code style issues
```

### Testing

```bash
composer test          # Run PHPUnit tests
```

### Pre-push Hook

```bash
composer git:prepush   # Runs style check + tests
```

## Quick Reference

### Creating an Entity

```php
$lead = new LeadModel();
$lead->setName('Deal')
     ->setPrice(50000)
     ->setPipelineId(1);

$lead = $apiClient->leads()->addOne($lead);
```

### Querying with Filters

```php
$filter = new LeadsFilter();
$filter->setPipelineIds([1])
       ->setResponsibleUserId(123)
       ->setLimit(50);

$leads = $apiClient->leads()->get($filter);
```

### Error Handling

```php
try {
    $lead = $apiClient->leads()->getOne(1);
} catch (AmoCRMoAuthApiException $e) {
    // Token issues
} catch (AmoCRMApiException $e) {
    // API errors
}
```