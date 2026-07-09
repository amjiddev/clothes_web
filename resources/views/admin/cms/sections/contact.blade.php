<!-- Contact Information Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Manage contact information displayed on the website</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'contact']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Contact Info
        </a>
    </div>
</div>

@if ($items->count() > 0)
    @foreach ($items as $item)
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $item->page_title }}</h5>
                <p class="card-text">{{ $item->page_content }}</p>

                @if ($item->data)
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $item->data['email'] ?? '#' }}">{{ $item->data['email'] ?? 'N/A' }}</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Phone:</strong><br>
                                <a href="tel:{{ $item->data['phone'] ?? '#' }}">{{ $item->data['phone'] ?? 'N/A' }}</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Address:</strong><br>
                                {{ $item->data['address'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-3 mt-4">
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
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No contact information set yet.
        <a href="{{ route('admin.cms.create', ['type' => 'contact']) }}">Add contact info</a>
    </div>
@endif
