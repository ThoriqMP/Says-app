<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    // Get list of contacts (other users)
    public function getContacts()
    {
        $currentUser = Auth::user();
        
        // Update user online status
        Cache::put('user_online_' . $currentUser->id, true, now()->addMinutes(1));

        $users = User::where('id', '!=', $currentUser->id)
            ->select('id', 'name', 'role')
            ->get()
            ->map(function ($user) use ($currentUser) {
                // Count unread messages from this specific user to me
                $unreadCount = Message::where('user_id', $user->id)
                    ->where('receiver_id', $currentUser->id)
                    ->where('is_read', false)
                    ->count();
                
                $user->unread_count = $unreadCount;
                $user->is_online = Cache::has('user_online_' . $user->id);
                return $user;
            });

        // Total unread for badge
        $totalUnread = Message::where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'contacts' => $users,
            'total_unread' => $totalUnread
        ]);
    }

    // Get messages between current user and specific user
    public function getMessages($userId)
    {
        $currentUser = Auth::id();

        $messages = Message::where(function($q) use ($currentUser, $userId) {
                $q->where('user_id', $currentUser)->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($currentUser, $userId) {
                $q->where('user_id', $userId)->where('receiver_id', $currentUser);
            })
            ->with('user:id,name,role')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        // Check if other user is typing (specific to this chat)
        $isTyping = Cache::has('user_is_typing_' . $userId);

        return response()->json([
            'messages' => $messages,
            'is_typing' => $isTyping
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message->load('user:id,name,role'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id'
        ]);

        Message::where('user_id', $request->sender_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function typing()
    {
        Cache::put('user_is_typing_' . Auth::id(), true, now()->addSeconds(3));
        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message->delete(); // Soft delete

        return response()->json(['status' => 'success', 'id' => $id]);
    }
}
