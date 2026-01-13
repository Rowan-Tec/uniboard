<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show inbox with conversation list
     */
    public function inbox()
    {
        $conversations = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id === Auth::id() ? $message->receiver_id : $message->sender_id;
            });

        return view('messages.inbox', compact('conversations'));
    }

    /**
     * Show chat between current user and another user
     */
    public function show(User $user)
    {
        // Prevent self-chat or invalid user
        if ($user->id === Auth::id()) {
            return redirect()->route('messages.inbox');
        }

        $messages = Message::where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })->with(['sender', 'receiver'])->latest()->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    /**
     * Poll endpoint - returns new messages and marks as read
     */
    public function poll(Request $request, User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })->with(['sender', 'receiver'])->latest()->take(50)->get();

        // Mark unread messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name,
                    'sender_photo' => $msg->sender->profile_photo_path ? asset('storage/' . $msg->sender->profile_photo_path) : asset('assets/img/avatars/1.png'),
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'is_read' => $msg->is_read,
                    'read_at' => $msg->read_at ? $msg->read_at->diffForHumans() : null,
                    'is_mine' => $msg->sender_id === Auth::id(),
                ];
            })->reverse(),
            'unread_count' => Message::where('receiver_id', Auth::id())->where('is_read', false)->count(),
        ]);
    }

    /**
     * Send a new message
     */
    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $msg = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        // Broadcast to both parties
        broadcast(new MessageSent($msg))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $msg->load('sender'),
        ]);
    }

    /**
     * Notify the other user that current user is typing
     */
    public function typing(Request $request, User $user)
    {
        broadcast(new UserTyping(Auth::user(), $user))->toOthers();

        return response()->json(['success' => true]);
    }
}