<?php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject' => config('app.url', 'https://your-app-domain.com'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $this->webPush = new WebPush($auth);
    }

    /**
     * Send a push notification to a specific user
     *
     * @param int $userId The ID of the user to notify
     * @param string $title Notification title
     * @param string $body Notification body text
     * @param string|null $url Optional URL to open when notification is clicked
     * @param string|null $icon Optional icon URL (defaults to your app icon)
     * @return void
     */
    public function sendToUser($userId, $title, $body, $url = null, $icon = null)
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) {
            Log::info("No push subscriptions found for user ID: {$userId}");
            return;
        }

        $defaultIcon = $icon ?? asset('assets/img/favicon/favicon.ico');
        $defaultUrl = $url ?? config('app.url');

        foreach ($subscriptions as $sub) {
            try {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->keys_p256dh,
                    'authToken' => $sub->keys_auth,
                ]);

                $payload = json_encode([
                    'title' => $title,
                    'body' => $body,
                    'icon' => $defaultIcon,
                    'url' => $defaultUrl,
                ]);

                $this->webPush->queueNotification($subscription, $payload);

                Log::info("Push queued for endpoint: {$sub->endpoint}");
            } catch (\Exception $e) {
                Log::error("Failed to queue push for endpoint {$sub->endpoint}: " . $e->getMessage());
            }
        }

        // Flush all queued notifications
        $reports = $this->webPush->flush();

        foreach ($reports as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                Log::info("Push sent successfully to {$endpoint}");
            } else {
                Log::warning("Push failed to {$endpoint}: " . $report->getReason());
            }
        }
    }
}