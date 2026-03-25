<?php

namespace App\Actions\Newsletter;

use App\Models\ActivityLog;
use App\Models\Subscriber;

class DeleteSubscriberAction
{
    public function execute(Subscriber $subscriber): void
    {
        $email = $subscriber->email;
        $subscriber->delete();

        ActivityLog::log('newsletters.delete', null, [
            'email' => $email,
        ]);
    }
}
