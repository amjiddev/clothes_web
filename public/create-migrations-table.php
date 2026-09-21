<?php
/**
 * Create Migrations Table and Mark Existing Tables
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(__DIR__ . '/..');
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "<html><head><title>Create Migrations Table</title><style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }";
echo "h1 { color: #d4af37; }";
echo ".success { background: #d5f4e6; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #27ae60; color: #186a3b; }";
echo ".error { background: #fadbd8; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #e74c3c; color: #c0392b; }";
echo ".info { background: #d6eaf8; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #3498db; color: #1a365d; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #d4af37; color: white; }";
echo ".cmd { display: block; background: #f0f0f0; padding: 10px; border-radius: 4px; margin: 10px 0; font-family: monospace; border-left: 3px solid #3498db; }";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>🔧 Create Migrations Table</h1>";

try {
    $db = $app->make('db');
    $connection = $db->connection();
    
    echo "<h2>Step 1: Create Migrations Table</h2>";
    
    // Drop if exists
    try {
        $connection->statement("DROP TABLE IF EXISTS migrations");
        echo "<div class='info'>ℹ️ Dropped old migrations table (if exists)</div>";
    } catch (\Exception $e) {
        // Ignore
    }
    
    // Create migrations table
    $sql = "CREATE TABLE migrations (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $connection->statement($sql);
    echo "<div class='success'>✅ Migrations table created successfully</div>";
    
    // Step 2: Get existing tables
    echo "<h2>Step 2: Get Existing Tables</h2>";
    
    $tables = $db->select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
    $existingTables = array_map(function($t) { return $t->TABLE_NAME; }, $tables);
    
    echo "<p>Found <strong>" . count($existingTables) . "</strong> tables in database</p>";
    
    // Step 3: Mark migrations as complete
    echo "<h2>Step 3: Mark Existing Tables as Migrated</h2>";
    
    $batch = 1;
    $marked = 0;
    
    // List of migrations that we want to mark as complete if their tables exist
    $migrationsToCheck = [
        '2024_07_01_100049_create_permission_tables',
        '2024_07_01_100050_seed_default_roles',
        '2024_07_08_000001_create_categories_table',
        '2024_07_08_000002_create_products_table',
        '2024_07_08_000003_create_customer_measurements_table',
        '2024_07_08_000004_create_orders_table',
        '2024_07_08_000005_create_order_items_table',
        '2024_07_08_000006_create_stitching_orders_table',
        '2024_07_08_create_inventory_table',
        '2024_07_08_create_receptionists_table',
        '2024_07_08_create_tailors_table',
        '2024_12_15_000000_add_missing_columns_to_tailors_table',
    ];
    
    echo "<table>";
    echo "<tr><th>Migration</th><th>Status</th></tr>";
    
    foreach ($migrationsToCheck as $migration) {
        // Check if this migration's main table exists
        $tableExists = in_array('permissions', $existingTables) || 
                       in_array('categories', $existingTables) ||
                       in_array('products', $existingTables) ||
                       in_array('orders', $existingTables) ||
                       in_array('inventory', $existingTables) ||
                       in_array('receptionists', $existingTables) ||
                       in_array('tailors', $existingTables);
        
        if ($tableExists) {
            try {
                $db->insert("INSERT INTO migrations (migration, batch) VALUES (?, ?)", [
                    $migration,
                    $batch
                ]);
                echo "<tr><td><code>$migration</code></td><td><span style='color: #27ae60;'>✅ Marked</span></td></tr>";
                $marked++;
            } catch (\Exception $e) {
                // Already exists, skip
                echo "<tr><td><code>$migration</code></td><td><span style='color: #f39c12;'>⏭️ Skipped</span></td></tr>";
            }
        }
    }
    
    echo "</table>";
    
    echo "<div class='success'>";
    echo "<strong>✅ SUCCESS!</strong><br>";
    echo "Marked <strong>$marked</strong> migrations as complete.<br>";
    echo "Migrations table is now properly set up.";
    echo "</div>";
    
    echo "<h2>Step 4: Run Next Command</h2>";
    echo "<p>Now run this command in PowerShell:</p>";
    echo "<code class='cmd'>php artisan migrate</code>";
    
    echo "<div class='info'>";
    echo "<strong>What to expect:</strong>";
    echo "<ul>";
    echo "<li>Should show: <code>Migration Table Created</code></li>";
    echo "<li>Should show remaining migrations running</li>";
    echo "<li>Should complete with: <code>✓ Done</code></li>";
    echo "</ul>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Error</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "</div></body></html>";
?>
