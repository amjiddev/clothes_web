<!-- Notification Bell Component -->
<div class="notification-bell-container" style="position: relative;">
    <!-- Bell Icon Button -->
    <button type="button" 
            class="notification-bell-button" 
            id="notificationBellButton"
            style="background: none; border: none; cursor: pointer; position: relative; padding: 5px;">
        <i class="fas fa-bell" style="font-size: 1.3rem; color: var(--text-dark);"></i>
        <span class="notification-badge" 
              id="notificationBadge" 
              style="display: none; position: absolute; top: -5px; right: -10px; background-color: #e74c3c; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">0</span>
    </button>

    <!-- Notification Dropdown (Custom - No Modal) -->
    <div class="notification-dropdown" 
         id="notificationDropdown"
         style="display: none; position: fixed; top: 60px; right: 20px; width: 500px; max-width: 90vw; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); z-index: 10000; max-height: 600px; overflow-y: auto;">
        
        <!-- Dropdown Header -->
        <div style="padding: 15px 20px; border-bottom: 1px solid #e0e0e0; background-color: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
            <h6 style="margin: 0; font-weight: 600;"><i class="fas fa-bell" style="color: #d4af37; margin-right: 8px;"></i>Notifications</h6>
            <button type="button" id="notificationCloseBtn" style="background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #999;">×</button>
        </div>

        <!-- Dropdown Body -->
        <div id="notificationBellBody" style="padding: 0;">
            <div style="text-align: center; padding: 40px 20px;">
                <div class="spinner-border" role="status" style="color: #d4af37;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p style="margin-top: 15px; color: #999;">Loading notifications...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBellButton = document.getElementById('notificationBellButton');
    const notificationCloseBtn = document.getElementById('notificationCloseBtn');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationBellBody = document.getElementById('notificationBellBody');
    const notificationDropdown = document.getElementById('notificationDropdown');

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Determine the current route prefix
    const currentPath = window.location.pathname;
    const isAdmin = currentPath.includes('/admin/');
    const isReceptionist = currentPath.includes('/receptionist/');
    const routePrefix = isAdmin ? '/admin' : isReceptionist ? '/receptionist' : '/admin';

    console.log('🔔 Notification Bell initialized');
    console.log('   Path:', currentPath);
    console.log('   Prefix:', routePrefix);
    console.log('   CSRF Token:', csrfToken ? 'Present' : 'MISSING!');

    /**
     * Update badge count
     */
    async function updateBadgeCount() {
        try {
            const url = `${routePrefix}/notifications/unread-count`;
            console.log('📊 Fetching badge count from:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                console.warn('❌ Badge count failed with status:', response.status);
                return;
            }

            const data = await response.json();
            console.log('✅ Badge data:', data);

            if (data.success && data.total > 0) {
                notificationBadge.textContent = data.total;
                notificationBadge.style.display = 'flex';
                console.log('🔴 Badge showing:', data.total);
            } else {
                notificationBadge.style.display = 'none';
                console.log('⚫ Badge hidden');
            }
        } catch (error) {
            console.error('❌ Badge update error:', error.message);
        }
    }

    /**
     * Load notifications
     */
    async function loadNotifications() {
        try {
            const url = `${routePrefix}/notifications`;
            console.log('📬 Fetching notifications from:', url);
            
            notificationBellBody.innerHTML = '<div style="padding: 40px 20px; text-align: center;"><div class="spinner-border" style="color: #d4af37;"></div><p style="margin-top: 10px; color: #999;">Loading notifications...</p></div>';

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                const text = await response.text();
                console.error('❌ API Error:', response.status, text);
                notificationBellBody.innerHTML = '<div style="padding: 20px;"><div class="alert alert-danger">Failed to load notifications (HTTP ' + response.status + ').<br><small>Check browser console for details.</small></div></div>';
                return;
            }

            const data = await response.json();
            console.log('✅ Notifications:', data);

            if (!data.success) {
                console.error('❌ API returned success=false:', data);
                let errorMsg = 'API Error: ' + (data.message || 'Unknown error');
                if (data.errors && data.errors.length > 0) {
                    errorMsg += '<br><small>' + data.errors.join('<br>') + '</small>';
                }
                notificationBellBody.innerHTML = '<div style="padding: 20px;"><div class="alert alert-danger">' + errorMsg + '</div></div>';
                return;
            }

            if (data.notifications.length === 0) {
                notificationBellBody.innerHTML = '<div style="text-align: center; padding: 40px 20px;"><i class="fas fa-check-circle" style="font-size: 3rem; color: #27ae60; opacity: 0.5;"></i><p style="margin-top: 15px; color: #999;">No new notifications</p></div>';
            } else {
                console.log('📋 Processing notifications:', {
                    total: data.notifications.length,
                    orders: data.notifications.filter(n => n.type === 'order').length,
                    messages: data.notifications.filter(n => n.type === 'contact_message').length,
                    measurements: data.notifications.filter(n => n.type === 'measurement').length
                });
                
                let html = '';
                data.notifications.forEach((notification, idx) => {
                    console.log(`  [${idx}] Type: ${notification.type}, Title: ${notification.title}`);
                    
                    const title = notification.title.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
                    const subtitle = notification.subtitle.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
                    const description = notification.description.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
                    
                    html += `
                        <div class="notification-item" style="padding: 12px 15px; border-bottom: 1px solid #e0e6ed; cursor: pointer; display: flex; gap: 12px; border-left: 3px solid ${notification.color}; transition: background 0.2s; pointer-events: auto;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'" onclick="window.markNotificationAsSeen('${notification.id}', '${notification.type}', '${notification.url}')">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: ${notification.color}; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="${notification.icon}"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #1a1a1a; margin-bottom: 4px;">${title}</div>
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 4px;">${subtitle}</div>
                                <div style="font-size: 0.8rem; color: #999; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${description}</div>
                                <div style="font-size: 0.75rem; color: #bbb;">${notification.timestamp}</div>
                            </div>
                        </div>
                    `;
                });
                console.log('✅ Rendered', data.notifications.length, 'notifications');
                notificationBellBody.innerHTML = html;
            }

            updateBadgeCount();
        } catch (error) {
            console.error('❌ Error loading notifications:', error);
            notificationBellBody.innerHTML = '<div style="padding: 20px;"><div class="alert alert-danger">Error: ' + error.message + '</div></div>';
        }
    }

    /**
     * Mark notification as seen
     */
    window.markNotificationAsSeen = async function(id, type, url) {
        try {
            console.log('✏️ Marking as seen:', id, type);
            
            const response = await fetch(`${routePrefix}/notifications/mark-as-seen`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id: parseInt(id), type: type }),
            });

            if (response.ok) {
                console.log('✅ Marked as seen, redirecting to:', url);
                window.location.href = url;
            } else {
                console.error('❌ Mark as seen failed:', response.status);
            }
        } catch (error) {
            console.error('❌ Error marking as seen:', error);
        }
    };

    // Toggle dropdown on button click
    notificationBellButton.addEventListener('click', function(e) {
        e.stopPropagation();
        if (notificationDropdown.style.display === 'none') {
            notificationDropdown.style.display = 'block';
            loadNotifications();
        } else {
            notificationDropdown.style.display = 'none';
        }
    });

    // Close dropdown when close button clicked
    notificationCloseBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.style.display = 'none';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && e.target !== notificationBellButton && !notificationBellButton.contains(e.target)) {
            notificationDropdown.style.display = 'none';
        }
    });

    // Initial badge load on page load
    updateBadgeCount();

    // Poll for updates every 30 seconds
    setInterval(updateBadgeCount, 30000);
    
    console.log('✅ Notification Bell fully initialized');
});
</script>

<style>
    .notification-bell-container {
        display: flex;
        align-items: center;
    }

    .notification-bell-button:hover i {
        color: var(--accent-color) !important;
    }

    .notification-item:hover {
        background-color: #f8f9fa !important;
    }
</style>
