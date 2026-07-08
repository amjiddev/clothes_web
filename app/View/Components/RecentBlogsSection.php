<?php

namespace App\View\Components;

use App\Models\Blog;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RecentBlogsSection extends Component
{
    public $blogs;
    public $limit;

    /**
     * Create a new component instance.
     *
     * @param int $limit  Number of blogs to show (default 4)
     */
    public function __construct(int $limit = 4)
    {
        $this->limit = $limit;

        $this->blogs = Blog::where('is_active', true)
            ->select(['title', 'slug', 'image', 'image_alt_text', 'short_description'])
            ->latest()
            ->take($this->limit)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.recent-blogs-section');
    }
}
