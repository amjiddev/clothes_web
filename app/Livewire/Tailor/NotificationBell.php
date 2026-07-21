<?php

namespace App\Livewire\Tailor;

use Livewire\Component;
use App\Models\TailorNotification;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    protected $listeners = [
        'notificationAdded' => 'refreshNotifications',
        'notificationRead' => 'refreshNotifications',
    ];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        
        $this->unreadCount = TailorNotification::getUnreadCount($user->id);
        
        $this->notifications = TailorNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'action_url' => $notification->action_url,
                    'icon' => $notification->getIcon(),
                    'color' => $notification->getColor(),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->isRead(),
                ];
            })
            ->toArray();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = TailorNotification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notificationRead');
            $this->refreshNotifications();
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        TailorNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        $this->dispatch('notificationRead');
        $this->refreshNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $user = Auth::user();
        TailorNotification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->delete();
        
        $this->refreshNotifications();
    }

    public function clearAll()
    {
        $user = Auth::user();
        TailorNotification::where('user_id', $user->id)->delete();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.tailor.notification-bell');
    }
}
