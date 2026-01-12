<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
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

    public function show(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->with(['sender', 'receiver'])->latest()->get();

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

 
    public function poll(Request $request, User $user)
{
    $messages = Message::where(function ($q) use ($user) {
        $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
    })->orWhere(function ($q) use ($user) {
        $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
    })->with(['sender', 'receiver'])->latest()->take(50)->get();

    // Mark as read
    Message::where('sender_id', $user->id)
        ->where('receiver_id', Auth::id())
        ->whereNull('read_at')
        ->update(['is_read' => true, 'read_at' => now()]);

    return response()->json([
        'messages' => $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
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

public function send(Request $request, User $user)
{
    $request->validate(['message' => 'required|string|max:2000']);

    $msg = Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $user->id,
        'message' => $request->message,
    ]);

    return response()->json(['success' => true]);
}
}