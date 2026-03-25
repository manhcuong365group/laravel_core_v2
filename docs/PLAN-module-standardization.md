# 📋 Kế hoạch: Đồng bộ hóa & Chuẩn hóa toàn bộ Hệ thống Backend (V2)

Bản kế hoạch mở rộng nhằm chuẩn hóa tất cả các module trong hệ thống, thiết lập hệ thống ghi log/task và cập nhật tài liệu kiến trúc chính thức.

---

## 🏗️ Kiến trúc & Tiêu chuẩn chung

1.  **Logic Backend**: Sử dụng `App\Traits\WithBackendTable` cho mọi trang `Index`.
2.  **Thông báo**: Chuyển đổi 100% sang hệ thống `Toast` mới.
3.  **UI Table**: `<x-backend.table-th>`, `<x-backend.status-badge>`, `<x-backend.action-icon>`.
4.  **Bulk Actions**: Thanh công cụ Toolbar cố định ở chân trang.
5.  **Form Layout**: Thiết kế **Glassmorphism 2 cột** hiện đại.
6.  **Trình soạn thảo**: Đồng bộ hóa cấu hình và giao diện của Editor trên toàn hệ thống.

---

## ⚡ Các giai đoạn thực hiện (Phased Implementation)

### Phase 1: Nhóm Module Thương mại & Nội dung (Core Modules)
- [ ] **Products**: Refactor logic Trait, đồng bộ UI Table & Form 2 cột.
- [ ] **Categories**: Chuẩn hóa đa loại hình (Product Cat, Article Cat).
- [ ] **Brands**: Chuẩn hóa Table & Form (Logo integration).
- [ ] **Articles (News, Pages, Posts)**: Hoàn thiện nốt các phần còn lại của Form Page.

### Phase 2: Nhóm Module Quản trị & Người dùng (Management Modules)
- [ ] **Users**: Table logic, Bulk status update, Form Profile style.
- [ ] **Roles & Permissions**: Chuẩn hóa giao diện phân quyền.
- [ ] **Urls (Shortener)**: Áp dụng Table chuẩn và thống kê.

### Phase 3: Nhóm Module Tương tác & Marketing (Interaction Modules)
- [ ] **Orders**: Table filter nâng cao, Bulk update status, Show page (Timeline).
- [ ] **Newsletters**: Quản lý danh sách đăng ký.
- [ ] **Contacts**: Giao diện Inbox, tính năng phản hồi ngay trong trang chi tiết.

### Phase 4: Nhóm Module Cấu hình & Công cụ (System Modules)
- [ ] **Settings (General, Social, SEO, Redirects)**: Chuyển đổi sang giao diện mới.
- [ ] **Menu Manager**: Tinh chỉnh UI kéo thả.
- [ ] **Layout Builder**: Đồng bộ style với Form Page chuẩn.

### Phase 5: Hệ thống Ghi Logs & Quản lý Tasks (Logging & Tasks)
- [ ] **Activity Logs**: 
    - [ ] Áp dụng `WithBackendTable` cho module Logs.
    - [ ] Thêm tính năng Filter theo User, Model và Hành động.
    - [ ] Hiển thị chi tiết thay đổi (Old vs New) trực quan.
- [ ] **Tasks System**: 
    - [ ] Thiết lập module quản lý Task công việc nội bộ (nếu có).

### Phase 6: Cập nhật Tài liệu Kiến trúc (Architecture Update)
- [ ] **Update `docs/architecture/`**:
    - [ ] Cập nhật `06-livewire-pattern.md`: Ghi nhận chuẩn mới sử dụng Trait `WithBackendTable`.
    - [ ] Cập nhật `07-backend.md`: Mô tả cấu trúc Form 2 cột & Toast system.
    - [ ] Cập nhật `04-coding-rules.md`: Thêm quy định về việc sử dụng Blade Components mới.
    - [ ] Cập nhật `16-implementation-blueprint-product.md`: Làm mới blueprint dựa trên chuẩn 2026.

---

## 🛠️ Checklist Kỹ thuật cho mỗi Module

- [ ] **Backend**: `use WithBackendTable;`, `dispatch('toast')`, Sorting logic.
- [ ] **Frontend**: `<x-backend.table-th>`, Bulk Toolbar, Glassmorphism Form, Action Icons.
- [ ] **Permissions**: Đảm bảo mọi route đều được bảo vệ đúng quyền.

---

🤖 **Assigning Agents:**
- `laravel-expert`: Refactor Backend & Logic.
- `frontend-specialist`: Nâng cấp giao diện & Components.
- `project-planner`: Giám sát việc cập nhật Documentation & Architecture.
