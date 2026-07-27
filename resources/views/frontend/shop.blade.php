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
                    <!-- Filter: Category -->
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-filter"></i> Categories
                            </h6>
                            <form method="GET" action="{{ route('shop') }}" id="categoryForm">
                                <!-- Preserve other filter parameters -->
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('min_price'))
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                @endif
                                @if(request('max_price'))
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

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
                                            onchange="document.getElementById('categoryForm').submit()"
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
                                            onchange="document.getElementById('categoryForm').submit()"
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
                                            onchange="document.getElementById('categoryForm').submit()"
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
                                            onchange="document.getElementById('categoryForm').submit()"
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
                                            onchange="document.getElementById('categoryForm').submit()"
                                        >
                                        <span style="color: var(--primary-dark);">Boski</span>
                                    </label>
                                    <!-- Dhanak -->
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="category" 
                                            value="dhanakye"
                                            {{ request('category') == 'dhanakye' ? 'checked' : '' }}
                                            onchange="document.getElementById('categoryForm').submit()"
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
                        <form method="GET" action="{{ route('shop') }}" class="d-flex justify-content-end align-items-center" style="gap: 12px;">
                            <!-- Preserve other filter parameters -->
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('min_price'))
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif
                            @if(request('max_price'))
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif

                            <!-- Search Bar -->
                            <div class="input-group" style="width: 250px; flex-shrink: 0; position: relative;">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="searchInput"
                                    class="form-control" 
                                    placeholder="Search products..."
                                    value="{{ request('search') }}"
                                    style="border-color: var(--accent-gold); font-size: 0.9rem; padding-right: 40px;"
                                    oninput="toggleClearBtn()"
                                >
                                <!-- Clear Button (X icon) inside input -->
                                <button type="button" id="clearSearchBtn" onclick="clearSearchText()" style="position: absolute; right: 45px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; cursor: pointer; font-size: 1.1rem; padding: 0; display: none; align-items: center; justify-content: center; z-index: 10;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn" type="submit" style="background: var(--accent-gold); color: var(--primary-dark); border: none;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                            <!-- Sort Dropdown -->
                            <select name="sort" class="form-select" style="width: auto; border-color: var(--accent-gold); font-size: 0.9rem; flex-shrink: 0;" onchange="this.form.submit()">
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
                        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <!-- Image Section -->
                            <div style="position: relative; width: 100%; height: 320px; background: #e9e9e9; overflow: hidden;">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/320x320?text=No+Image'">
                                @else
                                    <img src="https://via.placeholder.com/320x320?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                                
                                <!-- Discount Badge -->
                                @if($product->hasDiscount())
                                    <span style="position: absolute; top: 15px; left: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 8px 15px; border-radius: 4px; font-size: 0.9rem; font-weight: 700; z-index: 5;">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Info Section -->
                            <div style="padding: 20px; text-align: center;">
                                <!-- Product Name -->
                                <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.5rem; line-height: 1.4;">
                                    {{ $product->name }}
                                </h3>

                                <!-- Pricing -->
                                <div style="margin-bottom: 1.5rem;">
                                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--accent-gold);">
                                        Rs. {{ number_format($product->final_price, 0) }}
                                    </span>
                                    @if($product->hasDiscount())
                                        <span style="font-size: 0.8rem; color: #999; text-decoration: line-through; margin-left: 0.5rem;">
                                            Rs. {{ number_format($product->price, 0) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                    <button onclick="addToCart({{ $product->id }}); event.stopPropagation();" style="flex: 1; background: var(--accent-gold); border: none; color: #0B0B0B; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fas fa-shopping-cart"></i> Add
                                    </button>
                                    <a href="{{ route('product.detail', $product->slug) }}" style="width: 45px; height: 45px; background: white; border: 2px solid var(--accent-gold); color: var(--accent-gold); border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
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

function toggleClearBtn() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    if (searchInput.value.trim() !== '') {
        clearBtn.style.display = 'flex';
    } else {
        clearBtn.style.display = 'none';
    }
}

function clearSearchText() {
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';
    document.getElementById('clearSearchBtn').style.display = 'none';
    searchInput.focus();
    // Redirect to shop page to show all products
    window.location.href = "{{ route('shop') }}";
}

// Initialize clear button visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleClearBtn();
});
</script>
@endsection
