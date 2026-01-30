<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Update user last activity for typing status
        Cache::put('user_online_' . $user->id, true, now()->addMinutes(1));

        $messages = Message::with('user:id,name,role')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        // Count unread messages for current user (messages NOT from current user and NOT read)
        $unreadCount = Message::where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();

        // Get typing users
        $typingUsers = [];
        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $u) {
            if (Cache::has('user_is_typing_' . $u->id)) {
                $typingUsers[] = $u->name;
            }
        }

        return response()->json([
            'messages' => $messages,
            'unread_count' => $unreadCount,
            'typing_users' => $typingUsers
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message->load('user:id,name,role'));
    }

    public function markAsRead()
    {
        Message::where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function typing()
    {
        Cache::put('user_is_typing_' . Auth::id(), true, now()->addSeconds(3));
        return response()->json(['status' => 'success']);
    }
}
