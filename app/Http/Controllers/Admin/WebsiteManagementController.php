<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteCms;
use Illuminate\Http\Request;

class WebsiteManagementController extends Controller
{
    /**
     * Contact Page Management
     */
    public function contact()
    {
        $pageTitle = 'Contact Page Management';
        $pageDescription = 'Manage contact page content, contact information, and store locations';
        
        // Get main contact info
        $contactInfo = WebsiteCms::where('section_type', 'contact')
                                 ->where('is_published', true)
                                 ->first();
        
        // Get all contact entries (including locations)
        $contactEntries = WebsiteCms::whereIn('section_type', ['contact', 'contact_location'])
                                    ->orderBy('section_type', 'asc')
                                    ->orderBy('display_order', 'asc')
                                    ->get();
        
        // Get locations
        $locations = WebsiteCms::where('section_type', 'contact_location')
                               ->orderBy('display_order')
                               ->get();
        
        // Statistics
        $stats = [
            'total_entries' => $contactEntries->count(),
            'published' => $contactEntries->where('is_published', true)->count(),
            'locations' => $locations->count(),
            'has_main_contact' => $contactInfo ? true : false,
        ];
        
        return view('admin.website-management.contact', compact(
            'pageTitle',
            'pageDescription',
            'contactInfo',
            'contactEntries',
            'locations',
            'stats'
        ));
    }
    
    /**
     * Store new contact entry or update existing
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'section_type' => 'required|in:contact,contact_location',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'contact_response_time' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'locations.*.title' => 'nullable|string',
            'locations.*.email' => 'nullable|email',
            'locations.*.phone' => 'nullable|string',
            'locations.*.address' => 'nullable|string',
            'locations.*.timings' => 'nullable|string',
            'new_locations.*.title' => 'nullable|string',
            'new_locations.*.email' => 'nullable|email',
            'new_locations.*.phone' => 'nullable|string',
            'new_locations.*.address' => 'nullable|string',
            'new_locations.*.timings' => 'nullable|string',
            'delete_locations' => 'nullable|array',
        ]);
        
        // Build data array for main contact
        $data = [
            'email' => $request->contact_email,
            'phone' => $request->contact_phone,
            'address' => $request->contact_address,
            'response_time' => $request->contact_response_time,
        ];
        
        $cmsData = [
            'section_type' => $validated['section_type'],
            'page_slug' => 'contact-information',
            'page_title' => 'Contact Information',
            'page_content' => null,
            'data' => $data,
            'is_published' => $request->has('is_published'),
            'display_order' => $validated['display_order'] ?? 0,
            'created_by' => auth()->id(),
        ];
        
        if ($request->has('is_published')) {
            $cmsData['published_at'] = now();
        }
        
        // Create or update main contact
        $mainContact = WebsiteCms::where('section_type', 'contact')->first();
        
        if ($mainContact) {
            $mainContact->update($cmsData);
        } else {
            WebsiteCms::create($cmsData);
        }
        
        // Handle location updates
        if ($request->has('locations')) {
            foreach ($request->locations as $index => $locationData) {
                if (isset($locationData['id'])) {
                    $location = WebsiteCms::find($locationData['id']);
                    if ($location) {
                        $location->update([
                            'page_title' => $locationData['title'],
                            'data' => [
                                'email' => $locationData['email'] ?? null,
                                'phone' => $locationData['phone'] ?? null,
                                'address' => $locationData['address'] ?? null,
                                'timings' => $locationData['timings'] ?? null,
                            ],
                            'is_published' => $request->has('is_published'),
                        ]);
                    }
                }
            }
        }
        
        // Handle new locations
        if ($request->has('new_locations')) {
            $displayOrder = WebsiteCms::where('section_type', 'contact_location')->max('display_order') ?? 0;
            
            foreach ($request->new_locations as $newLocation) {
                if (!empty($newLocation['title'])) {
                    $displayOrder++;
                    
                    // Generate slug from title
                    $slug = \Illuminate\Support\Str::slug($newLocation['title']);
                    
                    WebsiteCms::create([
                        'section_type' => 'contact_location',
                        'page_slug' => $slug,
                        'page_title' => $newLocation['title'],
                        'data' => [
                            'email' => $newLocation['email'] ?? null,
                            'phone' => $newLocation['phone'] ?? null,
                            'address' => $newLocation['address'] ?? null,
                            'timings' => $newLocation['timings'] ?? null,
                        ],
                        'is_published' => $request->has('is_published'),
                        'published_at' => $request->has('is_published') ? now() : null,
                        'display_order' => $displayOrder,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }
        
        // Handle location deletions
        if ($request->has('delete_locations')) {
            WebsiteCms::whereIn('id', $request->delete_locations)->delete();
        }
        
        return redirect()->route('admin.website-management.contact')
                       ->with('success', 'Contact page updated successfully');
    }
    
    /**
     * Update contact entry
     */
    public function updateContact(Request $request, $id)
    {
        $cms = WebsiteCms::findOrFail($id);
        
        $validated = $request->validate([
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'contact_response_time' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        // Build data array
        $data = [
            'email' => $request->contact_email,
            'phone' => $request->contact_phone,
            'address' => $request->contact_address,
            'response_time' => $request->contact_response_time,
        ];
        
        $cmsData = [
            'page_title' => 'Contact Information',
            'page_content' => null,
            'data' => $data,
            'is_published' => $request->has('is_published'),
            'display_order' => $validated['display_order'] ?? $cms->display_order,
        ];
        
        if ($request->has('is_published') && !$cms->published_at) {
            $cmsData['published_at'] = now();
        }
        
        $cms->update($cmsData);
        
        return redirect()->route('admin.website-management.contact')
                       ->with('success', 'Contact entry updated successfully');
    }
    
    /**
     * Delete contact entry
     */
    public function deleteContact($id)
    {
        $cms = WebsiteCms::findOrFail($id);
        $cms->delete();
        
        return redirect()->route('admin.website-management.contact')
                       ->with('success', 'Contact entry deleted successfully');
    }

    /**
     * Home Page Management
     */
    public function homePage()
    {
        $pageTitle = 'Home Page Management';
        $pageDescription = 'Manage home page content, hero section, featured products, and sections';
        
        // Get home page content (published or draft)
        $homePageContent = WebsiteCms::where('section_type', 'home_page')
                                     ->where('page_slug', 'home-page')
                                     ->first();
        
        // Get all home page related sections
        $homePageSections = WebsiteCms::where('section_type', 'home_page')
                                      ->orderBy('display_order', 'asc')
                                      ->get();
        
        // Statistics
        $stats = [
            'total_sections' => $homePageSections->count(),
            'published' => $homePageSections->where('is_published', true)->count(),
            'has_main_content' => $homePageContent ? true : false,
        ];
        
        return view('admin.website-management.home-page', compact(
            'pageTitle',
            'pageDescription',
            'homePageContent',
            'homePageSections',
            'stats'
        ));
    }
    
    /**
     * Store or update home page content
     */
    public function storeHomePage(Request $request)
    {
        $validated = $request->validate([
            'page_title' => 'nullable|string|max:255',
            'page_content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'is_published' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            // Hero images
            'hero_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hero_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hero_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hero_image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tailoring services
            'tailoring_title_1' => 'nullable|string',
            'tailoring_title_2' => 'nullable|string',
            'tailoring_title_3' => 'nullable|string',
            'tailoring_description_1' => 'nullable|string',
            'tailoring_description_2' => 'nullable|string',
            'tailoring_description_3' => 'nullable|string',
            'tailoring_icon_1' => 'nullable|string',
            'tailoring_icon_2' => 'nullable|string',
            'tailoring_icon_3' => 'nullable|string',
            'tailoring_price_1' => 'nullable|string',
            'tailoring_price_2' => 'nullable|string',
            'tailoring_price_3' => 'nullable|string',
            'tailoring_section_title' => 'nullable|string',
            'tailoring_section_description' => 'nullable|string',
            // Collection - Product fields
            'collection_product_name' => 'nullable|string|max:255',
            'collection_product_slug' => 'nullable|string|max:255',
            'collection_product_category' => 'nullable|string',
            'collection_product_brand' => 'nullable|string|max:255',
            'collection_regular_price' => 'nullable|numeric|min:0',
            'collection_sale_price' => 'nullable|numeric|min:0',
            'collection_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'collection_short_description' => 'nullable|string',
            'collection_full_description' => 'nullable|string',
            'collection_featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'collection_gallery_images' => 'nullable|array',
            'collection_gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'collection_products' => 'nullable|json',
        ]);
        
        // Build data array
        $data = [
            'hero_title' => $validated['hero_title'] ?? null,
            'hero_subtitle' => $validated['hero_subtitle'] ?? null,
            // Collection products (parsed from JSON)
            'collection_products' => !empty($validated['collection_products']) ? json_decode($validated['collection_products'], true) : [],
        ];
        
        // Add tailoring services data
        for ($i = 1; $i <= 3; $i++) {
            $data['tailoring_title_' . $i] = $validated['tailoring_title_' . $i] ?? null;
            $data['tailoring_description_' . $i] = $validated['tailoring_description_' . $i] ?? null;
            $data['tailoring_icon_' . $i] = $validated['tailoring_icon_' . $i] ?? null;
            $data['tailoring_price_' . $i] = $validated['tailoring_price_' . $i] ?? null;
        }
        
        // Add tailoring section header
        $data['tailoring_section_title'] = $validated['tailoring_section_title'] ?? null;
        $data['tailoring_section_description'] = $validated['tailoring_section_description'] ?? null;
        
        // Use existing title if not provided
        $pageTitle = $validated['page_title'] ?? 'Home Page';
        
        $cmsData = [
            'section_type' => 'home_page',
            'page_slug' => 'home-page',
            'page_title' => $pageTitle,
            'page_content' => $validated['page_content'],
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'data' => $data,
            'is_published' => $request->has('is_published'),
            'display_order' => $validated['display_order'] ?? 0,
            'created_by' => auth()->id(),
        ];
        
        // Handle hero images upload
        for ($i = 1; $i <= 4; $i++) {
            $fileKey = 'hero_image_' . $i;
            if ($request->hasFile($fileKey)) {
                try {
                    $path = $request->file($fileKey)->store('home-page/hero', 'public');
                    $data['hero_image_' . $i] = $path;
                } catch (\Exception $e) {
                    \Log::error('Error uploading hero image ' . $i . ': ' . $e->getMessage());
                }
            }
        }
        
        // Handle collection featured image
        if ($request->hasFile('collection_featured_image')) {
            try {
                $path = $request->file('collection_featured_image')->store('home-page/collection', 'public');
                $data['collection_featured_image'] = $path;
            } catch (\Exception $e) {
                \Log::error('Error uploading collection featured image: ' . $e->getMessage());
            }
        }
        
        // Handle collection gallery images
        if ($request->hasFile('collection_gallery_images')) {
            try {
                $galleryPaths = [];
                foreach ($request->file('collection_gallery_images') as $image) {
                    $path = $image->store('home-page/collection/gallery', 'public');
                    $galleryPaths[] = $path;
                }
                $data['collection_gallery_images'] = $galleryPaths;
            } catch (\Exception $e) {
                \Log::error('Error uploading collection gallery images: ' . $e->getMessage());
            }
        }
        
        // Update data with processed images
        $cmsData['data'] = $data;
        
        if ($request->has('is_published')) {
            $cmsData['published_at'] = now();
        }
        
        // Create or update home page
        $homePage = WebsiteCms::where('section_type', 'home_page')->first();
        
        if ($homePage) {
            // Preserve existing images if new ones not uploaded
            $existingData = $homePage->data ?? [];
            for ($i = 1; $i <= 4; $i++) {
                if (!$request->hasFile('hero_image_' . $i) && isset($existingData['hero_image_' . $i])) {
                    $data['hero_image_' . $i] = $existingData['hero_image_' . $i];
                }
            }
            
            // Preserve existing collection images if new ones not uploaded
            if (!$request->hasFile('collection_featured_image') && isset($existingData['collection_featured_image'])) {
                $data['collection_featured_image'] = $existingData['collection_featured_image'];
            }
            if (!$request->hasFile('collection_gallery_images') && isset($existingData['collection_gallery_images'])) {
                $data['collection_gallery_images'] = $existingData['collection_gallery_images'];
            }
            
            $cmsData['data'] = $data;
            $homePage->update($cmsData);
            $message = 'Home page updated successfully';
        } else {
            WebsiteCms::create($cmsData);
            $message = 'Home page created successfully';
        }
        
        return redirect()->route('admin.website-management.home-page')
                       ->with('success', $message);
    }
}
