<?php

namespace App\View\Components;

use App\Models\ServingCity;
use Illuminate\View\Component;

class LocationWeServed extends Component
{
    public $serving_areas;

    public function __construct()
    {
        $this->serving_areas = ServingCity::where('is_active', true)
            ->select('area_name', 'slug')
            ->get();
    }

    public function render()
    {
        // Sirf view return karo, data manually mat pass karo
        return view('components.location-we-served');
    }
}