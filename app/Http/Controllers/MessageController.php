<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display the messages index page.
     */
    public function index(): View
    {
        return view('messages.index');
    }

    /**
     * Display messages with a specific user.
     */
    public function show(int $userId): View
    {
        // For now, just return a basic view
        return view('messages.show', compact('userId'));
    }

    /**
     * Send a message.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string|max:1000',
        ]);

        // TODO: Implement message sending logic

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}
