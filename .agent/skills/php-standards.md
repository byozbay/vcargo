---
name: PHP Standards
description: Coding guidelines for the vCargo PHP backend
---

# Skill: PHP Coding Standards

This skill enforces strict PHP programming practices for the vCargo backend architecture. Adhere to these principles whenever writing models, controllers, or services.

## Core Rules
- **Strict Types**: Always declare strict types `declare(strict_types=1);` on the first line after `<?php`.
- **Version Requirements**: Use PHP 8.2+ features:
  - Strongly typed properties (e.g. `private \PDO $db;`, `public string $name;`).
  - Return types and nullable returns whenever applicable (e.g. `public function find(int $id): ?array`).
  - Nullsafe operators and match expressions.
  - Enumerations (Enums) for static lists like Shipment Statuses or Roles if appropriate.

## Naming Conventions
- **Classes**: `PascalCase` (e.g., `ShipmentController`).
- **Variables & Methods**: `camelCase` (e.g., `$totalPrice`, `calculateCommission()`).
- Use English names for all functions, variables, and database queries. Only the User Interface output should be in Turkish.

## Controller & View Flow
Controllers should be thin. Do not put heavy parsing or business logic directly within a controller method if it can be abstracted to a Service or Model. Ensure database concerns stay within the Model tier.
