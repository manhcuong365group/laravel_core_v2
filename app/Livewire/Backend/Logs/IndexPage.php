<?php

namespace App\Livewire\Backend\Logs;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public $user_id;
    public $action;
    public $date_from;
    public $date_to;

    protected $queryString = ['user_id', 'action', 'date_from', 'date_to'];

    public function updating()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['user_id', 'action', 'date_from', 'date_to']);
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::query()
            ->with(['user'])
            ->when($this->user_id, fn($query) => $query->where('user_id', $this->user_id))
            ->when($this->action, fn($query) => $query->where('action', 'like', '%' . $this->action . '%'))
            ->when($this->date_from, fn($query) => $query->whereDate('created_at', '>=', $this->date_from))
            ->when($this->date_to, fn($query) => $query->whereDate('created_at', '<=', $this->date_to))
            ->latest()
            ->paginate(30);

        return view('livewire.backend.logs.index-page', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
        ])->layout('backend.layouts.app', ['title' => 'Hoạt động gần đây']);
    }
}

