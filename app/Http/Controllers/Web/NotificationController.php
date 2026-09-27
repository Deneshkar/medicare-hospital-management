<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(15);
        $unreadCount = $request->user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('web.notifications.index')
            ->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->route('web.notifications.index')
            ->with('success', 'All notifications marked as read.');
    }
}
