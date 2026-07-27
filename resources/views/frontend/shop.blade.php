@extends('frontend.layouts.app')

@section('title', 'Shop - Men\'s Clothing & Tailoring')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif;">Our Collection</h1>
    <p>Browse our premium selection of men's fashion</p>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('shop') }}" class="mb-4">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="Search products..."
                                value="{{ request('search') }}"
                                style="border-color: var(--accent-gold);"
                            >
                            <button class="btn" type="submit" style="background: var(--accent-gold); color: var(--primary-dark); border: none;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Filter: Category -->
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-filter"></i> Categories
                            </h6>
                            <form method="GET" action="{{ route('shop') }}">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('shop') }}" class="list-group-item list-group-item-action p-2" style="border: none; color: var(--primary-dark);">
                                        All Products
                                    </a>
                                    <!-- Cotton -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="cotton"
                                            {{ request('category') == 'cotton' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Cotton</span>
                                    </label>
                                    <!-- Wash & Wear -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="wash-wear"
                                            {{ request('category') == 'wash-wear' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Wash & Wear</span>
                                    </label>
                                    <!-- Khaddar -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="khaddar"
                                            {{ request('category') == 'khaddar' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Khaddar</span>
                                    </label>
                                    <!-- Linen -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="linen"
                                            {{ request('category') == 'linen' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Linen</span>
                                    </label>
                                    <!-- Boski -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="boski"
                                            {{ request('category') == 'boski' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Boski</span>
                                    </label>
                                    <!-- Dhanakye -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="dhanakye"
                                            {{ request('category') == 'dhanakye' ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Dhanak</span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Filter: Price Range -->
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-tag"></i> Price Range
                            </h6>
                            <form method="GET" action="{{ route('shop') }}" id="priceForm">
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">Min Price (Rs.)</label>
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        class="form-control"
                                        placeholder="0"
                                        value="{{ request('min_price') }}"
                                        style="border-color: var(--accent-gold);"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">Max Price (Rs.)</label>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        class="form-control"
                                        placeholder="50000"
                                        value="{{ request('max_price') }}"
                                        style="border-color: var(--accent-gold);"
                                    >
                                </div>
                                <button type="submit" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600;">
                                    Apply Filter
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Filter: Size -->
                    @if($sizes->count() > 0)
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-ruler"></i> Size
                            </h6>
                            <form method="GET" action="{{ route('shop') }}">
                                <div class="list-group list-group-flush">
                                    @foreach($sizes as $size)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="size" 
                                            value="{{ $size }}"
                                            {{ request('size') == $size ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">{{ $size }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Filter: Color -->
                    @if($colors->count() > 0)
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-palette"></i> Color
                            </h6>
                            <form method="GET" action="{{ route('shop') }}">
                                <div class="list-group list-group-flush">
                                    @foreach($colors as $color)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="color" 
                                            value="{{ $color }}"
                                            {{ request('color') == $color ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">{{ $color }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Filter: Fabric Type -->
                    @if($fabrics->count() > 0)
                    <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-layer-group"></i> Fabric Type
                            </h6>
                            <form method="GET" action="{{ route('shop') }}">
                                <div class="list-group list-group-flush">
                                    @foreach($fabrics as $fabric)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="fabric" 
                                            value="{{ $fabric }}"
                                            {{ request('fabric') == $fabric ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        <span style="color: var(--primary-dark);">{{ $fabric }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Clear Filters -->
                    @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'size', 'color', 'fabric']))
                    <div class="mt-3">
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100" style="border-color: var(--accent-gold); color: var(--accent-gold);">
                            <i class="fas fa-times"></i> Clear All Filters
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-md-8">
                <!-- Top Bar: Sort & Results -->
                <div class="row mb-4 align-items-center" style="border-bottom: 2px solid var(--accent-gold); padding-bottom: 15px;">
                    <div class="col-md-6">
                        <p class="mb-0" style="color: var(--primary-dark); font-weight: 500;">
                            Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
                        </p>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('shop') }}" class="d-flex justify-content-end">
                            <select name="sort" class="form-select" style="width: auto; border-color: var(--accent-gold);" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort: Latest</option>
                                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="product-card">
                            <div class="product-image" style="position: relative; height: 300px;">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                                @else
                                    <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                                
                                <!-- Stock Status Badge -->
                                @if($product->stock_quantity > 0)
                                    <span class="product-badge" style="position: absolute; top: 15px; right: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        In Stock
                                    </span>
                                @else
                                    <span class="product-badge" style="position: absolute; top: 15px; right: 15px; background: #dc3545; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        Out of Stock
                                    </span>
                                @endif

                                <!-- Discount Badge -->
                                @if($product->hasDiscount())
                                    <span style="position: absolute; top: 15px; left: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; font-weight: 600;">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif

                                <!-- Quick View & Add to Cart Overlay -->
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(11, 11, 11, 0.9); opacity: 0; transition: opacity 0.3s ease; display: flex; gap: 10px; padding: 15px; height: auto;" class="product-overlay">
                                    <button class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; border: none; padding: 10px;" onclick="quickView('{{ $product->slug }}')">
                                        <i class="fas fa-eye"></i> Quick View
                                    </button>
                                    <button class="btn w-100" style="background: transparent; color: var(--accent-gold); font-weight: 600; border: 2px solid var(--accent-gold);" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="product-info" style="padding: 1.5rem;">
                                <!-- Category -->
                                <p style="font-size: 0.8rem; color: var(--accent-gold); font-weight: 600; margin-bottom: 0.5rem;">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </p>

                                <!-- Product Name -->
                                <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--primary-dark); margin-bottom: 0.5rem; min-height: 2.5rem;">
                                    {{ $product->name }}
                                </h3>

                                <!-- Attributes -->
                                @if($product->color || $product->material)
                                <small style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">
                                    @if($product->color)
                                        <strong>Color:</strong> {{ $product->color }}
                                    @endif
                                    @if($product->material)
                                        | <strong>Material:</strong> {{ $product->material }}
                                    @endif
                                </small>
                                @endif

                                <!-- Available Sizes -->
                                @if($product->available_sizes && is_array($product->available_sizes) && count($product->available_sizes) > 0)
                                <div style="margin-bottom: 0.5rem;">
                                    <small style="color: var(--text-muted);">
                                        <strong>Sizes:</strong> {{ implode(', ', $product->available_sizes) }}
                                    </small>
                                </div>
                                @elseif($product->size)
                                <div style="margin-bottom: 0.5rem;">
                                    <small style="color: var(--text-muted);">
                                        <strong>Size:</strong> {{ $product->size }}
                                    </small>
                                </div>
                                @endif

                                <!-- Pricing -->
                                <div style="margin-bottom: 1rem;">
                                    <span style="font-size: 1.3rem; font-weight: 700; color: var(--accent-gold);">
                                        Rs. {{ number_format($product->final_price, 0) }}
                                    </span>
                                    @if($product->hasDiscount())
                                        <span style="font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through; margin-left: 0.5rem;">
                                            Rs. {{ number_format($product->price, 0) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="btn w-100" style="background: transparent; color: var(--primary-dark); border: 2px solid var(--primary-dark); font-weight: 600; padding: 8px;">
                                        <i class="fas fa-info-circle"></i> Details
                                    </a>
                                    <button class="btn" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; border: none; padding: 8px 15px;" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                        <p class="text-muted" style="font-size: 1.1rem;">No products found matching your criteria.</p>
                        <a href="{{ route('shop') }}" class="btn-premium mt-3">View All Products</a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
                @else
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p class="text-muted" style="font-size: 1.1rem;">No products available at the moment.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 2px solid var(--accent-gold);">
            <div class="modal-header" style="border-bottom: 2px solid var(--accent-gold); background: #F8F5EF;">
                <h5 class="modal-title fw-bold" style="color: var(--primary-dark);">Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<style>
    .product-card:hover .product-overlay {
        opacity: 1 !important;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(212, 175, 55, 0.2);
    }

    .pagination .page-link {
        color: var(--primary-dark);
        border-color: var(--accent-gold);
    }

    .pagination .page-link:hover,
    .pagination .page-link.active {
        background: var(--accent-gold);
        border-color: var(--accent-gold);
        color: var(--primary-dark);
    }

    .sticky-top {
        z-index: 100;
    }

    @media (max-width: 768px) {
        .sticky-top {
            position: static;
        }

        .product-overlay {
            opacity: 1 !important;
            position: relative;
            background: white;
            padding: 10px !important;
            border-top: 2px solid var(--accent-gold);
        }
    }
</style>

@endsection

@section('scripts')
<script>
function addToCart(productId) {
    // TODO: Implement add to cart functionality
    alert('Product added to cart! (Demo)');
    document.querySelector('.cart-badge').textContent = parseInt(document.querySelector('.cart-badge').textContent) + 1;
}

function quickView(slug) {
    // TODO: Load product details via AJAX for quick view
    alert('Quick View - Product: ' + slug);
    new bootstrap.Modal(document.getElementById('quickViewModal')).show();
}
</script>
@endsection
