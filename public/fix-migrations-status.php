<?php
/**
 * Fix Migration Status
 * Marks existing tables as already migrated in the migrations table
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(__DIR__ . '/..');
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "<html><head><title>Fix Migrations</title><style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }";
echo "h1 { color: #d4af37; }";
echo ".success { background: #d5f4e6; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #27ae60; }";
echo ".error { background: #fadbd8; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #e74c3c; }";
echo ".info { background: #d6eaf8; padding: 15px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #3498db; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #d4af37; color: white; }";
echo ".done { color: #27ae60; font-weight: bold; }";
echo ".pending { color: #e67e22; font-weight: bold; }";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>🔧 Fix Migration Status</h1>";

try {
    $db = $app->make('db');
    
    // Step 1: Check if migrations table exists
    echo "<h2>Step 1: Check Migrations Table</h2>";
    $migrationsTableExists = $db->select("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'migrations' LIMIT 1");
    
    if (empty($migrationsTableExists)) {
        echo "<div class='info'>⚠️ Migrations table doesn't exist, creating it...</div>";
        $db->statement("CREATE TABLE migrations (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL
        )");
        echo "<div class='success'>✅ Migrations table created</div>";
    } else {
        echo "<div class='success'>✅ Migrations table exists</div>";
    }
    
    // Step 2: Get list of migration files
    echo "<h2>Step 2: Check Database Tables</h2>";
    $tables = $db->select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
    $existingTables = array_map(function($t) { return $t->TABLE_NAME; }, $tables);
    
    echo "<p>Found " . count($existingTables) . " tables in database:</p>";
    echo "<ul>";
    foreach ($existingTables as $table) {
        echo "<li><code>$table</code></li>";
    }
    echo "</ul>";
    
    // Step 3: Mark migrations as run if tables exist
    echo "<h2>Step 3: Mark Tables as Migrated</h2>";
    
    $migrations = [
        '2024_07_01_100049_create_permission_tables' => ['permissions', 'roles', 'role_has_permissions', 'model_has_roles', 'model_has_permissions'],
        '2024_07_01_100050_seed_default_roles' => ['roles'],
        '2024_07_08_000001_create_categories_table' => ['categories'],
        '2024_07_08_000002_create_products_table' => ['products'],
        '2024_07_08_000003_create_customer_measurements_table' => ['customer_measurements'],
        '2024_07_08_000004_create_orders_table' => ['orders'],
        '2024_07_08_000005_create_order_items_table' => ['order_items'],
        '2024_07_08_000006_create_stitching_orders_table' => ['stitching_orders'],
        '2024_07_08_create_inventory_table' => ['inventory'],
        '2024_07_08_create_receptionists_table' => ['receptionists'],
        '2024_07_08_create_tailors_table' => ['tailors'],
    ];
    
    // Get already migrated
    $migrated = $db->select("SELECT migration FROM migrations");
    $migratedNames = array_map(function($m) { return $m->migration; }, $migrated);
    
    echo "<table>";
    echo "<tr><th>Migration</th><th>Tables</th><th>Status</th></tr>";
    
    $marked = 0;
    foreach ($migrations as $migrationName => $requiredTables) {
        // Check if all required tables exist
        $allTablesExist = true;
        foreach ($requiredTables as $table) {
            if (!in_array($table, $existingTables)) {
                $allTablesExist = false;
                break;
            }
        }
        
        $alreadyMigrated = in_array($migrationName, $migratedNames);
        
        $tablesList = implode(', ', array_map(function($t) { return "<code>$t</code>"; }, $requiredTables));
        
        if ($allTablesExist && !$alreadyMigrated) {
            // Mark as migrated
            $batch = $db->select("SELECT MAX(batch) as max_batch FROM migrations");
            $nextBatch = ($batch[0]->max_batch ?? 0) + 1;
            
            $db->insert("INSERT INTO migrations (migration, batch) VALUES (?, ?)", [
                $migrationName,
                $nextBatch
            ]);
            
            echo "<tr>";
            echo "<td><code>$migrationName</code></td>";
            echo "<td>$tablesList</td>";
            echo "<td><span class='done'>✅ MARKED</span></td>";
            echo "</tr>";
            $marked++;
        } else if ($allTablesExist && $alreadyMigrated) {
            echo "<tr>";
            echo "<td><code>$migrationName</code></td>";
            echo "<td>$tablesList</td>";
            echo "<td><span class='done'>✅ ALREADY DONE</span></td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td><code>$migrationName</code></td>";
            echo "<td>$tablesList</td>";
            echo "<td><span class='pending'>⏳ PENDING</span></td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    echo "<div class='success'><strong>✅ Complete!</strong></div>";
    echo "<p>Marked <strong>$marked</strong> migration(s) as already run.</p>";
    
    // Step 4: Run remaining migrations
    echo "<h2>Step 4: Run Remaining Migrations</h2>";
    echo "<p>Now try running:</p>";
    echo "<code style='display: block; background: #f0f0f0; padding: 10px; border-radius: 4px; margin: 10px 0;'>php artisan migrate</code>";
    
    echo "<div class='info'>";
    echo "<strong>📝 Next Steps:</strong>";
    echo "<ol>";
    echo "<li>Go back to PowerShell</li>";
    echo "<li>Run: <code>php artisan migrate</code></li>";
    echo "<li>It should complete successfully now</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "</div></body></html>";
?>
