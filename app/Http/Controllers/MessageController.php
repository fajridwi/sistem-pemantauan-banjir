<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image'   => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat-images', 'public');
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'image' => $imagePath,
            'is_read' => false
        ]);

        return back();
    }
}
