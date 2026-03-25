# 16-implementation-blueprint-product.md - Bản Mẫu Chuẩn Cho Module Admin

Tài liệu này đúc kết các tính năng thực chiến từ Module **Product** (đã hoàn thiện) làm tiêu chuẩn vàng (Gold Standard) để áp dụng cho các module khác (Category, Brand, v.v.).

---

## 1) Kiến Trúc Luồng Dữ Liệu (Data Flow)

```
Livewire Component → Trait (Form State + Rules) → DTO (Data Object) → Action → Model
```

| Layer | Thư mục | Vai trò | Ví dụ |
| :--- | :--- | :--- | :--- |
| **Livewire** | `app/Livewire/Backend/{Module}/` | UI Controller - mỏng, chỉ validate + gọi Action | `CreatePage.php`, `EditPage.php` |
| **Trait** | `app/Traits/` | Tái sử dụng form fields, rules, helpers giữa Create/Edit | `WithProductForms.php` |
| **DTO** | `app/Data/` | Data Transfer Object - type-safe contract giữa UI và Business | `ProductData.php` extends `BaseData` |
| **Action** | `app/Actions/{Module}/` | Single Responsibility - chứa business logic duy nhất | `CreateProductAction.php` |
| **Event** | `app/Events/` | Dispatch sự kiện sau khi Action thành công | `ProductCreated.php` |
| **Service** | `app/Services/` | Xử lý logic chia sẻ giữa nhiều Action (Media, Mail...) | `MediaService.php` |
| **Model** | `app/Models/` | Active Record - tương tác DB, relationships, scopes | `Product.php` |
| **Policy** | `app/Policies/` | Authorization - phân quyền CRUD | `ProductPolicy.php` |

---

## 2) Quy Trình 7 Bước Triển Khai Module Mới

Khi tạo một Module Admin mới (ví dụ `Category`), hãy tuân thủ thứ tự sau:

1.  **Base Data (nếu chưa có)**: Kế thừa `App\Data\BaseData` - tự động hydrate array → constructor params.
2.  **Data Contract (DTO)**: Tạo `App\Data\CategoryData extends BaseData`.
3.  **Business Logic (Actions)**: Tạo `App\Actions\Category\CreateCategoryAction`, `Update...`, `Delete...`.
4.  **Events**: Tạo `App\Events\CategoryCreated`, `CategoryUpdated` để mở rộng logic hậu kỳ.
5.  **Security (Policy)**: Tạo `App\Policies\CategoryPolicy` và áp dụng quyền `viewAny`, `create`, `update`, `delete`.
6.  **UI State (Livewire)**: Tạo `App\Livewire\Backend\Categories\IndexPage`, `CreatePage`, `EditPage`.
7.  **UX Enhancement**: Thêm các bộ lọc, chỉnh sửa nhanh (Inline Edit), và Bulk Actions.

---

## 3) Enterprise Patterns Đã Áp Dụng

### A) Pattern: DB Transaction (Data Integrity)
Mọi Action có thao tác ghi/sửa qua nhiều bảng hoặc Services ngoài đều **BẮT BUỘC** bọc `DB::transaction`.

**Mục đích**: Nếu upload media thất bại, Product cũng rollback khỏi DB → không sinh rác.

```php
// app/Actions/Product/CreateProductAction.php
public function execute(ProductData $data): Product
{
    $product = DB::transaction(function () use ($data) {
        $product = Product::create($data->toArray());
        $this->mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
        $this->mediaService->uploadMultiple($product, $data->gallery, 'gallery');
        return $product;
    });

    event(new ProductCreated($product));
    return $product;
}
```

### B) Pattern: Event Dispatching (Extensibility)
Bắn Event **sau Transaction thành công** (ngoài closure `DB::transaction`).

**Mục đích**: Các Listener có thể lắng nghe để gửi mail, đồng bộ ElasticSearch, xóa cache mà không cần sửa Action.

```php
// app/Events/ProductCreated.php
class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Product $product) {}
}
```

### C) Pattern: Try-Catch ở Livewire Layer (User Safety)
Mọi hàm `save()` trong Livewire đều bọc `try-catch(\Throwable $e)`.

**Mục đích**: End User không bao giờ thấy màn hình đỏ lỗi. Luôn hiện Toast thân thiện.

```php
// app/Livewire/Backend/Products/CreatePage.php
public function save(CreateProductAction $action): void
{
    $validated = $this->validate();

    try {
        $data = ProductData::fromArray($validated);
        $action->execute($data);

        session()->flash('success', 'Thành công!');
        $this->redirect(route('backend.products.index'), navigate: true);
    } catch (\Throwable $e) {
        report($e);                                          // Log to Sentry/Telescope
        $this->dispatch('toast', message: 'Có lỗi xảy ra!', type: 'error');
    }
}
```

### D) Pattern: BaseData Reflection (DRY - Don't Repeat Yourself)
Lớp `App\Data\BaseData` sử dụng **PHP Reflection** để tự động map array vào constructor.

**Mục đích**: Khi tạo DTO mới, **không cần viết hàm `fromArray()` thủ công** nữa. Chỉ cần khai báo constructor.

```php
// app/Data/CategoryData.php (Ví dụ mẫu cho module mới)
class CategoryData extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public bool $is_active = true,
    ) {}
}

// Sử dụng: CategoryData::fromArray($validated) → Tự động!
```

### E) Pattern: Inline Editing (Sửa nhanh tại bảng)
Giúp người dùng đổi giá, số lượng kho hoặc **Vị trí hiển thị** mà không cần vào trang Edit.
-   **Kỹ thuật**: Sử dụng Alpine.js quản lý `editing` state và `$wire.updateField()` để lưu.
-   **Quy chuẩn**: Các cột số (Giá, Kho, Vị trí) **BẮT BUỘC** dùng `text-center` ở cả Header và Body.
-   **Mẫu code Vị trí (Order)**:
```html
<div x-data="{ editing: false, value: '{{ $item->order ?? 0 }}' }">
    <div x-show="!editing" @click="editing = true; $nextTick(() => $refs.input.focus())" class="cursor-pointer">
        <span class="text-xs font-black">{{ $item->order ?? 0 }}</span>
    </div>
    <div x-show="editing" x-cloak>
        <input x-ref="input" type="number" x-model="value" 
            @blur="editing = false; if(value != '{{ $item->order ?? 0 }}') $wire.updateField({{ $item->id }}, 'order', value)"
            class="w-16 text-center rounded-lg border-primary">
    </div>
</div>
```

### F) Pattern: Quick Toggle (Bật/Tắt đồng bộ)
Sử dụng Component chung để đảm bảo màu sắc và icon nhất quán toàn hệ thống.
-   **Component**: `x-backend.toggle-icon`
-   **Mẫu code**:
```html
<x-backend.toggle-icon 
    type="featured" 
    :active="$item->is_featured" 
    wire:click="toggleFeatured({{ $item->id }})" 
/>
```

### I) Pattern: Magic SEO Suggestion (Gợi ý SEO 1-click)
Tự động điền thông tin SEO từ nội dung chính (Name -> Meta Title, Excerpt -> Meta Desc).
-   **Kỹ thuật**: Sử dụng Alpine.js gán trực tiếp giá trị vào biến Livewire qua `$wire`.
-   **Mẫu code**:
```html
<button type="button" x-on:click="
    $wire.meta_title = $wire.name || $wire.title;
    $wire.meta_description = ($wire.short_description || $wire.excerpt || '').replace(/<[^>]*>?/gm, '').substring(0, 160);
    $dispatch('toast', { message: 'Đã gợi ý SEO!', type: 'info' })
">
    Gợi ý SEO
</button>
```

### G) Pattern: Action Centric & Copy Logic
Tách biệt hoàn toàn việc ghi log, kiểm tra nghiệp vụ và xử lý Media ra khỏi Controller/Livewire.
-   **Copy**: Khi sao chép, cần nhân bản cả Slug, SKU (thêm hậu tố `-copy`) và **Media** (dùng `$media->copy($clone, 'collection')`).
-   **Bulk Actions**: Luôn xử lý mảng `selectedItems` qua `BulkDeleteAction` hoặc `BulkStatusAction` để tối ưu SQL (sử dụng `whereIn`).

### H) Pattern: Media Handling (Xử lý Ảnh)
Sử dụng một `MediaService` trung tâm để xử lý upload cho mọi Model.
```php
$mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
```

---

## 4) Cấu Trúc Thư Mục Module Product

```
app/
├── Actions/Product/
│   ├── CreateProductAction.php      # DB::transaction + event(ProductCreated)
│   ├── UpdateProductAction.php      # DB::transaction + event(ProductUpdated)
│   ├── DeleteProductAction.php
│   ├── CopyProductAction.php        # DB::transaction + media copy
│   ├── DeleteProductMediaAction.php
│   ├── BulkDeleteProductAction.php
│   └── BulkStatusProductAction.php
├── Data/
│   ├── BaseData.php                 # Abstract - PHP Reflection auto-hydrate
│   └── ProductData.php              # extends BaseData
├── Events/
│   ├── ProductCreated.php
│   └── ProductUpdated.php
├── Livewire/Backend/Products/
│   ├── IndexPage.php
│   ├── CreatePage.php               # try-catch + toast error
│   └── EditPage.php                 # try-catch + toast error
├── Models/
│   └── Product.php                  # Spatie Media, HasSlug, SoftDeletes
├── Policies/
│   └── ProductPolicy.php
├── Services/Media/
│   └── MediaService.php             # uploadSingle, uploadMultiple
└── Traits/
    ├── WithProductForms.php         # Shared form fields + rules + helpers
    └── WithBackendTable.php         # (Global Trait) Search, Sort, Select All logic
```

---

## 5) Công Nghệ & Package Sử Dụng

| Công nghệ | Package / Version | Mục đích |
| :--- | :--- | :--- |
| **Laravel** | 10/11+ | Framework chính |
| **Livewire** | 3.x | Full-page component, SPA-like UX |
| **Alpine.js** | 3.x | Inline editing, toggle, UI micro-interaction |
| **Spatie Media Library** | 10/11 | Upload, conversion (thumb/medium), collection |
| **Spatie Sluggable** | 3.x | Auto-generate slug từ name |
| **PHP Reflection** | Built-in | BaseData auto-hydrate DTO từ array |
| **DB::transaction** | Laravel Built-in | Data integrity, rollback on failure |
| **Laravel Events** | Built-in | Decoupled side-effects (mail, cache, search) |

---

## 6) Reusable UI Components (Blade)

Luôn ưu tiên dùng các component có sẵn trong `resources/views/components/backend`:
-   `<x-backend.toggle-icon>`: Dùng cho mọi nút Bật/Tắt (Featured, Status) với màu sắc đồng bộ.
-   `<x-backend.action-icon>`: Các icon hành động (Edit, Delete, Copy) có tooltip.
-   `<x-backend.table-th>`: Header bảng hỗ trợ `text-center` và Sort tự động.
-   `<x-backend.confirm-modal>`: Dùng chung cho mọi hành động xóa/xác nhận nguy hiểm.
-   `<x-backend.button variant="danger/success/primary">`: Đồng nhất style nút.
-   `<x-backend.status-badge>`: Hiển thị trạng thái màu sắc nhất quán.

---

## 7) Kiểm Tra Hoàn Tất (Checklist)

| Tính năng | Trạng thái | Ghi chú |
| :--- | :---: | :--- |
| **Search & Pagination** | ✅ | Reset page khi search, giữ query string trên URL |
| **Bulk Selection** | ✅ | Chọn tất cả trên trang (Centered Checkbox), đếm số lượng |
| **Inline Order Edit** | ✅ | Cột "Vị trí" (text-center) cho phép sửa nhanh số thứ tự |
| **Magic SEO Suggest** | ✅ | Nút "Gợi ý SEO" trong form để auto-fill metadata |
| **Toggle Components** | ✅ | Sử dụng x-backend.toggle-icon thay vì button thủ công |
| **Authorizations** | ✅ | Luôn gọi `$this->authorize()` trước khi gọi Action |
| **Toast Notifications** | ✅ | Trả thông báo thành công/thất bại sau mỗi thao tác |
| **Media Persistence** | ✅ | Ảnh hiển thị đúng sau khi upload (đã cấu hình APP_URL) |
| **DB Transaction** | ✅ | Create/Update Action bọc transaction, rollback khi lỗi |
| **Event Dispatching** | ✅ | ProductCreated, ProductUpdated bắn sau transaction |
| **Error Handling** | ✅ | Try-catch ở Livewire, report() + Toast lỗi thân thiện |
| **BaseData Abstraction** | ✅ | DTO kế thừa BaseData, không còn mapping tay |

---
*Tài liệu này thuộc sở hữu của hệ thống Laravel V2 - Admin Refactor.*
