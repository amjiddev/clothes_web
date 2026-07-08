<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class RentalEquipmentProductsSection extends Component
{
    public $staticCategories;
    public $initialProducts;
    
    /**
     * Create a new component instance.
     */

    public function __construct()
    {
        // Static tabs with exact category names from your DB
        $this->staticCategories = [
            ['label' => 'Featured',         'slug' => 'featured',         'category_name' => 'Featured'],
            ['label' => 'Medical Equipment','slug' => 'medical-equipment','category_name' => 'Medical Equipment'],
            ['label' => 'Supplies',         'slug' => 'supplies',         'category_name' => 'Supplies'],
            ['label' => 'Parts',            'slug' => 'parts',            'category_name' => 'Parts'],
        ];

        // Resolve category IDs
        foreach ($this->staticCategories as &$tab) {
            $category = Cache::rememberForever("category_{$tab['category_name']}", function () use ($tab) {
                return Category::where('name', $tab['category_name'])->first(['id']);
            });
            $tab['category_id'] = $category?->id;
        }

        // Default: Load Featured category products
        $featuredCategoryId = $this->staticCategories[0]['category_id'] ?? null;

        $this->initialProducts = Product::where('is_active', true)
            ->whereIn('type', ['for_rent', 'both'])
            ->when($featuredCategoryId, fn($q) => $q->where('category_id', $featuredCategoryId))
            ->select(['name', 'slug', 'short_description', 'description', 'price', 'thumbnail', 'image_alt'])
            ->latest()
            ->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rental-equipment-products-section');
    }
}
