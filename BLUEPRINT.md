# 🏗️ BLUEPRINT: Hệ thống Đồng bộ hóa & Standardize Backend (V2)

Bản thiết kế này xác định các tiêu chuẩn kỹ thuật cho việc đồng bộ hóa và tái cấu trúc (Refactor) hệ thống quản trị nội bộ.

---

## 🎨 TIÊU CHUẨN GIAO DIỆN (UI TOKENS)

### 1. Palette & Styles
- **Glassmorphism**: 
    - `bg-white/80 backdrop-blur-md border border-white/20`
    - `dark:bg-slate-900/80 dark:border-slate-800/50`
- **Typography**: 
    - Tựa đề: `text-slate-900 dark:text-white font-bold`
    - Nội dung phụ: `text-slate-500 dark:text-slate-400 text-sm`
- **Badges**: Sử dụng component `<x-backend.status-badge />`.

### 2. Layout Patterns
- **Index Page**: 
    - Header: Search (left) + Action Buttons (right).
    - Table: Rounded corners, hover effects.
    - Footer: Fixed Bulk Actions Toolbar (nếu có item được chọn).
- **Form Page**: 
    - Layout 2 cột (70/30 hoặc 75/25).
    - Cột chính: Thông tin cốt lõi (Title, Content, SEO).
    - Cột phụ: Sidebar (Status, Categories, Tags, Images).

---

## ⚙️ TIÊU CHUẨN LOGIC (CODING PATTERNS)

### 1. App\Traits\WithBackendTable
Mọi class `IndexPage.php` PHẢI:
- `use WithBackendTable;`
- Triển khai property `$search`, `$sortField`, `$sortDirection`.
- Triển khai các filter bổ sung (nếu có).
- Sử dụng `dispatch('toast')` cho Feedback.

### 2. Blade Components
- Header cột: `<x-backend.table-th field="column_name" :sortField="$sortField" :sortDirection="$sortDirection" />`.
- Badge: `<x-backend.status-badge status="..." />`.
- Button thao tác: `<x-backend.action-icon variant="edit/delete/view/copy" />`.

---

## 🗺️ LỘ TRÌNH THỰC THI PHASE 1 (CONSTRUCTION)

### Node A: Refactor Products (The Big One)
1. **Backend**: 
    - Chuyển logic sorting và selection sang `WithBackendTable`.
    - Thỏa hiệp logic `updateField` (chỉnh sửa trực tiếp trên bảng) vào chuẩn mới.
2. **Frontend**:
    - Thiết kế lại `index-page.blade.php` với Flat Search & Bulk Toolbar.
    - Chuyển `create-page` và `edit-page` sang giao diện Glassmorphism 2 cột.

### Node B: Refactor Users & Roles
1. **Users**:
    - Đồng bộ Table logic.
    - Giao diện Form User chuyên nghiệp (Profile style).
2. **Roles**:
    - Tối ưu hóa Table hiển thị danh sách quyền.

---

## 🛡️ ĐẢM BẢO CHẤT LƯỢNG (QA)
- [ ] Kiểm tra phân quyền (Permissions) trên mọi Action.
- [ ] Đảm bảo Toast hiển thị đúng thông điệp tiếng Việt.
- [ ] Kiểm tra tính tương thích Responsive của Table.
- [ ] Code sạch (Clean Code), không lặp lại logic xử lý Sorting/Pagination.

---

**Kiến trúc sư trưởng**: Antigravity (Senior AI Architect)
**Trạng thái**: Chờ phê duyệt (Node 2: GATEKEEPER)
