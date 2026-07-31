<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
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

        // Get featured products from product_display_sections
        $featuredProducts = Product::where('is_active', true)
                                   ->where('stock_quantity', '>', 0)
                                   ->whereHas('displaySections', function ($query) {
                                       $query->where('section', 'home_featured')
                                             ->where('is_active', true);
                                   })
                                   ->with(['images', 'category', 'displaySections'])
                                   ->orderBy('created_at', 'desc')
                                   ->take(8)
                                   ->get();

        // If no products are assigned to home_featured, show none
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = collect();
        }

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
                // Only show products explicitly assigned to the Shop Page section.
                $query = Product::where('is_active', true)
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

        // Filter by category - search by ID, slug, or name
        if ($request->has('category') && $request->category) {
            $categoryValue = $request->category;
            
            // Find category by ID, slug, or name
            $category = Category::where(function ($q) use ($categoryValue) {
                $q->where('id', $categoryValue)
                  ->orWhere('slug', $categoryValue)
                  ->orWhere('name', 'like', "%{$categoryValue}%");
            })->first();
            
            if ($category) {
                $query->where('category_id', $category->id);
            }
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

        $products = $query->with(['images', 'category'])->paginate(12)->appends($request->query());

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
                         ->with(['images', 'category', 'displaySections'])
                         ->first();
        
        // If not found by slug, try by ID
        if (!$product) {
            $product = Product::where('id', $identifier)
                             ->where('is_active', true)
                             ->with(['images', 'category', 'displaySections'])
                             ->firstOrFail();
        }
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->with(['images', 'category'])
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
        // Get published collection sections
        $collectionSections = \App\Models\CollectionSection::published()
                                                          ->ordered()
                                                          ->with('images')
                                                          ->get();

        return view('frontend.collections', compact('collectionSections'));
    }

    public function bestSellers(Request $request)
    {
        // Get best selling products from product sections
        $query = Product::where('is_active', true)
                       ->where('stock_quantity', '>', 0)
                       ->whereHas('displaySections', function ($q) {
                           $q->where('section', 'best_sellers')
                             ->where('is_active', true);
                       })
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
                $query->orderBy('sale_price', 'asc')->orderBy('regular_price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('sale_price')->orderByDesc('regular_price');
                break;
            case 'popular':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->with(['images', 'category'])->paginate(12)->appends($request->query());

        return view('frontend.collection-detail', [
            'products' => $products,
            'title' => 'Best Sellers',
            'description' => 'Our most popular items that customers love',
            'collectionType' => 'best-sellers'
        ]);
    }

    public function summer2026(Request $request)
    {
        // Get summer 2026 collection products from product sections
        $query = Product::where('is_active', true)
                       ->where('stock_quantity', '>', 0)
                       ->whereHas('displaySections', function ($q) {
                           $q->where('section', 'summer_2026')
                             ->where('is_active', true);
                       })
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
                $query->orderBy('sale_price', 'asc')->orderBy('regular_price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('sale_price')->orderByDesc('regular_price');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->with(['images', 'category'])->paginate(12)->appends($request->query());

        return view('frontend.collection-detail', [
            'products' => $products,
            'title' => 'Summer 2026 Collection',
            'description' => 'Fresh styles for the warm season - lightweight, breathable, and effortlessly stylish',
            'collectionType' => 'summer-2026'
        ]);
    }

    public function featured(Request $request)
    {
                // Only get products assigned to the Collections feature section
                $query = Product::where('is_active', true)
                                             ->where('stock_quantity', '>', 0)
                                             ->whereHas('displaySections', function ($q) {
                                                     $q->where('section', 'collections')
                                                         ->where('is_active', true);
                                             })
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
        // Get products assigned to "New In" section
        $query = Product::where('is_active', true)
                       ->whereHas('displaySections', function ($q) {
                           $q->where('section', 'new_in')
                             ->where('is_active', true);
                       })
                       ->orderByDesc('created_at');

        // If no products are assigned to this section, return an empty result
        if ($query->count() == 0) {
            $query = Product::whereRaw('0 = 1');
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
                $query->orderBy('sale_price', 'asc')->orderBy('regular_price', 'asc');
                break;
            case 'price-high':
                $query->orderByDesc('sale_price')->orderByDesc('regular_price');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $products = $query->with(['images', 'category'])->paginate(12)->appends($request->query());

        return view('frontend.new-in', compact('products'));
    }

    public function brandsPage(Request $request)
    {
        // Get products assigned to "Brands Page" section with a specific brand
        $brand = $request->get('brand');
        
        $query = Product::where('is_active', true)
                       ->whereHas('displaySections', function ($q) {
                           $q->where('section', 'brands_page')
                             ->where('is_active', true);
                       });

        // Filter by brand if provided
        if ($brand) {
            $query->where('brand_id', $brand);
        }

        $query->orderByDesc('created_at');

        // If no products are assigned to this section, return an empty result
        if ($query->count() == 0) {
            $query = Product::whereRaw('0 = 1');
        }

        // Apply search filter if provided
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

        // Apply sorting
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

        $products = $query->with(['images', 'category', 'brandModel'])->paginate(12)->appends($request->query());

        // Get all brands for filter
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->get();

        // Get filter data
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

        return view('frontend.brands-page', compact('products', 'brands', 'colors', 'sizes', 'fabrics', 'brand'));
    }
}
