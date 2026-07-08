<?php

namespace App\View\Components;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RentalProductListColumns extends Component
{
    /**
     * Create a new component instance.
     */
    public $productColumns;

    public function __construct()
    {
        $products = Product::where('is_active', true)
            ->whereIn('type', ['for_rent', 'both'])
            ->select('name', 'slug')
            ->orderBy('name')
            ->get();

        $this->productColumns = $products->chunk(ceil($products->count() / 3));
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rental-product-list-columns');
    }
}
