<!-- Livewire Notification Bell Component -->
<div class="dropdown notification-dropdown">
    <button class="btn btn-link position-relative" type="button" id="notificationBellLivewire" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-lg"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </button>
    
    <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu" aria-labelledby="notificationBellLivewire" style="width: 450px;">
        <!-- Header -->
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-bell"></i> Notifications
            </h6>
            @if($unreadCount > 0)
                <span class="badge bg-danger">{{ $unreadCount }} Unread</span>
            @endif
        </div>
        <hr class="my-2">
        
        <!-- Notifications Container -->
        <div class="notification-list" style="max-height: 450px; overflow-y: auto;">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <div class="notification-item p-3 border-bottom" data-notification-id="{{ $notification['id'] }}">
                        <div class="d-flex">
                            <div class="notification-icon me-3" style="color: {{ $notification['color'] }}; font-size: 1.5rem;">
                                <i class="{{ $notification['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $notification['title'] }}</h6>
                                        <p class="mb-2 small text-muted">{{ $notification['message'] }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $notification['created_at'] }}
                                        </small>
                                    </div>
                                    <span class="badge" style="background-color: {{ $notification['color'] }}; font-size: 0.7rem;">
                                        New
                                    </span>
                                </div>
                                <div class="mt-2 d-flex gap-1">
                                    @if($notification['action_url'])
                                        <a href="{{ $notification['action_url'] }}" class="btn btn-sm btn-light">
                                            <i class="fas fa-arrow-right"></i> View
                                        </a>
                                    @endif
                                    <button wire:click="markAsRead({{ $notification['id'] }})" class="btn btn-sm btn-light">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button wire:click="deleteNotification({{ $notification['id'] }})" class="btn btn-sm btn-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted">No new notifications</p>
                </div>
            @endif
        </div>
        
        <hr class="my-2">
        
        <!-- Footer -->
        <div class="dropdown-footer d-flex justify-content-between gap-2">
            <a href="{{ route('tailor.notifications.index') }}" class="btn btn-sm btn-light flex-grow-1">
                <i class="fas fa-list"></i> View All
            </a>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            @endif
            <button wire:click="clearAll" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>

<style>
    .notification-dropdown .btn {
        color: inherit;
        text-decoration: none;
        padding: 0.5rem;
    }

    .notification-dropdown .btn:hover {
        color: #0d6efd;
    }

    .notification-dropdown-menu {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
    }

    .notification-item {
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-icon {
        min-width: 2rem;
        text-align: center;
    }

    .dropdown-footer {
        padding: 0.5rem 1rem;
    }

    .gap-1 {
        gap: 0.25rem;
    }
</style>
