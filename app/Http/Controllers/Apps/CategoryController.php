<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_categories', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_categories', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_categories', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_categories', ['only' => ['destroy']]);
    }

    /**
     * Display all categories with products count
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search by category name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', (bool)$request->status);
        }

        // Order by sort_order
        $categories = $query->orderBy('sort_order')->paginate(15)->appends($request->query());

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $defaultCategories = [
            'Shalwar Kameez',
            'Suits',
            'Kurta',
            'Waistcoat',
            'Sherwani',
            'Formal Wear',
            'Casual Wear',
            'Fabric Collection',
        ];

        return view('admin.categories.create', compact('defaultCategories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Set default sort_order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Category::max('sort_order') + 1;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load(['products' => function ($query) {
            $query->where('is_active', true)->latest();
        }]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        $productCount = $category->products()->count();
        return view('admin.categories.edit', compact('category', 'productCount'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id . '|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category with existing products. Please delete or move products first.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    /**
     * Get statistics for a category
     */
    public function getStats(Category $category)
    {
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'products_count' => $category->products()->count(),
            'active_products' => $category->products()->where('is_active', true)->count(),
            'inactive_products' => $category->products()->where('is_active', false)->count(),
            'status' => $category->is_active ? 'Active' : 'Inactive',
        ]);
    }
}
