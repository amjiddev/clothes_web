<?php
/**
 * Direct Query Testing Script
 * Tests the exact queries used by NotificationController
 * Access: http://localhost:8000/test-queries.php
 */

require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\ContactSubmission;
use App\Models\CustomerMeasurement;

$results = [];
$timestamp = date('Y-m-d H:i:s');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Query Test Results</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #1e1e1e; color: #d4d4d4; }
        .section { margin: 20px 0; padding: 15px; background: #252526; border-left: 4px solid #007acc; }
        .title { color: #4ec9b0; font-weight: bold; font-size: 14px; margin-bottom: 10px; }
        .query { background: #1e1e1e; padding: 10px; margin: 10px 0; border-radius: 3px; font-size: 12px; overflow-x: auto; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .info { color: #9cdcfe; }
        .data { background: #1e1e1e; padding: 10px; margin: 5px 0; border-radius: 3px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 12px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #3e3e42; }
        th { background: #2d2d30; color: #4ec9b0; }
    </style>
</head>
<body>

<h1>🔧 Query Test Results</h1>
<p>Testing: $timestamp</p>
";

try {
    // ============================================================================
    // TEST 1: RAW SQL - Check if tables exist and have data
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 1: Table Existence & Record Count</div>";

    foreach (['orders', 'customer_measurements', 'contact_submissions'] as $table) {
        try {
            $count = DB::table($table)->count();
            echo "<div class='data'><span class='success'>✓</span> <span class='info'>$table</span>: <span class='success'>$count records</span></div>";
        } catch (Exception $e) {
            echo "<div class='data'><span class='error'>✗ $table: " . $e->getMessage() . "</span></div>";
        }
    }

    echo "</div>";

    // ============================================================================
    // TEST 2: Check if required columns exist
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 2: Column Existence Check</div>";

    try {
        $columns = DB::select("SHOW COLUMNS FROM orders");
        $col_names = collect($columns)->pluck('Field')->toArray();
        $has_is_seen = in_array('is_seen', $col_names);
        $status = $has_is_seen ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
        echo "<div class='data'>orders.is_seen: $status</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>Error checking orders: " . $e->getMessage() . "</span></div>";
    }

    try {
        $columns = DB::select("SHOW COLUMNS FROM customer_measurements");
        $col_names = collect($columns)->pluck('Field')->toArray();
        $has_is_seen = in_array('is_seen', $col_names);
        $status = $has_is_seen ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
        echo "<div class='data'>customer_measurements.is_seen: $status</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>Error: " . $e->getMessage() . "</span></div>";
    }

    try {
        $columns = DB::select("SHOW COLUMNS FROM contact_submissions");
        $col_names = collect($columns)->pluck('Field')->toArray();
        $has_is_read = in_array('is_read', $col_names);
        $status = $has_is_read ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
        echo "<div class='data'>contact_submissions.is_read: $status</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>Error: " . $e->getMessage() . "</span></div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 3: Raw SQL queries
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 3: Raw SQL Queries</div>";

    // Orders
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>3A. Orders Query</strong></p>";
    try {
        $query = "SELECT COUNT(*) as total, COUNT(CASE WHEN is_seen = 0 THEN 1 END) as unseen FROM orders LIMIT 1";
        echo "<div class='query'>$query</div>";
        $result = DB::select($query);
        echo "<div class='data'><span class='success'>✓ Result:</span> " . json_encode($result) . "</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    // Customer Measurements
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>3B. Customer Measurements Query</strong></p>";
    try {
        $query = "SELECT COUNT(*) as total, COUNT(CASE WHEN is_seen = 0 THEN 1 END) as unseen FROM customer_measurements LIMIT 1";
        echo "<div class='query'>$query</div>";
        $result = DB::select($query);
        echo "<div class='data'><span class='success'>✓ Result:</span> " . json_encode($result) . "</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    // Contact Submissions
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>3C. Contact Submissions Query</strong></p>";
    try {
        $query = "SELECT COUNT(*) as total, COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread FROM contact_submissions LIMIT 1";
        echo "<div class='query'>$query</div>";
        $result = DB::select($query);
        echo "<div class='data'><span class='success'>✓ Result:</span> " . json_encode($result) . "</div>";
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 4: Eloquent Model Queries (same as NotificationController)
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 4: Eloquent Model Queries (As Used in NotificationController)</div>";

    // Test Orders
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>4A. Order::with('user')->unseen()->latest('created_at')->limit(5)->get()</strong></p>";
    try {
        $orders = Order::with('user')->unseen()->latest('created_at')->limit(5)->get();
        echo "<div class='data'><span class='success'>✓ Returned " . count($orders) . " records</span></div>";
        
        if (count($orders) > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Order#</th><th>is_seen</th><th>User</th></tr></thead>";
            echo "<tbody>";
            foreach ($orders as $order) {
                echo "<tr>";
                echo "<td>" . $order->id . "</td>";
                echo "<td>" . $order->order_number . "</td>";
                echo "<td>" . ($order->is_seen ? 'true' : 'false') . "</td>";
                echo "<td>" . ($order->user?->name ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No unseen orders found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    // Test ContactSubmission Messages
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>4B. ContactSubmission::where('type', 'message')->unread()->latest('created_at')->limit(5)->get()</strong></p>";
    try {
        $messages = ContactSubmission::where('type', 'message')->unread()->latest('created_at')->limit(5)->get();
        echo "<div class='data'><span class='success'>✓ Returned " . count($messages) . " records</span></div>";
        
        if (count($messages) > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Type</th><th>is_read</th></tr></thead>";
            echo "<tbody>";
            foreach ($messages as $msg) {
                echo "<tr>";
                echo "<td>" . $msg->id . "</td>";
                echo "<td>" . $msg->full_name . "</td>";
                echo "<td>" . $msg->type . "</td>";
                echo "<td>" . ($msg->is_read ? 'true' : 'false') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No unread messages found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    // Test ContactSubmission Tailoring
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>4C. ContactSubmission::where('type', 'tailoring_request')->unread()->latest('created_at')->limit(5)->get()</strong></p>";
    try {
        $tailoring = ContactSubmission::where('type', 'tailoring_request')->unread()->latest('created_at')->limit(5)->get();
        echo "<div class='data'><span class='success'>✓ Returned " . count($tailoring) . " records</span></div>";
        
        if (count($tailoring) > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Type</th><th>is_read</th></tr></thead>";
            echo "<tbody>";
            foreach ($tailoring as $tail) {
                echo "<tr>";
                echo "<td>" . $tail->id . "</td>";
                echo "<td>" . $tail->full_name . "</td>";
                echo "<td>" . $tail->type . "</td>";
                echo "<td>" . ($tail->is_read ? 'true' : 'false') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No unread tailoring requests found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    // Test CustomerMeasurement
    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>4D. CustomerMeasurement::with('user')->unseen()->latest('created_at')->limit(5)->get()</strong></p>";
    try {
        $measurements = CustomerMeasurement::with('user')->unseen()->latest('created_at')->limit(5)->get();
        echo "<div class='data'><span class='success'>✓ Returned " . count($measurements) . " records</span></div>";
        
        if (count($measurements) > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Profile</th><th>is_seen</th><th>User</th></tr></thead>";
            echo "<tbody>";
            foreach ($measurements as $meas) {
                echo "<tr>";
                echo "<td>" . $meas->id . "</td>";
                echo "<td>" . $meas->profile_name . "</td>";
                echo "<td>" . ($meas->is_seen ? 'true' : 'false') . "</td>";
                echo "<td>" . ($meas->user?->name ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No unseen measurements found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 5: Test User Relationships
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 5: User Relationship Tests</div>";

    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>5A. Sample Order with User</strong></p>";
    try {
        $order = Order::with('user')->first();
        if ($order) {
            echo "<div class='data'>";
            echo "<span class='success'>✓ Order ID:</span> " . $order->id . "<br>";
            echo "<span class='success'>✓ Order Number:</span> " . $order->order_number . "<br>";
            echo "<span class='success'>✓ User ID:</span> " . $order->user_id . "<br>";
            echo "<span class='success'>✓ User Name:</span> " . ($order->user?->name ?? 'NULL - MISSING USER!') . "<br>";
            echo "</div>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No orders found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    echo "<p style='color: #4ec9b0; margin-top: 10px;'><strong>5B. Sample Measurement with User</strong></p>";
    try {
        $meas = CustomerMeasurement::with('user')->first();
        if ($meas) {
            echo "<div class='data'>";
            echo "<span class='success'>✓ Measurement ID:</span> " . $meas->id . "<br>";
            echo "<span class='success'>✓ User ID:</span> " . $meas->user_id . "<br>";
            echo "<span class='success'>✓ User Name:</span> " . ($meas->user?->name ?? 'NULL - MISSING USER!') . "<br>";
            echo "</div>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No measurements found</span></div>";
        }
    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 6: Simulate Full API Response
    // ============================================================================
    echo "<div class='section'>
        <div class='title'>TEST 6: Full API Response Simulation</div>";

    try {
        $allNotifications = [];

        // Orders
        $unseenOrders = Order::with('user')->unseen()->latest('created_at')->limit(5)->get();
        foreach ($unseenOrders as $order) {
            $allNotifications[] = [
                'type' => 'order',
                'id' => $order->id,
                'title' => 'New Order #' . $order->order_number,
                'subtitle' => 'Customer: ' . ($order->user?->name ?? 'Unknown'),
            ];
        }

        // Messages
        $messages = ContactSubmission::where('type', 'message')->unread()->latest('created_at')->limit(5)->get();
        foreach ($messages as $msg) {
            $allNotifications[] = [
                'type' => 'message',
                'id' => $msg->id,
                'title' => 'New Message from ' . $msg->full_name,
            ];
        }

        // Tailoring
        $tailoring = ContactSubmission::where('type', 'tailoring_request')->unread()->latest('created_at')->limit(5)->get();
        foreach ($tailoring as $tail) {
            $allNotifications[] = [
                'type' => 'tailoring_request',
                'id' => $tail->id,
                'title' => 'Tailoring Request from ' . $tail->full_name,
            ];
        }

        // Measurements
        $measurements = CustomerMeasurement::with('user')->unseen()->latest('created_at')->limit(5)->get();
        foreach ($measurements as $meas) {
            $allNotifications[] = [
                'type' => 'measurement',
                'id' => $meas->id,
                'title' => 'New Measurement from ' . ($meas->user?->name ?? 'Unknown'),
            ];
        }

        echo "<div class='data'>";
        echo "<span class='success'>✓ Total Notifications:</span> " . count($allNotifications) . "<br>";
        echo "</div>";

        if (count($allNotifications) > 0) {
            echo "<table>";
            echo "<thead><tr><th>Type</th><th>ID</th><th>Title</th></tr></thead>";
            echo "<tbody>";
            foreach ($allNotifications as $notif) {
                echo "<tr>";
                echo "<td>" . $notif['type'] . "</td>";
                echo "<td>" . $notif['id'] . "</td>";
                echo "<td>" . $notif['title'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='data'><span class='error'>⚠ No notifications generated</span></div>";
        }

    } catch (Exception $e) {
        echo "<div class='data'><span class='error'>✗ Error: " . $e->getMessage() . "</span></div>";
    }

    echo "</div>";

} catch (Exception $e) {
    echo "<div class='section' style='border-left-color: #f48771;'>
        <div class='title' style='color: #f48771;'>FATAL ERROR</div>
        <div class='data'><span class='error'>" . $e->getMessage() . "</span></div>
        <div class='data'><span class='error'>" . $e->getFile() . ":" . $e->getLine() . "</span></div>
    </div>";
}

echo "</body>
</html>";
