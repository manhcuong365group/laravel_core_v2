<?php

namespace App\Actions\Newsletter;

use App\Models\ActivityLog;
use App\Models\Subscriber;

class UpdateSubscriberStatusAction
{
    public function execute(Subscriber $subscriber, string $status): Subscriber
    {
        $oldStatus = $subscriber->status;

        $subscriber->update([
            'status' => $status,
            'subscribed_at' => $status === 'subscribed'
                ? ($subscriber->subscribed_at ?? now())
                : $subscriber->subscribed_at,
            'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
        ]);

        ActivityLog::log('newsletters.status', $subscriber, [
            'old_status' => $oldStatus,
            'new_status' => $status,
            'email' => $subscriber->email,
        ]);

        return $subscriber;
    }
}
