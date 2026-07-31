<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionSection;
use App\Models\CollectionSectionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollectionSectionController extends Controller
{
    /**
     * Display the collection sections management page
     */
    public function index()
    {
        $pageTitle = 'Collections Page Management';
        $pageDescription = 'Manage the dynamic sections on the collections page with images and content';

        $sections = CollectionSection::ordered()->get();

        $stats = [
            'total_sections' => CollectionSection::count(),
            'published' => CollectionSection::published()->count(),
            'total_images' => CollectionSectionImage::count(),
        ];

        return view('admin.website-management.collections', compact(
            'pageTitle',
            'pageDescription',
            'sections',
            'stats'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $pageTitle = 'Create Collection Section';
        $pageDescription = 'Add a new collection section with images';

        return view('admin.website-management.collections-form', compact('pageTitle', 'pageDescription'));
    }    /**
     * Store a new collection section
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:255',
            'section_key' => 'required|string|unique:collection_sections,section_key',
            'badge_text' => 'nullable|string|max:100',
            'badge_bg_color' => 'required|in:gold,dark',
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:4',
            'images.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*.alt_text' => 'nullable|string|max:255',
            'images.*.product_name' => 'nullable|string|max:255',
            'images.*.product_price' => 'nullable|string|max:100',
        ]);

        // Create section
        $section = CollectionSection::create([
            'section_name' => $validated['section_name'],
            'section_key' => $validated['section_key'],
            'badge_text' => $validated['badge_text'],
            'badge_bg_color' => $validated['badge_bg_color'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'button_text' => $validated['button_text'],
            'button_link' => $validated['button_link'],
            'features' => $request->has('features') ? array_filter(array_map('trim', explode(',', $validated['features']))) : [],
            'is_published' => $request->has('is_published'),
            'display_order' => $validated['display_order'] ?? 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Store images
        if ($request->has('images')) {
            foreach ($request->file('images', []) as $index => $imageData) {
                // Check if imageData is an array (nested structure)
                if (is_array($imageData)) {
                    $imageFile = $imageData['image'] ?? null;
                    $altText = $imageData['alt_text'] ?? null;
                    $productName = $imageData['product_name'] ?? null;
                    $productPrice = $imageData['product_price'] ?? null;
                } else {
                    $imageFile = $imageData;
                    $altText = $request->input("images.{$index}.alt_text");
                    $productName = $request->input("images.{$index}.product_name");
                    $productPrice = $request->input("images.{$index}.product_price");
                }

                if ($imageFile && is_object($imageFile) && $imageFile->isValid()) {
                    $path = $imageFile->store('collections', 'public');

                    CollectionSectionImage::create([
                        'collection_section_id' => $section->id,
                        'image_path' => $path,
                        'image_alt_text' => $altText,
                        'product_name' => $productName,
                        'product_price' => $productPrice,
                        'display_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.website-management.collections.index')
                       ->with('success', 'Collection section created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(CollectionSection $section)
    {
        $pageTitle = 'Edit Collection Section';
        $pageDescription = 'Update collection section details and images';

        return view('admin.website-management.collections-form', compact('pageTitle', 'pageDescription', 'section'));
    }

    /**
     * Update a collection section
     */
    public function update(Request $request, CollectionSection $section)
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:255',
            'section_key' => 'required|string|unique:collection_sections,section_key,' . $section->id,
            'badge_text' => 'nullable|string|max:100',
            'badge_bg_color' => 'required|in:gold,dark',
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'new_images' => 'nullable|array|max:4',
            'new_images.*.image' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'new_images.*.alt_text' => 'nullable|string|max:255',
            'new_images.*.product_name' => 'nullable|string|max:255',
            'new_images.*.product_price' => 'nullable|string|max:100',
            'delete_images' => 'nullable|array',
        ]);

        // Update section
        $section->update([
            'section_name' => $validated['section_name'],
            'section_key' => $validated['section_key'],
            'badge_text' => $validated['badge_text'],
            'badge_bg_color' => $validated['badge_bg_color'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'button_text' => $validated['button_text'],
            'button_link' => $validated['button_link'],
            'features' => $request->has('features') ? array_filter(array_map('trim', explode(',', $validated['features']))) : [],
            'is_published' => $request->has('is_published'),
            'display_order' => $validated['display_order'] ?? $section->display_order,
            'updated_by' => auth()->id(),
        ]);

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = CollectionSectionImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Store new images
        if ($request->has('new_images')) {
            $maxOrder = $section->images()->max('display_order') ?? -1;

            foreach ($request->file('new_images', []) as $index => $imageData) {
                // Check if imageData is an array (nested structure)
                if (is_array($imageData)) {
                    $imageFile = $imageData['image'] ?? null;
                } else {
                    $imageFile = $imageData;
                }

                if ($imageFile && is_object($imageFile) && $imageFile->isValid()) {
                    $path = $imageFile->store('collections', 'public');
                    $maxOrder++;

                    CollectionSectionImage::create([
                        'collection_section_id' => $section->id,
                        'image_path' => $path,
                        'image_alt_text' => $request->input("new_images.{$index}.alt_text"),
                        'product_name' => $request->input("new_images.{$index}.product_name"),
                        'product_price' => $request->input("new_images.{$index}.product_price"),
                        'display_order' => $maxOrder,
                    ]);
                }
            }
        }

        return redirect()->route('admin.website-management.collections.index')
                       ->with('success', 'Collection section updated successfully');
    }

    /**
     * Delete a collection section
     */
    public function destroy(CollectionSection $section)
    {
        // Delete associated images from storage
        foreach ($section->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $section->delete();

        return redirect()->route('admin.website-management.collections.index')
                       ->with('success', 'Collection section deleted successfully');
    }

    /**
     * Toggle published status
     */
    public function togglePublished(CollectionSection $section)
    {
        $section->update([
            'is_published' => !$section->is_published,
            'updated_by' => auth()->id(),
        ]);

        $status = $section->is_published ? 'published' : 'unpublished';

        return redirect()->route('admin.website-management.collections.index')
                       ->with('success', "Collection section {$status} successfully");
    }
}
