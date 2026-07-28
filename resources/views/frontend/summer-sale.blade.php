@extends('frontend.layouts.app')

@section('title', 'Summer Sale - Special Discounts')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="background: #f8f9fa; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
    <div class="container">
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--primary-dark); text-decoration: none;"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: var(--accent-gold); font-weight: 600;">Summer Sale</li>
        </ol>
    </div>
</nav>

<!-- Products Section -->
<section class="section-padding" style="background: white; padding: 60px 0;">
    <div class="container">
        <!-- Top Bar: Search & Sort -->
        <div class="row mb-5 align-items-center" style="border-bottom: 2px solid var(--accent-gold); padding-bottom: 20px;">
            <div class="col-md-6">
                <p class="mb-0" style="color: var(--primary-dark); font-weight: 500; font-size: 1.1rem;">
                    Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> sale items
                </p>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('summer-sale') }}" class="d-flex justify-content-end align-items-center" style="gap: 12px;">
                    <!-- Search Bar -->
                    <div class="input-group" style="width: 250px; flex-shrink: 0; position: relative;">
                        <input 
                            type="text" 
                            name="search" 
                            id="searchInput"
                            class="form-control" 
                            placeholder="Search sale items..."
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
                        <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Sort: Highest Discount</option>
                        <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                    <!-- Image Section -->
                    <div style="position: relative; width: 100%; height: 320px; background: #e9e9e9; overflow: hidden;">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onerror="this.src='https://via.placeholder.com/320x320?text=No+Image'">
                        @else
                            <img src="https://via.placeholder.com/320x320?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        @endif
                        
                        <!-- Discount Badge -->
                        @if($product->hasDiscount())
                            <span style="position: absolute; top: 15px; left: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 8px 15px; border-radius: 4px; font-size: 0.85rem; font-weight: 700; z-index: 5;">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>

                    <!-- Info Section -->
                    <div style="padding: 20px; text-align: center;">
                        <!-- Product Name -->
                        <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.5rem; line-height: 1.4; min-height: 40px;">
                            {{ $product->name }}
                        </h3>

                        <!-- Pricing -->
                        <div style="margin-bottom: 1.5rem;">
                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--accent-gold);">
                                PKR {{ number_format($product->final_price, 0) }}
                            </span>
                            @if($product->hasDiscount())
                                <span style="font-size: 0.8rem; color: #999; text-decoration: line-through; margin-left: 0.5rem;">
                                    PKR {{ number_format($product->price, 0) }}
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                            <button onclick="addToCart({{ $product->id }}); event.stopPropagation();" style="flex: 1; background: var(--accent-gold); border: none; color: #0B0B0B; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <a href="{{ route('product.detail', $product->slug) }}" style="width: 45px; height: 45px; background: white; border: 2px solid var(--accent-gold); color: var(--accent-gold); border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-tag" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <p class="text-muted" style="font-size: 1.1rem;">No sale items available at the moment. Check back soon!</p>
            <a href="{{ route('shop') }}" class="btn-premium mt-3">Browse All Products</a>
        </div>
        @endif
    </div>
</section>

<style>
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(212, 175, 55, 0.2);
        border: 2px solid var(--accent-gold);
    }

    .product-card:hover img {
        transform: scale(1.05);
    }

    .product-card button:hover {
        background: var(--primary-dark) !important;
        color: var(--accent-gold) !important;
    }

    .product-card a:hover {
        background: var(--accent-gold) !important;
        color: var(--primary-dark) !important;
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

    @media (max-width: 768px) {
        .row.mb-5 .col-md-6 {
            text-align: center !important;
        }

        .row.mb-5 .col-md-6 form {
            justify-content: center !important;
            margin-top: 1rem;
        }
    }
</style>

@endsection

@section('scripts')
<script>
function addToCart(productId) {
    // TODO: Implement add to cart functionality
    alert('Product added to cart! (Demo)');
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = parseInt(badge.textContent) + 1;
    }
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
    // Redirect to summer-sale page to show all sale items
    window.location.href = "{{ route('summer-sale') }}";
}

// Initialize clear button visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleClearBtn();
});
</script>
@endsection
