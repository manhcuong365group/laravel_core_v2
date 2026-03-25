---
description: Kích hoạt hệ thống điều phối Agent theo mô hình Graph (phong cách n8n) để giải quyết các tác vụ phức tạp trong project này.
---

# 🚀 Quy Trình Điều Phối Agent (n8n Style)

Sử dụng lệnh này khi bạn cần xây dựng một tính năng mới (Feature) hoặc tái cấu trúc (Refactor) toàn bộ một module từ đầu đến cuối một cách chuyên nghiệp.

## 🏗️ KIẾN TRÚC MÔ HÌNH GRAPH

Hệ thống sẽ tự động kích hoạt các "Node" sau đây theo thứ tự logic:

### 1. 🟢 Node Khởi Tạo: [DANG_KY_YEU_CAU]

- **Agent**: `project-planner`
- **Nhiệm vụ**: Phân tích yêu cầu khách hàng, xác định các file bị tác động.
- **Sản phẩm**: Tạo file `BLUEPRINT.md` (Bản thiết kế hạ tầng).

### 2. 🚦 Node Phê Duyệt: [GATEKEEPER]

- **Hành động**: Dừng lại để người dùng xem bản thiết kế.
- **Tiếp tục**: Chỉ khi người dùng nói "Duyệt" hoặc "Proceed".

### 3. 🛠️ Node Thực Thi: [CONSTRUCTION] (Chạy Song Song)

- **Backend Node**: Sử dụng `laravel-middle-be` để viết Controller, Model, Migration.
- **Frontend Node**: Sử dụng `laravel-middle-fe` để viết Blade, Livewire, Tailwind CSS.

### 4. 🛡️ Node Kiểm Soát: [QUALITY_CONTROL]

- **Agent**: `security-auditor` + `tester`
- **Nhiệm vụ**: Quét bảo mật, chạy unit test của Laravel.
- **Logic**: Nếu lỗi -> Quay lại Bước 3 để sửa tự động.

### 5. 🏁 Node Hoàn Tất: [SHIP_IT]

- **Agent**: `orchestrator`
- **Nhiệm vụ**: Push code lên GitHub, chạy script Deploy tự động.

---

## 💡 CÁCH SỬ DỤNG

Mỗi khi bạn vào một project mới và muốn có hệ thống này, bạn chỉ cần gọi:

> "Hãy kích hoạt quy trình n8n cho project này"

Hoặc gõ:

> `/n8n [mô tả tính năng bạn muốn xây dựng]`

---

## 📝 GHI CHÚ CHO AI

- Luôn giữ vai trò **Senior Architect** khi thực hiện workflow này.
- Tuyệt đối không viết code trực tiếp cho đến khi hoàn thành giai đoạn thiết kế (Node 1).
- Mọi thay đổi về hạ tầng phải được lưu vào `ARCHITECTURE.md` của dự án.
