<?php

namespace App\Livewire\Backend\Brands;

use App\Actions\Brand\CreateBrandAction;
use App\Data\BrandData;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $website = '';
    public string $order = '0';
    public bool $is_active = true;
    public $logo;

    public function mount(): void
    {
        $this->authorize('create', Brand::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
        }
    }

    public function save(CreateBrandAction $action): void
    {
        $validated = $this->validate();

        $data = BrandData::fromArray($validated);
        $action->execute($data);

        session()->flash('success', 'Thương hiệu đã được tạo thành công!');
        $this->redirect(route('backend.brands.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.brands.create-page')
            ->layout('backend.layouts.app', [
                'title' => 'Thêm thương hiệu',
            ]);
    }
}


