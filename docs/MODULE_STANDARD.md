# 📐 MODULE STANDARD — Catalogue Hub v2

> **Quy tắc thép**: Mọi module mới PHẢI tuân thủ tài liệu này. Không có ngoại lệ.
> **Cập nhật lần cuối**: 2026-03-25

---

## 📂 1. CẤU TRÚC THƯ MỤC CHUẨN

```
app/
├── Models/
│   └── {Module}.php              # Eloquent Model (LogsActivity, SoftDeletes)
├── Data/
│   └── {Module}Data.php          # DTO kế thừa BaseData
├── Actions/
│   └── {Module}/
│       ├── Create{Module}Action.php
│       ├── Update{Module}Action.php
│       └── Delete{Module}Action.php
├── Traits/
│   └── With{Module}Forms.php     # Livewire Form Logic (validation rules, getters)
└── Livewire/
    └── Backend/
        └── {Module}s/
            ├── IndexPage.php      # Danh sách, Search, Filter, Bulk Actions
            ├── CreatePage.php     # Trang tạo mới
            └── EditPage.php       # Trang chỉnh sửa
```

---

## 🛡️ 2. QUY TẮC MODEL

### Bắt buộc phải có:
- `SoftDeletes` — không xóa cứng.
- `LogsActivity` (Spatie) — ghi lại mọi thay đổi dữ liệu.
- `HasSlug` (Spatie) — nếu có URL công khai.

### Cấu hình `getActivitylogOptions()`:
```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly([/* danh sách trường DB quan trọng */])
        ->logOnlyDirty()          // Chỉ log khi có thay đổi thực
        ->dontSubmitEmptyLogs()   // Bỏ qua logs rỗng
        ->setDescriptionForEvent(fn($event) => "{ModuleName} đã được {$event}");
}
```

---

## 📦 3. QUY TẮC DATA (DTO)

- Kế thừa từ `App\Data\BaseData`.
- Định nghĩa đầy đủ kiểu dữ liệu cho từng thuộc tính.
- Override `toArray()` để loại bỏ các trường không lưu DB (ví dụ: `featured_image`, `gallery`).

```php
class {Module}Data extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        // ... các trường khác
        public mixed $featured_image = null, // Media – không lưu DB
    ) {}

    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['featured_image']); // Loại các trường media
        return $data;
    }
}
```

---

## ⚙️ 4. QUY TẮC ACTIONS

- Mỗi Action là một class đơn nhiệm (Single Responsibility).
- Inject `MediaService` nếu có upload file.
- Dùng `DB::transaction()` để đảm bảo data integrity.
- Dispatch Event sau khi action thành công.

```php
class Create{Module}Action
{
    public function __construct(protected MediaService $mediaService) {}

    public function execute({Module}Data $data): {Module}
    {
        $model = DB::transaction(function () use ($data) {
            $record = {Module}::create($data->toArray());
            $this->mediaService->uploadSingle($record, $data->featured_image, 'featured_image');
            return $record;
        });

        event(new {Module}Created($model));
        return $model;
    }
}
```

---

## 🖥️ 5. QUY TẮC LIVEWIRE PAGES

### IndexPage:
- Phải có: Search (by name), Filter (by status), Pagination.
- Phải có: Bulk Action (xóa hàng loạt, đổi trạng thái hàng loạt).
- Dùng `WithPagination` của Livewire.

### CreatePage & EditPage:
- Dùng chung Trait `With{Module}Forms` để tránh lặp rules & getters.
- Xử lý tiền tệ trong `save()` trước `validate()`.
- Thông báo thành công: `session()->flash('success', '...')` → redirect.
- Thông báo lỗi: `dispatch('toast', message: '...', type: 'error')`.

```php
// ✅ Đúng — Thứ tự xử lý trong save()
public function save(CreateAction $action): void
{
    // 1. Normalize dữ liệu (tiền tệ, FK rỗng)
    $this->price = $this->normalizeMoney($this->price) ?? '0';
    if (empty($this->category_id)) $this->category_id = null;

    // 2. Validate
    $validated = $this->validate();

    // 3. Execute Action (trong try/catch)
    try {
        $data = {Module}Data::fromArray($validated);
        $action->execute($data);
        session()->flash('success', 'Tạo thành công!');
        $this->redirect(route('backend.{modules}.index'), navigate: true);
    } catch (\Throwable $e) {
        report($e);
        $this->dispatch('toast', message: 'Có lỗi xảy ra!', type: 'error');
    }
}
```

---

## 🔒 6. QUY TẮC PHÂN QUYỀN

- Mọi action trong Livewire PHẢI gọi `$this->authorize('verb', Model::class)`.
- Định nghĩa Policy tương ứng trong `app/Policies/{Module}Policy.php`.
- Register Policy trong `AuthServiceProvider`.

---

## 🌐 7. QUY TẮC NGÔN NGỮ

| Thành phần | Ngôn ngữ |
|---|---|
| UI Text (Label, Button, Toast) | **Tiếng Việt** |
| Variable, Method, Class names | **English** |
| DB Column names | **English** |
| Code Comments | **English** |

---

## ✅ 8. CHECKLIST NGHIỆM THU MODULE MỚI

Trước khi merge một module mới, kiểm tra:

- [ ] Model có `SoftDeletes`, `LogsActivity`, `HasSlug` (nếu cần).
- [ ] `getActivitylogOptions()` khai báo đúng và đủ.
- [ ] DTO kế thừa `BaseData`, `toArray()` loại bỏ trường media.
- [ ] Actions dùng `DB::transaction()`.
- [ ] Livewire Pages authorize trước khi thực thi.
- [ ] IndexPage có Search + Filter + Pagination + Bulk Actions.
- [ ] Không có logic xử lý DB nặng trực tiếp trong Livewire Component.
- [ ] Chạy `php artisan test` thành công.

---
*Tài liệu này được quản lý bởi Antigravity Brain. Cập nhật cùng với KNOWLEDGE.md.*
