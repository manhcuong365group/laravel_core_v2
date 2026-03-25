<?php

namespace App\Actions\Newsletter;

use App\Data\NewsletterData;
use App\Models\ActivityLog;
use App\Models\Subscriber;

class CreateSubscriberAction
{
    public function execute(NewsletterData $data): Subscriber
    {
        $payload = $data->toArray();
        $subscriber = Subscriber::create([
            ...$payload,
            'subscribed_at' => $data->status === 'subscribed' ? now() : null,
            'unsubscribed_at' => $data->status === 'unsubscribed' ? now() : null,
        ]);

        ActivityLog::log('newsletters.create', $subscriber, [
            'email' => $subscriber->email,
            'status' => $subscriber->status,
        ]);

        return $subscriber;
    }
}
