<?php
/**
 * Public Debug Endpoint for Notification System
 * Access: http://localhost:8000/debug-notifications.php
 */

// Bootstrap Laravel
require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Order;
use App\Models\ContactSubmission;
use App\Models\CustomerMeasurement;
use Illuminate\Support\Facades\DB;

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    $report = [
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => 'Notification System Debug Report',
    ];

    // ======================
    // 1. DATABASE CONNECTION
    // ======================
    echo "<h2>1. Database Connection</h2>";
    try {
        $db = DB::connection()->getDatabaseName();
        echo "<p>✅ Connected to database: <strong>$db</strong></p>";
        $report['database']['status'] = 'connected';
        $report['database']['name'] = $db;
    } catch (Exception $e) {
        echo "<p>❌ Database connection failed: {$e->getMessage()}</p>";
        $report['database']['status'] = 'failed';
        $report['database']['error'] = $e->getMessage();
    }

    // ======================
    // 2. TABLE COUNTS
    // ======================
    echo "<h2>2. Data Count in Tables</h2>";
    $tables_data = [
        'orders' => ['total' => 0, 'is_seen_false' => 0],
        'customer_measurements' => ['total' => 0, 'is_seen_false' => 0],
        'contact_submissions' => ['total' => 0, 'is_read_false' => 0],
    ];

    foreach ($tables_data as $table => $data) {
        try {
            $total = DB::table($table)->count();
            echo "<p><strong>$table:</strong> $total total records";

            if ($table === 'orders') {
                $unseen = DB::table($table)->where('is_seen', false)->count();
                echo " | $unseen unseen";
                $tables_data[$table]['is_seen_false'] = $unseen;
            } elseif ($table === 'customer_measurements') {
                $unseen = DB::table($table)->where('is_seen', false)->count();
                echo " | $unseen unseen";
                $tables_data[$table]['is_seen_false'] = $unseen;
            } elseif ($table === 'contact_submissions') {
                $unread = DB::table($table)->where('is_read', false)->count();
                $messages = DB::table($table)->where('type', 'message')->count();
                $tailoring = DB::table($table)->where('type', 'tailoring_request')->count();
                echo " | $unread unread | $messages messages | $tailoring tailoring";
                $tables_data[$table]['is_read_false'] = $unread;
                $tables_data[$table]['messages'] = $messages;
                $tables_data[$table]['tailoring'] = $tailoring;
            }

            $tables_data[$table]['total'] = $total;
            echo "</p>";
        } catch (Exception $e) {
            echo "<p>❌ Error querying $table: {$e->getMessage()}</p>";
        }
    }
    $report['tables'] = $tables_data;

    // ======================
    // 3. COLUMN CHECKS
    // ======================
    echo "<h2>3. Required Columns</h2>";
    $columns_check = [];

    try {
        $cols = DB::select("SHOW COLUMNS FROM orders");
        $col_names = collect($cols)->pluck('Field')->toArray();
        $has_is_seen = in_array('is_seen', $col_names);
        echo "<p><strong>orders table:</strong> " . ($has_is_seen ? "✅ has 'is_seen' column" : "❌ missing 'is_seen' column") . "</p>";
        $columns_check['orders_is_seen'] = $has_is_seen;
    } catch (Exception $e) {
        echo "<p>❌ Could not check orders columns: {$e->getMessage()}</p>";
    }

    try {
        $cols = DB::select("SHOW COLUMNS FROM customer_measurements");
        $col_names = collect($cols)->pluck('Field')->toArray();
        $has_is_seen = in_array('is_seen', $col_names);
        echo "<p><strong>customer_measurements table:</strong> " . ($has_is_seen ? "✅ has 'is_seen' column" : "❌ missing 'is_seen' column") . "</p>";
        $columns_check['measurements_is_seen'] = $has_is_seen;
    } catch (Exception $e) {
        echo "<p>❌ Could not check measurements columns: {$e->getMessage()}</p>";
    }

    try {
        $cols = DB::select("SHOW COLUMNS FROM contact_submissions");
        $col_names = collect($cols)->pluck('Field')->toArray();
        $has_is_read = in_array('is_read', $col_names);
        echo "<p><strong>contact_submissions table:</strong> " . ($has_is_read ? "✅ has 'is_read' column" : "❌ missing 'is_read' column") . "</p>";
        $columns_check['contact_is_read'] = $has_is_read;
    } catch (Exception $e) {
        echo "<p>❌ Could not check contact_submissions columns: {$e->getMessage()}</p>";
    }
    $report['columns'] = $columns_check;

    // ======================
    // 4. SAMPLE DATA
    // ======================
    echo "<h2>4. Sample Data</h2>";

    try {
        $order = Order::with('user')->first();
        if ($order) {
            echo "<p><strong>Sample Order:</strong>";
            echo "<ul>";
            echo "<li>ID: {$order->id}</li>";
            echo "<li>Order Number: {$order->order_number}</li>";
            echo "<li>is_seen: " . ($order->is_seen ? 'true' : 'false') . "</li>";
            echo "<li>User ID: {$order->user_id}</li>";
            echo "<li>User Name: {$order->user?->name}</li>";
            echo "</ul></p>";
        } else {
            echo "<p>No orders found in database</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error fetching sample order: {$e->getMessage()}</p>";
    }

    try {
        $contact = ContactSubmission::first();
        if ($contact) {
            echo "<p><strong>Sample Contact Submission:</strong>";
            echo "<ul>";
            echo "<li>ID: {$contact->id}</li>";
            echo "<li>Type: {$contact->type}</li>";
            echo "<li>Name: {$contact->full_name}</li>";
            echo "<li>is_read: " . ($contact->is_read ? 'true' : 'false') . "</li>";
            echo "</ul></p>";
        } else {
            echo "<p>No contact submissions found in database</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error fetching sample contact: {$e->getMessage()}</p>";
    }

    try {
        $measurement = CustomerMeasurement::with('user')->first();
        if ($measurement) {
            echo "<p><strong>Sample Measurement:</strong>";
            echo "<ul>";
            echo "<li>ID: {$measurement->id}</li>";
            echo "<li>Profile: {$measurement->profile_name}</li>";
            echo "<li>is_seen: " . ($measurement->is_seen ? 'true' : 'false') . "</li>";
            echo "<li>User ID: {$measurement->user_id}</li>";
            echo "<li>User Name: {$measurement->user?->name}</li>";
            echo "</ul></p>";
        } else {
            echo "<p>No measurements found in database</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error fetching sample measurement: {$e->getMessage()}</p>";
    }

    // ======================
    // 5. SCOPE TESTS
    // ======================
    echo "<h2>5. Model Scopes Test</h2>";

    try {
        $unseen = Order::unseen()->count();
        echo "<p>✅ Order::unseen() scope works - returned $unseen records</p>";
    } catch (Exception $e) {
        echo "<p>❌ Order::unseen() scope failed: {$e->getMessage()}</p>";
    }

    try {
        $unread = ContactSubmission::unread()->count();
        echo "<p>✅ ContactSubmission::unread() scope works - returned $unread records</p>";
    } catch (Exception $e) {
        echo "<p>❌ ContactSubmission::unread() scope failed: {$e->getMessage()}</p>";
    }

    try {
        $unseen_meas = CustomerMeasurement::unseen()->count();
        echo "<p>✅ CustomerMeasurement::unseen() scope works - returned $unseen_meas records</p>";
    } catch (Exception $e) {
        echo "<p>❌ CustomerMeasurement::unseen() scope failed: {$e->getMessage()}</p>";
    }

    // ======================
    // 6. API ENDPOINT TEST
    // ======================
    echo "<h2>6. Test Notification API Endpoint</h2>";
    echo "<p>Visit: <a href='/admin/notifications' target='_blank'>/admin/notifications</a> (requires authentication)</p>";

} catch (Exception $e) {
    echo "<h2>Fatal Error</h2>";
    echo "<p><strong>Error:</strong> {$e->getMessage()}</p>";
    echo "<p><strong>File:</strong> {$e->getFile()}</p>";
    echo "<p><strong>Line:</strong> {$e->getLine()}</p>";
    echo "<pre>{$e->getTraceAsString()}</pre>";
}

?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    p { margin: 10px 0; }
    ul { margin: 10px 0 10px 20px; }
    li { margin: 5px 0; }
    strong { color: #007bff; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .error { color: #dc3545; }
    .success { color: #28a745; }
</style>
