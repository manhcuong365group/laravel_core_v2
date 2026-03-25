# PLAN: Backend Module Standardization & Reusability

Đồng bộ hóa các module Articles, Categories, Brands theo tiêu chuẩn của module Products. Sử dụng Trait và Component dùng chung để giảm thiểu lặp code.

---

## 🎯 Mục tiêu
- **Đồng bộ hóa**: Tất cả các trang danh sách (Index) đều có trải nghiệm người dùng giống nhau (Search, Sort, Bulk actions, Toasts).
- **Tái sử dụng**: Sử dụng `Trait` để quản lý logic bảng (Table logic) và `Blade Components` cho UI.
- **Hiện đại hóa**: Chuyển từ `session()->flash()` sang `dispatch('toast')`.

---

## 🏗️ Kiến trúc đề xuất

### 1. `App\Traits\WithBackendTable` (Tâm điểm tái sử dụng)
Trait này sẽ chứa toàn bộ logic lặp đi lặp lại trong các trang Index:
- **Properties**: `$search`, `$sortField`, `$sortDirection`, `$selectedItems`, `$selectAll`, `$showDeleteModal`, `$deleteTargetId`, `$deleteTargetName`.
- **Logic**: 
    - `sortBy(string $field)`: Xử lý đổi chiều sắp xếp.
    - `confirmDelete(int $id, string $name)`: Mở modal xác nhận xóa.
    - `toggleSelectAll()`: Chọn/bỏ chọn tất cả item.
    - `updatedSelectedItems()`: Cập nhật trạng thái selectAll khi chọn lẻ.
    - `dispatchToast(string $message, string $type = 'success')`: Helper bắn thông báo.

### 2. Standard Actions Pattern
Sử dụng `App\Actions\{Module}\...` cho mọi thao tác CUD (Create, Update, Delete) để tách biệt logic khỏi Controller/Livewire.

---

## ⚡ Các giai đoạn thực hiện

### Phase 1: Xây dựng nền tảng Reusability
- [ ] Tạo Trait `App\Traits\WithBackendTable`.
- [ ] Cập nhật hoặc tạo mới các Blade Component dùng chung (nếu cần):
    - `<x-backend.table-header>`: Tự động hiển thị icon sort và gọi hàm `sortBy`.
    - `<x-backend.bulk-actions-bar>`: Hiển thị khi có item được chọn.

### Phase 2: Đồng bộ hóa Module Articles
- [ ] Áp dụng `WithBackendTable` vào `Articles\IndexPage`.
- [ ] Chuyển `session()->flash` sang `$this->dispatch('toast')`.
- [ ] Cập nhật giao diện `index-page.blade.php` chuẩn theo Product.
- [ ] Tương tự cho `CreatePage` và `EditPage` (sử dụng Toast).

### Phase 3: Đồng bộ hóa Module Categories & Brands
- [ ] Áp dụng Trait và kiểu thông báo Toast cho `Categories`.
- [ ] Áp dụng Trait và kiểu thông báo Toast cho `Brands`.
- [ ] Đảm bảo Modal xóa sử dụng chung một cơ chế.

### Phase 4: Refactor Module Products (Final Polish)
- [ ] Chuyển logic trong `Products\IndexPage` sang sử dụng Trait `WithBackendTable` để tinh gọn code.

---

## ✅ Kiểm tra & Nghiệm thu
- [ ] Kiểm tra tính năng Search/Sort ở tất cả các module.
- [ ] Kiểm tra xóa đơn lẻ và xóa hàng loạt.
- [ ] Kiểm tra hiển thị Toast sau mỗi hành động thành công.
- [ ] Đảm bảo tính nhất quán về UI/UX (padding, màu sắc, icon).

---

## 💡 Ý tưởng nâng cao cho tương lai
- **BaseAction class**: Một class cha cho các Action để handle Logging tự động.
- **Dynamic Table Component**: Một component Livewire duy nhất có thể render table cho bất kỳ Model nào thông qua cấu hình (nhưng vẫn giữ được sự linh hoạt của Blade).
