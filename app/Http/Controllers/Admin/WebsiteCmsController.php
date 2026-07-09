<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteCms;
use Illuminate\Http\Request;

class WebsiteCmsController extends Controller
{
    /**
     * Display all CMS sections grouped by type
     */
    public function index()
    {
        $sections = WebsiteCms::orderBy('section_type')
                              ->orderBy('display_order')
                              ->paginate(20);

        $groupedSections = $sections->groupBy('section_type');

        return view('admin.cms.index', compact('sections', 'groupedSections'));
    }

    /**
     * Show create form with section type selection
     */
    public function create(Request $request)
    {
        $sectionType = $request->get('type', 'slider');
        $sectionTypes = [
            'slider' => 'Homepage Slider',
            'hero' => 'Hero Text',
            'about' => 'About Section',
            'services' => 'Services',
            'testimonial' => 'Testimonials',
            'contact' => 'Contact Information',
            'social' => 'Social Links',
            'footer' => 'Footer Content',
        ];

        return view('admin.cms.create', compact('sectionType', 'sectionTypes'));
    }

    /**
     * Store new CMS section
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_type' => 'required|in:slider,hero,about,services,testimonial,contact,social,footer',
            'page_title' => 'required|string|max:255',
            'page_content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            // Dynamic fields based on section type
            'hero_subtitle' => 'nullable|string',
            'hero_button_text' => 'nullable|string',
            'hero_button_url' => 'nullable|url',
            'service_icon' => 'nullable|string',
            'testimonial_author' => 'nullable|string',
            'testimonial_position' => 'nullable|string',
            'testimonial_rating' => 'nullable|integer|min:1|max:5',
            'social_icon' => 'nullable|string',
            'social_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'footer_copyright' => 'nullable|string',
        ]);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                                                  ->store('cms/sections', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('cms/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Build data array with dynamic fields
        $data = [];
        switch ($validated['section_type']) {
            case 'hero':
                $data = [
                    'subtitle' => $request->hero_subtitle,
                    'button_text' => $request->hero_button_text,
                    'button_url' => $request->hero_button_url,
                ];
                break;
            case 'services':
                $data = [
                    'icon' => $request->service_icon,
                ];
                break;
            case 'testimonial':
                $data = [
                    'author' => $request->testimonial_author,
                    'position' => $request->testimonial_position,
                    'rating' => $request->testimonial_rating,
                ];
                break;
            case 'social':
                $data = [
                    'icon' => $request->social_icon,
                    'url' => $request->social_url,
                ];
                break;
            case 'contact':
                $data = [
                    'email' => $request->contact_email,
                    'phone' => $request->contact_phone,
                    'address' => $request->contact_address,
                ];
                break;
            case 'footer':
                $data = [
                    'copyright' => $request->footer_copyright,
                ];
                break;
        }

        $validated['data'] = $data;
        $validated['created_by'] = auth()->id();
        $validated['display_order'] = $validated['display_order'] ?? 0;

        if ($request->is_published) {
            $validated['published_at'] = now();
        }

        WebsiteCms::create($validated);

        return redirect()->route('admin.cms.index')
                       ->with('success', 'CMS section created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(WebsiteCms $cms)
    {
        $sectionTypes = [
            'slider' => 'Homepage Slider',
            'hero' => 'Hero Text',
            'about' => 'About Section',
            'services' => 'Services',
            'testimonial' => 'Testimonials',
            'contact' => 'Contact Information',
            'social' => 'Social Links',
            'footer' => 'Footer Content',
        ];

        return view('admin.cms.edit', compact('cms', 'sectionTypes'));
    }

    /**
     * Update CMS section
     */
    public function update(Request $request, WebsiteCms $cms)
    {
        $validated = $request->validate([
            'section_type' => 'required|in:slider,hero,about,services,testimonial,contact,social,footer',
            'page_title' => 'required|string|max:255',
            'page_content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            // Dynamic fields
            'hero_subtitle' => 'nullable|string',
            'hero_button_text' => 'nullable|string',
            'hero_button_url' => 'nullable|url',
            'service_icon' => 'nullable|string',
            'testimonial_author' => 'nullable|string',
            'testimonial_position' => 'nullable|string',
            'testimonial_rating' => 'nullable|integer|min:1|max:5',
            'social_icon' => 'nullable|string',
            'social_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'footer_copyright' => 'nullable|string',
        ]);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                                                  ->store('cms/sections', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('cms/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Build data array
        $data = [];
        switch ($validated['section_type']) {
            case 'hero':
                $data = [
                    'subtitle' => $request->hero_subtitle,
                    'button_text' => $request->hero_button_text,
                    'button_url' => $request->hero_button_url,
                ];
                break;
            case 'services':
                $data = [
                    'icon' => $request->service_icon,
                ];
                break;
            case 'testimonial':
                $data = [
                    'author' => $request->testimonial_author,
                    'position' => $request->testimonial_position,
                    'rating' => $request->testimonial_rating,
                ];
                break;
            case 'social':
                $data = [
                    'icon' => $request->social_icon,
                    'url' => $request->social_url,
                ];
                break;
            case 'contact':
                $data = [
                    'email' => $request->contact_email,
                    'phone' => $request->contact_phone,
                    'address' => $request->contact_address,
                ];
                break;
            case 'footer':
                $data = [
                    'copyright' => $request->footer_copyright,
                ];
                break;
        }

        $validated['data'] = $data;
        $validated['display_order'] = $validated['display_order'] ?? $cms->display_order;

        if ($request->is_published && !$cms->published_at) {
            $validated['published_at'] = now();
        }

        $cms->update($validated);

        return redirect()->route('admin.cms.index')
                       ->with('success', 'CMS section updated successfully');
    }

    /**
     * Delete CMS section
     */
    public function destroy(WebsiteCms $cms)
    {
        $cms->delete();

        return redirect()->route('admin.cms.index')
                       ->with('success', 'CMS section deleted successfully');
    }

    /**
     * Show individual CMS section details
     */
    public function show(WebsiteCms $cms)
    {
        return view('admin.cms.show', compact('cms'));
    }
}
