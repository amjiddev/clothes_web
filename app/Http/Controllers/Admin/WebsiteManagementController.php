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
}
