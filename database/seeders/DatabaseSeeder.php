<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Order matters - Roles must be created before users are assigned roles
        $this->call([
            RoleAndPermissionSeeder::class,  // Create roles and permissions first
            SettingsSeeder::class,           // Settings before other seeders
            SuperAdminSeeder::class,         // Then create admin user
            DemoUserSeeder::class,           // Demo user for testing
            UsersSeeder::class,              // Other users
            CategorySeeder::class,           // Product data
            ProductSeeder::class,            // Product data
            WebsiteCmsSeeder::class,         // Website CMS content
        ]);
    }
}
