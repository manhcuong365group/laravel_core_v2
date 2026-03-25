<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\CreateProductAction;
use App\Data\ProductData;
use App\Models\Product;
use App\Traits\WithProductForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads, WithProductForms;

    public function mount(): void
    {
        $this->authorize('create', Product::class);
    }

    protected function rules(): array
    {
        return $this->productRules();
    }

    public function save(CreateProductAction $action): void
    {
        $rawPrice = $this->price;
        $rawSalePrice = $this->sale_price;

        $this->price = $this->normalizeMoney($this->price) ?? '0';
        $this->sale_price = $this->normalizeMoney($this->sale_price);
        
        // Convert empty strings to null for foreign keys
        if (empty($this->category_id)) $this->category_id = null;
        if (empty($this->brand_id)) $this->brand_id = null;

        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->price = $rawPrice;
            $this->sale_price = $rawSalePrice;
            throw $e;
        }

        try {
            $data = ProductData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', 'Sản phẩm đã được tạo thành công!');
            $this->redirect(route('backend.products.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo sản phẩm. Vui lòng thử lại!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.products.create-page', [
            'categories' => $this->getCategories(),
            'brands' => $this->getBrands(),
            'pageTitle' => 'Thêm sản phẩm',
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm sản phẩm',
        ]);
    }
}
