<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active categories
        $categories = Category::where('is_active', true)
                             ->orderBy('sort_order')
                             ->get();

        // Get featured products (all active products for now)
        $featuredProducts = Product::where('is_active', true)
                                   ->where('stock_quantity', '>', 0)
                                   ->take(8)
                                   ->get();

        // Get categories with product count
        $categoriesWithCount = Category::where('is_active', true)
                                      ->withCount('products')
                                      ->orderBy('sort_order')
                                      ->take(6)
                                      ->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'categoriesWithCount'));
    }

    public function shop(Request $request)
    {
        $query = Product::where('is_active', true);

        // Search by product name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category - search by ID, slug, name, or product name
        if ($request->has('category') && $request->category) {
            $categoryValue = $request->category;
            $query->where(function ($q) use ($categoryValue) {
                // Try to find by category_id first
                $q->where('category_id', $categoryValue)
                  // Also search by category name or slug in product name/description
                  ->orWhere('name', 'like', "%{$categoryValue}%")
                  ->orWhere('description', 'like', "%{$categoryValue}%");
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
                // Could add popular/bestseller column
                $query->orderByDesc('stock_quantity');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        // Get filter data
        $categories = Category::where('is_active', true)
                             ->orderBy('sort_order')
                             ->get();

        $colors = Product::where('is_active', true)
                        ->whereNotNull('color')
                        ->distinct()
                        ->pluck('color');

        $sizes = Product::where('is_active', true)
                       ->whereNotNull('size')
                       ->distinct()
                       ->pluck('size');

        $fabrics = Product::where('is_active', true)
                         ->whereNotNull('fabric_type')
                         ->distinct()
                         ->pluck('fabric_type');

        return view('frontend.shop', compact('products', 'categories', 'colors', 'sizes', 'fabrics'));
    }

    public function productDetail($identifier)
    {
        // Try to find by slug first, then by ID
        $product = Product::where('slug', $identifier)
                         ->where('is_active', true)
                         ->first();
        
        // If not found by slug, try by ID
        if (!$product) {
            $product = Product::where('id', $identifier)
                             ->where('is_active', true)
                             ->firstOrFail();
        }
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->take(4)
                                 ->get();

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }

    public function tailoring()
    {
        return view('frontend.tailoring');
    }

    public function collections()
    {
        // Get best sellers (products with high stock movement - for now, most recent active products)
        $bestSellers = Product::where('is_active', true)
                             ->where('stock_quantity', '>', 0)
                             ->orderByDesc('created_at')
                             ->take(8)
                             ->get();

        // Get summer 2026 collection (recent products - can be tagged with a category later)
        $summerCollection = Product::where('is_active', true)
                                  ->where('stock_quantity', '>', 0)
                                  ->orderByDesc('created_at')
                                  ->skip(8)
                                  ->take(8)
                                  ->get();

        // Get featured collection (highlighted products)
        $featuredCollection = Product::where('is_active', true)
                                    ->where('stock_quantity', '>', 0)
                                    ->orderByDesc('created_at')
                                    ->skip(16)
                                    ->take(8)
                                    ->get();

        return view('frontend.collections', compact('bestSellers', 'summerCollection', 'featuredCollection'));
    }

    public function bestSellers(Request $request)
    {
        // Get best selling products
        $query = Product::where('is_active', true)
                       ->where('stock_quantity', '>', 0)
                       ->orderByDesc('created_at');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price')->orderByDesc('price');
                break;
            case 'popular':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('frontend.collection-detail', [
            'products' => $products,
            'title' => 'Best Sellers',
            'description' => 'Our most popular items that customers love',
            'collectionType' => 'best-sellers'
        ]);
    }

    public function summer2026(Request $request)
    {
        // Get summer 2026 collection products
        $query = Product::where('is_active', true)
                       ->where('stock_quantity', '>', 0)
                       ->orderByDesc('created_at');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price')->orderByDesc('price');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('frontend.collection-detail', [
            'products' => $products,
            'title' => 'Summer 2026 Collection',
            'description' => 'Fresh styles for the warm season - lightweight, breathable, and effortlessly stylish',
            'collectionType' => 'summer-2026'
        ]);
    }

    public function featured(Request $request)
    {
        // Get featured collection products
        $query = Product::where('is_active', true)
                       ->where('stock_quantity', '>', 0)
                       ->orderByDesc('created_at');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price')->orderByDesc('price');
                break;
            case 'featured':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('frontend.collection-detail', [
            'products' => $products,
            'title' => 'Featured Collection',
            'description' => 'Handpicked pieces that define this season\'s elegance',
            'collectionType' => 'featured'
        ]);
    }

    public function contact()
    {
        // Get contact information from CMS
        $contactInfo = \App\Models\WebsiteCms::where('section_type', 'contact')
                                             ->where('is_published', true)
                                             ->first();
        
        // Get contact locations (multiple contact entries)
        $locations = \App\Models\WebsiteCms::where('section_type', 'contact_location')
                                          ->where('is_published', true)
                                          ->orderBy('display_order')
                                          ->get();
        
        return view('frontend.contact', compact('contactInfo', 'locations'));
    }

    public function disclaimer()
    {
        return view('frontend.disclaimer');
    }

    public function returnExchange()
    {
        return view('frontend.return-exchange');
    }

    public function shippingPolicy()
    {
        return view('frontend.shipping-policy');
    }

    public function trackOrder()
    {
        return view('frontend.track-order');
    }

    public function feedbackSurvey()
    {
        return view('frontend.feedback-survey');
    }

    public function privacyPolicy()
    {
        return view('frontend.privacy-policy');
    }

    public function termsOfService()
    {
        return view('frontend.terms-of-service');
    }

    public function newIn(Request $request)
    {
        // Get the latest products (created in the last 30 days or newest 12 products)
        $query = Product::where('is_active', true)
                       ->where('created_at', '>=', now()->subDays(30))
                       ->orderByDesc('created_at');

        // If no products in last 30 days, just show the newest products
        if ($query->count() == 0) {
            $query = Product::where('is_active', true)
                           ->orderByDesc('created_at');
        }

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc')->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price')->orderByDesc('price');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('frontend.new-in', compact('products'));
    }

    public function summerSale(Request $request)
    {
        // Get products with discount (sale items)
        $query = Product::where('is_active', true)
                       ->whereNotNull('discount_price')
                       ->where('discount_price', '<', \DB::raw('price'))
                       ->orderByDesc('created_at');

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'discount');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('discount_price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('discount_price');
                break;
            case 'discount':
            default:
                // Sort by discount percentage (highest discount first)
                $query->orderByRaw('((price - discount_price) / price) DESC');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('frontend.summer-sale', compact('products'));
    }
}
