<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_products', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_products', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_products', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_products', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of products with search and filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by product name or SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', (bool)$request->status);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        $products = $query->paginate(15)->appends($request->query());
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL'];
        $colors = ['Black', 'White', 'Navy', 'Gray', 'Brown', 'Red', 'Blue', 'Green', 'Gold', 'Silver', 'Cream', 'Maroon'];
        $fabricTypes = ['Cotton', 'Silk', 'Wool', 'Polyester', 'Linen', 'Blend', 'Chiffon', 'Georgette', 'Satin', 'Velvet'];

        return view('admin.products.create', compact('categories', 'sizes', 'colors', 'fabricTypes'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'fabric_type' => 'nullable|string',
            'color' => 'nullable|string',
            'available_sizes' => 'nullable|array',
            'available_sizes.*' => 'string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Handle main product image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL'];
        $colors = ['Black', 'White', 'Navy', 'Gray', 'Brown', 'Red', 'Blue', 'Green', 'Gold', 'Silver', 'Cream', 'Maroon'];
        $fabricTypes = ['Cotton', 'Silk', 'Wool', 'Polyester', 'Linen', 'Blend', 'Chiffon', 'Georgette', 'Satin', 'Velvet'];

        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors', 'fabricTypes'));
    }

    /**
     * Update the specified product in database
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'fabric_type' => 'nullable|string',
            'color' => 'nullable|string',
            'available_sizes' => 'nullable|array',
            'available_sizes.*' => 'string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Handle main product image
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            // Delete old gallery images if exists
            if ($product->gallery) {
                foreach ($product->gallery as $img) {
                    Storage::disk('public')->delete($img);
                }
            }

            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from database
     */
    public function destroy(Product $product)
    {
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete gallery images
        if ($product->gallery) {
            foreach ($product->gallery as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
