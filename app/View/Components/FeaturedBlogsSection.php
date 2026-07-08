<?php

namespace App\View\Components;

use App\Models\Blog;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeaturedBlogsSection extends Component
{
    public $featuredBlogs;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->featuredBlogs = Blog::with(['category:id,name'])
            ->where('type', 'featured')
            ->where('is_active', 1)
            ->latest()
            ->take(6)
            ->select(
                'id',
                'title',
                'slug',
                'image',
                'category_id',
                'updated_at',
                'image_alt_text',
                'short_description',
                'read_time'
            )
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.featured-blogs-section');
    }
}
