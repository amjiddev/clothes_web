<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class QueryCategories extends Command
{
    protected $signature = 'query:categories';
    protected $description = 'Show all categories with count of active products';

    public function handle()
    {
        $categories = DB::table('categories as c')
            ->leftJoin('products as p', function($join) {
                $join->on('c.id', '=', 'p.category_id')
                     ->where('p.is_active', '=', 1);
            })
            ->select('c.id', 'c.name', 'c.slug', DB::raw('COUNT(p.id) as active_products_count'))
            ->groupBy('c.id', 'c.name', 'c.slug')
            ->orderBy('c.id')
            ->get();

        $this->info('Categories with Active Products Count:');
        $this->line(str_repeat('=', 80));
        $this->table(
            ['ID', 'Name', 'Slug', 'Active Products'],
            $categories->map(function($cat) {
                return [
                    $cat->id,
                    $cat->name,
                    $cat->slug,
                    $cat->active_products_count
                ];
            })->toArray()
        );
        $this->line('Total Categories: ' . count($categories));
    }
}
