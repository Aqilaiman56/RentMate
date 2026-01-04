<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display admin's notifications
     */
    public function index()
    {
        $notifications = Notification::where('UserID', auth()->id())
            ->orderBy('CreatedAt', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('UserID', auth()->id())
            ->unread()
            ->count();

        return view('admin.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark all admin notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('UserID', auth()->id())
            ->unread()
            ->update(['IsRead' => true]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'All notifications marked as read');
    }

    /**
     * Clear all admin notifications
     */
    public function clearAll()
    {
        $deletedCount = Notification::where('UserID', auth()->id())->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', "All notifications cleared ({$deletedCount} notification(s) removed)");
    }

    /**
     * Delete a single notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('NotificationID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();

        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted');
    }

    /**
     * Mark notification as read and redirect
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('NotificationID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();

        $notification->markAsRead();

        // Redirect based on notification type to admin routes
        if ($notification->RelatedType === 'report') {
            return redirect()->route('admin.reports');
        } elseif ($notification->RelatedType === 'RefundQueue') {
            return redirect()->route('admin.refund-queue');
        } elseif ($notification->RelatedType === 'deposit') {
            return redirect()->route('admin.deposits');
        } elseif ($notification->RelatedType === 'penalty') {
            return redirect()->route('admin.reports'); // Redirect to reports since penalties are now merged there
        }

        return redirect()->route('admin.notifications.index');
    }
}
