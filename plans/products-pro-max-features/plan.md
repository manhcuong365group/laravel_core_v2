# KẾ HOẠCH NÂNG CẤP PRODUCTS MODULE (PRO MAX TIER)

## Tổng Quan Dự Án
Thực hiện 4 tính năng nâng cao (Đẳng cấp E-commerce) cho module Sản phẩm bao gồm: Variants, Import/Export, Quick Edit, và Kéo thả ảnh. Tất cả UI phải bám sát phong cách **Glassmorphism + Bento Grid**.

---

## 📊 Bảng Tiến Độ (Phases)

| Phase | Tên Giai Đoạn                   | Trạng Thái | Tóm tắt công việc                                                           |
|-------|---------------------------------|------------|-----------------------------------------------------------------------------|
| 01    | Database & Models               | ⬜ Pending | Tạo migrations cho Biến thể (Attributes, Variants), model relations.        |
| 02    | Biến Thể & Thuộc Tính (Variants)| ⬜ Pending | Làm giao diện đa tuỳ chọn (UI Matrix) trong trang Create/Edit.              |
| 03    | Quick Edit (Sửa Nhanh Drawer)   | ⬜ Pending | Tạo Component MaryUI Drawer trượt ngang trên trang Index để chỉnh giá/kho.  |
| 04    | Cấu trúc Kéo Thả (Drag & Drop)  | ⬜ Pending | Tích hợp SortableJS qua Alpine để sắp xếp thứ tự ảnh trong Spatie Media.    |
| 05    | Import / Export (Nhập/Xuất CSV) | ⬜ Pending | Cài `maatwebsite/excel`, làm màn hình Preview Import, Job Queue xử lý file. |

---

## 🛠️ Chi Tiết Triển Khai (Technical Specs)

### 1. Biến Thể & Thuộc Tính (Product Variants)
- **Cấu trúc bảng (Schema):**
  - `attributes` (Tên thuộc tính: Màu sắc, Dung lượng,...)
  - `attribute_values` (Giá trị: Đỏ, Xanh, 64GB, 128GB,...)
  - `product_variants` (Mỗi dòng biến thể sẽ có: `sku`, `price`, `stock_quantity`, `image` riêng)
  - `product_variant_values` (Pivot table nối biến thể với các giá trị)
- **UX/UI:** Component Livewire render dạng lưới (Grid) ma trận, cho phép gõ thuộc tính bằng thẻ (Tags), máy tự nhân chéo (VD: Đỏ-64GB, Đỏ-128GB) để Admin chỉ việc nhập giá sỉ/lẻ.

### 2. Sắp Xếp Ảnh Kéo Thả (Drag & Drop Gallery)
- **Công nghệ:** Alpine.js x-sortable (hoặc Sortable.js backend bindings) kết hợp với Livewire.
- **Luồng (Flow):** Bảng lưới hiển thị ảnh Gallery -> Admin dùng chuột di chuyển (kéo ảnh đại diện ra trước) -> Bắn event qua Livewire lưu thuộc tính `order_column` của Spatie MediaLibrary.

### 3. Chỉnh Sửa Tốc Độ Cao (Quick Edit Drawer)
- **Công nghệ:** Livewire event dispatch đến `<x-mary-drawer>`.
- **Luồng:** 
  1. Trên trang Index, thêm nút `[Sửa Nhanh]`.
  2. Drawer Kính mờ (Bento layout) trượt ra từ bên phải. 
  3. Cung cấp sẵn form sửa: *Tên, Giá, Mức Tồn Kho, Trạng Thái* không cần tải lại toàn bộ trình biên tập văn bản nặng (Quill/CKEditor).

### 4. Excel Import & Export (Bulk Upload)
- **Thư viện:** `maatwebsite/excel`
- **Chức năng:**  
  - Export: Tải xuống tất cả sản phẩm ra *.xlsx kèm cột Biến thể.
  - Import: Tải File mẫu (Template) -> Admin upload file -> Check validation (lỗi nếu thiếu Mã SKU) -> Chạy Background Job để insert 10,000 dòng không bị giật lác.

---

## 🚀 Hướng Dẫn Kích Hoạt (Dành cho Anh Trưởng Dự Án)

Để tránh loạn não, chúng ta sẽ **code tuấn tự từng Phase một**. 
Khi anh sẵn sàng, hãy gõ 1 trong các lệnh dưới đây để bắt đầu triển khai ngay:

👉 `Làm Phase 1` (Thiết lập Database cho hệ thống Biến thể siêu to khổng lồ)
👉 `Làm Phase 3` (Code Drawer chỉnh sửa nhanh bóng bẩy trên trang Index)
👉 `Làm Phase 4` (Thêm kéo thả ảnh)
