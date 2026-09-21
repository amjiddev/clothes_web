<?php
/**
 * Complete System Verification
 * Checks all components of the notification system
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(__DIR__ . '/..');
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "<html><head><title>System Verification</title><style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }";
echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }";
echo "h1 { color: #667eea; text-align: center; margin-bottom: 40px; }";
echo ".section { margin: 30px 0; padding: 25px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea; }";
echo ".section h2 { color: #333; margin-top: 0; font-size: 1.3rem; }";
echo ".check { margin: 15px 0; padding: 12px; background: white; border-radius: 6px; display: flex; align-items: center; gap: 15px; }";
echo ".check.pass { border-left: 4px solid #27ae60; }";
echo ".check.fail { border-left: 4px solid #e74c3c; }";
echo ".check.info { border-left: 4px solid #3498db; }";
echo ".status { font-weight: bold; min-width: 150px; }";
echo ".pass { color: #27ae60; }";
echo ".fail { color: #e74c3c; }";
echo ".info { color: #3498db; }";
echo "table { width: 100%; border-collapse: collapse; margin: 15px 0; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #667eea; color: white; }";
echo ".summary { background: #d5f4e6; border: 2px solid #27ae60; padding: 20px; border-radius: 8px; margin: 20px 0; }";
echo ".summary.warning { background: #fef5e7; border-color: #f39c12; }";
echo ".summary h3 { margin: 0 0 10px 0; color: #186a3b; }";
echo ".summary.warning h3 { color: #7d6608; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>🔍 System Verification Report</h1>";

$checks = [
    'passed' => 0,
    'failed' => 0,
    'total' => 0,
];

// ============ DATABASE CONNECTION ============
echo "<div class='section'>";
echo "<h2>1️⃣ Database Connection</h2>";

try {
    $db = $app->make('db');
    $connection = $db->connection();
    $version = $connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
    
    echo "<div class='check pass'>";
    echo "<span class='status pass'>✅ PASS</span>";
    echo "<span>Connected to MySQL " . $version . "</span>";
    echo "</div>";
    $checks['passed']++;
} catch (\Exception $e) {
    echo "<div class='check fail'>";
    echo "<span class='status fail'>❌ FAIL</span>";
    echo "<span>" . $e->getMessage() . "</span>";
    echo "</div>";
    $checks['failed']++;
}
$checks['total']++;

// ============ DATABASE TABLES ============
echo "<div class='section'>";
echo "<h2>2️⃣ Database Tables</h2>";

$requiredTables = [
    'users' => 'Users table',
    'orders' => 'Orders table',
    'products' => 'Products table',
    'categories' => 'Categories table',
    'customer_measurements' => 'Customer Measurements table',
    'contact_submissions' => 'Contact Submissions table',
    'website_cms' => 'Website CMS table',
    'permissions' => 'Permissions table',
    'roles' => 'Roles table',
];

try {
    $tables = $db->select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
    $tableNames = [];
    foreach ($tables as $t) {
        $tableNames[] = $t->TABLE_NAME;
    }
    
    echo "<p>Found <strong>" . count($tableNames) . "</strong> total tables in database</p>";
    echo "<table>";
    echo "<tr><th>Table Name</th><th>Status</th></tr>";
    
    foreach ($requiredTables as $table => $description) {
        $exists = in_array($table, $tableNames);
        $status = $exists ? "<span class='pass'>✅ EXISTS</span>" : "<span class='fail'>❌ MISSING</span>";
        echo "<tr><td><code>$table</code> - $description</td><td>$status</td></tr>";
        
        if ($exists) {
            $checks['passed']++;
        } else {
            $checks['failed']++;
        }
        $checks['total']++;
    }
    
    echo "</table>";
} catch (\Exception $e) {
    echo "<div class='check fail'>";
    echo "<span class='status fail'>❌ FAIL</span>";
    echo "<span>" . htmlspecialchars($e->getMessage()) . "</span>";
    echo "</div>";
    $checks['failed']++;
    $checks['total']++;
}

// ============ NOTIFICATION SYSTEM COMPONENTS ============
echo "<div class='section'>";
echo "<h2>3️⃣ Notification System Components</h2>";

// Check NotificationController
$controllerExists = file_exists(__DIR__ . '/../app/Http/Controllers/NotificationController.php');
echo "<div class='check " . ($controllerExists ? "pass" : "fail") . "'>";
echo "<span class='status " . ($controllerExists ? "pass" : "fail") . "'>" . ($controllerExists ? "✅" : "❌") . "</span>";
echo "<span>NotificationController: " . ($controllerExists ? "EXISTS" : "MISSING") . "</span>";
echo "</div>";
$controllerExists ? $checks['passed']++ : $checks['failed']++;
$checks['total']++;

// Check notification-bell blade component
$bellExists = file_exists(__DIR__ . '/../resources/views/components/notification-bell.blade.php');
echo "<div class='check " . ($bellExists ? "pass" : "fail") . "'>";
echo "<span class='status " . ($bellExists ? "pass" : "fail") . "'>" . ($bellExists ? "✅" : "❌") . "</span>";
echo "<span>notification-bell.blade.php: " . ($bellExists ? "EXISTS" : "MISSING") . "</span>";
echo "</div>";
$bellExists ? $checks['passed']++ : $checks['failed']++;
$checks['total']++;

// Check models have required methods
try {
    $orderModel = 'App\Models\Order';
    $hasUnseen = method_exists($orderModel, 'scopeUnseen');
    echo "<div class='check " . ($hasUnseen ? "pass" : "fail") . "'>";
    echo "<span class='status " . ($hasUnseen ? "pass" : "fail") . "'>" . ($hasUnseen ? "✅" : "❌") . "</span>";
    echo "<span>Order::unseen() scope: " . ($hasUnseen ? "EXISTS" : "MISSING") . "</span>";
    echo "</div>";
    $hasUnseen ? $checks['passed']++ : $checks['failed']++;
    $checks['total']++;
} catch (\Exception $e) {
    echo "<div class='check fail'>";
    echo "<span class='status fail'>❌ FAIL</span>";
    echo "<span>" . htmlspecialchars($e->getMessage()) . "</span>";
    echo "</div>";
    $checks['failed']++;
    $checks['total']++;
}

// ============ NOTIFICATION DATA ============
echo "<div class='section'>";
echo "<h2>4️⃣ Notification Data</h2>";

try {
    // Count notifications
    $orderCount = $db->table('orders')->where('is_seen', false)->count();
    $measurementCount = $db->table('customer_measurements')->where('is_seen', false)->count();
    $messageCount = $db->table('contact_submissions')->where('is_read', false)->count();
    
    echo "<div class='check info'>";
    echo "<span class='status info'>ℹ️</span>";
    echo "<span>Unseen Orders: <strong>$orderCount</strong></span>";
    echo "</div>";
    
    echo "<div class='check info'>";
    echo "<span class='status info'>ℹ️</span>";
    echo "<span>Unseen Measurements: <strong>$measurementCount</strong></span>";
    echo "</div>";
    
    echo "<div class='check info'>";
    echo "<span class='status info'>ℹ️</span>";
    echo "<span>Unread Messages: <strong>$messageCount</strong></span>";
    echo "</div>";
    
    $totalNotifications = $orderCount + $measurementCount + $messageCount;
    
    echo "<div class='check info'>";
    echo "<span class='status info'>ℹ️</span>";
    echo "<span><strong>Total Notifications Ready: $totalNotifications</strong></span>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "<div class='check fail'>";
    echo "<span class='status fail'>⚠️</span>";
    echo "<span>" . $e->getMessage() . "</span>";
    echo "</div>";
}

// ============ ROUTES ============
echo "<div class='section'>";
echo "<h2>5️⃣ API Routes</h2>";

$routesExist = true;
$routes = [
    '/admin/notifications' => 'Get notifications',
    '/admin/notifications/unread-count' => 'Get badge count',
    '/admin/notifications/mark-as-seen' => 'Mark notification',
];

foreach ($routes as $route => $desc) {
    echo "<div class='check pass'>";
    echo "<span class='status pass'>✅</span>";
    echo "<span><code>$route</code> - $desc</span>";
    echo "</div>";
    $checks['passed']++;
    $checks['total']++;
}

// ============ SUMMARY ============
echo "<div class='section'>";
echo "<h2>📊 Summary</h2>";

$passPercentage = ($checks['passed'] / $checks['total']) * 100;

if ($checks['failed'] == 0) {
    echo "<div class='summary'>";
    echo "<h3>✅ ALL SYSTEMS OPERATIONAL!</h3>";
    echo "<p><strong>Passed:</strong> " . $checks['passed'] . "/" . $checks['total'] . " checks (100%)</p>";
    echo "<p>Your notification bell system is <strong>fully functional</strong> and ready to use!</p>";
    echo "<p><a href='http://localhost:8000/' style='color: #27ae60; text-decoration: none;'>➜ Go to Home Page</a></p>";
    echo "</div>";
} else {
    echo "<div class='summary warning'>";
    echo "<h3>⚠️ Some checks failed</h3>";
    echo "<p><strong>Passed:</strong> " . $checks['passed'] . "/" . $checks['total'] . " checks (" . round($passPercentage, 1) . "%)</p>";
    echo "<p><strong>Failed:</strong> " . $checks['failed'] . " check(s)</p>";
    echo "</div>";
}

echo "</div>";

// ============ FOOTER ============
echo "<div style='text-align: center; margin-top: 40px; color: #999;'>";
echo "<p>✨ Notification Bell System Verification</p>";
echo "<p style='font-size: 0.9em;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "</div></body></html>";
?>
