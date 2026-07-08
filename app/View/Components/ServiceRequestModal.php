<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceRequestModal extends Component
{
    public $all_categories;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->all_categories = Category::select('id', 'name', 'slug')
            ->where('status', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.service-request-modal');
    }
}
