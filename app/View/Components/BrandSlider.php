<?php

namespace App\View\Components;

use App\Models\BrandWeCarry;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BrandSlider extends Component
{
    public $brands;

    public function __construct($brands = null)
    {
        // Agar brands pass na kiye jayein to default DB se le lo
        $this->brands = $brands ?? BrandWeCarry::get();
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.brand-slider');
    }
}
