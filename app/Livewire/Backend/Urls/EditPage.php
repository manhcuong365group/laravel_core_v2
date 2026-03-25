<?php

namespace App\Livewire\Backend\Urls;

use App\Actions\Url\UpdateUrlAction;
use App\Models\Url;
use App\Traits\WithUrlForms;
use Livewire\Component;

class EditPage extends Component
{
    use WithUrlForms;

    public int $urlId;

    public function mount(Url $url): void
    {
        $this->authorize('update', $url);

        $this->urlId = $url->id;
        $this->title = $url->title;
        $this->original_url = $url->original_url;
        $this->short_url = $url->short_url;
        $this->description = $url->description ?? '';
        $this->is_active = (bool) $url->is_active;
    }

    protected function rules(): array
    {
        return $this->urlRules($this->urlId);
    }

    public function save(UpdateUrlAction $action): void
    {
        $url = Url::findOrFail($this->urlId);
        $this->authorize('update', $url);

        $validated = $this->validate();

        try {
            $action->execute($url, \App\Data\UrlData::fromArray($validated));

            session()->flash('success', 'URL đã được cập nhật thành công!');
            $this->redirect(route('backend.urls.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật URL. Vui lòng thử lại!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.urls.edit-page', [
            'url' => Url::findOrFail($this->urlId),
            'pageTitle' => 'Sửa URL: ' . $this->title,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa URL: ' . $this->title,
        ]);
    }
}
