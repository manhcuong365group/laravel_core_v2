<?php

namespace App\Actions\Contact;

use App\Models\ActivityLog;
use App\Models\Contact;

class UpdateContactNotesAction
{
    public function execute(Contact $contact, ?string $notes): Contact
    {
        $contact->update(['admin_notes' => $notes]);

        ActivityLog::log('contacts.notes', $contact, [
            'has_notes' => !empty($notes),
        ]);

        return $contact;
    }
}
