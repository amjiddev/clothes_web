<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions
        $permissions = [
            // Product Management
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',

            // Category Management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Order Management
            'view_all_orders',
            'view_own_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',
            'export_orders',

            // Stitching Management
            'view_stitching_orders',
            'assign_stitching_orders',
            'view_assigned_stitching_orders',
            'update_stitching_status',

            // Customer Management
            'view_customers',
            'view_all_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            'block_customers',

            // Measurement Management
            'view_all_measurements',
            'manage_own_measurements',
            'create_measurements',
            'edit_measurements',
            'delete_measurements',

            // Tailor Management
            'view_tailors',
            'assign_tailors',
            'edit_tailors',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // Permission Management
            'view_permissions',
            'manage_permissions',

            // Dashboard & Reports
            'view_dashboard',
            'view_reports',
            'export_reports',

            // Settings
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign permissions
        $superAdminRole = Role::findOrCreate('super_admin', 'web');
        $superAdminRole->givePermissionTo(Permission::all());

        $receptionistRole = Role::findOrCreate('receptionist', 'web');
        $receptionistRole->givePermissionTo([
            'view_products',
            'view_categories',
            'view_all_orders',
            'create_orders',
            'edit_orders',
            'view_stitching_orders',
            'assign_stitching_orders',
            'view_customers',
            'view_all_customers',
            'create_customers',
            'edit_customers',
            'block_customers',
            'view_all_measurements',
            'create_measurements',
            'edit_measurements',
            'view_tailors',
            'assign_tailors',
            'view_dashboard',
            'view_reports',
        ]);

        $tailorRole = Role::findOrCreate('tailor', 'web');
        $tailorRole->givePermissionTo([
            'view_assigned_stitching_orders',
            'update_stitching_status',
            'view_all_measurements',
        ]);

        $customerRole = Role::findOrCreate('customer', 'web');
        $customerRole->givePermissionTo([
            'view_products',
            'view_categories',
            'view_own_orders',
            'create_orders',
            'manage_own_measurements',
            'create_measurements',
            'edit_measurements',
        ]);
    }
}
