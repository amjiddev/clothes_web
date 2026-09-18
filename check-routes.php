<?php
// Quick route diagnostics

define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Get router
$router = $app['router'];

// Get all routes
$routes = $router->getRoutes();

echo "\n=== NOTIFICATION ROUTES CHECK ===\n\n";

$found = false;
foreach ($routes as $route) {
    if (strpos($route->uri, 'notifications') !== false) {
        echo "✓ Found route: " . $route->methods[0] . " " . $route->uri . "\n";
        $found = true;
    }
}

if (!$found) {
    echo "✗ NO NOTIFICATION ROUTES FOUND!\n";
    echo "\n⚠️  This means the routes file hasn't been properly loaded.\n";
    echo "\n📋 Routes that ARE registered:\n";
    $count = 0;
    foreach ($routes as $route) {
        if (strpos($route->uri, 'admin') !== false && $count < 10) {
            echo "  - " . $route->methods[0] . " " . $route->uri . "\n";
            $count++;
        }
    }
    if ($count === 0) {
        echo "  (No admin routes found either!)\n";
    }
}

echo "\n";
?>
