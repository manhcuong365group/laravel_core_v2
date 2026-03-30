<?php

namespace App\Livewire\Backend\Products;

use App\Actions\Product\BulkDeleteProductAction;
use App\Actions\Product\BulkStatusProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Actions\Product\CopyProductAction;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use App\Livewire\Forms\Backend\ProductForm;

class IndexPage extends Component
{
    use \App\Traits\WithBackendTable, WithFileUploads;

    public ProductForm $form;

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

    public ?int $brandFilter = null;
    
    // Quick Edit Drawer State
    public bool $showQuickDrawer = false;
    public array $editingProduct = [
        'id' => null,
        'name' => '',
        'sku' => '',
        'price' => '0',
        'sale_price' => '0',
        'stock_quantity' => 0,
        'is_active' => true,
        'has_variants' => false,
    ];

    public $importFile;
    public bool $importModal = false;

    #[Computed]
    public function categories()
    {
        return Category::ofType('product')->active()->orderBy('name')->get();
    }

    #[Computed]
    public function brands()
    {
        return \App\Models\Brand::active()->orderBy('name')->get();
    }

    #[Computed]
    public function products()
    {
        return Product::with(['category', 'brand', 'media'])
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->brandFilter, fn($query) => $query->where('brand_id', $this->brandFilter))
            ->when($this->statusFilter !== '', fn($query) => $query->where('is_active', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll(collect(Product::query()->paginate($this->perPage)->items()));
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
        $this->executeBulkStatus($isActive, $action, 'sản phẩm', Product::class);
    }

    public function editProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->form->setProduct($product);
        $this->editingProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price ? number_format((float) $product->price, 0, '', '.') : '0',
            'sale_price' => $product->sale_price ? number_format((float) $product->sale_price, 0, '', '.') : '',
            'stock_quantity' => $product->stock_quantity,
            'is_active' => (bool) $product->is_active,
            'has_variants' => $product->variants()->count() > 0,
        ];
        $this->showQuickDrawer = true;
    }

    public function updateQuickEdit(): void
    {
        $product = Product::findOrFail($this->editingProduct['id']);
        $this->authorize('update', $product);

        // Sync values from local editing state to form
        $this->form->price = $this->editingProduct['price'];
        $this->form->sale_price = $this->editingProduct['sale_price'];
        $this->form->stock_quantity = (string)$this->editingProduct['stock_quantity'];
        $this->form->is_active = $this->editingProduct['is_active'];

        $this->form->normalizeMoney();
        $this->validate($this->form->getRules());

        $product->update([
            'price' => $this->form->price,
            'sale_price' => $this->form->sale_price,
            'stock_quantity' => $this->form->stock_quantity,
            'is_active' => $this->form->is_active,
        ]);

        $this->showQuickDrawer = false;
        $this->notify('Đã cập nhật sản phẩm nhanh thành công.');
    }

    public function exportExcel()
    {
        $this->authorize('viewAny', Product::class);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProductsExport, 'danh-sach-san-pham.xlsx');
    }

    public function importExcel()
    {
        $this->authorize('update', Product::class);

        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ProductsImport, $this->importFile->getRealPath());
            
            $this->importModal = false;
            $this->importFile = null;
            $this->dispatch('toast', message: 'Nhập dữ liệu thành công!', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Lỗi: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.products.index-page', [
            'title' => 'Quản lý sản phẩm',
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý sản phẩm',
        ]);
    }
}

