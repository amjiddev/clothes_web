<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            'view_dashboard',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'view_permissions', 'manage_permissions',
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            'view_stitching_orders', 'assign_stitching_orders', 'update_stitching_status',
            'delete_orders',
            'view_customers', 'create_customers', 'edit_customers', 'delete_customers', 'block_customers',
            'create_measurements', 'edit_measurements', 'delete_measurements',
            'view_tailors', 'assign_tailors',
            'view_reports', 'export_reports',
            'view_receptionists', 'manage_receptionists',
            'view_payments', 'manage_payments',
            'view_coupons', 'manage_coupons',
            'view_settings', 'manage_settings',
            'manage_website',
        ];

        $now = now();

        foreach ($permissions as $name) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name, 'guard_name' => 'web'],
                [
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $role = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('guard_name', 'web')
            ->first();

        if (!$role) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->where('guard_name', 'web')
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $role->id,
            ]);
        }
    }

    public function down(): void
    {
        // Permissions are shared configuration and should not be removed on rollback.
    }
};