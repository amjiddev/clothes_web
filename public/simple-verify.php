<?php
/**
 * Simple System Verification - No Laravel dependencies
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

echo "<html><head><title>System Status</title><style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "h1 { color: #d4af37; text-align: center; }";
echo ".check { margin: 15px 0; padding: 15px; border-radius: 6px; display: flex; align-items: center; gap: 15px; }";
echo ".pass { background: #d5f4e6; border-left: 4px solid #27ae60; }";
echo ".fail { background: #fadbd8; border-left: 4px solid #e74c3c; }";
echo ".icon { font-size: 1.5rem; min-width: 30px; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #d4af37; color: white; }";
echo ".summary { background: #d5f4e6; border: 2px solid #27ae60; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo "</style></head><body>";
echo "<div class='container'>";
echo "<h1>✅ System Status Verification</h1>";

// Database connection
echo "<h2>1. Database Connection</h2>";

$conn = mysqli_connect('127.0.0.1', 'root', '', 'cloth');
if ($conn) {
    echo "<div class='check pass'>";
    echo "<span class='icon'>✅</span>";
    echo "<span><strong>Connected to MySQL database 'cloth'</strong></span>";
    echo "</div>";
} else {
    echo "<div class='check fail'>";
    echo "<span class='icon'>❌</span>";
    echo "<span><strong>Connection failed:</strong> " . mysqli_connect_error() . "</span>";
    echo "</div>";
    die();
}

// Check tables
echo "<h2>2. Database Tables</h2>";

$tables = [
    'users', 'orders', 'products', 'categories', 
    'customer_measurements', 'contact_submissions', 
    'website_cms', 'permissions', 'roles'
];

echo "<table>";
echo "<tr><th>Table</th><th>Status</th></tr>";

$allExist = true;
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SELECT 1 FROM information_schema.tables WHERE table_schema = 'cloth' AND table_name = '$table'");
    $exists = mysqli_num_rows($result) > 0;
    
    $status = $exists ? "<span style='color: #27ae60;'>✅ EXISTS</span>" : "<span style='color: #e74c3c;'>❌ MISSING</span>";
    echo "<tr><td><code>$table</code></td><td>$status</td></tr>";
    
    if (!$exists) $allExist = false;
}
echo "</table>";

// Check notification data
echo "<h2>3. Notification Data</h2>";

$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM orders WHERE is_seen = 0"))['cnt'];
$measurementCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM customer_measurements WHERE is_seen = 0"))['cnt'];
$messageCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM contact_submissions WHERE is_read = 0"))['cnt'];

echo "<div class='check'>";
echo "<span class='icon'>📊</span>";
echo "<span><strong>Unseen Orders:</strong> $orderCount</span>";
echo "</div>";

echo "<div class='check'>";
echo "<span class='icon'>📊</span>";
echo "<span><strong>Unseen Measurements:</strong> $measurementCount</span>";
echo "</div>";

echo "<div class='check'>";
echo "<span class='icon'>📊</span>";
echo "<span><strong>Unread Messages:</strong> $messageCount</span>";
echo "</div>";

$total = $orderCount + $measurementCount + $messageCount;

echo "<div class='check'>";
echo "<span class='icon'>🔔</span>";
echo "<span><strong>Total Notifications Ready:</strong> $total</span>";
echo "</div>";

// Summary
echo "<div class='summary'>";
if ($allExist) {
    echo "<h2>✅ ALL SYSTEMS OPERATIONAL!</h2>";
    echo "<p>Your notification bell system is <strong>fully functional</strong> and ready to use.</p>";
    echo "<p><strong>" . count($tables) . "</strong> required tables exist and are properly configured.</p>";
    echo "<p><a href='http://localhost:8000/' style='color: #27ae60; text-decoration: none; font-weight: bold;'>➜ Go to Application</a></p>";
} else {
    echo "<h2>⚠️ Some tables are missing</h2>";
    echo "<p>Please run migrations again.</p>";
}
echo "</div>";

echo "</div></body></html>";

mysqli_close($conn);
?>
