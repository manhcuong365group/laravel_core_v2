# Product Enterprise Refactor

## Goal
Nâng cấp module Product đạt chuẩn kiến trúc Enterprise (Transaction, Event, Error Handling) để làm kim chỉ nam tái sử dụng cho các module khác.

## Tasks
- [x] Task 1: Bọc `DB::transaction` vào `CreateProductAction` và `UpdateProductAction` → ✅ Done
- [x] Task 2: Tạo các Event `ProductCreated`, `ProductUpdated` → ✅ Done (`app/Events/`)
- [x] Task 3: Gọi `event()` trong các class Action sau khi thao tác DB thành công → ✅ Done (gọi sau transaction)
- [x] Task 4: Thêm `try-catch` vào hàm `save()` trong Livewire `CreatePage.php` và `EditPage.php` → ✅ Done (report + toast)
- [x] Task 5: Tạo class `App\Data\BaseData` và cho `ProductData` kế thừa → ✅ Done (PHP Reflection auto-hydrate)

## Done When
- [x] Toàn vẹn dữ liệu (Data Integrity) được đảm bảo qua DB Transaction.
- [x] Ứng dụng không bao giờ bị Crash màn hình lỗi cho End User do đã bắt Try-Catch.
- [x] Hệ thống dễ mở rộng tính năng ngầm (gửi mail, noti, ...) nhờ Events.
