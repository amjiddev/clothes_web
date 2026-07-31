<!-- About Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Manage the about page section with company information</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'about']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add About Section
        </a>
    </div>
</div>

@if ($items->count() > 0)
    @foreach ($items as $item)
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if ($item->featured_image)
                            <img src="{{ $item->featured_image_url }}" class="img-fluid rounded" alt="{{ $item->page_title }}">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title">{{ $item->page_title }}</h5>
                        <p class="text-muted">{{ $item->page_content }}</p>
                        
                        @if ($item->gallery_images && count($item->gallery_images) > 0)
                            <div class="mb-3">
                                <strong>Gallery Images:</strong> {{ count($item->gallery_images) }} images
                            </div>
                        @endif

                        <div class="mb-3">
                            @if ($item->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.cms.edit', $item) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.cms.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No about section set yet.
        <a href="{{ route('admin.cms.create', ['type' => 'about']) }}">Create one</a>
    </div>
@endif
