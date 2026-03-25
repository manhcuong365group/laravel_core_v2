# PLAN-url-management.md - URL Management Module

Bản kế hoạch xây dựng module Quản lý URL theo chuẩn Enterprise (Product Gold Standard).

---

## 1. Overview
Thiết lập trang quản lý danh sách và CRUD URL trong khu vực Admin (Backend). Sử dụng mô hình Livewire 3 + Actions + DTOs để đảm bảo tính mở rộng và khả năng bảo trì.

- **Project Type**: WEB (Laravel 12 + Livewire 3)
- **Primary Agent**: `frontend-specialist` (Module UI/UX) & `backend-specialist` (Actions/Logic)

---

## 2. Success Criteria
- [ ] CRUD danh sách URL hoạt động hoàn hảo.
- [ ] Tính năng Quick Toggle Trạng thái (Active/Inactive) mượt mà.
- [ ] Inline Editing cho Tiêu đề và URL gốc.
- [ ] Bulk Actions xóa và cập nhật trạng thái đồng loạt.
- [ ] Thống kê số lượt click cơ bản.
- [ ] UI đồng nhất với bộ component `resources/views/components/backend`.
- [ ] Mã nguồn tuân thủ Clean Code và SOLID.

---

## 3. Tech Stack
- **Framework**: Laravel 12.x
- **UI Layer**: Livewire 3.x
- **Frontend Interaction**: Alpine.js (Inline Edit, Toggle)
- **Data Transfer**: BaseData Reflection DTOs
- **Database**: MySQL/PostgreSQL with Foreign Keys & Indices
- **Authorization**: Laravel Policies

---

## 4. File Structure
```
app/
├── Actions/Url/
│   ├── CreateUrlAction.php
│   ├── UpdateUrlAction.php
│   ├── DeleteUrlAction.php
│   ├── BulkDeleteUrlAction.php
│   └── BulkStatusUrlAction.php
├── Data/
│   └── UrlData.php (extends BaseData)
├── Livewire/Backend/Urls/
│   ├── IndexPage.php
│   ├── CreatePage.php
│   └── EditPage.php
├── Models/
│   └── Url.php
├── Policies/
│   └── UrlPolicy.php
├── Traits/
│   └── WithUrlForms.php
resources/views/
├── livewire/backend/urls/
│   ├── index-page.blade.php
│   ├── create-page.blade.php
│   └── edit-page.blade.php
```

---

## 5. Task Breakdown

### Phase 1: Foundation (Database & Security)
- **task_id**: 1.1
- **name**: Create Migration & Model
- **agent**: `backend-specialist`
- **priority**: P0
- **dependencies**: []
- **INPUT**: Schema requirements (title, original_url, short_url, status, click_count)
- **OUTPUT**: `database/migrations/xxx_create_urls_table.php`, `app/Models/Url.php`
- **VERIFY**: `php artisan migrate`, kiểm tra bảng trong DB.

- **task_id**: 1.2
- **name**: Create UrlPolicy
- **agent**: `backend-specialist`
- **priority**: P0
- **dependencies**: [1.1]
- **INPUT**: Auth context
- **OUTPUT**: `app/Policies/UrlPolicy.php`, registered in AuthServiceProvider.
- **VERIFY**: Gate::check('viewAny', Url::class) trả về true cho Admin.

### Phase 2: Logic Layer (DTO & Actions)
- **task_id**: 2.1
- **name**: Create UrlData DTO
- **agent**: `backend-specialist`
- **priority**: P1
- **dependencies**: [1.1]
- **INPUT**: Model fields
- **OUTPUT**: `app/Data/UrlData.php`
- **VERIFY**: Khởi tạo `UrlData::fromArray([...])` thành công.

- **task_id**: 2.2
- **name**: Implement CRUD Actions
- **agent**: `backend-specialist`
- **priority**: P1
- **dependencies**: [2.1]
- **INPUT**: Business requirements
- **OUTPUT**: `CreateUrlAction`, `UpdateUrlAction`, `DeleteUrlAction` (có DB::transaction).
- **VERIFY**: Unit test cho từng Action.

- **task_id**: 2.3
- **name**: Implement Bulk Actions
- **agent**: `backend-specialist`
- **priority**: P1
- **dependencies**: [2.2]
- **INPUT**: Bulk requirements
- **OUTPUT**: `BulkDeleteUrlAction`, `BulkStatusUrlAction`.
- **VERIFY**: `whereIn` được sử dụng để tối ưu query.

### Phase 3: UI Layer (Livewire & Blade)
- **task_id**: 3.1
- **name**: Create WithUrlForms Trait
- **agent**: `frontend-specialist`
- **priority**: P2
- **dependencies**: [2.1]
- **INPUT**: Form rules
- **OUTPUT**: `app/Traits/WithUrlForms.php`
- **VERIFY**: Rules đồng bộ với Product module.

- **task_id**: 3.2
- **name**: Build IndexPage (Listing & Filter)
- **agent**: `frontend-specialist`
- **priority**: P2
- **dependencies**: [2.3, 3.1]
- **INPUT**: UI Components
- **OUTPUT**: `Livewire/Backend/Urls/IndexPage.php`, `index-page.blade.php`.
- **VERIFY**: Hiển thị bảng, search, pagination hoạt động.

- **task_id**: 3.3
- **name**: Build Create/Edit Pages
- **agent**: `frontend-specialist`
- **priority**: P2
- **dependencies**: [3.2]
- **INPUT**: Form UI
- **OUTPUT**: `CreatePage.php`, `EditPage.php` + views.
- **VERIFY**: Thêm/Sửa thành công, có try-catch & toast notification.

- **task_id**: 3.4
- **name**: Implement Inline Edit & Quick Toggle
- **agent**: `frontend-specialist`
- **priority**: P2
- **dependencies**: [3.2]
- **INPUT**: Alpine.js logic
- **OUTPUT**: Cập nhật view `index-page.blade.php`.
- **VERIFY**: Thay đổi giá trị tại chỗ không cần load lại trang.

---

## 6. Phase X: Final Verification
- [ ] **Security**: `python .agent/skills/vulnerability-scanner/scripts/security_scan.py .`
- [ ] **UX Audit**: `python .agent/skills/frontend-design/scripts/ux_audit.py .`
- [ ] **Lint/Build**: `npm run lint` & `php artisan route:list` (check routes).
- [ ] **Manual Test**: Thử nghiệm luồng CRUD, Bulk actions, Inline edit.
- [ ] **Verification**: Tất cả các bước [ ] đã được đánh dấu [x].

---
*Created by Antigravity - project-planner*
