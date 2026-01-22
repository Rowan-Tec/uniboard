<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            ['user_id' => Auth::id(), 'endpoint' => $data['endpoint']],
            [
                'keys_p256dh' => $data['keys']['p256dh'],
                'keys_auth' => $data['keys']['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|url']);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['success' => true]);
    }
}