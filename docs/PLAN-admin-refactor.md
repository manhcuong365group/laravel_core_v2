# PLAN: Admin Refactor Theo Architecture (`/docs/architecture`)

## 1) Mục tiêu

Refactor khu vực admin theo kiến trúc chuẩn trong `/docs/architecture` với trọng tâm:

- Module-first theo domain.
- Action-centric cho business logic.
- Livewire + Blade tách vai trò rõ ràng.
- Authorization qua Policy/Gate.
- Test coverage đầy đủ cho auth/permission/CRUD.
- API-ready thông qua `Action + DTO + Resource`.

## 2) Phạm vi

- Tập trung vào admin hiện tại (ưu tiên module Product trước, rồi nhân rộng pattern).
- Không mở rộng feature ngoài scope refactor admin.
- Không thay đổi schema DB lớn; chỉ bổ sung index/FK khi có lý do rõ ràng về hiệu năng/toàn vẹn dữ liệu.

## 3) Nguyên tắc kiến trúc bắt buộc

- Livewire chỉ quản lý UI state/interaction, không chứa business logic.
- Action xử lý business logic cho từng use case.
- Service xử lý tích hợp ngoài hoặc shared logic xuyên module.
- Blade chỉ render, không chứa kiểm tra role/permission hardcode.
- Authorization đi qua Policy/Gate (`viewAny`, `view`, `create`, `update`, `delete`).
- Business layer không nhận `Request` trực tiếp; Action nhận DTO.
- Output chuẩn hóa qua Resource để sẵn sàng API.

## 4) Tài liệu kiến trúc tham chiếu

- `03-folder-structure.md`
- `06-livewire-pattern.md`
- `09-module-system.md`
- `10-service-action-pattern.md`
- `11-permission-policy.md`

## 5) Naming conventions chuẩn hóa

- Livewire pages: `IndexPage`, `CreatePage`, `EditPage`.
- Actions: `ListXAction`, `CreateXAction`, `UpdateXAction`, `DeleteXAction`.
- DTO: `XData` (ví dụ `ProductData`) làm input contract cho Action.
- Resource: `XResource` làm output contract chuẩn hóa.

## 6) Phases triển khai

## Phase 0: Architecture Alignment Audit

### Mục tiêu

Map toàn bộ code admin hiện tại với chuẩn kiến trúc để xác định điểm lệch.

### Công việc

- Audit folder/module hiện tại theo `03-folder-structure` và `09-module-system`.
- Audit luồng Livewire/Blade theo `06-livewire-pattern`.
- Audit business logic trong Controller/Livewire/Blade để tách về Action/Service theo `10-service-action-pattern`.
- Audit quyền truy cập hiện tại theo `11-permission-policy`.
- Tạo bảng gap analysis (Current vs Target).

### Deliverables

- `docs/audits/admin-architecture-gap.md`.
- Danh sách module/admin entities cần refactor theo ưu tiên.
- Checklist vi phạm naming/responsibility hiện tại.

### Điều kiện hoàn thành

- Có inventory đầy đủ route, controller/livewire, views, actions/services hiện hữu.
- Mỗi vi phạm được gắn action item cụ thể cho phase sau.

### Tiêu chí review

- Không thiếu entity admin quan trọng.
- Mỗi nhận định có file/path tham chiếu.

---

## Phase 1: Module-first Restructure (Theo Domain)

### Mục tiêu

Chuẩn hóa cấu trúc admin theo domain module, giảm coupling chéo.

### Công việc

- Nhóm lại code theo domain (ưu tiên Product làm blueprint).
- Loại bỏ truy cập trực tiếp cross-module không qua contract/service rõ ràng.
- Chuẩn hóa namespace/path theo folder structure mục tiêu.

### Deliverables

- Cấu trúc module admin theo domain rõ ràng.
- Migration notes cho path/namespace thay đổi (nếu có).

### Điều kiện hoàn thành

- Module Product đạt chuẩn và dùng được làm mẫu mở rộng.
- Không còn import/phụ thuộc chéo không cần thiết giữa module.

### Tiêu chí review

- Module boundaries rõ, dễ tìm code theo domain.
- Không tạo vòng phụ thuộc giữa module.

---

## Phase 2: Responsibility Refactor (Livewire/Action/Service/Blade)

### Mục tiêu

Đưa logic về đúng tầng trách nhiệm.

### Công việc

- Livewire pages chuẩn hóa về `IndexPage`, `CreatePage`, `EditPage`.
- Tách business logic từ Controller/Livewire/Blade sang Actions.
- Tách shared/integration logic sang Services.
- Blade chỉ giữ render + binding hiển thị.
- Chuẩn hóa Action naming/signature theo entity.

### Deliverables

- Bộ Actions chuẩn cho từng entity admin:
  - `ListXAction`
  - `CreateXAction`
  - `UpdateXAction`
  - `DeleteXAction`
- Livewire pages theo naming chuẩn.
- Blade templates sạch logic business.

### Điều kiện hoàn thành

- Không còn business rule xử lý trực tiếp trong Blade.
- Livewire không chứa logic persistence/business phức tạp.
- Action là điểm vào chính của use case.

### Tiêu chí review

- Code review pass theo nguyên tắc single-responsibility.
- Đọc một use case có thể lần từ UI -> Action -> Service rõ ràng.

---

## Phase 3: Authorization Refactor (Policy/Gate)

### Mục tiêu

Chuẩn hóa phân quyền theo Policy/Gate và middleware route.

### Công việc

- Route admin bắt buộc `auth` middleware.
- Áp quyền theo Policy methods:
  - `viewAny`
  - `view`
  - `create`
  - `update`
  - `delete`
- Loại bỏ check role hardcode trong Blade/Livewire.
- Chuẩn hóa permission mapping theo từng entity admin.

### Deliverables

- Policy classes/methods hoàn chỉnh cho entity admin.
- Route group admin chuẩn middleware (`auth` + permission/policy).
- Permission matrix docs cho admin entities.

### Điều kiện hoàn thành

- Truy cập trái quyền trả `403` đúng.
- Không còn logic role-check trực tiếp trong view layer.

### Tiêu chí review

- Mọi quyền được enforce ở policy/gate hoặc middleware, không tản mát.
- Dễ audit quyền từ route đến action.

---

## Phase 4: API-ready Contract (Action + DTO + Resource)

### Mục tiêu

Đảm bảo business core tái sử dụng được cho API mà không viết lại.

### Công việc

- Chuẩn hóa DTO cho input contract (ví dụ `ProductData`).
- Action chỉ nhận DTO, không nhận `Request`.
- Chuẩn hóa output qua Resource (`XResource`).
- Tách format response khỏi business logic.

### Deliverables

- DTO contract áp dụng thống nhất cho create/update/list filter input.
- Resource contract chuẩn hóa output cho admin responses.
- Tài liệu contract ngắn cho mỗi entity.

### Điều kiện hoàn thành

- Có thể reuse Action cho HTTP controller/API controller mà không đổi business core.
- Không còn xử lý mapping input/output thủ công rải rác.

### Tiêu chí review

- Input/output contract nhất quán giữa modules.
- Sẵn sàng mở API route mới với chi phí thấp.

---

## Phase 5: Test Coverage & Regression Safety

### Mục tiêu

Bảo vệ hành vi hệ thống sau refactor.

### Công việc

- Feature tests auth:
  - User chưa đăng nhập không vào được admin routes.
  - User đăng nhập nhưng thiếu quyền nhận `403`.
- Feature tests permission matrix:
  - Đúng quyền cho `viewAny/view/create/update/delete`.
  - Xác nhận không phụ thuộc role hardcode trong view.
- CRUD tests theo module:
  - Happy path `create/update/delete/list`.
  - Validation fail path.
  - Boundary check để đảm bảo business logic không nằm trong Blade/Livewire.
- Unit tests cho Actions:
  - Action nhận DTO và xử lý đúng business rules.
  - Service có thể mock/stub khi tích hợp ngoài.
- Regression tests:
  - Route names quan trọng.
  - Luồng admin hiện hữu quan trọng.

### Deliverables

- Bộ test auth/permission/CRUD/action đầy đủ.
- Test matrix file mô tả coverage theo entity/use case.

### Điều kiện hoàn thành

- Toàn bộ test mới pass.
- Không có regression ở route names/flow quan trọng.

### Tiêu chí review

- Test fail rõ khi vi phạm phân quyền hoặc business contract.
- Coverage tập trung vào boundary đúng tầng.

---

## Phase 6: Legacy Cleanup (Sau Khi Test Pass)

### Mục tiêu

Xóa bỏ flow cũ/trùng lặp để giảm nợ kỹ thuật.

### Công việc

- Xóa logic legacy trùng sau khi có test bảo vệ.
- Không duy trì song song controller flow cũ quá lâu.
- Dọn code chết, alias tạm, bridge tạm thời.
- Cập nhật docs kỹ thuật liên quan.

### Deliverables

- PR cleanup rõ phần xóa/giữ.
- `docs/migration/admin-refactor-notes.md` (nếu có route/name đổi bắt buộc).

### Điều kiện hoàn thành

- Không còn duplicate business path cho cùng một use case.
- Hệ thống chạy ổn định trên flow mới.

### Tiêu chí review

- Diff cleanup an toàn, có test chứng minh.
- Codebase gọn, dễ maintain hơn rõ rệt.

## 7) Public Interfaces / Contracts

- Route contract:
  - Admin routes thống nhất tên, prefix, middleware group (`auth` + permission/policy).
- Authorization contract:
  - Policy methods chuẩn `viewAny/view/create/update/delete` cho từng entity.
- Action contract:
  - Chỉ nhận DTO, không nhận `Request` trực tiếp.
- DTO contract:
  - `ProductData` và DTO tương tự là input chuẩn cho business actions.
- Resource contract:
  - `XResource` chuẩn hóa output, phục vụ API-ready.

## 8) Assumptions & Defaults

- Ưu tiên refactor theo kiến trúc mới, không mở rộng feature ngoài scope admin.
- Giữ tương thích URL/route name ở mức hợp lý; nếu bắt buộc đổi thì ghi migration notes.
- Không thay đổi schema DB lớn, chỉ bổ sung index/FK khi có justification rõ.
- Stack mặc định: Laravel 12 + Livewire + Blade + Alpine + Tailwind.

## 9) Definition of Done (Toàn Kế Hoạch)

- Admin module chính (ít nhất Product) tuân thủ đầy đủ module-first + action-centric.
- Authorization chuẩn policy/gate, không hardcode role trong view.
- Action dùng DTO; output chuẩn hóa Resource.
- Test auth/permission/CRUD/action pass đầy đủ.
- Legacy duplicated flows được dọn sạch sau khi test pass.
- Có migration notes nếu có thay đổi route/contract ảnh hưởng.
