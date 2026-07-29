<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductDisplaySection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSectionController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $pageTitle = 'Product Sections Management';
        $pageDescription = 'Manage products and their display sections across the website';
        
        $products = Product::with(['category', 'images', 'displaySections'])
                          ->latest()
                          ->paginate(15);
        
        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'in_stock' => Product::where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];
        
        return view('admin.website-management.product-sections.index', compact(
            'pageTitle',
            'pageDescription',
            'products',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $pageTitle = 'Add New Product';
        $pageDescription = 'Create a new product and assign it to display sections';
        
        $categories = Category::where('is_active', true)->get();
        $sections = ProductDisplaySection::SECTIONS;
        
        return view('admin.website-management.product-sections.create', compact(
            'pageTitle',
            'pageDescription',
            'categories',
            'sections'
        ));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections' => 'nullable|array',
            'sections.*' => 'in:' . implode(',', array_keys(ProductDisplaySection::SECTIONS)),
        ]);
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Auto-generate SKU if not provided
        $sku = 'PRD-' . strtoupper(Str::random(8));
        while (Product::where('sku', $sku)->exists()) {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        }
        
        // Calculate discount percentage
        $discountPrice = null;
        if (!empty($validated['sale_price'])) {
            $discountPrice = $validated['sale_price'];
        }
        
        // Create product
        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'sku' => $sku,
            'brand' => $validated['brand'] ?? null,
            'price' => $validated['regular_price'],
            'regular_price' => $validated['regular_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'discount_price' => $discountPrice,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['short_description'] ?? null,
            'full_description' => $validated['full_description'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image');
            $path = $featuredImage->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_featured' => true,
                'display_order' => 0,
                'alt_text' => $product->name,
            ]);
            
            // Also update product image field for backward compatibility
            $product->update(['image' => $path]);
        }
        
        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $displayOrder = 1;
            foreach ($request->file('gallery_images') as $galleryImage) {
                $path = $galleryImage->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => false,
                    'display_order' => $displayOrder,
                    'alt_text' => $product->name . ' - Image ' . $displayOrder,
                ]);
                
                $displayOrder++;
            }
        }
        
        // Handle display sections
        if ($request->has('sections') && is_array($request->sections)) {
            $displayOrder = 1;
            foreach ($request->sections as $section) {
                ProductDisplaySection::create([
                    'product_id' => $product->id,
                    'section' => $section,
                    'display_order' => $displayOrder,
                    'is_active' => true,
                ]);
                $displayOrder++;
            }
        }
        
        return redirect()->route('admin.website-management.product-sections.index')
                       ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $productSection)
    {
        $product = $productSection->load(['category', 'images', 'displaySections']);
        
        $pageTitle = 'Product Details: ' . $product->name;
        $pageDescription = 'View product information and display sections';
        
        return view('admin.website-management.product-sections.show', compact(
            'pageTitle',
            'pageDescription',
            'product'
        ));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $productSection)
    {
        $product = $productSection->load(['category', 'images', 'displaySections']);
        
        $pageTitle = 'Edit Product: ' . $product->name;
        $pageDescription = 'Update product information and display sections';
        
        $categories = Category::where('is_active', true)->get();
        $sections = ProductDisplaySection::SECTIONS;
        $selectedSections = $product->displaySections->pluck('section')->toArray();
        
        return view('admin.website-management.product-sections.edit', compact(
            'pageTitle',
            'pageDescription',
            'product',
            'categories',
            'sections',
            'selectedSections'
        ));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $productSection)
    {
        $product = $productSection;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections' => 'nullable|array',
            'sections.*' => 'in:' . implode(',', array_keys(ProductDisplaySection::SECTIONS)),
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
        ]);
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Auto-generate SKU if product doesn't have one
        if (empty($product->sku)) {
            $sku = 'PRD-' . strtoupper(Str::random(8));
            while (Product::where('sku', $sku)->exists()) {
                $sku = 'PRD-' . strtoupper(Str::random(8));
            }
        } else {
            $sku = $product->sku;
        }
        
        // Calculate discount price
        $discountPrice = null;
        if (!empty($validated['sale_price'])) {
            $discountPrice = $validated['sale_price'];
        }
        
        // Update product
        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'sku' => $sku,
            'brand' => $validated['brand'] ?? null,
            'price' => $validated['regular_price'],
            'regular_price' => $validated['regular_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'discount_price' => $discountPrice,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['short_description'] ?? null,
            'full_description' => $validated['full_description'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            $oldFeatured = $product->featuredImage;
            if ($oldFeatured) {
                if (Storage::disk('public')->exists($oldFeatured->image_path)) {
                    Storage::disk('public')->delete($oldFeatured->image_path);
                }
                $oldFeatured->delete();
            }
            
            // Upload new featured image
            $featuredImage = $request->file('featured_image');
            $path = $featuredImage->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_featured' => true,
                'display_order' => 0,
                'alt_text' => $product->name,
            ]);
            
            // Update product image field
            $product->update(['image' => $path]);
        }
        
        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            // Get max display order
            $maxOrder = ProductImage::where('product_id', $product->id)
                                   ->where('is_featured', false)
                                   ->max('display_order') ?? 0;
            
            $displayOrder = $maxOrder + 1;
            foreach ($request->file('gallery_images') as $galleryImage) {
                $path = $galleryImage->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => false,
                    'display_order' => $displayOrder,
                    'alt_text' => $product->name . ' - Image ' . $displayOrder,
                ]);
                
                $displayOrder++;
            }
        }
        
        // Handle display sections - sync them
        // Delete sections that are not selected
        ProductDisplaySection::where('product_id', $product->id)->delete();
        
        // Create new sections
        if ($request->has('sections') && is_array($request->sections)) {
            $displayOrder = 1;
            foreach ($request->sections as $section) {
                ProductDisplaySection::create([
                    'product_id' => $product->id,
                    'section' => $section,
                    'display_order' => $displayOrder,
                    'is_active' => true,
                ]);
                $displayOrder++;
            }
        }
        
        return redirect()->route('admin.website-management.product-sections.index')
                       ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $productSection)
    {
        $product = $productSection;
        
        // Delete all product images
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        // Delete product (sections will be deleted automatically due to cascade)
        $product->delete();
        
        return redirect()->route('admin.website-management.product-sections.index')
                       ->with('success', 'Product deleted successfully!');
    }

    /**
     * Delete a specific product image via AJAX
     */
    public function deleteImage(Product $productSection, ProductImage $image)
    {
        // Verify the image belongs to this product
        if ($image->product_id !== $productSection->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete database record
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
