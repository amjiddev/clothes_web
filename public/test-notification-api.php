<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    echo "=== TESTING NOTIFICATION API ===\n\n";
    
    // Simulate the controller logic
    $allNotifications = [];
    $errors = [];
    
    echo "Step 1: Generating routes...\n";
    $adminOrderShowRoute = null;
    $adminContactShowRoute = null;
    $measurementRoute = null;
    
    try {
        $adminOrderShowRoute = route('admin.orders.show', ['id' => ':ID:']);
        echo "✅ admin.orders.show route: $adminOrderShowRoute\n";
    } catch (\Exception $e) {
        $adminOrderShowRoute = '/admin/orders/:ID:';
        echo "⚠️ Fallback for admin.orders.show: $adminOrderShowRoute\n";
    }
    
    try {
        $adminContactShowRoute = route('admin.contact-submissions.show', ['contact_submission' => ':ID:']);
        echo "✅ admin.contact-submissions.show route: $adminContactShowRoute\n";
    } catch (\Exception $e) {
        $adminContactShowRoute = '/admin/contact-submissions/:ID:';
        echo "⚠️ Fallback for admin.contact-submissions.show: $adminContactShowRoute\n";
    }
    
    try {
        $measurementRoute = route('receptionist.measurements.show', ['measurement' => ':ID:']);
        echo "✅ receptionist.measurements.show route: $measurementRoute\n";
    } catch (\Exception $e) {
        try {
            $measurementRoute = route('admin.measurements.show', ['id' => ':ID:']);
            echo "✅ admin.measurements.show route: $measurementRoute\n";
        } catch (\Exception $e2) {
            $measurementRoute = '/receptionist/measurements/:ID:';
            echo "⚠️ Fallback for measurements route: $measurementRoute\n";
        }
    }
    
    echo "\nStep 2: Querying unseen orders...\n";
    $unseenOrders = \App\Models\Order::unseen()
        ->with('user')
        ->latest('created_at')
        ->limit(5)
        ->get();
    
    echo "Found " . count($unseenOrders) . " unseen orders\n";
    foreach ($unseenOrders as $order) {
        echo "  - Order #{$order->id}: {$order->order_number}\n";
    }
    
    echo "\nStep 3: Querying unread contact messages...\n";
    $messages = \App\Models\ContactSubmission::unread()
        ->where('type', 'message')
        ->latest('created_at')
        ->limit(5)
        ->get();
    echo "Found " . count($messages) . " unread messages\n";
    
    echo "\nStep 4: Querying unread tailoring requests...\n";
    $tailoringRequests = \App\Models\ContactSubmission::unread()
        ->where('type', 'tailoring_request')
        ->latest('created_at')
        ->limit(5)
        ->get();
    echo "Found " . count($tailoringRequests) . " unread tailoring requests\n";
    
    echo "\nStep 5: Querying unseen measurements...\n";
    $unseenMeasurements = \App\Models\CustomerMeasurement::unseen()
        ->with('user')
        ->latest('created_at')
        ->limit(5)
        ->get();
    echo "Found " . count($unseenMeasurements) . " unseen measurements\n";
    
    echo "\n=== SUMMARY ===\n";
    echo "Orders: " . count($unseenOrders) . "\n";
    echo "Messages: " . count($messages) . "\n";
    echo "Tailoring Requests: " . count($tailoringRequests) . "\n";
    echo "Measurements: " . count($unseenMeasurements) . "\n";
    echo "Total: " . (count($unseenOrders) + count($messages) + count($tailoringRequests) + count($unseenMeasurements)) . "\n";
    
    echo "\n✅ API simulation completed successfully\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
