<!-- Notification Bell Icon with Dropdown -->
<div class="dropdown notification-dropdown">
    <button class="btn btn-link position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-lg"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notificationBadge" style="display: none;">
            <span id="notificationCount">0</span>
        </span>
    </button>
    
    <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu" aria-labelledby="notificationDropdown" style="width: 400px;">
        <!-- Header -->
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            <small id="unreadLabel" class="text-muted"></small>
        </div>
        <hr class="my-2">
        
        <!-- Notifications Container -->
        <div id="notificationsContainer" class="notification-list" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
        </div>
        
        <hr class="my-2">
        
        <!-- Footer -->
        <div class="dropdown-footer d-flex justify-content-between">
            <a href="{{ route('tailor.notifications.index') }}" class="dropdown-item text-center py-2 flex-grow-1">
                View All Notifications
            </a>
            <form action="{{ route('tailor.notifications.mark-all-read') }}" method="POST" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark all as read">
                    <i class="fas fa-check-double"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});

function loadNotifications() {
    fetch('{{ route("tailor.notifications.unread") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationUI(data);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationUI(data) {
    const container = document.getElementById('notificationsContainer');
    const badge = document.getElementById('notificationBadge');
    const count = document.getElementById('notificationCount');
    const unreadLabel = document.getElementById('unreadLabel');
    
    // Update badge
    if (data.unreadCount > 0) {
        badge.style.display = 'inline-block';
        count.textContent = data.unreadCount;
        unreadLabel.textContent = `${data.unreadCount} unread`;
    } else {
        badge.style.display = 'none';
        unreadLabel.textContent = 'All caught up!';
    }
    
    // Update notifications list
    if (data.notifications && data.notifications.length > 0) {
        container.innerHTML = data.notifications.map(notification => `
            <div class="notification-item p-3 border-bottom" data-notification-id="${notification.id}">
                <div class="d-flex">
                    <div class="notification-icon me-3" style="color: ${notification.color};">
                        <i class="${notification.icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${notification.title}</h6>
                                <p class="mb-1 small">${notification.message}</p>
                                <small class="text-muted">${notification.created_at}</small>
                            </div>
                            ${!notification.is_read ? '<span class="badge bg-info ms-2">New</span>' : ''}
                        </div>
                        <div class="mt-2">
                            ${notification.action_url ? `<a href="${notification.action_url}" class="btn btn-sm btn-light">View</a>` : ''}
                            <button class="btn btn-sm btn-light" onclick="markNotificationAsRead(${notification.id})">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                <p class="text-muted">No new notifications</p>
            </div>
        `;
    }
}

function markNotificationAsRead(notificationId) {
    const form = new FormData();
    
    fetch(`{{ route('tailor.notifications.mark-read', ':id') }}`.replace(':id', notificationId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}
</script>

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
        font-size: 1.25rem;
        min-width: 2rem;
        text-align: center;
    }

    .dropdown-footer {
        padding: 0.5rem 1rem;
    }

    .notification-badge {
        font-size: 0.625rem;
    }
</style>
