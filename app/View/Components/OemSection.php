<?php

namespace App\View\Components;

use App\Models\OemContent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OemSection extends Component
{
    /**
     * Create a new component instance.
     */
    public $oems;

    public function __construct()
    {
        $this->oems = OemContent::select([
                'title',
                'slug',
                'image',
                'image_alt',
                'description',
            ])
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.oem-section');
    }
}
