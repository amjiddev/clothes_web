<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    $db = app('db');
    
    // Check orders table columns
    echo "=== ORDERS TABLE COLUMNS ===\n";
    $columns = $db->select("SHOW COLUMNS FROM orders");
    foreach ($columns as $col) {
        echo $col->Field . " (" . $col->Type . ")\n";
    }
    
    // Check if is_seen exists
    $has_is_seen = collect($columns)->pluck('Field')->contains('is_seen');
    echo "\nHas 'is_seen' column: " . ($has_is_seen ? 'YES ✅' : 'NO ❌') . "\n";
    
    // Check customer_measurements table columns
    echo "\n=== CUSTOMER_MEASUREMENTS TABLE COLUMNS ===\n";
    $columns = $db->select("SHOW COLUMNS FROM customer_measurements");
    foreach ($columns as $col) {
        echo $col->Field . " (" . $col->Type . ")\n";
    }
    
    $has_is_seen = collect($columns)->pluck('Field')->contains('is_seen');
    echo "\nHas 'is_seen' column: " . ($has_is_seen ? 'YES ✅' : 'NO ❌') . "\n";
    
    // Check contact_submissions table columns
    echo "\n=== CONTACT_SUBMISSIONS TABLE COLUMNS ===\n";
    $columns = $db->select("SHOW COLUMNS FROM contact_submissions");
    foreach ($columns as $col) {
        echo $col->Field . " (" . $col->Type . ")\n";
    }
    
    $has_is_read = collect($columns)->pluck('Field')->contains('is_read');
    echo "\nHas 'is_read' column: " . ($has_is_read ? 'YES ✅' : 'NO ❌') . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
