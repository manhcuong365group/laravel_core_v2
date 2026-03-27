<?php

namespace App\Livewire\Backend\Brands;

use App\Actions\Brand\UpdateBrandAction;
use App\Data\BrandData;
use App\Models\Brand;
use App\Traits\WithBrandForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads, WithBrandForms;

    public int $brandId;
    public ?string $currentLogoUrl = null;

    public function mount(Brand $brand): void
    {
        $this->authorize('update', $brand);
        
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->description = $brand->description ?? '';
        $this->website = $brand->website ?? '';
        $this->order = (string) ($brand->order ?? '0');
        $this->is_active = (bool) $brand->is_active;

        $this->currentLogoUrl = $brand->getFirstMediaUrl('logo', 'thumb') ?: null;
    }

    protected function rules(): array
    {
        return $this->brandRules($this->brandId);
    }

    public function save(UpdateBrandAction $action): void
    {
        $brand = Brand::findOrFail($this->brandId);
        $this->authorize('update', $brand);

        $validated = $this->validate();

        try {
            $data = BrandData::fromArray($validated);
            $action->execute($brand, $data);

            session()->flash('success', 'Thương hiệu đã được cập nhật!');
            $this->redirect(route('backend.brands.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật thương hiệu!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.brands.edit-page')
            ->layout('backend.layouts.app', [
                'title' => 'Sửa thương hiệu',
            ]);
    }
}
