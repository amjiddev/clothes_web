<!DOCTYPE html>
<html>
<head>
    <title>Check Users</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #4CAF50; color: white; }
        .success { background: #d4edda; }
        .error { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>Users & Roles Check</h1>
    
    <?php
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use App\Models\User;
    use Spatie\Permission\Models\Role;

    try {
        // Check roles
        $roles = Role::all();
        echo "<h2>Roles in Database</h2>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Guard</th></tr>";
        foreach ($roles as $role) {
            echo "<tr><td>{$role->id}</td><td>{$role->name}</td><td>{$role->guard_name}</td></tr>";
        }
        echo "</table>";

        // Check users
        echo "<h2>Users in Database</h2>";
        $users = User::all();
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Email Verified</th><th>Roles</th></tr>";
        foreach ($users as $user) {
            $roleNames = $user->roles()->pluck('name')->join(', ') ?: 'No roles';
            $verified = $user->email_verified_at ? '✅ Yes' : '❌ No';
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$verified}</td>";
            echo "<td>{$roleNames}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Check admin@example.com specifically
        echo "<h2>Admin User Details</h2>";
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            echo "<div class='success'>";
            echo "<p><strong>✅ User Found</strong></p>";
            echo "<p>Name: {$admin->name}</p>";
            echo "<p>Email: {$admin->email}</p>";
            echo "<p>Email Verified: " . ($admin->email_verified_at ? 'Yes ✅' : 'No ❌') . "</p>";
            echo "<p>Roles: " . ($admin->roles()->count() > 0 ? $admin->roles()->pluck('name')->join(', ') : 'No roles ❌') . "</p>";
            echo "<p><strong>Password:</strong> password</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<p><strong>❌ User Not Found</strong></p>";
            echo "<p>admin@example.com does not exist in database</p>";
            echo "</div>";
        }

        // Check demo@demo.com
        echo "<h2>Demo User Details</h2>";
        $demo = User::where('email', 'demo@demo.com')->first();
        if ($demo) {
            echo "<div class='success'>";
            echo "<p><strong>✅ User Found</strong></p>";
            echo "<p>Name: {$demo->name}</p>";
            echo "<p>Email: {$demo->email}</p>";
            echo "<p>Email Verified: " . ($demo->email_verified_at ? 'Yes ✅' : 'No ❌') . "</p>";
            echo "<p>Roles: " . ($demo->roles()->count() > 0 ? $demo->roles()->pluck('name')->join(', ') : 'No roles ❌') . "</p>";
            echo "<p><strong>Password:</strong> demo</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<p><strong>❌ User Not Found</strong></p>";
            echo "</div>";
        }

    } catch (\Exception $e) {
        echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
