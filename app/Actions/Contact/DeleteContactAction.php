<?php

namespace App\Actions\Contact;

use App\Models\ActivityLog;
use App\Models\Contact;

class DeleteContactAction
{
    public function execute(Contact $contact): void
    {
        $contactId = $contact->id;
        $contact->delete();

        ActivityLog::log('contacts.delete', null, [
            'contact_id' => $contactId,
        ]);
    }
}
