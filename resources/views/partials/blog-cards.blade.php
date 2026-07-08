@forelse($blogs as $blog)
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="news-card bg-white">

            <img src="{{ $blog->image ? asset('storage/blog/images/' . $blog->image) : '' }}" class="img-fluid w-100"
                alt="{{ $blog->image_alt_text ?? plainBracketText($blog->title) }}">

            <div class="p-3 boddy">
                <h5 class="news-title fw-bold mt-2 mb-2">
                    {{ \Illuminate\Support\Str::limit(plainBracketText($blog->title), 70) }}
                </h5>

                <p class="news-desc small text-muted mb-3">
                    {{-- {{ Str::limit(strip_tags($blog->description), 120) }} --}}
                    {{ \Illuminate\Support\Str::limit($blog->short_description ?? '', 120) }}
                </p>

                <a href="{{ route('blog.detail', $blog->slug) }}"
                    class="read-more-link d-flex align-items-center text-decoration-none">
                    Read More <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">No blogs available.</p>
    </div>
@endforelse
