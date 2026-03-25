<?php

namespace App\Livewire\Backend\Contacts;

use App\Actions\Contact\DeleteContactAction;
use App\Actions\Contact\UpdateContactNotesAction;
use App\Actions\Contact\UpdateContactStatusAction;
use App\Models\Contact;
use Livewire\Component;

class ShowPage extends Component
{
    public Contact $contact;
    public string $status;
    public ?string $admin_notes;

    public function mount(Contact $contact): void
    {
        $this->authorize('view', $contact);
        
        $this->contact = $contact;
        $this->status = $contact->status;
        $this->admin_notes = $contact->admin_notes;

        if ($contact->status === 'new') {
            $this->updateStatus('read', new UpdateContactStatusAction());
        }
    }

    public function updateStatus(string $newStatus, UpdateContactStatusAction $action): void
    {
        $this->authorize('update', $this->contact);
        
        $this->contact = $action->execute($this->contact, $newStatus);
        $this->status = $this->contact->status;
        
        $this->dispatch('toast', message: 'Trạng thái đã được cập nhật.', type: 'success');
    }

    public function updateNotes(UpdateContactNotesAction $action): void
    {
        $this->authorize('update', $this->contact);
        
        $this->contact = $action->execute($this->contact, $this->admin_notes);
        
        $this->dispatch('toast', message: 'Ghi chú đã được cập nhật.', type: 'success');
    }

    public function deleteContact(DeleteContactAction $action): void
    {
        $this->authorize('delete', $this->contact);
        
        $action->execute($this->contact);
        
        session()->flash('success', 'Liên hệ đã được xóa thành công.');
        $this->redirectroute('backend.contacts.index');
    }

    public function render()
    {
        return view('livewire.backend.contacts.show-page', [
            'statuses' => [
                'new' => 'Mới',
                'read' => 'Đã đọc',
                'replied' => 'Đã phản hồi',
            ],
        ])->layout('backend.layouts.app', [
            'title' => 'Chi tiết liên hệ #' . $this->contact->id,
        ]);
    }
}


