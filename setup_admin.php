<?php

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

try {
    echo "\n";
    echo "╔════════════════════════════════════════════════════════╗\n";
    echo "║           Super Admin Setup                            ║\n";
    echo "╚════════════════════════════════════════════════════════╝\n\n";

    // Create roles
    echo "📍 Creating roles...\n";
    $superAdminRole = Role::firstOrCreate(
        ['name' => 'super_admin', 'guard_name' => 'web'],
        ['name' => 'super_admin', 'guard_name' => 'web']
    );
    echo "✅ Super Admin role created\n";

    $adminRole = Role::firstOrCreate(
        ['name' => 'administrator', 'guard_name' => 'web'],
        ['name' => 'administrator', 'guard_name' => 'web']
    );
    echo "✅ Administrator role created\n\n";

    // Create user
    echo "📍 Creating Super Admin user...\n";
    $user = User::where('email', 'admin@clothes.local')->first();
    
    if (!$user) {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@clothes.local',
            'password' => Hash::make('admin@123'),
            'email_verified_at' => now(),
        ]);
        echo "✅ Super Admin user created\n";
    } else {
        echo "⚠️ Super Admin user already exists\n";
        $user->update([
            'password' => Hash::make('admin@123'),
            'email_verified_at' => now(),
        ]);
        echo "✅ Password updated\n";
    }

    // Assign role
    $user->syncRoles('super_admin');
    echo "✅ Role assigned to user\n\n";

    // Display credentials
    echo "╔════════════════════════════════════════════════════════╗\n";
    echo "║           ✅ Setup Complete!                          ║\n";
    echo "╚════════════════════════════════════════════════════════╝\n\n";
    echo "📧 Email:    admin@clothes.local\n";
    echo "🔑 Password: admin@123\n";
    echo "👤 Role:     Super Admin\n\n";
    echo "🌐 Login URL: http://localhost:8000/login\n";
    echo "📊 Dashboard: http://localhost:8000/admin/dashboard\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
