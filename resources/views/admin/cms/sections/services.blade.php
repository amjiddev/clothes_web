<!-- Services Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Add and manage services offered</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'services']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Service
        </a>
    </div>
</div>

@if ($items->count() > 0)
    <div class="row">
        @foreach ($items as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        @if ($item->data && isset($item->data['icon']))
                            <div class="mb-3" style="font-size: 2.5rem;">
                                <i class="{{ $item->data['icon'] }} text-primary"></i>
                            </div>
                        @else
                            <div class="mb-3">
                                <i class="fas fa-cog fa-2x text-muted"></i>
                            </div>
                        @endif

                        <h5 class="card-title">{{ $item->page_title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($item->page_content, 100) }}</p>

                        @if ($item->featured_image)
                            <img src="{{ $item->featured_image_url }}" class="img-fluid rounded mb-2" alt="{{ $item->page_title }}" style="height: 150px; object-fit: cover; width: 100%;">
                        @endif

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
        No services added yet.
        <a href="{{ route('admin.cms.create', ['type' => 'services']) }}">Add a service</a>
    </div>
@endif
