@forelse($reviews as $review)
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
        <div class="review-card">

            <!-- Stars -->
            <div class="rate-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                @endfor
            </div>

            <!-- Message -->
            <div class="review-scroll auto-scroll">
                <p class="review-text">
                    {{ $review->message ?? '' }}
                </p>
            </div>

            <!-- Name -->
            <h5 class="client-name">{{ $review->name }}</h5>

        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">No reviews available.</p>
    </div>
@endforelse
