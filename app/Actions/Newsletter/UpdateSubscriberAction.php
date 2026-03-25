<?php

namespace App\Actions\Newsletter;

use App\Data\NewsletterData;
use App\Models\ActivityLog;
use App\Models\Subscriber;

class UpdateSubscriberAction
{
    public function execute(Subscriber $subscriber, NewsletterData $data): Subscriber
    {
        $oldStatus = $subscriber->status;
        $payload = $data->toArray();
        $status = $data->status;

        $subscriber->update([
            ...$payload,
            'subscribed_at' => $status === 'subscribed' ? ($subscriber->subscribed_at ?? now()) : $subscriber->subscribed_at,
            'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
        ]);

        ActivityLog::log('newsletters.update', $subscriber, [
            'old_status' => $oldStatus,
            'new_status' => $status,
            'email' => $subscriber->email,
        ]);

        return $subscriber;
    }
}
