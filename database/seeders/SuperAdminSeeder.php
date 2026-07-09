<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a super admin user for production use
     */
    public function run(): void
    {
        // Ensure super_admin role exists (created by RoleAndPermissionSeeder)
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );
        
        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role to user
        $superAdmin->syncRoles('super_admin');

        echo "\n";
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║           ✅ Super Admin User Created!               ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
        echo "📧 Email: admin@example.com\n";
        echo "🔑 Password: password\n";
        echo "👤 Role: Super Admin\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
}
