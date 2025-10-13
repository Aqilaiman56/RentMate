<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display user's notifications
     */
    public function index()
    {
        $notifications = Notification::where('UserID', auth()->id())
            ->orderBy('CreatedAt', 'desc')
            ->paginate(20);
        
        $unreadCount = Notification::where('UserID', auth()->id())
            ->unread()
            ->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('NotificationID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();
        
        $notification->markAsRead();
        
        // Redirect based on notification type
        if ($notification->RelatedType === 'message') {
            $message = \App\Models\Message::find($notification->RelatedID);
            if ($message) {
                $otherUserId = $message->SenderID == auth()->id() ? $message->ReceiverID : $message->SenderID;
                return redirect()->route('messages.show', $otherUserId);
            }
        } elseif ($notification->RelatedType === 'booking') {
            return redirect()->route('booking.show', $notification->RelatedID);
        }
        
        return redirect()->route('notifications.index');
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        Notification::where('UserID', auth()->id())
            ->unread()
            ->update(['IsRead' => true]);
        
        return redirect()->route('notifications.index')
            ->with('success', 'All notifications marked as read');
    }

    /**
     * Get unread count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('UserID', auth()->id())
            ->unread()
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('NotificationID', $id)
            ->where('UserID', auth()->id())
            ->firstOrFail();
        
        $notification->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted');
    }
}