@extends('tailor.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Notifications</h1>
            <p class="text-muted mb-0">
                <span class="badge bg-info">{{ $unreadCount }} Unread</span>
                <span class="badge bg-secondary">{{ $notifications->total() }} Total</span>
            </p>
        </div>
        <div class="btn-group">
            @if($unreadCount > 0)
                <form action="{{ route('tailor.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm" title="Mark all as read">
                        <i class="fas fa-check-double"></i> Mark All Read
                    </button>
                </form>
            @endif
            <form action="{{ route('tailor.notifications.clear-all') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" title="Clear all notifications">
                    <i class="fas fa-trash"></i> Clear All
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('tailor.notifications.index') }}" class="row g-2">
                        <!-- Status Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="filter" class="form-select" onchange="this.form.submit()">
                                <option value="">All Notifications</option>
                                <option value="unread" {{ $currentFilter === 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ $currentFilter === 'read' ? 'selected' : '' }}>Read</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach($types as $typeKey => $typeLabel)
                                    <option value="{{ $typeKey }}" {{ $currentType === $typeKey ? 'selected' : '' }}>
                                        {{ $typeLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <a href="{{ route('tailor.notifications.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-redo"></i> Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div class="row">
            <div class="col-md-12">
                @foreach($notifications as $notification)
                    <div class="card mb-3 notification-item {{ !$notification->isRead() ? 'border-left-4 border-info' : 'border-left-4 border-light' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="notification-icon" style="color: {{ $notification->getColor() }};">
                                        <i class="{{ $notification->getIcon() }} fa-2x"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                {{ $notification->title }}
                                                @if(!$notification->isRead())
                                                    <span class="badge bg-info ms-2">New</span>
                                                @endif
                                            </h5>
                                            <p class="card-text mb-2">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                                <span class="ms-3">
                                                    <span class="badge" style="background-color: {{ $notification->getColor() }};">
                                                        {{ $notification->getTypeLabel() }}
                                                    </span>
                                                </span>
                                            </small>
                                        </div>
                                        <div class="btn-group ms-3" role="group">
                                            @if(!$notification->isRead())
                                                <form action="{{ route('tailor.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark as read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="fas fa-arrow-right"></i> View
                                                </a>
                                            @endif
                                            <form action="{{ route('tailor.notifications.delete', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            No notifications found. All caught up!
        </div>
    @endif
</div>

<style>
    .notification-item {
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 50%;
    }

    .border-left-4 {
        border-left: 4px solid !important;
    }
</style>
@endsection
