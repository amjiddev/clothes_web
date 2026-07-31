<!-- Social Links Section -->
<div class="row mb-3">
    <div class="col">
        <p class="text-muted">Manage social media links displayed on website</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.cms.create', ['type' => 'social']) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Social Link
        </a>
    </div>
</div>

@if ($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Platform</th>
                    <th>Icon</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->page_title }}</strong>
                        </td>
                        <td>
                            @if ($item->data && isset($item->data['icon']))
                                <i class="{{ $item->data['icon'] }} fa-lg"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->data && isset($item->data['url']))
                                <a href="{{ $item->data['url'] }}" target="_blank" class="text-decoration-none">
                                    {{ Str::limit($item->data['url'], 40) }} <i class="fas fa-external-link-alt fa-xs"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.cms.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cms.destroy', $item) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No social links added yet.
        <a href="{{ route('admin.cms.create', ['type' => 'social']) }}">Add a social link</a>
    </div>
@endif
