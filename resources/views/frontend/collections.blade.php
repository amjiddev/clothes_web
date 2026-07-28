@extends('frontend.layouts.app')

@section('title', 'Collections - Premium Fashion')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="background: #f8f9fa; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
    <div class="container">
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--primary-dark); text-decoration: none;"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: var(--accent-gold); font-weight: 600;">Collections</li>
        </ol>
    </div>
</nav>

<!-- Collections Section -->
<section style="background: white; padding: 80px 0;">
    <div class="container">

        <!-- Best Sellers Collection -->
        <div class="collection-section mb-5" style="position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div style="padding: 60px; background: linear-gradient(135deg, #F8F5EF 0%, white 100%);">
                        <div style="display: inline-block; background: var(--accent-gold); color: var(--primary-dark); padding: 8px 20px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: 1px;">
                            TRENDING NOW
                        </div>
                        <h2 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; color: var(--primary-dark); margin-bottom: 1rem;">Best Sellers</h2>
                        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.8;">
                            Discover our most loved pieces. These customer favorites combine timeless style with exceptional quality, making them the cornerstone of any wardrobe.
                        </p>
                        <div style="display: flex; gap: 15px; margin-bottom: 2rem;">
                            <div>
                                <div style="font-size: 2rem; font-weight: 700; color: var(--accent-gold);">{{ $bestSellers->count() }}+</div>
                                <div style="font-size: 0.9rem; color: var(--text-muted);">Products</div>
                            </div>
                            <div style="border-left: 2px solid var(--accent-gold); padding-left: 20px;">
                                <div style="font-size: 2rem; font-weight: 700; color: var(--accent-gold);">10K+</div>
                                <div style="font-size: 0.9rem; color: var(--text-muted);">Happy Customers</div>
                            </div>
                        </div>
                        <a href="{{ route('collections.best-sellers') }}" class="btn-premium" style="display: inline-flex; align-items: center; gap: 10px;">
                            Shop Best Sellers
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2 order-1">
                    <div class="collection-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0;">
                        @foreach($bestSellers->take(4) as $product)
                        <div style="position: relative; overflow: hidden; aspect-ratio: 1/1; background: #f5f5f5;">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onerror="this.src='https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&h=400&fit=crop'">
                            @else
                                <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&h=400&fit=crop" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            @endif
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); opacity: 0; transition: opacity 0.3s ease;">
                                <div style="position: absolute; bottom: 15px; left: 15px; color: white;">
                                    <div style="font-size: 0.85rem; font-weight: 600;">{{ Str::limit($product->name, 20) }}</div>
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--accent-gold);">PKR {{ number_format($product->final_price, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Summer 2026 Collection -->
        <div class="collection-section mb-5" style="position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6">
                    <div class="collection-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0;">
                        @foreach($summerCollection->take(4) as $product)
                        <div style="position: relative; overflow: hidden; aspect-ratio: 1/1; background: #f5f5f5;">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onerror="this.src='https://images.unsplash.com/photo-1622445275463-afa2ab738c34?w=400&h=400&fit=crop'">
                            @else
                                <img src="https://images.unsplash.com/photo-1622445275463-afa2ab738c34?w=400&h=400&fit=crop" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            @endif
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); opacity: 0; transition: opacity 0.3s ease;">
                                <div style="position: absolute; bottom: 15px; left: 15px; color: white;">
                                    <div style="font-size: 0.85rem; font-weight: 600;">{{ Str::limit($product->name, 20) }}</div>
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--accent-gold);">PKR {{ number_format($product->final_price, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="padding: 60px; background: linear-gradient(135deg, white 0%, #F8F5EF 100%);">
                        <div style="display: inline-block; background: var(--primary-dark); color: var(--accent-gold); padding: 8px 20px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: 1px;">
                            SUMMER 2026
                        </div>
                        <h2 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; color: var(--primary-dark); margin-bottom: 1rem;">Summer Collection</h2>
                        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.8;">
                            Embrace the warmth with our Summer 2026 Collection. Lightweight fabrics, breathable designs, and contemporary cuts perfect for the season.
                        </p>
                        <div style="display: flex; gap: 20px; margin-bottom: 2rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-check-circle" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                                <span style="color: var(--text-muted);">Breathable Fabrics</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-check-circle" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                                <span style="color: var(--text-muted);">Modern Cuts</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-check-circle" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                                <span style="color: var(--text-muted);">Vibrant Colors</span>
                            </div>
                        </div>
                        <a href="{{ route('collections.summer-2026') }}" class="btn-premium" style="display: inline-flex; align-items: center; gap: 10px;">
                            Shop Summer Collection
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Collection -->
        <div class="collection-section mb-5" style="position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div style="padding: 60px; background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white;">
                        <div style="display: inline-block; background: var(--accent-gold); color: var(--primary-dark); padding: 8px 20px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: 1px;">
                            EDITOR'S CHOICE
                        </div>
                        <h2 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; color: white; margin-bottom: 1rem;">Featured Collection</h2>
                        <p style="font-size: 1.1rem; color: var(--text-cream); margin-bottom: 2rem; line-height: 1.8;">
                            Handpicked by our style experts, this exclusive collection showcases pieces that embody sophistication, elegance, and contemporary fashion.
                        </p>
                        <div style="background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--accent-gold); padding: 20px; margin-bottom: 2rem; border-radius: 4px;">
                            <p style="font-style: italic; color: var(--text-cream); margin: 0;">
                                "Each piece is carefully selected to represent the pinnacle of craftsmanship and design."
                            </p>
                            <div style="margin-top: 10px; font-size: 0.9rem; color: var(--accent-gold);">- Style Curator</div>
                        </div>
                        <a href="{{ route('collections.featured') }}" class="btn-outline-premium" style="display: inline-flex; align-items: center; gap: 10px;">
                            Explore Collection
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2 order-1">
                    <div class="collection-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0;">
                        @foreach($featuredCollection->take(4) as $product)
                        <div style="position: relative; overflow: hidden; aspect-ratio: 1/1; background: #f5f5f5;">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onerror="this.src='https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=400&h=400&fit=crop'">
                            @else
                                <img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=400&h=400&fit=crop" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            @endif
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); opacity: 0; transition: opacity 0.3s ease;">
                                <div style="position: absolute; bottom: 15px; left: 15px; color: white;">
                                    <div style="font-size: 0.85rem; font-weight: 600;">{{ Str::limit($product->name, 20) }}</div>
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--accent-gold);">PKR {{ number_format($product->final_price, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    .collection-section:hover .collection-grid img {
        transform: scale(1.05);
    }

    .collection-grid > div:hover > div {
        opacity: 1 !important;
    }

    @media (max-width: 991px) {
        .collection-section > .row > div > div {
            padding: 40px 30px !important;
        }

        .collection-section h2 {
            font-size: 2rem !important;
        }

        .collection-section p {
            font-size: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .collection-grid {
            grid-template-columns: 1fr 1fr !important;
        }

        .collection-section > .row > div > div {
            padding: 30px 20px !important;
        }
    }
</style>

@endsection
