<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin Role if not exists
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        // Create Demo User
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demoadmin'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role to user
        $demoUser->syncRoles('super_admin');

        echo "\n";
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║           ✅ Demo User Created!                       ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
        echo "📧 Email:    demo@demo.com\n";
        echo "🔑 Password: demoadmin\n";
        echo "👤 Role:     Super Admin\n\n";
        echo "🌐 Login URL: http://localhost:8000/login\n";
        echo "📊 Dashboard: http://localhost:8000/admin/dashboard\n\n";
    }
}
