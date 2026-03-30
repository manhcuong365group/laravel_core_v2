# 📋 PLAN-refactor-categories.md

## ℹ️ Overview
Tái cấu trúc module `categories` theo chuẩn **SRE (Scalable, Robust, Elegant)** dựa trên kiến trúc của module `products`. Mục tiêu là chuyển đổi sang mô hình **DTO-based, Service-driven** và nâng cấp giao diện thành **Bento Grid + Glassmorphism**.

- **🔍 Khảo sát**: Đã hoàn tất (Module hiện tại sử dụng Trait `WithCategoryForms`, cần chuyển sang `CategoryForm` Class).
- **💡 Mục tiêu**: 0 Lỗi N+1, Type-hinting toàn diện, 100% Responsive, UI/UX cao cấp.

---

## 🏗️ Project Architecture & Tech Stack
- **Framework**: Laravel 11 + Livewire 3
- **UI Components**: MaryUI (Bento Grid) + Alpine.js (Glassmorphism hiệu ứng)
- **Patterns**:
    - **Logic**: Actions + Service Layer (`CategoryService`)
    - **Data**: DTO (`CategoryData`)
    - **UI**: Dedicated Form Class (`CategoryForm`)
- **Assets**: SortableJS cho phần sắp xếp danh mục.

---

## 📂 File Structure Changes
```text
app/
├── Livewire/
│   ├── Forms/Backend/
│   │   └── CategoryForm.php (NEW)
│   └── Backend/Categories/
│       ├── IndexPage.php (Refactor)
│       ├── CreatePage.php (Refactor)
│       └── EditPage.php (Refactor)
├── Actions/Category/
│   ├── CreateCategoryAction.php (Update)
│   └── UpdateCategoryAction.php (Update)
├── Services/Category/
│   └── CategoryService.php (NEW)
└── Data/
    └── CategoryData.php (Refactor - Ensure typing)

resources/views/livewire/backend/categories/
├── index-page.blade.php (New UI Blueprint)
├── create-page.blade.php (Refactor)
└── edit-page.blade.php (Refactor)
```

---

## 🛠️ Task Breakdown

### 🟦 Phase 1: Foundation (P0 - Backend Core)
1. **[TASK-001]** Khởi tạo `app/Livewire/Forms/Backend/CategoryForm.php` chuyển logic từ Trait sang Form class.
    - **INPUT**: `WithCategoryForms.php`
    - **OUTPUT**: `CategoryForm.php` với validation rules & helper methods.
    - **VERIFY**: Kiểm tra method `getParentCategories` trả về đúng collection.
2. **[TASK-002]** Cập nhật `app/Data/CategoryData.php` đảm bảo Strong Typing.
3. **[TASK-003]** Khởi tạo `app/Services/Category/CategoryService.php` điều phối logic phối hợp giữa Action và Media.
    - **AGENT**: `backend-specialist`
4. **[TASK-004]** Cập nhật `CreateCategoryAction` & `UpdateCategoryAction` để sử dụng `updateOrCreate` pattern và bảo toàn ID gốc khi xử lý liên kết (nếu có).

### 🟨 Phase 2: Refactoring Livewire Pages (P1)
1. **[TASK-005]** Refactor `IndexPage.php`:
    - Sử dụng `#[Computed]` cho collection `categories`.
    - Triển khai **Eager Loading** (`with(['parent', 'media', 'children'])`) để tránh N+1.
    - Tích hợp xử lý search/filter đồng bộ với `Product IndexPage`.
2. **[TASK-006]** Refactor `CreatePage.php` & `EditPage.php`:
    - Sử dụng `CategoryForm` làm thuộc tính chính.
    - Chuyển `mount()` logic sang Service/Action.

### 🟪 Phase 3: UI/UX Masterpiece (P2)
1. **[TASK-007]** Thiết kế lại `index-page.blade.php`:
    - Chuyển sang **Bento Grid Layout**.
    - Áp dụng **Glassmorphism style** (`bg-white/5`, `backdrop-blur-xl`).
    - Tích hợp Keyboard Shortcut `/` để focus search bar.
2. **[TASK-008]** Tích hợp **SortableJS** cho danh mục:
    - Cho phép thay đổi thuộc tính `order` trực tiếp qua kéo thả.
    - Ajax update `order` field thông qua Livewire.
3. **[TASK-009]** Đồng bộ Responsive cho mobile (Drawer/Modal trượt mượt mà).

---

## ✅ Phase X: Verification Checklist
- [ ] **Eager Loading**: 100% Query đã được Eager Load. Index Page < 10 queries.
- [ ] **Type-hinting**: Toàn bộ Method arguments và Return types đã được khai báo.
- [ ] **Auth Gate**: Đã kiểm tra `$this->authorize()` tại `mount` và các method nhạy cảm.
- [ ] **UI Audit**: Kiểm tra Glassmorphism hiển thị tốt trên nền tối/sáng.
- [ ] **Keyboard UX**: Nhấn `/` có thể tìm kiếm nhanh.
- [ ] **Performance**: Run `python .agent/scripts/checklist.py` và pass toàn bộ audit.

---

## 🤖 Agent Assignments
| Agent | Responsibilities |
|-------|------------------|
| `backend-specialist` | Task 001 - 006 (Logic, Data, Services) |
| `frontend-specialist` | Task 007 - 009 (UI, Glassmorphism, SortableJS) |
| `security-auditor` | Phase X (Final Audit) |
