<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BestSellingProductsSection extends Component
{
    public $categories;
    public $initialProducts;

    public function __construct()
    {
        // ✅ Sirf woh categories jin ke products exist karti hain
        $this->categories = Category::where('status', true)
            ->whereHas('products', function ($q) {
                $q->where('is_active', true)
                    ->whereIn('type', ['for_store', 'both']);
            })
            ->select(['id', 'name', 'slug'])
            ->get();

        // ✅ Initial products (All - latest 16)
        $this->initialProducts = Product::select([
            'name',
            'slug',
            'short_description',
            'price',
            'discount_percent',
            'sale_price',
            'thumbnail',
            'image_alt',
        ])
            ->where('is_active', true)
            ->whereIn('type', ['for_store', 'both'])
            ->latest()
            ->take(16)
            ->get();
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.best-selling-products-section');
    }
}
