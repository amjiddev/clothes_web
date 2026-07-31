<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            // Generate slug from brand name
            $validated['slug'] = Str::slug($validated['name']);
            
            // Check if slug already exists
            $existingBrand = Brand::where('slug', $validated['slug'])->first();
            if ($existingBrand) {
                $validated['slug'] = Str::slug($validated['name']) . '-' . time();
            }

            $brand = Brand::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand created successfully',
                    'brand' => $brand,
                ], 201);
            }

            return redirect()->route('admin.website-management.product-sections.index')
                ->with('success', 'Brand created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating brand: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error creating brand: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            // Update slug if name changed
            if ($validated['name'] !== $brand->name) {
                $validated['slug'] = Str::slug($validated['name']);
                
                // Check if new slug already exists
                $existingBrand = Brand::where('slug', $validated['slug'])
                    ->where('id', '!=', $brand->id)
                    ->first();
                
                if ($existingBrand) {
                    $validated['slug'] = Str::slug($validated['name']) . '-' . time();
                }
            }

            $brand->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand updated successfully',
                    'brand' => $brand,
                ], 200);
            }

            return redirect()->route('admin.website-management.product-sections.index')
                ->with('success', 'Brand updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating brand: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error updating brand: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy(Brand $brand, Request $request)
    {
        try {
            $brand->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand deleted successfully',
                ], 200);
            }

            return redirect()->route('admin.website-management.product-sections.index')
                ->with('success', 'Brand deleted successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting brand: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }

    /**
     * Get brand by ID for editing (API endpoint)
     */
    public function getById($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return response()->json([
                'success' => true,
                'brand' => $brand,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found',
            ], 404);
        }
    }
}
