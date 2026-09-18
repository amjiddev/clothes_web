<?php
/**
 * Comprehensive Notification System Diagnostic
 * This script checks everything without requiring Artisan to work
 */

header('Content-Type: text/plain');

$issues = [];
$checks = [];

echo "🔍 NOTIFICATION BELL SYSTEM DIAGNOSTIC\n";
echo str_repeat("=", 50) . "\n\n";

// 1. Check files exist
$requiredFiles = [
    'app/Http/Controllers/NotificationController.php' => 'NotificationController',
    'resources/views/components/notification-bell.blade.php' => 'Bell component',
    'app/Models/Order.php' => 'Order model',
    'app/Models/CustomerMeasurement.php' => 'CustomerMeasurement model',
    'app/Models/ContactSubmission.php' => 'ContactSubmission model',
];

echo "1️⃣  FILE EXISTENCE CHECK\n";
foreach ($requiredFiles as $file => $desc) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "   ✅ $desc\n";
        $checks[] = true;
    } else {
        echo "   ❌ MISSING: $desc at $file\n";
        $issues[] = "Missing file: $file";
        $checks[] = false;
    }
}
echo "\n";

// 2. Check routes are defined
echo "2️⃣  ROUTE DEFINITIONS CHECK\n";
$adminRoutesFile = __DIR__ . '/routes/admin.php';
$receptionistRoutesFile = __DIR__ . '/routes/receptionist.php';

$adminRoutes = file_exists($adminRoutesFile) ? file_get_contents($adminRoutesFile) : '';
$receptionistRoutes = file_exists($receptionistRoutesFile) ? file_get_contents($receptionistRoutesFile) : '';

$routeChecks = [
    'NotificationController' => 'Controller imported',
    '/notifications' => 'Route group defined',
    'getNotifications' => 'getNotifications endpoint',
    'getUnreadCount' => 'getUnreadCount endpoint',
    'markAsSeen' => 'markAsSeen endpoint',
];

foreach ($routeChecks as $pattern => $desc) {
    $found = (strpos($adminRoutes, $pattern) !== false || strpos($receptionistRoutes, $pattern) !== false);
    if ($found) {
        echo "   ✅ $desc\n";
        $checks[] = true;
    } else {
        echo "   ❌ NOT FOUND: $desc\n";
        $issues[] = "Route pattern not found: $pattern";
        $checks[] = false;
    }
}
echo "\n";

// 3. Check model methods
echo "3️⃣  MODEL METHODS CHECK\n";
$modelMethods = [
    'app/Models/Order.php' => ['unseen', 'markAsSeen', 'isSeen'],
    'app/Models/CustomerMeasurement.php' => ['unseen', 'markAsSeen', 'isSeen'],
    'app/Models/ContactSubmission.php' => ['unread', 'markAsRead', 'isRead'],
];

foreach ($modelMethods as $file => $methods) {
    $content = file_get_contents(__DIR__ . '/' . $file);
    $modelName = basename($file, '.php');
    foreach ($methods as $method) {
        if (strpos($content, "function $method(") !== false || strpos($content, "public function $method") !== false) {
            echo "   ✅ $modelName::$method()\n";
            $checks[] = true;
        } else {
            echo "   ❌ MISSING: $modelName::$method()\n";
            $issues[] = "Missing method: $modelName::$method()";
            $checks[] = false;
        }
    }
}
echo "\n";

// 4. Check migrations exist
echo "4️⃣  MIGRATION FILES CHECK\n";
$migrations = [
    'database/migrations/2026_09_14_000001_add_is_seen_to_orders_table.php',
    'database/migrations/2026_09_14_000002_add_is_seen_to_customer_measurements_table.php',
];

foreach ($migrations as $mig) {
    $fullPath = __DIR__ . '/' . $mig;
    if (file_exists($fullPath)) {
        echo "   ✅ Migration created: " . basename($mig) . "\n";
        $checks[] = true;
    } else {
        echo "   ⚠️  Migration file missing: $mig\n";
        // Not an immediate issue if DB already has columns
        $checks[] = null;
    }
}
echo "\n";

// 5. Check blade component includes
echo "5️⃣  BLADE COMPONENT INCLUDES CHECK\n";
$topbarFiles = [
    'resources/views/admin/components/topbar.blade.php',
    'resources/views/receptionist/layouts/app.blade.php',
];

foreach ($topbarFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        if (strpos($content, "notification-bell") !== false) {
            echo "   ✅ " . basename(dirname($file)) . " includes notification bell\n";
            $checks[] = true;
        } else {
            echo "   ❌ " . basename(dirname($file)) . " does NOT include notification bell\n";
            $issues[] = "Missing notification bell include in: $file";
            $checks[] = false;
        }
    }
}
echo "\n";

// 6. Check for PSR-4 compliance
echo "6️⃣  PSR-4 AUTOLOAD COMPLIANCE CHECK\n";
$componentDir = __DIR__ . '/app/View/Components';
if (is_dir($componentDir)) {
    $files = glob($componentDir . '/*.php');
    $psr4Issues = [];
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            $className = $matches[1];
            $expectedName = $className . '.php';
            $actualName = basename($file);
            
            if ($expectedName !== $actualName) {
                $psr4Issues[] = "$actualName (should be $expectedName)";
                $checks[] = false;
            }
        }
    }
    
    if (empty($psr4Issues)) {
        echo "   ✅ All View Components follow PSR-4\n";
        $checks[] = true;
    } else {
        echo "   ❌ PSR-4 violations found:\n";
        foreach ($psr4Issues as $issue) {
            echo "      - $issue\n";
            $issues[] = "PSR-4 violation: $issue";
        }
    }
}
echo "\n";

// Summary
echo str_repeat("=", 50) . "\n";
echo "📊 SUMMARY\n";

$totalChecks = count($checks);
$passedChecks = array_sum(array_map(fn($c) => $c === true ? 1 : 0, $checks));
$failedChecks = array_sum(array_map(fn($c) => $c === false ? 1 : 0, $checks));
$warningChecks = array_sum(array_map(fn($c) => $c === null ? 1 : 0, $checks));

echo "   Passed: $passedChecks/$totalChecks\n";
if ($failedChecks > 0) echo "   Failed: $failedChecks\n";
if ($warningChecks > 0) echo "   Warnings: $warningChecks\n";

if (!empty($issues)) {
    echo "\n⚠️  ISSUES FOUND:\n";
    foreach ($issues as $idx => $issue) {
        echo "   " . ($idx + 1) . ". $issue\n";
    }
    echo "\n🔧 RECOMMENDATIONS:\n";
    echo "   1. Run: php artisan composer dump-autoload -o\n";
    echo "   2. Run: php artisan cache:clear\n";
    echo "   3. Run: php artisan route:clear\n";
    echo "   4. Run: php artisan migrate (if not already run)\n";
    echo "   5. Hard refresh browser: Ctrl+Shift+R or Cmd+Shift+R\n";
} else {
    echo "\n✅ ALL CHECKS PASSED!\n";
}

echo "\n";
