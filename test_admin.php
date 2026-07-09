<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Access</title>
</head>
<body>
    <h1>Testing Admin Access</h1>
    <?php
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use App\Models\User;

    try {
        $user = User::where('email', 'demo@demo.com')->first();
        
        if ($user) {
            echo "<h2>User Found: " . $user->name . "</h2>";
            echo "<p>Email: " . $user->email . "</p>";
            echo "<p>Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "</p>";
            echo "<h3>Roles:</h3>";
            echo "<ul>";
            foreach ($user->roles as $role) {
                echo "<li>" . $role->name . "</li>";
            }
            echo "</ul>";
            
            echo "<h3>Role Checks:</h3>";
            echo "<p>hasRole('super_admin'): " . ($user->hasRole('super_admin') ? 'YES ✅' : 'NO ❌') . "</p>";
            echo "<p>hasRole('administrator'): " . ($user->hasRole('administrator') ? 'YES ✅' : 'NO ❌') . "</p>";
            echo "<p>hasRole('receptionist'): " . ($user->hasRole('receptionist') ? 'YES ✅' : 'NO ❌') . "</p>";
            echo "<p>hasRole('tailor'): " . ($user->hasRole('tailor') ? 'YES ✅' : 'NO ❌') . "</p>";
        } else {
            echo "<p style='color: red;'>User not found!</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
