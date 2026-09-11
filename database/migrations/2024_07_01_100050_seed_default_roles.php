<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (['customer', 'super_admin', 'receptionist', 'tailor'] as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down(): void
    {
        DB::table('roles')
            ->whereIn('name', ['customer', 'super_admin', 'receptionist', 'tailor'])
            ->where('guard_name', 'web')
            ->delete();
    }
};