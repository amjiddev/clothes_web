<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class OurLatestProducts extends Component
{
    public $latestProductCategories;
    public $initialProducts;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Static tabs with exact category names
        $this->latestProductCategories = [
            ['label' => 'Featured',         'slug' => 'featured',         'category_name' => 'Featured'],
            ['label' => 'Medical Equipment', 'slug' => 'medical-equipment', 'category_name' => 'Medical Equipment'],
            ['label' => 'Supplies',         'slug' => 'supplies',         'category_name' => 'Supplies'],
            ['label' => 'Parts',            'slug' => 'parts',            'category_name' => 'Parts'],
        ];

        // Resolve category IDs from DB
        foreach ($this->latestProductCategories as &$tab) {
            $category = Cache::rememberForever("category_{$tab['category_name']}", function () use ($tab) {
                return Category::where('name', $tab['category_name'])->first(['id']);
            });
            $tab['category_id'] = $category?->id;
        }

        // Default Featured category products (latest 4)
        $featuredCategoryId = $this->latestProductCategories[0]['category_id'] ?? null;

        $this->initialProducts = Product::where('is_active', true)
            ->when($featuredCategoryId, fn($q) => $q->where('category_id', $featuredCategoryId))
            ->select(['name', 'slug', 'short_description', 'price', 'discount_percent', 'sale_price', 'thumbnail', 'image_alt'])
            ->latest()
            ->take(4)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.our-latest-products');
    }
}
