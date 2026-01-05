<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::approved()
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

        $isApproved = auth()->user()->role === 'admin';

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'user_id' => auth()->id(),
            'is_approved' => $isApproved,
            'approved_by' => $isApproved ? auth()->id() : null,
            'approved_at' => $isApproved ? now() : null,
            'published_at' => $isApproved ? now() : null,
        ]);

        return back()->with('success', $isApproved 
            ? 'Notice posted and published!' 
            : 'Notice submitted for approval.');
    }

    public function approval()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $pendingNotices = Notice::where('is_approved', false)
            ->with('user')
            ->latest()
            ->get();

        return view('notices.approval', compact('pendingNotices'));
    }

    public function approve(Notice $notice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $notice->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'published_at' => now(),
        ]);

        return back()->with('success', 'Notice approved!');
    }

    public function reject(Notice $notice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $notice->delete();

        return back()->with('success', 'Notice rejected.');
    }
}