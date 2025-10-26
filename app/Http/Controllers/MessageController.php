<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display list of conversations
     */
    public function index()
    {
        $userId = auth()->id();
        
        // Get unique conversations with last message
        $conversations = Message::where('SenderID', $userId)
            ->orWhere('ReceiverID', $userId)
            ->with(['sender', 'receiver', 'item'])
            ->orderBy('SentAt', 'desc')
            ->get()
            ->groupBy(function($message) use ($userId) {
                return $message->SenderID == $userId ? $message->ReceiverID : $message->SenderID;
            })
            ->map(function($messages) use ($userId) {
                $lastMessage = $messages->first();
                $otherUser = $lastMessage->SenderID == $userId ? $lastMessage->receiver : $lastMessage->sender;
                
                $unreadCount = $messages->where('ReceiverID', $userId)
                    ->where('IsRead', false)
                    ->count();
                
                return [
                    'user' => $otherUser,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount
                ];
            })
            ->sortByDesc('lastMessage.SentAt')
            ->values();
        
        return view('messages.index', compact('conversations'));
    }

    /**
     * Display conversation with specific user
     */
    public function show(Request $request, $userId)
    {
        $currentUser = auth()->id();
        $otherUser = User::findOrFail($userId);
        
        // Get all messages in conversation
        $messages = Message::conversation($currentUser, $userId)
            ->with(['sender', 'receiver', 'item'])
            ->get();
        
        // Mark messages as read
        Message::where('ReceiverID', $currentUser)
            ->where('SenderID', $userId)
            ->where('IsRead', false)
            ->update(['IsRead' => true]);
        
        // Get item if provided in request
        $item = null;
        if ($request->has('item_id')) {
            $item = \App\Models\Item::with('images')->find($request->item_id);
        }
        
        return view('messages.show', compact('messages', 'otherUser', 'item'));
    }
    
    /**
     * Send a message
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,UserID',
            'item_id' => 'nullable|exists:items,ItemID',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'SenderID' => auth()->id(),
            'ReceiverID' => $validated['receiver_id'],
            'ItemID' => $validated['item_id'] ?? null,
            'MessageContent' => $validated['message'],
            'SentAt' => now()
        ]);

        // Create notification for receiver
        $sender = auth()->user();
        Notification::create([
            'UserID' => $validated['receiver_id'],
            'Type' => 'message',
            'Title' => 'New Message',
            'Content' => $sender->UserName . ' sent you a message',
            'RelatedID' => $message->MessageID,
            'RelatedType' => 'message',
            'CreatedAt' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load(['sender', 'receiver', 'item'])
            ]);
        }

        return redirect()->route('messages.show', $validated['receiver_id'])
            ->with('success', 'Message sent successfully');
    }

    /**
     * Get new messages (for AJAX polling)
     */
    public function getNewMessages($userId)
    {
        $currentUser = auth()->id();
        
        $messages = Message::where('SenderID', $userId)
            ->where('ReceiverID', $currentUser)
            ->where('IsRead', false)
            ->with(['sender', 'item'])
            ->orderBy('SentAt', 'asc')
            ->get();
        
        // Mark as read
        Message::where('SenderID', $userId)
            ->where('ReceiverID', $currentUser)
            ->where('IsRead', false)
            ->update(['IsRead' => true]);
        
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount()
    {
        $count = Message::where('ReceiverID', auth()->id())
            ->where('IsRead', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}