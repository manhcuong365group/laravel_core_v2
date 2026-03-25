<?php

namespace App\Livewire\Backend\Urls;

use App\Actions\Url\CreateUrlAction;
use App\Models\Url;
use App\Traits\WithUrlForms;
use Livewire\Component;

class CreatePage extends Component
{
    use WithUrlForms;

    public function mount(): void
    {
        $this->authorize('create', Url::class);
    }

    protected function rules(): array
    {
        return $this->urlRules();
    }

    public function save(CreateUrlAction $action): void
    {
        $validated = $this->validate();

        try {
            $action->execute(\App\Data\UrlData::fromArray($validated));

            session()->flash('success', 'URL đã được tạo thành công!');
            $this->redirect(route('backend.urls.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo URL. Vui lòng thử lại!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.urls.create-page', [
            'pageTitle' => 'Thêm URL',
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm URL',
        ]);
    }
}
