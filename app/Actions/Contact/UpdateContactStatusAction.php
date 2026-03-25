<?php

namespace App\Actions\Contact;

use App\Models\ActivityLog;
use App\Models\Contact;

class UpdateContactStatusAction
{
    public function execute(Contact $contact, string $status): Contact
    {
        $oldStatus = $contact->status;
        $contact->update(['status' => $status]);

        ActivityLog::log('contacts.status', $contact, [
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        return $contact;
    }
}
