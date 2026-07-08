@forelse($products as $index => $product)
    @if ($index % 2 == 0)
        <div class="row mb-5">
    @endif
    <div class="col-lg-6 col-md-6 animate-card">
        <div class="rental-card">
            <h4 class="rental-h4">{{ $product->name }}</h4>
            @if ($product->thumbnail)
                <img src="{{ asset('storage/products/thumbnails/' . $product->thumbnail) }}" class="rental-img"
                    alt="{{ $product->image_alt ?? $product->name }}">
            @else
                <p>{{ $product->image_alt ?? $product->name }}</p>
            @endif

            {!! $product->description ?? '' !!}
            <button class="btn-get" data-slug="{{ $product->slug ?? '' }}" data-open-form>Get Now</button>
        </div>
    </div>

    @if ($index % 2 == 1 || $loop->last)
        </div> <!-- close row -->
    @endif
@empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">No rental products available in this category yet.</p>
    </div>
@endforelse
