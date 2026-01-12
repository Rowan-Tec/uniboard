<?php

namespace App\Http\Controllers;

use App\Models\LostFoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LostFoundController extends Controller
{
    /**
     * Display list of lost and found items
     */
    public function index()
    {
        $lostItems = LostFoundItem::lost()
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        $foundItems = LostFoundItem::found()
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('lostfound.index', compact('lostItems', 'foundItems'));
    }

    /**
     * Store a new lost/found item with images
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'location' => 'required|string|max:255',
            'date_lost_found' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB max per image
        ]);

        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('lostfound-images', 'public');
                $images[] = $path;
            }
        }

        LostFoundItem::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'date_lost_found' => $request->date_lost_found,
            'images' => $images,
        ]);

        return back()->with('success', ucfirst($request->type) . ' item reported successfully!');
    }

    /**
     * Show single item with smart ML matches
     */
    public function show(LostFoundItem $item)
    {
        // Prevent viewing resolved items unless owner/admin
        if ($item->is_resolved && Auth::id() !== $item->user_id && Auth::user()->role !== 'admin') {
            abort(404);
        }

        $matches = collect();

        $oppositeType = $item->type === 'lost' ? 'found' : 'lost';

        $potentialMatches = LostFoundItem::where('type', $oppositeType)
            ->where('is_resolved', false)
            ->with('user')
            ->get();

        foreach ($potentialMatches as $potential) {
            $score = 0;

            // Description similarity (word overlap + length normalized)
            $itemWords = preg_split('/\s+/', strtolower($item->description));
            $potentialWords = preg_split('/\s+/', strtolower($potential->description));
            $commonWords = array_intersect($itemWords, $potentialWords);
            $descriptionScore = count($commonWords) / max(count($itemWords), count($potentialWords), 1);
            $score += $descriptionScore * 0.6;

            // Location match
            similar_text(strtolower($item->location), strtolower($potential->location), $locPercent);
            $score += ($locPercent / 100) * 0.3;

            // Image boost
            if ($item->images && $potential->images) {
                $score += 0.1;
            }

            // Date proximity (within 30 days)
            $daysDiff = abs($item->date_lost_found->diffInDays($potential->date_lost_found));
            if ($daysDiff <= 30) {
                $score += (1 - ($daysDiff / 30)) * 0.1;
            }

            if ($score >= 0.3) {
                $potential->match_score = round($score * 100);
                $matches->push($potential);
            }
        }

        // Sort by highest match score
        $matches = $matches->sortByDesc('match_score')->take(8);

        return view('lostfound.show', compact('item', 'matches'));
    }

    /**
     * Mark item as resolved (owner or admin only)
     */
    public function resolve(LostFoundItem $item)
    {
        if (Auth::id() !== $item->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $item->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Item marked as resolved!');
    }
}