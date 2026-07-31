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

        @foreach($collectionSections as $section)
            <!-- Collection Section -->
            <div class="collection-section mb-5" style="position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="row g-0">
                    <!-- Text Content (alternate sides) -->
                    <div class="col-lg-6 {{ $loop->even ? 'order-lg-1 order-2' : 'order-lg-2 order-1' }}" style="display: flex; align-items: center;">
                        <div style="padding: 60px; background: {{ $section->badge_bg_color === 'dark' ? 'linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white;' : 'linear-gradient(135deg, #F8F5EF 0%, white 100%);' }}; width: 100%; height: 100%;">
                            @if($section->badge_text)
                                <div style="display: inline-block; background: {{ $section->badge_bg_color === 'gold' ? 'var(--accent-gold)' : 'var(--accent-gold)' }}; color: {{ $section->badge_bg_color === 'gold' ? 'var(--primary-dark)' : 'var(--primary-dark)' }}; padding: 8px 20px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; letter-spacing: 1px;">
                                    {{ $section->badge_text }}
                                </div>
                            @endif
                            
                            <h2 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; {{ $section->badge_bg_color === 'dark' ? 'color: white;' : 'color: var(--primary-dark);' }} margin-bottom: 1rem;">
                                {{ $section->title }}
                            </h2>
                            
                            <p style="font-size: 0.95rem; {{ $section->badge_bg_color === 'dark' ? 'color: var(--text-cream);' : 'color: var(--text-muted);' }} margin-bottom: 1.2rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; min-height: 4.8rem;">
                                {{ $section->description }}
                            </p>

                            @if($section->features && count($section->features) > 0)
                                <div style="display: flex; gap: 20px; margin-bottom: 2rem; flex-wrap: wrap;">
                                    @foreach($section->features as $feature)
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-check-circle" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                                            <span style="{{ $section->badge_bg_color === 'dark' ? 'color: var(--text-cream);' : 'color: var(--text-muted);' }}">{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <a href="{{ $section->button_link ?? '#' }}" class="btn-premium" style="display: inline-flex; align-items: center; gap: 10px;">
                                {{ $section->button_text }}
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Images Grid -->
                    <div class="col-lg-6 {{ $loop->even ? 'order-lg-2 order-1' : 'order-lg-1 order-2' }}" style="display: flex; align-items: stretch; min-height: 280px;">
                        <div class="collection-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); grid-template-rows: repeat(2, 1fr); gap: 0; width: 100%; height: 100%;">
                            @forelse($section->images as $image)
                                <div style="position: relative; overflow: hidden; background: #f5f5f5;">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->image_alt_text ?? $image->product_name }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); opacity: 0; transition: opacity 0.3s ease;">
                                        <div style="position: absolute; bottom: 12px; left: 12px; color: white;">
                                            @if($image->product_name)
                                                <div style="font-size: 0.75rem; font-weight: 600;">{{ strlen($image->product_name) > 20 ? substr($image->product_name, 0, 20) . '...' : $image->product_name }}</div>
                                            @endif
                                            @if($image->product_price)
                                                <div style="font-size: 0.8rem; font-weight: 700; color: var(--accent-gold);">{{ $image->product_price }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @for($i = 0; $i < 4; $i++)
                                    <div style="position: relative; overflow: hidden; background: #f5f5f5;">
                                        <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&h=400&fit=crop" alt="placeholder" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                    </div>
                                @endfor
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

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
