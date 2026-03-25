<?php

namespace App\Livewire\Backend\Settings;

use App\Models\Redirect;
use Livewire\Component;
use Livewire\WithPagination;

class RedirectsPage extends Component
{
    use WithPagination;

    public $isEdit = false;
    public $redirectId;
    public $old_url;
    public $new_url;
    public $status_code = 301;
    public $is_active = true;

    protected $rules = [
        'old_url' => 'required|string|max:500',
        'new_url' => 'required|string|max:500',
        'status_code' => 'required|in:301,302,307',
        'is_active' => 'boolean',
    ];

    public function edit(Redirect $redirect)
    {
        $this->isEdit = true;
        $this->redirectId = $redirect->id;
        $this->old_url = $redirect->old_url;
        $this->new_url = $redirect->new_url;
        $this->status_code = $redirect->status_code;
        $this->is_active = $redirect->is_active;
    }

    public function resetFields()
    {
        $this->isEdit = false;
        $this->redirectId = null;
        $this->old_url = '';
        $this->new_url = '';
        $this->status_code = 301;
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $redirect = Redirect::find($this->redirectId);
            $redirect->update([
                'old_url' => $this->old_url,
                'new_url' => $this->new_url,
                'status_code' => $this->status_code,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', message: 'Redirect đã được cập nhật!', type: 'success');
        } else {
            Redirect::create([
                'old_url' => $this->old_url,
                'new_url' => $this->new_url,
                'status_code' => $this->status_code,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', message: 'Redirect đã được tạo!', type: 'success');
        }

        $this->resetFields();
    }

    public function delete(Redirect $redirect)
    {
        $redirect->delete();
        $this->dispatch('toast', message: 'Redirect đã được xóa!', type: 'success');
    }

    public function toggleStatus(Redirect $redirect)
    {
        $redirect->update(['is_active' => !$redirect->is_active]);
    }

    public function render()
    {
        $redirects = Redirect::latest()->paginate(20);

        return view('livewire.backend.settings.redirects-page', [
            'redirects' => $redirects,
        ])->layout('backend.layouts.app', ['title' => 'Điều hướng URL']);
    }
}

