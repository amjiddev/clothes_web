<!DOCTYPE html>
<html>
<head>
    <title>Database Schema Check</title>
    <style>
        body { font-family: monospace; background: #1e1e1e; color: #00ff88; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #00ff88; padding: 8px; text-align: left; }
        th { background: #003300; }
        .error { color: #ff4444; }
        .success { color: #51cf66; }
        .warn { color: #ffaa00; }
    </style>
</head>
<body>
    <h1>🗄️ Database Schema Check</h1>
    
    <?php
    try {
        // Load Laravel
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';
        
        $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
        $response = $kernel->handle($request = Illuminate\Http\Request::capture());
        
        $db = $app->make('db');
        
        // Check if columns exist
        $tables = [
            'orders' => ['is_seen', 'seen_at'],
            'customer_measurements' => ['is_seen', 'seen_at'],
            'contact_submissions' => ['is_read', 'read_at']
        ];
        
        foreach ($tables as $tableName => $requiredColumns) {
            echo "<h2>Table: <span class='success'>$tableName</span></h2>";
            
            try {
                $columns = $db->getSchemaBuilder()->getColumns($tableName);
                
                echo "<table>";
                echo "<tr><th>Column Name</th><th>Type</th><th>Required</th></tr>";
                
                foreach ($columns as $col) {
                    $isRequired = in_array($col['name'], $requiredColumns);
                    $class = $isRequired ? 'success' : '';
                    echo "<tr>";
                    echo "<td " . ($class ? "class='$class'" : "") . ">" . $col['name'] . "</td>";
                    echo "<td>" . $col['type'] . "</td>";
                    echo "<td>" . ($isRequired ? "✅ YES" : "No") . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Check if required columns exist
                $foundColumns = array_column($columns, 'name');
                $missingColumns = array_diff($requiredColumns, $foundColumns);
                
                if (empty($missingColumns)) {
                    echo "<p class='success'>✅ All required columns found!</p>";
                } else {
                    echo "<p class='error'>❌ Missing columns: " . implode(', ', $missingColumns) . "</p>";
                }
                
            } catch (\Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
        }
        
        // Test queries
        echo "<h2>Testing Queries</h2>";
        
        try {
            $unseenOrdersCount = $db->table('orders')->where('is_seen', false)->count();
            echo "<p class='success'>✅ Unseen orders count: $unseenOrdersCount</p>";
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Error counting unseen orders: " . $e->getMessage() . "</p>";
        }
        
        try {
            $unreadMessagesCount = $db->table('contact_submissions')->where('is_read', false)->count();
            echo "<p class='success'>✅ Unread messages count: $unreadMessagesCount</p>";
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Error counting unread messages: " . $e->getMessage() . "</p>";
        }
        
        try {
            $unseenMeasurementsCount = $db->table('customer_measurements')->where('is_seen', false)->count();
            echo "<p class='success'>✅ Unseen measurements count: $unseenMeasurementsCount</p>";
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Error counting unseen measurements: " . $e->getMessage() . "</p>";
        }
        
    } catch (\Exception $e) {
        echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    ?>
</body>
</html>
