<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display all brands
     */
    public function index()
    {
        $brands = Brand::where('is_active', true)
                      ->orderBy('sort_order')
                      ->get();

        return view('frontend.brands', compact('brands'));
    }

    /**
     * Show products for a specific brand
     */
    public function show($slug, Request $request)
    {
        $brand = Brand::where('slug', $slug)
                     ->where('is_active', true)
                     ->firstOrFail();

        // Get products for this brand
        $query = Product::where('brand_id', $brand->id)
                       ->where('is_active', true)
                       ->whereHas('displaySections', function ($q) {
                           $q->where('section', 'shop_page')
                             ->where('is_active', true);
                       });

        // Search by product name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                        ->where('price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                        ->where('price', '<=', $request->max_price);
                  });
            });
        }

        // Filter by size
        if ($request->has('size') && $request->size) {
            $query->where(function ($q) use ($request) {
                $q->where('size', $request->size)
                  ->orWhereJsonContains('available_sizes', $request->size);
            });
        }

        // Filter by color
        if ($request->has('color') && $request->color) {
            $query->where(function ($q) use ($request) {
                $q->where('color', $request->color)
                  ->orWhereJsonContains('available_colors', $request->color);
            });
        }

        // Filter by fabric type
        if ($request->has('fabric') && $request->fabric) {
            $query->where('fabric_type', $request->fabric);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price')->orderByDesc('price');
                break;
            case 'popular':
                $query->orderByDesc('stock_quantity');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->with(['images', 'category'])->paginate(12)->appends($request->query());

        // Get filter data
        $colors = Product::where('brand_id', $brand->id)
                        ->where('is_active', true)
                        ->whereNotNull('color')
                        ->distinct()
                        ->pluck('color');

        $sizes = Product::where('brand_id', $brand->id)
                       ->where('is_active', true)
                       ->whereNotNull('size')
                       ->distinct()
                       ->pluck('size');

        $fabrics = Product::where('brand_id', $brand->id)
                         ->where('is_active', true)
                         ->whereNotNull('fabric_type')
                         ->distinct()
                         ->pluck('fabric_type');

        return view('frontend.brand-detail', compact('brand', 'products', 'colors', 'sizes', 'fabrics'));
    }
}
