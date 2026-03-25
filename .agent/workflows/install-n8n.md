---
description: Hướng dẫn khởi tạo hệ thống tác vụ chuyên nghiệp (n8n, CI/CD, Senior Architect) cho một dự án mới.
---

# 🛠️ Khởi Tạo Hệ Thống Pro Agent (n8n style)

Sử dụng quy trình này khi bạn vừa tạo một source code mới hoặc muốn "nâng cấp" quản lý cho một dự án cũ.

## 📋 CÁC BƯỚC THỰC HIỆN

### Bước 1: Sao chép cấu trúc điều phối

AI sẽ tự động tạo thư mục `.agent/workflows/` và sao chép các kịch bản (n8n, senior-architect, deploy...) sang dự án mới.

### Bước 2: Thiết lập CI/CD (GitHub Actions)

Tạo file `.github/workflows/deploy.yml` để tự động hóa việc đẩy code lên server.

- _Yêu cầu:_ Bạn cần cung cấp IP và folder đích trên server.

### Bước 3: Cấu hình Nginx Proxy Manager (Tùy chọn)

Nếu bạn muốn quản lý Domain qua giao diện Web (Cổng 8181).

---

## ⚡ CÁCH GỌI LỆNH

Để thực hiện việc này cho một project khác, bạn chỉ cần yêu cầu:

> "Hãy cài đặt hệ thống n8n và CI/CD cho dự án tại đường dẫn: [Đường dẫn dự án]"

AI sẽ tự động:

1. Đọc source dự án đó.
2. Cài đặt các file cấu hình cần thiết.
3. Kiểm tra kết nối Git & Server.

---

## 🏗️ DANH SÁCH FILE SẼ ĐƯỢC CÀI ĐẶT

- `.agent/workflows/n8n.md`
- `.agent/workflows/senior-architect.md`
- `.github/workflows/deploy.yml`
- `deploy.sh` (Link tới mã nguồn SSH Key)
