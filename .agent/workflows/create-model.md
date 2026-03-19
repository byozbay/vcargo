---
description: Create a new Model in the vCargo project
---
# Workflow: Create Model

This workflow defines the standard steps to create a new model/repository in vCargo.

## 1. File Location & Naming
- **Path**: `app/Models/`
- **Class Naming**: `PascalCase` (e.g., `ShipmentModel.php`)
- **Table Naming**: `snake_case` (e.g., `shipments`)

## 2. Database Standards
- Use PDO Prepared Statements to prevent SQL injection.
- **Never hard delete financial records.** Implement soft delete (`is_active = 0` or `deleted_at`).
- Ensure the methods are strongly typed:
  ```php
  public function getShipmentById(int $id): ?array
  ```

## 3. Implementation Steps
1. Create the file in `app/Models/`.
2. Ensure `declare(strict_types=1);`.
3. Scaffold basic CRUD methods using PDO:
```php
<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ExampleModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Methods...
}
```
4. Perform an audit check: are financial transactions securely wrapped in DB transactions?
