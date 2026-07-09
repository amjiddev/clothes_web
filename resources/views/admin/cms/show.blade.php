@extends('admin.layouts.app')

@section('title', 'View CMS Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
    <li class="breadcrumb-item active">View Section</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-eye me-2"></i>{{ $cms->page_title }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.cms.edit', $cms) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('admin.cms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Section Details -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Section Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Section Type:</strong><br>
                            {{ ucfirst($cms->section_type) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            @if ($cms->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </div>
                    </div>

                    @if ($cms->featured_image)
                        <div class="mb-3">
                            <strong>Featured Image:</strong><br>
                            <img src="{{ $cms->featured_image_url }}" class="img-fluid rounded" alt="{{ $cms->page_title }}" style="max-height: 400px;">
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Content:</strong><br>
                        <p class="text-muted">{{ $cms->page_content }}</p>
                    </div>

                    @if ($cms->data)
                        <div class="mt-4">
                            <strong>Additional Data:</strong>
                            <div class="mt-2">
                                <code>{{ json_encode($cms->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gallery -->
            @if ($cms->gallery_images && count($cms->gallery_images) > 0)
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Gallery Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($cms->gallery_images as $image)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created By:</strong><br>
                        {{ $cms->creator->name ?? 'Unknown' }}
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        {{ $cms->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        {{ $cms->updated_at->format('M d, Y H:i') }}
                    </div>

                    @if ($cms->published_at)
                        <div class="mb-3">
                            <strong>Published:</strong><br>
                            {{ $cms->published_at->format('M d, Y H:i') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Display Order:</strong><br>
                        {{ $cms->display_order }}
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.cms.edit', $cms) }}" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.cms.destroy', $cms) }}" method="POST" style="flex: 1;">
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
    </div>
</div>

<style>
.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}
</style>
@endsection
