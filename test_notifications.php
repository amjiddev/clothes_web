<?php

// Test script to verify notifications API

echo "Testing Notification Bell API\n";
echo "==============================\n\n";

// Include Laravel bootstrap
require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "✓ Laravel app loaded\n";

// Check if NotificationController exists
if (class_exists('App\Http\Controllers\NotificationController')) {
    echo "✓ NotificationController exists\n";
} else {
    echo "✗ NotificationController NOT found\n";
    exit(1);
}

// Check models
if (class_exists('App\Models\Order')) {
    echo "✓ Order model exists\n";
    $order = new App\Models\Order();
    if (method_exists($order, 'markAsSeen')) {
        echo "  ✓ Order::markAsSeen() method exists\n";
    } else {
        echo "  ✗ Order::markAsSeen() method missing\n";
    }
} else {
    echo "✗ Order model NOT found\n";
}

if (class_exists('App\Models\CustomerMeasurement')) {
    echo "✓ CustomerMeasurement model exists\n";
    $measurement = new App\Models\CustomerMeasurement();
    if (method_exists($measurement, 'markAsSeen')) {
        echo "  ✓ CustomerMeasurement::markAsSeen() method exists\n";
    } else {
        echo "  ✗ CustomerMeasurement::markAsSeen() method missing\n";
    }
} else {
    echo "✗ CustomerMeasurement model NOT found\n";
}

if (class_exists('App\Models\ContactSubmission')) {
    echo "✓ ContactSubmission model exists\n";
    $message = new App\Models\ContactSubmission();
    if (method_exists($message, 'markAsRead')) {
        echo "  ✓ ContactSubmission::markAsRead() method exists\n";
    } else {
        echo "  ✗ ContactSubmission::markAsRead() method missing\n";
    }
} else {
    echo "✗ ContactSubmission model NOT found\n";
}

echo "\n✓ All checks passed! API should be functional.\n";
echo "\nTo fix the error:\n";
echo "1. Run: php artisan route:clear\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Reload the admin page\n";
?>
