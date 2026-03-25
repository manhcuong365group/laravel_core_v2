# 🤖 Hướng dẫn thiết lập Hệ điều hành AI (Agent OS)

Tài liệu này hướng dẫn cách triển khai hệ thống quản lý tri thức `.ai` vào một mã nguồn mới, giúp AI Agent có thể hiểu dự án ngay lập tức và làm việc như một nhân viên thực thụ.

---

## 📂 1. Cấu trúc thư mục `.ai` (Bắt buộc)

Khi copy sang dự án mới, hãy đảm bảo có đầy đủ các file sau:

- **`core/SOUL.md`**: Định nghĩa tính cách và phong cách làm việc của AI (Ngắn gọn, Builder-first, ship nhanh...).
- **`core/RULES.md`**: "Hiến pháp" làm việc. Quy định quy trình: Đọc Memory -> Thực thi -> Ghi Log.
- **`core/USER.md`**: Ghi nhớ sở thích của bạn (Ngôn ngữ, Tech stack ưa thích, thói quen chat).
- **`core/KNOWLEDGE.md`**: (Tự tạo) Chứa các tiêu chuẩn kỹ thuật của dự án (UI/UX, API chuẩn, Màu sắc...).
- **`memory/logs/`**: Chứa nhật ký hàng ngày dạng `YYYY-MM-DD.md`.
- **`runtime/tasks.json`**: Danh sách việc cần làm (Todo list) để AI tự theo dõi tiến độ.

---

## 🛠️ 2. Bước đầu triển khai trong dự án mới

Sau khi copy thư mục `.ai`, hãy yêu cầu AI Agent thực hiện các lệnh sau để "Boot" hệ thống:

### Bước A: Tạo file `runtime/tasks.json`
Nếu chưa có, hãy yêu cầu AI tạo file với cấu trúc:
```json
{
    "tasks": [
        {
            "id": "init-project",
            "title": "Nghiên cứu source code và thiết lập bộ nhớ",
            "assigned_to": "agent-name",
            "status": "pending"
        }
    ]
}
```

### Bước B: Tự động tạo tóm tắt kiến trúc (`ARCHITECTURE.md`)
Yêu cầu Agent: *"Hãy nghiên cứu toàn bộ source code này, quét cấu trúc thư mục, Tech stack, các module chính và Database (nếu có). Sau đó hãy viết một file docs/ARCHITECTURE.md thật chuyên nghiệp giống như mẫu của 365Group."*

**Agent sẽ thực hiện:**
1. `list_directory` toàn bộ dự án.
2. `read_file` các file cấu hình (`composer.json`, `package.json`, `.env.example`).
3. Quét thư mục `app/Models` hoặc `database/migrations` để hiểu dữ liệu.
4. Tổng hợp vào `docs/ARCHITECTURE.md`.

---

## 📝 3. Quy trình làm việc hàng ngày (Rules)

Để hệ thống hoạt động hiệu quả, hãy yêu cầu AI luôn tuân thủ:

1. **Trước khi bắt đầu:** Luôn đọc `logs` gần nhất và `tasks.json` để biết ngữ cảnh.
2. **Trong khi làm:** Cập nhật trạng thái `tasks.json` từ `pending` -> `in_progress`.
3. **Sau khi xong:** 
   - Viết nhật ký vào `memory/logs/YYYY-MM-DD.md`.
   - Cập nhật `tasks.json` sang `completed`.
   - Nếu có kiến thức mới quan trọng, cập nhật vào `core/KNOWLEDGE.md`.

---

## 💡 4. Mẹo nâng cao (Pro Tips)

- **Multi-Agent:** Nếu bạn dùng nhiều AI (Gemini, Claude, GPT), hãy tạo file riêng trong `agents/name.md` để phân vai (Builder, Reviewer...).
- **Knowledge Base:** Khi bạn thống nhất một phong cách UI (ví dụ: "Dùng màu Indigo và bo góc lớn"), hãy bắt AI ghi ngay vào `KNOWLEDGE.md` để các lần sau nó không làm sai.
- **Victory Effect:** Luôn yêu cầu AI thêm các hiệu ứng "Wow" (như pháo hoa Confetti) khi hoàn thành các cột mốc lớn trong `tasks.json`.

---
*Tài liệu được soạn thảo bởi Gemini Agent - 365Group Standard.*
