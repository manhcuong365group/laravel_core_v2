# Changelog

## [2026-03-27]
### Changed
- Tối ưu N+1 Query trong list bài viết (Article/IndexPage) bằng Livewire `#[Computed]`.
- Cấu hình tự động dọn dẹp Activity Log cũ bằng cron schedule `activitylog:clean`.
- Dịch logs tiếng việt chuyên nghiệp ở Article Model.

### Fixed
## [2026-03-28]
### Added
- Refactor module **Categories** sang chuẩn SRE (Service Layer, Form Object, DTO).
- Giao diện **Glassmorphism + Bento Grid** hiện đại cho Categories (Index, Create, Edit).
- Tích hợp **SortableJS** kéo thả sắp xếp danh mục mượt mà.

### Changed
- Nâng cấp **Security Hardening**: Thêm kiểm tra quyền `authorize()` cho toàn bộ các hành động Bulk Status, Bulk Delete, Export/Import trên toàn hệ thống.
- Diệt sạch **N+1 Query** trong list Categories bằng `with(['parent', 'media', 'children'])`.

### Fixed
- Vá lỗ hổng **Broken Access Control** tại các hàm hành động hàng loạt (Bulk Actions).
- Vá lỗ hổng bảo mật tại tính năng Quick Edit sản phẩm.

### Removed
- Xóa bỏ **Dead Code**: Ngừng sử dụng `WithCategoryForms` trait cũ, chuyển hoàn toàn sang `CategoryForm` class.

## [2026-03-30]
### Added
- **Quick Preview**: Thêm nút "Xem nhanh" (Eye icon) dẫn link ra Frontend `products.show`.
- **Dual Status Toggle**: Chuyển trạng thái "Nổi bật" sang dạng Toggle click trực tiếp (cạnh Toggle Hiển thị).

### Changed
- **UX Optimization (Product Index)**:
    - Loại bỏ hiện tượng "giật" bảng (Layout Shift/Horizontal Scroll) khi hover bằng cách gỡ bỏ `scale-1.002` và `translate-x-4`.
    - Thay đổi cơ chế hiển thị nút Thao tác: Giữ `opacity-30` mặc định (luôn thấy mờ) và `opacity-100` khi hover để tăng độ ổn định.
    - Tăng độ rộng cột Thao tác lên `w-48` để đảm bảo khoảng trống cho 4 icon.

### Fixed
- **UI Bug (Filters)**: Sửa lỗi Dropdown bộ lọc (Status, Category, Brand) bị xuyên thấu, trong suốt và không thấy chữ trên Windows/Chrome bằng CSS Inject (`.mary-select-content`).
- **Double Background**: Xử lý lỗi chồng lớp nền cho component `x-mary-select`.
