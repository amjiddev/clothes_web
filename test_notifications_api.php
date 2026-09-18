<?php
/**
 * Quick API Test Script for Notification Bell Endpoints
 * Place in project root and run: php test_notifications_api.php
 */

// Check if Laravel bootstrapping works
try {
    require __DIR__ . '/bootstrap/app.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    echo "✅ Laravel app initialized\n";
    
    // Check if models are accessible
    $order = new \App\Models\Order();
    echo "✅ Order model accessible\n";
    
    $measurement = new \App\Models\CustomerMeasurement();
    echo "✅ CustomerMeasurement model accessible\n";
    
    $contact = new \App\Models\ContactSubmission();
    echo "✅ ContactSubmission model accessible\n";
    
    // Check if routes are registered
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    echo "\n📍 Looking for notification routes...\n";
    
    $notificationRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'notifications') !== false) {
            $notificationRoutes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->action['uses'] ?? 'Closure',
            ];
        }
    }
    
    if (empty($notificationRoutes)) {
        echo "❌ NO NOTIFICATION ROUTES FOUND!\n";
        echo "   Check if routes/admin.php and routes/receptionist.php are properly registered\n";
    } else {
        echo "✅ Found " . count($notificationRoutes) . " notification routes:\n";
        foreach ($notificationRoutes as $route) {
            echo "   " . $route['method'] . " /" . $route['uri'] . "\n";
            echo "      Action: " . $route['action'] . "\n";
        }
    }
    
    // Check if NotificationController exists
    if (class_exists(\App\Http\Controllers\NotificationController::class)) {
        echo "\n✅ NotificationController exists at: App\Http\Controllers\NotificationController\n";
        
        // Check if controller methods exist
        $methods = ['getNotifications', 'getUnreadCount', 'markAsSeen', 'markAllAsSeen', 'getNotificationDetails'];
        $controller = new \App\Http\Controllers\NotificationController();
        foreach ($methods as $method) {
            if (method_exists($controller, $method)) {
                echo "   ✅ $method() method exists\n";
            } else {
                echo "   ❌ $method() method MISSING\n";
            }
        }
    } else {
        echo "\n❌ NotificationController NOT FOUND!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
