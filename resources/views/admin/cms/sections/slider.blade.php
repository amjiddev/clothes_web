<!-- Slider Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Manage homepage slider images and captions</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'slider']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Slider Item
        </a>
    </div>
</div>

@if ($items->count() > 0)
    <div class="row">
        @foreach ($items as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow h-100">
                    @if ($item->featured_image)
                        <img src="{{ $item->featured_image_url }}" class="card-img-top" alt="{{ $item->page_title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->page_title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($item->page_content, 100) }}</p>
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
        No slider items yet.
        <a href="{{ route('admin.cms.create', ['type' => 'slider']) }}">Create one</a>
    </div>
@endif
