# 🗺️ PLAN: Module Standardization & Sync Pattern

> **DỰ ÁN**: Catalogue Hub v2 (Laravel 12 / Vite 7 / Tailwind 4)
> **TÁC GIẢ**: Antigravity Brain
> **TRẠNG THÁI**: 🟡 PLANNING

---

## 🎯 MỤC TIÊU
Thiết lập một "Module Mẫu" (Blueprints) dựa trên module `Products` hiện tại để làm quy chuẩn đồng bộ cho tất cả các modules khác trong hệ thống, đảm bảo tính nhất quán về code, UI/UX và hệ thống Logging.

## 🤖 ĐIỀU PHỐI AGENTS
- **Backend Specialist**: Phụ trách Action logic, Activity Log & DTOs.
- **Frontend Specialist**: Phụ trách Livewire UI, Tailwind 4 & Toast Notifications.
- **Security Auditor**: Kiểm tra phân quyền (Policy) & XSS/SQL Injection.

---

## 📅 CÁC GIAI ĐOẠN TRIỂN KHAI

### Phase 1: Nâng cấp Foundation (Cơ sở hạ tầng)
- [ ] Cài đặt các Package bổ trợ quy chuẩn:
    - `spatie/laravel-activitylog`: Để lưu logs tác vụ tự động.
    - `spatie/laravel-data`: Chuẩn hóa DTO thay vì dùng class manual.
- [ ] Xây dựng `BaseLivewireComponent` để xử lý chung notify, breadcrumbs.

### Phase 2: Tối ưu hóa Module "Xương sống" (Product Module)
- [ ] **Model**: Add trait `LogsActivity` để capture thay đổi dữ liệu.
- [ ] **Actions**: Review và refactor `CreateProductAction`, `UpdateProductAction` để tích hợp logic log tùy chỉnh.
- [ ] **Livewire**:
    - Đồng bộ `CreatePage` và `EditPage` sử dụng chung một `ProductForm` Component (nếu khả thi).
    - Quy chuẩn hóa cách hiển thị lỗi và tiền tệ.

### Phase 3: Thiết lập quy chuẩn (Standard Sync Pattern)
- [ ] Tạo file `.ai/docs/MODULE_STANDARD.md` ghi lại "luật thép" cho source code.
- [ ] Xây dựng bộ UI Components (Table, Filter, Header) dùng chung cho trang Index.

### Phase 4: Đồng bộ hóa toàn diện (Legacy Sync)
- [ ] Kiểm tra các module khác (`Articles`, `Categories`, `Brands`) và update theo chuẩn Phase 2.
- [ ] Kiểm tra toàn bộ hệ thống phân quyền (Spatie Permissions).

---

## ✅ CHECKLIST NGHIỆM THU
1. [ ] Mọi Action đều tạo logs trong bảng `activity_log`.
2. [ ] UI trang Index của mọi module đều có cấu trúc giống hệt nhau (Search, Filter, Pagination).
3. [ ] Không còn code xử lý dữ liệu nặng trong file Livewire.
4. [ ] Chạy `php artisan test` đạt 100% (nếu có test suite).

---
*Lưu ý: Mọi thay đổi về code sẽ tuân thủ nghiêm ngặt quy tắc tại .ai/core/RULES.md (Phân biệt UI Tiếng Việt / Code Tiếng Anh).*
