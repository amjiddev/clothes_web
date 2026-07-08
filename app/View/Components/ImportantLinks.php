<?php

namespace App\View\Components;

use App\Models\ImportantLinks as ModelsImportantLinks;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImportantLinks extends Component
{
    public $links;
    public $forPage;

    /**
     * Create a new component instance.
     *
     * @param string $forPage  // privacy_policy, terms_conditions, disclaimer
     */
    public function __construct(string $forPage)
    {
        $this->forPage = $forPage;

        // Fetch only active links for this page, ordered by creation or custom order
        $this->links = ModelsImportantLinks::where('for_page', $forPage)
            ->orderBy('created_at', 'desc') // ya agar order column ho to uske basis pe
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.important-links');
    }
}
