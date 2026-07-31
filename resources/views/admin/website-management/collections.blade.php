@extends('admin.layouts.app')

@section('title', $pageTitle)

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ $pageTitle }}
                </h2>
                <p class="text-muted">{{ $pageDescription }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.website-management.collections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Section
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-truncate">
                        <h3 class="card-title text-muted font-weight-normal">Total Sections</h3>
                    </div>
                    <div class="mt-3">
                        <div class="display-6 font-weight-bold">{{ $stats['total_sections'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-truncate">
                        <h3 class="card-title text-muted font-weight-normal">Published</h3>
                    </div>
                    <div class="mt-3">
                        <div class="display-6 font-weight-bold text-success">{{ $stats['published'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-truncate">
                        <h3 class="card-title text-muted font-weight-normal">Total Images</h3>
                    </div>
                    <div class="mt-3">
                        <div class="display-6 font-weight-bold">{{ $stats['total_images'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($sections->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <h5>No Collection Sections Yet</h5>
                <p class="text-muted">Start by creating your first collection section</p>
                <a href="{{ route('admin.website-management.collections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First Section
                </a>
            </div>
        </div>
    @else
        <!-- Sections Table -->
        <div class="card">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Section Name</th>
                        <th>Title</th>
                        <th>Badge</th>
                        <th>Images</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td>
                            <div class="font-weight-bold">{{ $section->section_name }}</div>
                            <div class="small text-muted">{{ $section->section_key }}</div>
                        </td>
                        <td>
                            <div class="text-truncate" title="{{ $section->title }}">
                                {{ strlen($section->title) > 50 ? substr($section->title, 0, 50) . '...' : $section->title }}
                            </div>
                        </td>
                        <td>
                            @if($section->badge_text)
                                <span class="badge" style="background-color: {{ $section->badge_bg_color === 'gold' ? '#d4af37' : '#1a1a1a' }}; color: {{ $section->badge_bg_color === 'gold' ? '#000' : '#fff' }};">
                                    {{ $section->badge_text }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $section->images->count() }}/4</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.website-management.collections.toggle-published', $section) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-toggle" data-toggle="tooltip" title="{{ $section->is_published ? 'Click to unpublish' : 'Click to publish' }}">
                                    <input type="checkbox" {{ $section->is_published ? 'checked' : '' }} />
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="font-weight-bold">{{ $section->display_order }}</div>
                        </td>
                        <td>
                            <div class="btn-list">
                                <a href="{{ route('admin.website-management.collections.edit', $section) }}" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.website-management.collections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-ghost-danger" title="Delete">
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
    @endif
</div>

<style>
    .btn-toggle {
        background: none;
        border: none;
        padding: 0;
    }
    
    .btn-toggle input[type="checkbox"] {
        cursor: pointer;
        width: 40px;
        height: 20px;
        margin: 0;
    }
</style>
@endsection
