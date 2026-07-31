<header class="topbar">
    <div class="topbar-left">
        <div class="topbar-breadcrumb">
            <a href="{{ route('tailor.dashboard') }}" style="color: var(--tailor-color); font-weight: 600;">
                <i class="fas fa-home"></i> Tailor Panel
            </a>
            @if (request()->route())
                @php
                    $routeName = request()->route()->getName();
                    $routeSegments = explode('.', $routeName);
                    $currentPage = ucfirst(str_replace('-', ' ', end($routeSegments)));
                @endphp
                <span style="color: #999; margin: 0 8px;"> / </span>
                <span style="color: #666; font-weight: 500;">{{ $currentPage }}</span>
            @endif
        </div>
    </div>

    <div class="topbar-right">
        <!-- Notification Icon with Dropdown -->
        <div class="notification-container" style="position: relative; display: flex; align-items: center;">
            <div class="notification-icon" style="position: relative; cursor: pointer; transition: all 0.3s ease;" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationBadge" style="display: none; position: absolute; top: -8px; right: -8px; background: #d4af37; color: #0f0f1e; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 50%; min-width: 20px; text-align: center;">0</span>
            </div>

            <!-- Notification Dropdown -->
            <div class="notification-dropdown" style="display: none; position: absolute; right: 0; top: 100%; min-width: 350px; background: white; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); z-index: 999; margin-top: 15px; border: 1px solid #e9ecef; overflow: hidden; max-height: 500px; overflow-y: auto;">
                <!-- Loading State -->
                <div class="notification-loading" style="padding: 30px 20px; text-align: center; color: #999;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; color: #d4af37;"></i>
                    <p style="margin-top: 10px; font-size: 0.9rem;">Loading notifications...</p>
                </div>

                <!-- Dropdown Header (will be populated via JS) -->
                <div class="notification-header" style="padding: 16px 18px; border-bottom: 1px solid #e9ecef; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                    <h6 style="margin: 0; font-weight: 700; color: var(--text-dark); font-size: 0.95rem;">Notifications</h6>
                    <small style="color: #999; font-size: 0.8rem;">
                        <span id="unreadCount">0</span> unread
                    </small>
                </div>

                <!-- Notifications List (will be populated via JS) -->
                <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                    <!-- Items will be inserted here -->
                </div>

                <!-- Footer with Actions -->
                <div class="notification-footer" style="padding: 12px 18px; border-top: 1px solid #e9ecef; background: #f8f9fa; display: flex; gap: 8px; justify-content: space-between;">
                    <a href="{{ route('tailor.notifications.index') }}" style="flex: 1; padding: 8px 12px; text-align: center; color: var(--tailor-color); text-decoration: none; font-weight: 600; font-size: 0.85rem; border-radius: 6px; transition: all 0.3s ease; background: rgba(212, 175, 55, 0.1);">
                        <i class="fas fa-list"></i> View All
                    </a>
                    <button type="button" class="mark-all-read-btn" style="flex: 1; padding: 8px 12px; background: none; border: 1px solid #e9ecef; color: #666; text-decoration: none; font-weight: 600; font-size: 0.85rem; border-radius: 6px; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-check-double"></i> Mark All
                    </button>
                </div>
            </div>
        </div>

        <!-- User Menu -->
        <div class="user-menu" style="position: relative; display: flex; align-items: center; gap: 12px;">
            @if(auth()->user()->tailor && auth()->user()->tailor->profile_image)
                <div class="user-avatar" style="cursor: pointer;">
                    <img src="{{ asset('storage/' . auth()->user()->tailor->profile_image) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                </div>
            @else
                <div class="user-avatar" style="cursor: pointer;">
                    <strong>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</strong>
                </div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span class="user-name" style="color: var(--text-dark); font-weight: 600;">{{ auth()->user()->name }}</span>
                <small style="color: #d4af37; font-size: 0.75rem; font-weight: 600;">🧵 Tailor</small>
            </div>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; min-width: 240px; background: white; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); z-index: 1000; margin-top: 15px; border: 1px solid #e9ecef; overflow: hidden;">
                <div style="padding: 16px 18px; border-bottom: 1px solid #e9ecef; background: #f8f9fa;">
                    <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 4px; font-size: 0.95rem;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: #999;">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('tailor.profile.edit') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: var(--text-dark); text-decoration: none; border-bottom: 1px solid #e9ecef; transition: all 0.25s ease; font-weight: 500;">
                    <i class="fas fa-user-circle" style="width: 18px; color: var(--tailor-color); font-size: 1.1rem;"></i> 
                    <span>Profile Settings</span>
                </a>
                <a href="{{ route('tailor.profile.change-password') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: var(--text-dark); text-decoration: none; border-bottom: 1px solid #e9ecef; transition: all 0.25s ease; font-weight: 500;">
                    <i class="fas fa-lock" style="width: 18px; color: #f39c12; font-size: 1.1rem;"></i> 
                    <span>Change Password</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; padding: 14px 18px; color: #e74c3c; background: none; border: none; cursor: pointer; transition: all 0.25s ease; font-weight: 600;">
                        <i class="fas fa-sign-out-alt" style="width: 18px; font-size: 1.1rem;"></i> 
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .notification-icon {
        font-size: 1.3rem;
        color: var(--text-dark);
        transition: all 0.3s ease;
    }

    .notification-icon:hover {
        color: var(--tailor-color);
        transform: scale(1.1);
    }

    .notification-dropdown:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    }

    .notification-item:hover {
        background: #f8f9fa !important;
    }

    .notification-item button:hover {
        transform: scale(1.1);
        opacity: 0.9;
    }

    .mark-all-read-btn:hover {
        background: rgba(52, 152, 219, 0.1) !important;
        color: #3498db !important;
    }

    .dropdown-menu:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    }

    .user-menu a:hover,
    .user-menu button:hover {
        background: #f8f9fa;
    }

    .user-menu button:hover {
        background: #ffe8e8 !important;
    }
</style>

<script>
    // Notification functionality
    function loadNotifications() {
        const container = document.querySelector('.notification-container');
        const icon = container.querySelector('.notification-icon');
        const dropdown = container.querySelector('.notification-dropdown');
        const badge = document.getElementById('notificationBadge');
        const unreadCount = document.getElementById('unreadCount');
        const notificationList = dropdown.querySelector('.notification-list');
        const notificationLoading = dropdown.querySelector('.notification-loading');

        // Fetch unread notifications
        fetch('{{ route("tailor.notifications.unread") }}')
            .then(response => response.json())
            .then(data => {
                notificationLoading.style.display = 'none';
                
                const count = data.unreadCount;
                unreadCount.textContent = count;

                if (count > 0) {
                    badge.style.display = 'flex';
                    badge.textContent = count > 9 ? '9+' : count;
                } else {
                    badge.style.display = 'none';
                }

                // Clear and populate notification list
                notificationList.innerHTML = '';

                if (data.notifications.length === 0) {
                    notificationList.innerHTML = '<div style="padding: 40px 20px; text-align: center; color: #999;"><i class="fas fa-bell-slash" style="font-size: 2rem; color: #d4af37; opacity: 0.5;"></i><p style="margin-top: 12px; font-size: 0.9rem;">No notifications</p></div>';
                } else {
                    data.notifications.forEach(notification => {
                        const notifEl = createNotificationElement(notification);
                        notificationList.appendChild(notifEl);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationLoading.innerHTML = '<div style="padding: 20px; text-align: center; color: #e74c3c;"><i class="fas fa-exclamation-circle"></i> Error loading notifications</div>';
            });
    }

    function createNotificationElement(notification) {
        const div = document.createElement('div');
        div.className = 'notification-item';
        div.style.cssText = `
            padding: 14px 18px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            transition: all 0.3s ease;
            cursor: pointer;
        `;

        const readStatus = !notification.read_at ? 'unread' : 'read';
        if (readStatus === 'unread') {
            div.style.background = 'rgba(212, 175, 55, 0.05)';
        }

        const iconColor = notification.color || '#3498db';
        const icon = notification.icon || 'fas fa-bell';

        const timeAgo = formatTimeAgo(new Date(notification.created_at));

        div.innerHTML = `
            <div style="flex-shrink: 0; color: ${iconColor}; font-size: 1.2rem; width: 24px; text-align: center;">
                <i class="${icon}"></i>
            </div>
            <div style="flex-grow: 1; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                    <strong style="color: var(--text-dark); font-size: 0.9rem; margin: 0;">${notification.title}</strong>
                    ${readStatus === 'unread' ? '<span style="width: 6px; height: 6px; background: #d4af37; border-radius: 50%; flex-shrink: 0;"></span>' : ''}
                </div>
                <p style="color: #666; font-size: 0.85rem; margin: 4px 0 0 0; line-height: 1.4; word-break: break-word;">${notification.message}</p>
                <small style="color: #999; font-size: 0.75rem; margin-top: 4px; display: block;">${timeAgo}</small>
            </div>
            <div style="flex-shrink: 0; display: flex; gap: 4px;">
                ${notification.action_url ? `<a href="${notification.action_url}" style="padding: 6px 8px; background: rgba(212, 175, 55, 0.1); color: var(--tailor-color); border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease;" title="View"><i class="fas fa-arrow-right"></i></a>` : ''}
                ${readStatus === 'unread' ? `<button type="button" data-id="${notification.id}" class="mark-read-btn" style="padding: 6px 8px; background: rgba(52, 152, 219, 0.1); color: #3498db; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease;" title="Mark as read"><i class="fas fa-check"></i></button>` : ''}
                <button type="button" data-id="${notification.id}" class="delete-btn" style="padding: 6px 8px; background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease;" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
        `;

        // Add event listeners
        const markReadBtn = div.querySelector('.mark-read-btn');
        const deleteBtn = div.querySelector('.delete-btn');

        if (markReadBtn) {
            markReadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                markNotificationAsRead(notification.id, () => loadNotifications());
            });
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteNotification(notification.id, () => loadNotifications());
            });
        }

        if (notification.action_url) {
            const actionLink = div.querySelector('a');
            if (actionLink) {
                actionLink.addEventListener('click', (e) => {
                    if (readStatus === 'unread') {
                        e.preventDefault();
                        markNotificationAsRead(notification.id, () => {
                            window.location.href = notification.action_url;
                        });
                    }
                });
            }
        }

        return div;
    }

    function formatTimeAgo(date) {
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        if (days < 7) return `${days}d ago`;
        return date.toLocaleDateString();
    }

    function markNotificationAsRead(id, callback) {
        fetch(`{{ route('tailor.notifications.mark-read', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (callback) callback();
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function deleteNotification(id, callback) {
        fetch(`{{ route('tailor.notifications.delete', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (callback) callback();
        })
        .catch(error => console.error('Error deleting notification:', error));
    }

    // Toggle notification dropdown
    document.querySelectorAll('.notification-container').forEach(container => {
        const icon = container.querySelector('.notification-icon');
        const dropdown = container.querySelector('.notification-dropdown');

        if (icon && dropdown) {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display !== 'none';
                dropdown.style.display = isVisible ? 'none' : 'block';
                if (!isVisible) {
                    loadNotifications();
                }
            });

            // Mark all as read button
            const markAllBtn = dropdown.querySelector('.mark-all-read-btn');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    fetch('{{ route("tailor.notifications.mark-all-read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadNotifications())
                    .catch(error => console.error('Error marking all as read:', error));
                });
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.notification-dropdown').forEach(dropdown => {
            const container = dropdown.closest('.notification-container');
            if (container && !container.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Load unread count on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("tailor.notifications.unread") }}')
            .then(response => response.json())
            .then(data => {
                const count = data.unreadCount;
                const badge = document.getElementById('notificationBadge');
                if (count > 0) {
                    badge.style.display = 'flex';
                    badge.textContent = count > 9 ? '9+' : count;
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error loading unread count:', error));
    });

    document.querySelectorAll('.user-menu').forEach(menu => {
        const avatar = menu.querySelector('.user-avatar');
        const dropdownMenu = menu.querySelector('.dropdown-menu');
        
        if (avatar && dropdownMenu) {
            avatar.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
            });
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.parentElement.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>
