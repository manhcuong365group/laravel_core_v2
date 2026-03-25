<?php

namespace App\Livewire\Backend\Common;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationDropdown extends Component
{
    public function getNotificationsProperty()
    {
        return Auth::user()->unreadNotifications()->latest()->take(5)->get();
    }

    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        
        // Redirect to target URL if exists
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('toast', message: 'Đã đánh dấu tất cả thông báo là đã đọc.', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.common.notification-dropdown');
    }
}
