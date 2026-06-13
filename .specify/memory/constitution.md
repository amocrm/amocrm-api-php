# amoCRM API Library Constitution

<!--
Sync Impact Report:
- Version change: N/A → 1.0.0 (initial creation)
- Added sections: All 5 Core Principles, Code Organization, Testing Standards, Dependencies, Documentation, Versioning Policy, Governance
- Removed sections: N/A (initial creation)
- Templates requiring updates: ✅ all templates copied from spec-kit
- Follow-up TODOs: None
-->

## Core Principles

### I. API Stability & Backward Compatibility

This library MUST maintain backward compatibility for all public APIs. Breaking changes require:
- Major version bump (semver)
- Deprecation notice in previous version
- Migration guide documentation
- Minimum 2 minor version deprecation period

**Rationale**: Integrations built on this library should not break unexpectedly.

### II. Type Safety

All models MUST have explicit PHPDoc type annotations for properties and methods.
- Use strict typing (`declare(strict_types=1);`) in all files
- Nullable parameters MUST be explicitly typed as `?Type`
- Collections MUST enforce type checking via `ITEM_CLASS` constant

**Rationale**: PHP 7.1+ is the minimum version; type safety prevents runtime errors.

### III. Consistent Model Interface

All entity models MUST implement:
- `toArray(): array` — returns internal representation
- `toApi(?string $requestId): array` — returns API-compatible representation
- `fromArray(array $data): self` — creates model from API response
- `getId(): ?int` — consistent ID access

**Rationale**: Predictable interface across all 25+ entity types.

### IV. OAuth Token Management

The library MUST:
- Automatically refresh expired access tokens when possible
- Support both short-lived (OAuth) and long-lived tokens
- Provide callback hooks for token persistence
- Never expose tokens in logs or error messages

**Rationale**: Token management is critical for integrations; developers need flexibility.

### V. Error Handling

The library MUST provide granular exception hierarchy:
- `AmoCRMApiException` — base API errors
- `AmoCRMoAuthApiException` — authentication errors
- `AmoCRMApiErrorResponseException` — validation errors with details
- Domain-specific exceptions (e.g., `DisposableTokenExpiredException`)

All exceptions MUST include request context for debugging.

**Rationale**: Developers need to handle different error scenarios appropriately.

## Code Organization

### Directory Structure

```
src/AmoCRM/
├── Client/           # API client, request handling, OAuth
├── Collections/      # Typed collections for all entities
├── EntitiesServices/ # CRUD services per entity type
├── Enum/            # Type enums, status codes, constants
├── Exceptions/      # Exception hierarchy
├── Filters/         # Query filters per entity
├── Helpers/         # Utilities, interfaces
├── Models/          # Entity models, custom fields
└── OAuth/           # OAuth implementation
```

**Rationale**: Follows PSR-4 and common PHP conventions.

### Naming Conventions

- Classes: `PascalCase`
- Methods: `camelCase`
- Constants: `UPPER_SNAKE_CASE`
- Private properties: `$camelCase`
- Interfaces: `PascalCase` with `Interface` suffix

## Testing Standards

### Test Coverage

Core components requiring integration tests:
- Token refresh flow
- Pagination (next/prev page)
- Complex entity operations (lead + contact + company)
- Custom field serialization/deserialization
- Error response parsing

**Rationale**: These are high-risk areas that affect all integrations.

## Dependencies

### Allowed Dependencies

- `league/oauth2-client` — OAuth 2.0 protocol
- `guzzlehttp/guzzle` — HTTP client
- `lcobucci/jwt` — JWT token handling
- `nesbot/carbon` — Date/time utilities
- `ramsey/uuid` — UUID generation

### Prohibited Dependencies

- No templating engines
- No ORM/database libraries
- No frontend JavaScript frameworks

**Rationale**: Keep library lightweight and focused on API communication.

## Documentation

### Requirements

- PHPDoc for all public methods
- README with authentication examples
- Example files for each major entity type
- Changelog following Keep a Changelog format

**Rationale**: External developers need clear documentation.

## Versioning Policy

- Follow Semantic Versioning 2.0.0
- API_VERSION constant = 4 (amoCRM API v4)
- DRIVE_API_VERSION = 'v1.0'
- Library version in User-Agent header

## Governance

**Version**: 1.0.0 | **Ratified**: 2026-06-13 | **Last Amended**: 2026-06-13

This constitution supersedes all other development practices in this repository.

**Amendment Procedure**:
1. Changes require PR with constitution modification
2. Review must verify compliance with existing principles
3. Major changes require deprecation period
4. All PRs must pass `composer test` and `composer style:check`