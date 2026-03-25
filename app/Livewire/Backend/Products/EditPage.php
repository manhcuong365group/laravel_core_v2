<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\DeleteProductMediaAction;
use App\Actions\Product\UpdateProductAction;
use App\Data\ProductData;
use App\Models\Product;
use App\Traits\WithProductForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads, WithProductForms;

    public int $productId;
    public $currentFeaturedMedia = [];
    public $currentGalleryMedia = [];

    public function mount(Product $product): void
    {
        $this->authorize('update', $product);

        $this->productId = $product->id;
        
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku ?? '';
        $this->short_description = $product->short_description ?? '';
        $this->content = $product->content ?? '';
        
        // Ensure values are strings for formatting if needed, though they'll be normalized in save
        $this->price = $product->price ? number_format((float) $product->price, 0, '', '.') : '0';
        $this->sale_price = $product->sale_price ? number_format((float) $product->sale_price, 0, '', '.') : '';
        
        $this->stock_quantity = (string) ($product->stock_quantity ?? '0');
        $this->stock_status = $product->stock_status ?? 'in_stock';
        $this->category_id = (string) ($product->category_id ?? '');
        $this->brand_id = (string) ($product->brand_id ?? '');
        $this->is_active = (bool) $product->is_active;
        $this->is_featured = (bool) $product->is_featured;
        $this->order = (string) ($product->order ?? '0');
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';
        $this->meta_keywords = $product->meta_keywords ?? '';

        $this->currentFeaturedMedia = $product->getMedia('featured_image');
        $this->currentGalleryMedia = $product->getMedia('gallery');
    }

    protected function rules(): array
    {
        return $this->productRules($this->productId);
    }

    public function removeGalleryImage(int $index): void
    {
        array_splice($this->gallery, $index, 1);
    }

    public function save(UpdateProductAction $action): void
    {
        $product = Product::findOrFail($this->productId);
        $this->authorize('update', $product);

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
            $action->execute($product, $data);

            session()->flash('success', 'Sản phẩm đã được cập nhật thành công!');
            $this->redirect(route('backend.products.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật sản phẩm. Vui lòng thử lại!', type: 'error');
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

    public function render()
    {
        return view('livewire.backend.products.edit-page', [
            'product' => Product::findOrFail($this->productId),
            'categories' => $this->getCategories(),
            'brands' => $this->getBrands(),
            'pageTitle' => 'Sửa sản phẩm: ' . $this->name,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa sản phẩm: ' . $this->name,
        ]);
    }

}
