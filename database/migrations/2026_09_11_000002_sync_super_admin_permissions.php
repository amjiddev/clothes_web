<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $role = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('guard_name', 'web')
            ->first();

        if (!$role) {
            return;
        }

        $now = now();
        $rows = DB::table('permissions')
            ->where('guard_name', 'web')
            ->get(['id'])
            ->map(fn ($permission) => [
                'permission_id' => $permission->id,
                'role_id' => $role->id,
            ])
            ->all();

        if ($rows) {
            DB::table('role_has_permissions')->insertOrIgnore($rows);
        }
    }

    public function down(): void
    {
        $role = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('guard_name', 'web')
            ->first();

        if ($role) {
            DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
        }
    }
};