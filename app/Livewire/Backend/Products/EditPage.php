<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\DeleteProductMediaAction;
use App\Actions\Product\UpdateProductAction;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Livewire\Forms\Backend\ProductForm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;

class EditPage extends Component
{
    use WithFileUploads;

    public ProductForm $form;
    public int $productId;
    public $currentFeaturedMedia = [];
    public $currentGalleryMedia = [];
    
    public bool $has_variants = false;
    public array $selected_attributes = [];
    public array $variants = [];

    public function mount(Product $product): void
    {
        $this->authorize('update', $product);

        $this->productId = $product->id;
        $this->form->setProduct($product);

        $this->currentFeaturedMedia = $product->getMedia('featured_image');
        $this->currentGalleryMedia = $product->getMedia('gallery');
        
        // Load variants
        $variants = $product->variants()->with('attributeValues')->get();
        if ($variants->count() > 0) {
            $this->has_variants = true;
            foreach ($variants as $variant) {
                $idParts = $variant->attributeValues->pluck('id')->toArray();
                sort($idParts);
                $key = implode('-', $idParts);
                
                $this->variants[] = [
                    'key' => $key,
                    'name' => implode(' / ', $variant->attributeValues->pluck('value')->toArray()),
                    'attribute_value_ids' => $idParts,
                    'sku' => $variant->sku,
                    'price' => number_format((float) $variant->price, 0, '', '.'),
                    'stock' => (string) $variant->stock_quantity,
                    'is_active' => (bool) $variant->is_active,
                ];

                // Reconstruct selected attributes for the UI
                foreach ($variant->attributeValues as $av) {
                    $this->selected_attributes[$av->attribute_id][$av->id] = (string) $av->id;
                }
            }
        }
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

        // Preserve existing variant data if keys match
        $oldVariants = collect($this->variants)->keyBy('key');

        $this->variants = array_map(function ($combo) use ($oldVariants) {
            $nameParts = array_map(fn($v) => $v->value, $combo);
            $idParts = array_map(fn($v) => $v->id, $combo);
            sort($idParts);
            $key = implode('-', $idParts);

            if ($oldVariants->has($key)) {
                return $oldVariants->get($key);
            }

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

    public function removeGalleryImage(int $index): void
    {
        $this->form->removeGalleryImage($index);
    }

    public function save(UpdateProductAction $action): void
    {
        $product = Product::findOrFail($this->productId);
        $this->authorize('update', $product);

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

        // Sync component variant state to form
        $this->form->has_variants = $this->has_variants;
        $this->form->variants = $this->variants;

        try {
            $data = ProductData::fromArray($this->form->validate());
            $action->execute($product, $data);

            session()->flash('success', 'Sản phẩm đã được cập nhật thành công!');
            $this->redirect(route('backend.products.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteMedia(int $mediaId, DeleteProductMediaAction $action): void
    {
        $product = Product::findOrFail($this->productId);
        $this->authorize('update', $product);

        $action->execute($product, $mediaId);
        
        $this->currentFeaturedMedia = $product->getMedia('featured_image');
        $this->currentGalleryMedia = $product->getMedia('gallery');
        $this->dispatch('toast', message: 'Đã xóa ảnh.', type: 'success');
    }

    public function sortMedia(array $items): void
    {
        $product = Product::findOrFail($this->productId);
        $this->authorize('update', $product);

        foreach ($items as $item) {
            \Spatie\MediaLibrary\MediaCollections\Models\Media::where('id', $item['id'])
                ->update(['order_column' => $item['position']]);
        }

        $this->currentGalleryMedia = $product->getMedia('gallery');
        $this->dispatch('toast', message: 'Đã cập nhật thứ tự ảnh.', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.products.edit-page', [
            'product' => Product::findOrFail($this->productId),
            'pageTitle' => 'Sửa sản phẩm: ' . $this->form->name,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa sản phẩm: ' . $this->form->name,
        ]);
    }
}
