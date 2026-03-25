---
description: Quy trình tự động thêm đại lý mới vào hệ thống (JSON data)
---

# /add-dealer - Thêm đại lý 3M mới

Sử dụng workflow này để thêm đại lý vào tệp `data/dealers.json` một cách nhanh chóng và chính xác.

---

## Các tham số (Arguments)
Cung cấp thông tin theo định dạng sau:
- **Tên đại lý**: (Ví dụ: BMT Workshop)
- **Địa chỉ**: (Địa chỉ đầy đủ có tỉnh/thành)
- **Điện thoại**: (Số điện thoại liên hệ)
- **Tỉnh/Thành (Key)**: (Ví dụ: dak-lak, ho-chi-minh, ha-noi...)
- **Ngày gia nhập**: (Định dạng DD/MM/YYYY)

---

## Các bước thực hiện (Steps):

1. **Phân tích thông tin đại lý**
   - Kiểm tra các tham số đầu vào.
   - Xác định `key` của tỉnh thành trong `data/dealers.json`.

2. **Cập nhật dữ liệu tỉnh thành**
   - Tìm đến mục tỉnh thành tương ứng (ví dụ: `"dak-lak"`).
   - Thêm đối tượng đại lý mới vào mảng `dealers` của section đầu tiên (thường là `simple_list`).
   - Cấu trúc đại lý:
     ```json
     {
       "name": "Tên Đại Lý",
       "info": [
         "3M Autofilm - Tên Đại Lý",
         "Địa chỉ: [Địa chỉ]",
         "Điện thoại: [SĐT]"
       ],
       "map": ""
     }
     ```
   - Cập nhật `update_date` của tỉnh thành đó thành ngày hiện tại.

3. **Cập nhật mục "Mới gia nhập" (`moi`)**
   - Tìm đến mục `"moi"` -> `"years"` -> `[Năm hiện tại]`.
   - Tìm hoặc tạo mới mục tháng tương ứng (ví dụ: `"Tháng 03/2026"`).
   - Thêm đại lý vào danh sách `dealers` của tháng đó.
   - Thêm dòng `"Ngày gia nhập: [Ngày]"` vào mảng `info`.
   - Cập nhật `update_date` của mục `"moi"` thành ngày hiện tại.

// turbo
4. **Tự động tạo link bản đồ**
   - Chạy lệnh: `php scripts/update_map_links.php`
   - Lệnh này sẽ tự động đọc địa chỉ và tạo link Google Maps nhúng cho đại lý mới.

5. **Kiểm tra và xác nhận**
   - Chạy lệnh: `php validate_maps.php` để đảm bảo không có đại lý nào bị thiếu link bản đồ.
   - Thông báo cho người dùng kết quả thành công.

---

## Ví dụ sử dụng:
```
/add-dealer
- Tên: ABC Auto
- Địa chỉ: 123 Đường Láng, Hà Nội
- SĐT: 0912345678
- Tỉnh: ha-noi
- Ngày: 16/03/2026
```

---

## Lưu ý:
- Phải đảm bảo `key` tỉnh thành chính xác (có thể tìm trong `data/dealers.json`).
- Nếu tỉnh thành chưa có trong JSON, cần tạo mới cấu trúc tỉnh thành trước.
- Luôn sử dụng `scripts/update_map_links.php` thay vì nhập tay link bản đồ để đảm bảo định dạng chuẩn.
