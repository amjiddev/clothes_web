<?php

namespace App\View\Components;

use App\Models\Blog;
use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class LatestBlogsSection extends Component
{
    public $categories;
    public $blogs;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch categories which have blogs
        $this->categories = Category::whereIn('id', function ($query) {
            $query->select('category_id')
                ->from('blogs')
                ->whereNotNull('category_id')
                ->distinct();
        })
            ->where('status', 1)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();


        // Default: All blogs
        $this->blogs = Blog::where('is_active', 1)
            ->latest()
            ->paginate(12); // 12 per page
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.latest-blogs-section');
    }
}
