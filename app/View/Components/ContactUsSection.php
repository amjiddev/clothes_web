<?php

namespace App\View\Components;

use App\Models\ServingCity;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContactUsSection extends Component
{
    public $data;
    public $footerStates;
    public $area_names;

    /**
     * Create a new component instance.
     */
    public function __construct($data = null, $footerStates = [])
    {
        $this->data = $data;
        $this->footerStates = $footerStates;
        $this->area_names = ServingCity::where('is_active', true)->orderBy('area_name', 'asc')->select([
                'area_name',
                'slug',
            ])->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contact-us-section');
    }
}
