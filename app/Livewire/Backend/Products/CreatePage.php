<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\CreateProductAction;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Livewire\Forms\Backend\ProductForm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;

class CreatePage extends Component
{
    use WithFileUploads;

    public ProductForm $form;
    public bool $has_variants = false;
    public array $selected_attributes = [];
    public array $variants = [];

    public function mount(): void
    {
        $this->authorize('create', Product::class);
    }

    #[Computed]
    public function categories()
    {
        return Category::ofType('product')->active()->orderBy('name')->get();
    }

    #[Computed]
    public function brands()
    {
        return Brand::active()->orderBy('name')->get();
    }

    #[Computed]
    public function allAttributes()
    {
        return \App\Models\Attribute::with('values')->orderBy('display_order')->get();
    }

    public function updatedSelectedAttributes()
    {
        $this->generateVariants();
    }

    public function generateVariants()
    {
        // 1. Get all selected attribute values grouped by attribute
        $attributeGroups = [];
        foreach ($this->selected_attributes as $attrId => $valueIds) {
            $valueIds = array_filter($valueIds);
            if (!empty($valueIds)) {
                $attributeGroups[] = \App\Models\AttributeValue::whereIn('id', $valueIds)->get();
            }
        }

        if (empty($attributeGroups)) {
            $this->variants = [];
            return;
        }

        // 2. Cartesian product to get all combinations
        $combinations = [[]];
        foreach ($attributeGroups as $group) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($group as $value) {
                    $newCombinations[] = array_merge($combination, [$value]);
                }
            }
            $combinations = $newCombinations;
        }

        // 3. Map combinations to variant rows
        $this->variants = array_map(function ($combo) {
            $nameParts = array_map(fn($v) => $v->value, $combo);
            $idParts = array_map(fn($v) => $v->id, $combo);
            sort($idParts);
            $key = implode('-', $idParts);

            return [
                'key' => $key,
                'name' => implode(' / ', $nameParts),
                'attribute_value_ids' => $idParts,
                'sku' => $this->form->sku ? $this->form->sku . '-' . strtoupper(implode('-', $nameParts)) : '',
                'price' => $this->form->price ?: '0',
                'stock' => '0',
                'is_active' => true
            ];
        }, $combinations);
    }

    public function save(CreateProductAction $action): void
    {
        $rawPrice = $this->form->price;
        $rawSalePrice = $this->form->sale_price;

        $this->form->normalizeMoney();
        
        // Convert empty strings to null for foreign keys
        if (empty($this->form->category_id)) $this->form->category_id = null;
        if (empty($this->form->brand_id)) $this->form->brand_id = null;

        try {
            $validated = $this->form->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->form->price = $rawPrice;
            $this->form->sale_price = $rawSalePrice;
            throw $e;
        }

        // Update form object from component state for variants
        $this->form->has_variants = $this->has_variants;
        $this->form->variants = $this->variants;

        try {
            $data = ProductData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', 'Sản phẩm đã được tạo thành công!');
            $this->redirect(route('backend.products.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.products.create-page', [
            'pageTitle' => 'Thêm sản phẩm',
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm sản phẩm',
        ]);
    }
}
