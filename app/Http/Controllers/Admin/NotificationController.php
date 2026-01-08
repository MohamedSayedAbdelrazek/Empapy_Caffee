<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for dropdown
     */
    public function index()
    {
        $notifications = AdminNotification::latest()
            ->take(20)
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get notifications for AJAX polling
     */
    public function getNotifications(Request $request)
    {
        $lastCheck = $request->get('last_check');
        $isInitialLoad = $request->get('initial_load') === 'true';

        $query = AdminNotification::latest();

        // On initial page load, get all recent notifications (last 24 hours or last 20)
        // During polling, only get notifications STRICTLY newer than last check
        if (!$isInitialLoad && $lastCheck) {
            // Use >= to catch edge cases, but exclude exact time matches by using microseconds
            $query->where('created_at', '>', $lastCheck);
        }

        $notifications = $query->take(20)->get();
        $unreadCount = AdminNotification::unread()->count();

        // Only mark as "has_new" during polling (not initial load)
        $hasNew = !$isInitialLoad && $notifications->isNotEmpty();

        // Use the latest notification's created_at as the reference point for the next poll
        // This ensures we don't re-fetch the same notification
        $serverTime = $notifications->isNotEmpty()
            ? $notifications->first()->created_at->addSecond()->toISOString()  // Add 1 second buffer
            : now()->toISOString();

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'icon_color' => $notification->icon_color,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            }),
            'unread_count' => $unreadCount,
            'has_new' => $hasNew,
            'server_time' => $serverTime,
        ]);
    }

    /**
     * Get unread count only (lightweight endpoint)
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => AdminNotification::unread()->count(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(AdminNotification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => AdminNotification::unread()->count(),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(AdminNotification $notification)
    {
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار',
        ]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        AdminNotification::where('is_read', true)->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف جميع الإشعارات المقروءة',
        ]);
    }
}
