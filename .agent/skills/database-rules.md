---
name: Database Rules
description: Database architecture and strict query rules for vCargo
---

# Skill: Database Security and Structure

Ensure the integrity, denormalization, and security rules below are applied when dealing with the vCargo MySQL/MariaDB database.

## Critical Security Rules
- **No Raw Queries Without PDO**: SQL Injection prevention is mandatory. Always use PDO Prepared Statements. No variables should be interpolated directly into query strings.
- **Transactions for Finance**: Any operation touching `transactions` or `vault_transactions` MUST be wrapped in a database transaction (`beginTransaction()`, `commit()`, `rollBack()`).
- **Soft Delete**: Financial and tracking records are never physically deleted. Use `is_active = 0` or a `deleted_at` timestamp.

## Schema Naming Conventions
- **Tables and Columns**: `snake_case` only.
- Example: `tracking_no`, `payment_status`, `total_price`.

## Core Entities
Familiarize yourself with these foundational tables for joined operations:
- `accounts`: Clients, users, senders, receivers.
- `shipments`: Core cargo records (status, tracking numbers, payment settings).
- `transactions`: Logs of financial exchanges in and out.
- `vault_transactions`: Safe/Kasa balances for specific branches.
- `branches`: Physical locations/hubs (Franchise or Corporate).
- `trips`: Active vehicle routes (Plate, Driver, Company).
- `storage_records`: Items in "Emanet" or luggage lockers.
- `audit_logs`: History tracking for sensitive edits.
