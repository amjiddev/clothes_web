<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Shirts',
                'slug' => 'shirts',
                'description' => 'Formal and casual shirts for men',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pants',
                'slug' => 'pants',
                'description' => 'Trousers and pants for every occasion',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kurtas',
                'slug' => 'kurtas',
                'description' => 'Traditional Indian kurtas',
                'sort_order' => 3,
            ],
            [
                'name' => 'Blazers',
                'slug' => 'blazers',
                'description' => 'Professional blazers and jackets',
                'sort_order' => 4,
            ],
            [
                'name' => 'Suits',
                'slug' => 'suits',
                'description' => 'Complete suits for formal occasions',
                'sort_order' => 5,
            ],
            [
                'name' => 'Waistcoats',
                'slug' => 'waistcoats',
                'description' => 'Formal waistcoats and vests',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
