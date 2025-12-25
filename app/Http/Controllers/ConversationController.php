<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class ConversationController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        if ($auth->role === 'masyarakat') {
            $users = User::where('role', 'pemerintah')->get();
            return view('chat.index', compact('users'));
        }

        $conversations = Conversation::where('pemerintah_id', $auth->id)
            ->with(['masyarakat', 'lastMessage'])
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $auth = auth()->user();

        if ($auth->role === $user->role) {
            abort(403);
        }

        $masyarakatId = $auth->role === 'masyarakat' ? $auth->id : $user->id;
        $pemerintahId = $auth->role === 'pemerintah' ? $auth->id : $user->id;

        $conversation = Conversation::firstOrCreate([
            'masyarakat_id' => $masyarakatId,
            'pemerintah_id' => $pemerintahId,
        ]);

        if ($auth->role === 'pemerintah') {
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', $masyarakatId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $messages = $conversation->messages()->with('sender')->get();

        return view('chat.show', compact('conversation', 'messages'));
    }
}
