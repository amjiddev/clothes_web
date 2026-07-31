<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin permissions - Grant all permissions to super_admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing admin permissions...');

        try {
            // Get or create super_admin role
            $superAdminRole = Role::firstOrCreate(
                ['name' => 'super_admin', 'guard_name' => 'web'],
            );

            // Get all permissions
            $permissions = \Spatie\Permission\Models\Permission::all();

            if ($permissions->isEmpty()) {
                $this->warn('No permissions found. Please run: php artisan db:seed --class=RoleAndPermissionSeeder');
                return 1;
            }

            // Give all permissions to super_admin role
            $superAdminRole->syncPermissions($permissions);

            $this->info('✓ Super Admin role now has all permissions');

            // Get all super admin users and ensure they have the role
            $superAdmins = User::role('super_admin')->get();

            if ($superAdmins->isEmpty()) {
                $this->warn('No super admin users found.');
                $this->info('Creating default super admin...');
                
                $adminUser = User::where('email', 'admin@example.com')->first();
                if ($adminUser) {
                    $adminUser->syncRoles('super_admin');
                    $this->info("✓ Assigned super_admin role to {$adminUser->email}");
                } else {
                    $this->warn('No admin user found. Please create one first.');
                }
            } else {
                $this->info("✓ Found " . $superAdmins->count() . " super admin user(s)");
                foreach ($superAdmins as $admin) {
                    $this->line("  - {$admin->name} ({$admin->email})");
                }
            }

            $this->info('✓ Admin permissions fixed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
