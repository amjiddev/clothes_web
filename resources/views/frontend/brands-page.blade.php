@extends('frontend.layouts.app')

@section('title', 'Brands - Men\'s Clothing & Tailoring')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="background: #f8f9fa; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
    <div class="container">
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--primary-dark); text-decoration: none;"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: var(--accent-gold); font-weight: 600;">Brands</li>
        </ol>
    </div>
</nav>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Filter: Brand -->
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-store"></i> Brands
                            </h6>
                            <form method="GET" action="{{ route('brands-page') }}" id="brandForm">
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
                                    <a href="{{ route('brands-page') }}" class="list-group-item list-group-item-action p-2" style="border: none; color: var(--primary-dark);">
                                        All Brands
                                    </a>
                                    @forelse($brands as $brandOption)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="brand" 
                                            value="{{ $brandOption->id }}"
                                            {{ request('brand') == $brandOption->id ? 'checked' : '' }}
                                            onchange="document.getElementById('brandForm').submit()"
                                        >
                                        <span style="color: var(--primary-dark);">{{ $brandOption->name }}</span>
                                    </label>
                                    @empty
                                    <div class="text-muted p-2" style="font-size: 0.9rem;">
                                        No brands available
                                    </div>
                                    @endforelse
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
                            <form method="GET" action="{{ route('brands-page') }}" id="priceForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <div class="mb-2">
                                    <label style="font-size: 0.9rem; color: var(--text-muted);">Min Price</label>
                                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="mb-2">
                                    <label style="font-size: 0.9rem; color: var(--text-muted);">Max Price</label>
                                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                                <button type="submit" class="btn btn-sm w-100" style="background: var(--accent-gold); color: var(--primary-dark); border: none;">
                                    Apply
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
                            <form method="GET" action="{{ route('brands-page') }}" id="sizeForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <div class="list-group list-group-flush">
                                    @foreach($sizes as $size)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="size" 
                                            value="{{ $size }}"
                                            {{ request('size') == $size ? 'checked' : '' }}
                                            onchange="document.getElementById('sizeForm').submit()"
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
                            <form method="GET" action="{{ route('brands-page') }}" id="colorForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <div class="list-group list-group-flush">
                                    @foreach($colors as $color)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="color" 
                                            value="{{ $color }}"
                                            {{ request('color') == $color ? 'checked' : '' }}
                                            onchange="document.getElementById('colorForm').submit()"
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
                    <div class="card border-0 shadow-sm mb-3" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-3" style="color: var(--primary-dark); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
                                <i class="fas fa-layer-group"></i> Fabric Type
                            </h6>
                            <form method="GET" action="{{ route('brands-page') }}" id="fabricForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <div class="list-group list-group-flush">
                                    @foreach($fabrics as $fabric)
                                    <label class="list-group-item list-group-item-action p-2" style="border: none;">
                                        <input 
                                            type="radio" 
                                            name="fabric" 
                                            value="{{ $fabric }}"
                                            {{ request('fabric') == $fabric ? 'checked' : '' }}
                                            onchange="document.getElementById('fabricForm').submit()"
                                        >
                                        <span style="color: var(--primary-dark);">{{ $fabric }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Reset Filters -->
                    <a href="{{ route('brands-page') }}" class="btn btn-outline-secondary w-100" style="border-color: var(--accent-gold); color: var(--accent-gold);">
                        <i class="fas fa-redo"></i> Reset Filters
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Header with Search & Sort -->
                <div class="mb-4" style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                    <div class="row align-items-center g-3">
                        <!-- Search -->
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('brands-page') }}" id="searchForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        class="form-control" 
                                        placeholder="Search products..."
                                        value="{{ request('search') }}"
                                    >
                                    <button class="btn btn-outline-secondary" type="submit" style="border-color: var(--accent-gold); color: var(--accent-gold);">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sort -->
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('brands-page') }}" id="sortForm">
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                <select name="sort" class="form-select" onchange="document.getElementById('sortForm').submit()" style="border-color: var(--accent-gold);">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-6">
                                <div class="product-card" style="
                                    background: white;
                                    border-radius: 12px;
                                    overflow: hidden;
                                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                                    transition: all 0.4s ease;
                                "
                                onmouseenter="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.2)'"
                                onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.08)'">
                                    
                                    <!-- Product Image -->
                                    <div class="product-image" style="
                                        width: 100%;
                                        height: 320px;
                                        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        position: relative;
                                        overflow: hidden;
                                    ">
                                        <img 
                                            src="{{ $product->image_url }}" 
                                            alt="{{ $product->name }}"
                                            style="
                                                width: 100%;
                                                height: 100%;
                                                object-fit: cover;
                                                transition: transform 0.4s ease;
                                            "
                                            class="product-img"
                                            onmouseenter="this.style.transform='scale(1.08)'"
                                            onmouseleave="this.style.transform='scale(1)'"
                                        >
                                        
                                        @if($product->discount_percentage > 0)
                                            <span class="product-badge" style="
                                                position: absolute;
                                                top: 15px;
                                                right: 15px;
                                                background: var(--accent-gold);
                                                color: var(--primary-dark);
                                                padding: 6px 14px;
                                                border-radius: 6px;
                                                font-size: 0.75rem;
                                                font-weight: 700;
                                                letter-spacing: 0.5px;
                                            ">
                                                -{{ $product->discount_percentage }}%
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="product-info" style="padding: 20px; text-align: center;">
                                        <!-- Brand Name -->
                                        @if($product->brandModel)
                                            <div style="color: var(--accent-gold); font-size: 0.8rem; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 0.5rem; text-transform: uppercase;">
                                                {{ $product->brandModel->name }}
                                            </div>
                                        @endif

                                        <!-- Product Name -->
                                        <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.5rem; line-height: 1.4;">
                                            {{ $product->name }}
                                        </h3>

                                        <!-- Price -->
                                        <div style="margin-bottom: 1.5rem;">
                                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--accent-gold);">
                                                Rs. {{ number_format($product->final_price, 0) }}
                                            </span>
                                            @if($product->discount_price)
                                                <span style="font-size: 0.8rem; color: #999; text-decoration: line-through; margin-left: 0.5rem;">
                                                    Rs. {{ number_format($product->price, 0) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Actions -->
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
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: 40px;">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div style="
                        text-align: center;
                        padding: 80px 20px;
                    ">
                        <div style="
                            font-size: 4rem;
                            color: var(--accent-gold);
                            margin-bottom: 1.5rem;
                        ">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h2 style="
                            font-size: 1.8rem;
                            color: var(--primary-dark);
                            margin-bottom: 0.5rem;
                            font-weight: 700;
                        ">
                            No Products Found
                        </h2>
                        <p style="
                            color: var(--text-muted);
                            font-size: 1rem;
                            margin-bottom: 2rem;
                            max-width: 500px;
                            margin-left: auto;
                            margin-right: auto;
                        ">
                            Try adjusting your filters or search terms to find what you're looking for.
                        </p>
                        <a href="{{ route('brands-page') }}" style="
                            display: inline-block;
                            background: var(--accent-gold);
                            color: var(--primary-dark);
                            padding: 12px 30px;
                            border-radius: 6px;
                            text-decoration: none;
                            font-weight: 600;
                            transition: all 0.3s ease;
                        ">
                            View All Products
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
