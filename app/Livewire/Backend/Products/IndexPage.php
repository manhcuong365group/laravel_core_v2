<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\BulkDeleteProductAction;
use App\Actions\Product\BulkStatusProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Actions\Product\CopyProductAction;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class IndexPage extends Component
{
    use \App\Traits\WithBackendTable;

    public function mount(): void
    {
        $this->authorize('viewAny', Product::class);
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => '', 'as' => 'category'],
        'brandFilter' => ['except' => '', 'as' => 'brand'],
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public ?string $brandFilter = null;

    /**
     * Get the products query for the table.
     */
    public function getProductsQuery()
    {
        return Product::with(['category', 'brand'])
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->brandFilter, fn($query) => $query->where('brand_id', $this->brandFilter))
            ->when($this->statusFilter !== '', fn($query) => $query->where('is_active', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getProductsQuery()->get());
    }

    public function executeDelete(DeleteProductAction $deleteAction, BulkDeleteProductAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Product::class, 'sản phẩm');
    }

    public function toggleStatus(int $id, BulkStatusProductAction $action): void
    {
        $this->executeToggleStatus($id, Product::class, $action);
    }

    public function toggleFeatured(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);
        
        $product->update(['is_featured' => !$product->is_featured]);
        
        $this->notify('Đã cập nhật trạng thái nổi bật.');
    }

    public function updateField(int $id, string $field, $value): void
    {
        $this->executeUpdateField($id, $field, $value, Product::class);
    }

    public function copyProduct(int $id, CopyProductAction $action): void
    {
        $product = Product::findOrFail($id);
        $this->authorize('create', Product::class);

        $clone = $action->execute($product);

        $this->notify('Đã sao chép sản phẩm thành công.');
        
        $this->redirect(route('backend.products.edit', $clone), navigate: true);
    }

    public function bulkStatus(int $isActive, BulkStatusProductAction $action): void
    {
        $this->executeBulkStatus($isActive, $action, 'sản phẩm');
    }

    public function render()
    {
        $products = $this->getProductsQuery()->paginate($this->perPage);

        $categories = Category::ofType('product')
            ->active()
            ->orderBy('name')
            ->get();

        $brands = \App\Models\Brand::active()
            ->orderBy('name')
            ->get();

        return view('livewire.backend.products.index-page', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'title' => 'Quản lý sản phẩm',
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý sản phẩm',
        ]);
    }
}

