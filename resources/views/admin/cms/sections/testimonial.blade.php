<!-- Testimonials Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Manage customer testimonials and reviews</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'testimonial']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Testimonial
        </a>
    </div>
</div>

@if ($items->count() > 0)
    <div class="row">
        @foreach ($items as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        @if ($item->featured_image)
                            <img src="{{ $item->featured_image_url }}" class="rounded-circle mb-3" alt="{{ $item->page_title }}" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-muted"></i>
                            </div>
                        @endif

                        <div class="mb-2">
                            @if ($item->data && isset($item->data['rating']))
                                @for ($i = 0; $i < $item->data['rating']; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                                @for ($i = $item->data['rating']; $i < 5; $i++)
                                    <i class="fas fa-star text-muted"></i>
                                @endfor
                            @endif
                        </div>

                        <p class="card-text text-muted">{{ Str::limit($item->page_content, 100) }}</p>

                        <div class="mb-3">
                            <strong>{{ $item->data['author'] ?? 'Anonymous' }}</strong><br>
                            <small class="text-muted">{{ $item->data['position'] ?? '' }}</small>
                        </div>

                        <div class="mb-3">
                            @if ($item->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.cms.edit', $item) }}" class="btn btn-sm btn-primary flex-grow-1">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.cms.destroy', $item) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No testimonials added yet.
        <a href="{{ route('admin.cms.create', ['type' => 'testimonial']) }}">Add a testimonial</a>
    </div>
@endif
