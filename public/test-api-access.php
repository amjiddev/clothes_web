<?php
/**
 * Test API Access & Permissions
 * Tests if notification API endpoints are accessible
 * Access: http://localhost:8000/test-api-access.php
 */

require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>API Access Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #333; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .test { margin: 15px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .success { border-left-color: #28a745; color: #155724; background: #d4edda; }
        .error { border-left-color: #dc3545; color: #721c24; background: #f8d7da; }
        .warning { border-left-color: #ffc107; color: #856404; background: #fff3cd; }
        .info { border-left-color: #17a2b8; color: #0c5460; background: #d1ecf1; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .endpoint { font-weight: bold; color: #007bff; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 3px; font-weight: bold; font-size: 12px; }
        .status.ok { background: #28a745; color: white; }
        .status.error { background: #dc3545; color: white; }
        .status.warning { background: #ffc107; color: black; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f5f5f5; }
    </style>
</head>
<body>

<div class='container'>
    <h1>🔒 Notification API - Access & Permissions Test</h1>
    <p>Testing if API endpoints are accessible and middleware is working correctly</p>
";

try {
    // ============================================================================
    // TEST 1: Check if notification routes exist
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 1: Route Registration Check</h2>";

    $routes = Route::getRoutes();
    $notification_routes = [];

    foreach ($routes as $route) {
        $path = $route->uri;
        if (strpos($path, 'notifications') !== false || strpos($path, 'debug') !== false) {
            $notification_routes[] = [
                'method' => implode('|', $route->methods),
                'path' => $path,
                'name' => $route->name ?? 'unnamed',
                'middleware' => implode(', ', $route->middleware()),
            ];
        }
    }

    if (count($notification_routes) > 0) {
        echo "<div class='test success'><span class='status ok'>✓ OK</span> Found " . count($notification_routes) . " notification routes</div>";
        
        echo "<table>";
        echo "<thead><tr><th>Method</th><th>Path</th><th>Name</th><th>Middleware</th></tr></thead>";
        echo "<tbody>";
        foreach ($notification_routes as $route) {
            echo "<tr>";
            echo "<td><strong>" . $route['method'] . "</strong></td>";
            echo "<td><code>" . $route['path'] . "</code></td>";
            echo "<td>" . $route['name'] . "</td>";
            echo "<td><small>" . ($route['middleware'] ?: 'none') . "</small></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='test error'><span class='status error'>✗ ERROR</span> No notification routes found!</div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 2: Check authentication status
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 2: Authentication Status</h2>";

    $authenticated = Auth::check();
    $user = Auth::user();

    if ($authenticated) {
        echo "<div class='test info'><span class='status ok'>✓</span> Authenticated as: <strong>" . $user->name . "</strong> (ID: " . $user->id . ")</div>";
        echo "<div class='test info'>✓ Auth guard: " . config('auth.defaults.guard') . "</div>";
    } else {
        echo "<div class='test warning'><span class='status warning'>⚠</span> Not authenticated (API requires authentication)</div>";
        echo "<div class='test info'>→ To test with authentication, log in first</div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 3: Check user roles/permissions
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 3: User Roles & Permissions</h2>";

    if ($authenticated) {
        echo "<div class='test info'>";
        echo "<p><strong>User Details:</strong></p>";
        echo "<ul>";
        echo "<li>ID: " . $user->id . "</li>";
        echo "<li>Email: " . $user->email . "</li>";
        echo "<li>Name: " . $user->name . "</li>";
        
        // Check roles if exists
        if (method_exists($user, 'roles')) {
            $roles = $user->roles()->pluck('name')->toArray();
            echo "<li>Roles: " . (count($roles) > 0 ? implode(', ', $roles) : 'None') . "</li>";
        }
        
        // Check if admin
        if (method_exists($user, 'hasRole')) {
            $is_admin = $user->hasRole('admin') || $user->hasRole('Super Admin');
            echo "<li>Is Admin: " . ($is_admin ? 'YES ✓' : 'NO ✗') . "</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='test warning'><span class='status warning'>⚠</span> Not authenticated - Cannot check permissions</div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 4: Middleware check
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 4: Required Middleware</h2>";

    $required_middleware = [
        'auth' => 'Authentication',
        'verified' => 'Email Verification',
        'admin.only' => 'Admin Role Check',
    ];

    foreach ($required_middleware as $middleware => $description) {
        $exists = false;
        foreach ($routes as $route) {
            if (strpos($route->uri, 'notifications') !== false || strpos($route->uri, 'debug') !== false) {
                if (in_array($middleware, $route->middleware())) {
                    $exists = true;
                    break;
                }
            }
        }

        $status = $exists ? "<span class='status ok'>✓ Applied</span>" : "<span class='status warning'>⚠ Not Applied</span>";
        echo "<div class='test info'>$middleware - $description: $status</div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 5: API Endpoint Accessibility
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 5: API Endpoint Status</h2>";

    $endpoints = [
        [
            'method' => 'GET',
            'path' => '/admin/notifications',
            'description' => 'Get all notifications',
            'requires_auth' => true,
        ],
        [
            'method' => 'GET',
            'path' => '/admin/notifications/unread-count',
            'description' => 'Get notification count',
            'requires_auth' => true,
        ],
        [
            'method' => 'POST',
            'path' => '/admin/notifications/mark-as-seen',
            'description' => 'Mark notification as seen',
            'requires_auth' => true,
        ],
        [
            'method' => 'GET',
            'path' => '/debug/notifications/simple',
            'description' => 'Simple debug check',
            'requires_auth' => false,
        ],
        [
            'method' => 'GET',
            'path' => '/admin/debug/notifications/full',
            'description' => 'Full debug report',
            'requires_auth' => true,
        ],
    ];

    echo "<table>";
    echo "<thead><tr><th>Endpoint</th><th>Description</th><th>Auth Required</th><th>Status</th></tr></thead>";
    echo "<tbody>";

    foreach ($endpoints as $endpoint) {
        $route_exists = false;
        foreach ($notification_routes as $route) {
            if (strpos($route['path'], trim($endpoint['path'], '/')) !== false) {
                $route_exists = true;
                break;
            }
        }

        $auth_req = $endpoint['requires_auth'] ? 'Yes' : 'No';
        $status = $route_exists ? "<span class='status ok'>✓ Defined</span>" : "<span class='status error'>✗ Missing</span>";
        
        echo "<tr>";
        echo "<td><strong>" . $endpoint['method'] . "</strong> <code>" . $endpoint['path'] . "</code></td>";
        echo "<td>" . $endpoint['description'] . "</td>";
        echo "<td>" . $auth_req . "</td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    echo "</div>";

    // ============================================================================
    // TEST 6: CSRF Token Check
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 6: CSRF Protection</h2>";

    $csrf_token = csrf_token();
    if ($csrf_token) {
        echo "<div class='test success'><span class='status ok'>✓</span> CSRF token available</div>";
        echo "<div class='test info'>Token (first 20 chars): <code>" . substr($csrf_token, 0, 20) . "...</code></div>";
        echo "<div class='test info'>⚠ API calls must include X-CSRF-TOKEN header</div>";
    } else {
        echo "<div class='test error'><span class='status error'>✗</span> CSRF token NOT available</div>";
    }

    echo "</div>";

    // ============================================================================
    // TEST 7: Summary & Recommendations
    // ============================================================================
    echo "<div class='section'>
        <h2>TEST 7: Summary & Next Steps</h2>";

    if (!$authenticated) {
        echo "<div class='test warning'><span class='status warning'>⚠</span> You are not logged in</div>";
        echo "<p><strong>To test API endpoints:</strong></p>";
        echo "<ol>";
        echo "<li>Log in to admin panel</li>";
        echo "<li>Then reload this page</li>";
        echo "<li>Click bell icon to test live notifications</li>";
        echo "</ol>";
    } else {
        echo "<div class='test success'><span class='status ok'>✓</span> You are authenticated</div>";
        echo "<p><strong>To test notification API:</strong></p>";
        echo "<ol>";
        echo "<li>Open browser DevTools (F12)</li>";
        echo "<li>Go to Console tab</li>";
        echo "<li>Run: <code>fetch('/admin/notifications').then(r => r.json()).then(d => console.log(d))</code></li>";
        echo "<li>Check the response for errors</li>";
        echo "</ol>";
    }

    echo "<p style='margin-top: 20px;'><strong>Testing Tools:</strong></p>";
    echo "<ul>";
    echo "<li><a href='/test-queries.php' target='_blank'>/test-queries.php</a> - Query & data test</li>";
    echo "<li><a href='/debug-ui.html' target='_blank'>/debug-ui.html</a> - Visual dashboard</li>";
    echo "<li><a href='/debug-notifications.php' target='_blank'>/debug-notifications.php</a> - Simple report</li>";
    echo "</ul>";

    echo "</div>";

} catch (Exception $e) {
    echo "<div class='section'>
        <div class='test error'>
            <strong>Error:</strong> " . $e->getMessage() . "
        </div>
    </div>";
}

echo "</div>
</body>
</html>";
