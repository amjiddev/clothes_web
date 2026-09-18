<!DOCTYPE html>
<html>
<head>
    <title>Database Data Check</title>
    <style>
        body { font-family: monospace; background: #1e1e1e; color: #00ff88; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #00ff88; padding: 10px; text-align: left; }
        th { background: #003300; }
        .count { font-weight: bold; color: #ffaa00; }
    </style>
</head>
<body>
    <h1>🗄️ Database Data Check</h1>
    
    <?php
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->handle(Illuminate\Http\Request::capture());
        
        $db = $app->make('db');
        
        // Check unseen orders
        echo "<h2>📦 Unseen Orders</h2>";
        $unseenOrders = $db->table('orders')->where('is_seen', false)->get();
        echo "<p class='count'>Count: " . count($unseenOrders) . "</p>";
        if (count($unseenOrders) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Order Number</th><th>is_seen</th><th>seen_at</th></tr>";
            foreach ($unseenOrders as $order) {
                echo "<tr><td>" . $order->id . "</td><td>" . $order->order_number . "</td><td>" . ($order->is_seen ? 'Yes' : 'No') . "</td><td>" . $order->seen_at . "</td></tr>";
            }
            echo "</table>";
        }
        
        // Check unread messages
        echo "<h2>✉️ Unread Messages</h2>";
        $unreadMessages = $db->table('contact_submissions')->where('is_read', false)->where('type', 'message')->get();
        echo "<p class='count'>Count: " . count($unreadMessages) . "</p>";
        if (count($unreadMessages) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>is_read</th><th>read_at</th></tr>";
            foreach ($unreadMessages as $msg) {
                echo "<tr><td>" . $msg->id . "</td><td>" . $msg->full_name . "</td><td>" . $msg->email . "</td><td>" . ($msg->is_read ? 'Yes' : 'No') . "</td><td>" . $msg->read_at . "</td></tr>";
            }
            echo "</table>";
        }
        
        // Check unseen measurements
        echo "<h2>📏 Unseen Measurements</h2>";
        $unseenMeasurements = $db->table('customer_measurements')->where('is_seen', false)->get();
        echo "<p class='count'>Count: " . count($unseenMeasurements) . "</p>";
        if (count($unseenMeasurements) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>User ID</th><th>Profile Name</th><th>is_seen</th><th>seen_at</th></tr>";
            foreach ($unseenMeasurements as $meas) {
                echo "<tr><td>" . $meas->id . "</td><td>" . $meas->user_id . "</td><td>" . $meas->profile_name . "</td><td>" . ($meas->is_seen ? 'Yes' : 'No') . "</td><td>" . $meas->seen_at . "</td></tr>";
            }
            echo "</table>";
        }
        
        echo "<h2>✅ Summary</h2>";
        echo "<p>Unseen Orders: " . count($unseenOrders) . "</p>";
        echo "<p>Unread Messages: " . count($unreadMessages) . "</p>";
        echo "<p>Unseen Measurements: " . count($unseenMeasurements) . "</p>";
        echo "<p class='count'>Total: " . (count($unseenOrders) + count($unreadMessages) + count($unseenMeasurements)) . "</p>";
        
    } catch (\Exception $e) {
        echo "<p style='color: #ff4444;'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
