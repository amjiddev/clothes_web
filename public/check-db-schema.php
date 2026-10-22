<?php
/**
 * Check if the notification columns exist in the database
 * Access via: http://localhost/cloth/public/check-db-schema.php
 */

header('Content-Type: text/html; charset=utf-8');

?><!DOCTYPE html>
<html>
<head>
    <title>Database Schema Check</title>
    <style>
        body { font-family: monospace; background: #1e1e1e; color: #00ff00; padding: 20px; }
        pre { background: #0d0d0d; padding: 10px; border-left: 3px solid #00ff00; }
        .error { color: #ff6b6b; }
        .success { color: #51cf66; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #00ff00; padding: 8px; text-align: left; }
        th { background: #003300; }
    </style>
</head>
<body>
    <h2>🗄️  Database Schema Check</h2>
    
    <?php
    try {
        // Load Laravel
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Http\Kernel')->handle(
            $request = Illuminate\Http\Request::capture()
        );
        
        $db = app('db');
        
        // Check tables
        $tables = ['orders', 'customer_measurements', 'contact_submissions'];
        
        foreach ($tables as $table) {
            echo "<h3>Table: <span class='success'>$table</span></h3>";
            
            try {
                // Get columns
                $columns = $db->getSchemaBuilder()->getColumns($table);
                
                echo "<table>";
                echo "<tr><th>Column</th><th>Type</th><th>Nullable</th></tr>";
                
                $hasRequiredColumns = [];
                foreach ($columns as $col) {
                    $isNotification = in_array($col['name'], ['is_seen', 'seen_at', 'is_read', 'read_at']);
                    $class = $isNotification ? 'success' : '';
                    echo "<tr>";
                    echo "<td" . ($class ? " class='$class'" : "") . ">" . $col['name'] . "</td>";
                    echo "<td>" . $col['type'] . "</td>";
                    echo "<td>" . ($col['nullable'] ? 'Yes' : 'No') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
            } catch (\Exception $e) {
                echo "<pre class='error'>❌ Error: " . $e->getMessage() . "</pre>";
            }
        }
        
        echo "<h3 class='success'>✅ Database connection successful!</h3>";
        
    } catch (\Exception $e) {
        echo "<pre class='error'>❌ Error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
    }
    ?>
    
</body>
</html>
