<style>
    .buy-form-overlay {
        background-color:
    }
</style>

@if ($products->count())
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-6 animate-card">
            <div class="product-card">

                @if ($product->discount_percent > 0)
                    <span class="discount-badge">{{ $product->discount_percent }}% OFF</span>
                @endif  

                <img src="{{ $product->thumbnail ? asset('storage/products/thumbnails/' . $product->thumbnail) : '' }}"
                    alt="{{ $product->image_alt }}" class="product-img img-fluid">

                <h4 class="product-title">{{ $product->name }}</h4>
                <p class="product-desc">
                    {{ \Illuminate\Support\Str::limit($product->short_description ?? '', 180) }}
                </p>


                <div class="price-box d-flex justify-content-between">
                    @if (!empty($product->price) && $product->price > 0)
                        <span class="old-price">${{ number_format($product->price) }}</span>
                    @endif
                    <span class="new-price">${{ number_format($product->sale_price) }}</span>
                    <button class="buy-btn" data-slug="{{ $product->slug ?? '' }}" data-open-form>Get A Quote</button>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12 text-center py-5">
        <p class="text-muted">No products found.</p>
    </div>
@endif
