<?php

namespace App\Livewire\Backend\Brands;

use App\Actions\Brand\CreateBrandAction;
use App\Data\BrandData;
use App\Models\Brand;
use App\Traits\WithBrandForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads, WithBrandForms;

    public function mount(): void
    {
        $this->authorize('create', Brand::class);
    }

    protected function rules(): array
    {
        return $this->brandRules();
    }

    public function save(CreateBrandAction $action): void
    {
        $validated = $this->validate();

        try {
            $data = BrandData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', 'Thương hiệu đã được tạo thành công!');
            $this->redirect(route('backend.brands.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo thương hiệu!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.brands.create-page')
            ->layout('backend.layouts.app', [
                'title' => 'Thêm thương hiệu',
            ]);
    }
}
