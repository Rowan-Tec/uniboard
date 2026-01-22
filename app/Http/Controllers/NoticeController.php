<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Notification;
use App\Models\User;
use App\Events\NotificationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('is_approved', true)
            ->where('is_rejected', false)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('notices.index', compact('notices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,events,academic,exam,scholarship,emergency',
        ]);

        $isApproved = Auth::user()->role === 'admin';

        $notice = Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'user_id' => Auth::id(),
            'is_approved' => $isApproved,
            'approved_by' => $isApproved ? Auth::id() : null,
            'approved_at' => $isApproved ? now() : null,
            'published_at' => $isApproved ? now() : null,
        ]);

        // If approved immediately (admin posted), notify users
        if ($isApproved) {
            $this->notifyUsers($notice);
        }

        return back()->with('success', $isApproved
            ? 'Notice posted and published!'
            : 'Notice submitted for approval.');
    }

    public function approval()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $pendingNotices = Notice::where('is_approved', false)
            ->where('is_rejected', false)
            ->with('user')
            ->latest()
            ->get();

        $rejectedNotices = Notice::where('is_rejected', true)
            ->with(['user', 'approver'])
            ->latest()
            ->get();

        return view('notices.approval', compact('pendingNotices', 'rejectedNotices'));
    }

    public function approve(Notice $notice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $notice->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'published_at' => now(),
        ]);

        // Notify users when approved
        $this->notifyUsers($notice);

        return back()->with('success', 'Notice approved and users notified!');
    }

    public function reject(Request $request, Notice $notice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ]);

        $notice->update([
            'is_rejected' => true,
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'reject_reason' => $request->reject_reason,
        ]);

        return back()->with('success', 'Notice rejected with reason.');
    }

    /**
     * Notify relevant users about the new/approved notice
     */
    private function notifyUsers(Notice $notice)
    {
        // Get users to notify (students + staff, or all except poster)
        $users = User::where('id', '!=', Auth::id()) // exclude poster
            ->whereIn('role', ['student', 'staff']) // adjust roles
            ->get();

        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => 'new_notice',
                'data' => [
                    'title' => 'New Notice: ' . $notice->title,
                    'message' => Str::limit($notice->content, 120),
                    'url' => route('notices.index', $notice->id), // optional link
                    'notice_id' => $notice->id,
                ],
            ]);

            // Broadcast real-time
            broadcast(new \App\Events\NotificationReceived($notification));
        }
    }
    public function show(Notice $notice)
{
    // Optional: mark as read if linked from notification
    // if (request()->query('from_notification')) {
    //     $notification = Notification::where('user_id', Auth::id())
    //         ->whereJsonContains('data->notice_id', $notice->id)
    //         ->first();
    //     if ($notification) $notification->update(['read' => true]);
    // }

    return view('notices.show', compact('notice'));
}
}