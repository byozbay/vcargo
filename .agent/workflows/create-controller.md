---
description: Create a new Controller in the vCargo project
---
# Workflow: Create Controller

This workflow defines the standard steps to create a new controller in the vCargo architecture.

## 1. File Location & Naming
- **Path**: `app/Controllers/`
- **Naming**: `PascalCase` trailing with `Controller` (e.g., `ShipmentController.php`)

## 2. Requirements & Standards
- Enforce strict types: `declare(strict_types=1);` at the top of the file.
- Use **PHP 8.2+ Typed Properties** for any class dependencies (e.g., `private ShipmentModel $shipmentModel;`).
- All controllers must be part of the Custom MVC architecture.

## 3. Template Implementation
When scaffolding the controller, always include the constructor for dependency injection and standard CRUD methods if applicable:
```php
<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller; // Or correct base class context

class NameController extends Controller
{
    public function __construct()
    {
        // Auth checks or Model injections
    }
    
    public function index(): void
    {
        // ...
    }
}
```

## 4. Final Review
- Verify that the Controller doesn't contain raw database queries (use Models instead).
- Ensure routing can map correctly to these methods.
