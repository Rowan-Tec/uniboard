<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * For navbar dropdown (limited results, JSON response)
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications->map(function ($notif) {
                $data = $notif->data ?? [];

                return [
                    'id' => $notif->id,
                    'read' => $notif->read,
                    'data' => $data,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? 'You have a new update',
                    'created_at' => $notif->created_at->diffForHumans(),
                    'url' => $data['url'] ?? '#',
                ];
            }),
        ]);
    }

    /**
     * Full "View All Notifications" page (paginated Blade view)
     */
    public function all()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('notifications.all', compact('notifications'));
    }

    /**
     * Optional: Mark a single notification as read (via AJAX)
     */
    public function read(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Optional: Mark all notifications as read (via AJAX from navbar)
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }
}