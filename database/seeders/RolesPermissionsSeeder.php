<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $abilities = [
            'read',
            'write',
            'create',
            'delete',
        ];

        $permissions_by_role = [
            'administrator' => [
                'user management',
            ],
            'super_admin' => [
                'user management',
            ],
            'department_admin' => [],
            'teacher' => [],
            'student' => [],
        ];

        // Create permissions
        foreach ($permissions_by_role as $permissions) {
            foreach ($permissions as $permission) {
                foreach ($abilities as $ability) {
                    $permissionName = $ability . ' ' . $permission;
                    if (!Permission::where('name', $permissionName)->exists()) {
                        Permission::create(['name' => $permissionName]);
                    }
                }
            }
        }

        // Create administrator role and sync permissions
        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];
            foreach ($abilities as $ability) {
                foreach ($permissions as $permission) {
                    $full_permissions_list[] = $ability . ' ' . $permission;
                }
            }

            $roleInstance = Role::firstOrCreate(['name' => $role]);
            if (!empty($full_permissions_list)) {
                $roleInstance->syncPermissions($full_permissions_list);
            }
        }

        // Assign administrator role to demo@demo.com
        User::where('email', 'demo@demo.com')->first()?->assignRole('administrator');
    }
}
