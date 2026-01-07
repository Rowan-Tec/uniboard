<?php

namespace App\Http\Controllers;

use App\Models\LostFoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundController extends Controller
{
    public function index()
    {
        $lostItems = LostFoundItem::lost()->with('user')->latest()->get();
        $foundItems = LostFoundItem::found()->with('user')->latest()->get();

        return view('lostfound.index', compact('lostItems', 'foundItems'));
    }

    // We'll add store(), show(), resolve() later
}