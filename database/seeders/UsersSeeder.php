<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        User::updateOrCreate(
            ['email' => 'demo@demo.com'], // unique check
            [
                'name'              => $faker->name,
                'password'          => Hash::make('demo'),
                'email_verified_at' => now(),
            ]
        );
    }
}
