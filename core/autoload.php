<?php
declare(strict_types=1);

/**
 * Autoloader — requires model and controller files automatically.
 */
spl_autoload_register(function (string $className): void {
    $dirs = [
        __DIR__ . '/../models/',
        __DIR__ . '/../controllers/',
        __DIR__ . '/',
    ];

    foreach ($dirs as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Always load Database
require_once __DIR__ . '/database.php';
