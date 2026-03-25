---
description: Master workflow that guides through the complete project lifecycle from idea to deployment.
---

# /standard-flow - Quy Trình Làm Việc Chuẩn (Master Lifecycle)

Lệnh này cung cấp một quy trình khép kín giúp bạn đi từ ý tưởng thô đến sản phẩm hoàn thiện một cách khoa học, chuyên nghiệp và có kiểm soát.

---

## 🚦 Tóm Tắt Quy Trình 5 Bước

| Giai Đoạn           | Lệnh Thực Thi          | Mục Tiêu                                | Kết Quả                  |
| :------------------ | :--------------------- | :-------------------------------------- | :----------------------- |
| **1. Khám Phá**     | `/brainstorm`          | Làm rõ yêu cầu & tìm giải pháp tối ưu.  | 3 giải pháp gợi ý.       |
| **2. Lập Kế Hoạch** | `/plan`                | Chia nhỏ task, xác định Agent & rủi ro. | File `PLAN-{slug}.md`.   |
| **3. Thực Thi**     | `/create` / `/enhance` | Viết code dựa trên kế hoạch đã duyệt.   | Code hoàn thiện.         |
| **4. Kiểm Định**    | `/test` / `/debug`     | Đảm bảo code chạy đúng & không có bug.  | Test pass 100%.          |
| **5. Triển Khai**   | `/deploy`              | Quét bảo mật, tối ưu & xuất bản.        | Sản phẩm lên Production. |

---

## 🛠️ Hướng Dẫn Chi Tiết Từng Bước

### 🔹 Bước 1: Khám Phá Ý Tưởng (`/brainstorm`)

- **Khi nào dùng:** Khi bạn có ý tưởng mới nhưng chưa biết bắt đầu từ đâu.
- **Lợi ích:** Tránh việc bắt đầu sai hướng, lãng phí thời gian sửa code sau này.
- **Hành động:** AI sẽ đưa ra ít nhất 3 phương án kèm ưu nhược điểm để bạn lựa chọn.

### 🔹 Bước 2: Lập Kế Hoạch Chi Tiết (`/plan`)

- **Khi nào dùng:** Sau khi đã chốt phương án từ Bước 1.
- **Lợi ích:** Tạo ra bản lộ trình (Roadmap) cho AI. Giúp AI làm việc có kỷ luật.
- **Hành động:** AI tạo file `PLAN.md`. Bạn nên đọc kỹ file này để xem AI có hiểu đúng ý mình không.

### 🔹 Bước 3: Viết Code (`/create` hoặc `/enhance`)

- **Khi nào dùng:** Sau khi bạn đã duyệt file `PLAN.md`.
- **Lợi ích:** Code được viết một cách có hệ thống, tuân thủ Clean Code.
- **Hành động:**
  - Dùng `/create` cho dự án/module mới.
  - Dùng `/enhance` để cập nhật code đã có.
  - Dùng `/ui-ux-pro-max` nếu muốn giao diện cực phẩm.

### 🔹 Bước 4: Kiểm Tra & Sửa Lỗi (`/test` & `/debug`)

- **Khi nào dùng:** Ngay sau khi code xong hoặc khi gặp lỗi phát sinh.
- **Lợi ích:** Đảm bảo tính ổn định của toàn bộ hệ thống.
- **Hành động:** AI sẽ tự động viết test case và sửa lỗi nếu có. Đừng bao giờ bỏ qua bước này.

### 🔹 Bước 5: Triển Khai & Bàn Giao (`/deploy`)

- **Khi nào dùng:** Khi mọi tính năng đã hoàn thiện và test đã pass.
- **Lợi ích:** Sản phẩm an toàn, hiệu suất cao và chuẩn SEO.
- **Hành động:** AI quét lỗ hổng bảo mật và tối ưu Core Web Vitals.

---

## 🔴 CÁC QUY TẮC "BẤT BIẾN" (CRITICAL RULES)

1.  **Luôn Planning trước khi Coding:** Không bao giờ gõ lệnh viết code khi chưa có file `PLAN.md`.
2.  **Socratic Gate:** Luôn trả lời đầy đủ các câu hỏi của AI để nó có đủ dữ liệu.
3.  **Kiểm tra Status:** Dùng `/status` sau mỗi giai đoạn để nắm bắt tình hình.
4.  **Admin Rights:** Khi chạy các lệnh liên quan đến hệ thống (như Symlink), hãy đảm bảo CLI có quyền Administrator.

---

> [!TIP]
> Bạn có thể bắt đầu ngay bây giờ bằng cách gõ: `/standard-flow [ý tưởng của bạn]`
