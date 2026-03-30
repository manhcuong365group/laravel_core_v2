# 🎨 PRODUCT PRO MAX - DESIGN SPECIFICATIONS

> Metadata: v1.0 | Project: Laravel Core v2 | Theme: Glassmorphism Bento
> Author: Antigravity UI Specialist (Mai)

---

## 1. Color System (🎨 Core Palette)

Mẫu Palette tập trung vào sự hiện đại và khả năng phản chiếu của Glassmorphism.

| Layer | Color (Hex) | Usage | Style |
| :--- | :--- | :--- | :--- |
| **Background** | `#0f172a` | Toàn bộ Dashboard | Deep Blue Dark |
| **Surface** | `bg-white/5` | Cards, Bento Sections | Matte Glass |
| **Primary** | `#6366f1` | Buttons, Active Icons | Indigo Glow |
| **Success** | `#10b981` | Prices & Active Status | Glowing Emerald |
| **Warning** | `#f59e0b` | Low Stock & Sale Price | Warm Amber |
| **Danger** | `#ef4444` | Out of Stock & Actions | Crimson Red |
| **Text Main** | `#f8fafc` | Titles, Main Data | Ultra White |
| **Text Muted** | `#94a3b8` | Subtitles, SKU | Slate Blue |

---

## 2. Component Blueprint (📦 Thành phần con)

### 2.1. Product Table Line (Row Style)
- **Container**: `hover:bg-white/10 transition-all duration-300 transform-gpu hover:scale-[1.002]`.
- **Image (Avatar Card)**: 
    - Size: `w-14 h-14` (56px).
    - Radius: `rounded-2xl` (16px).
    - Border: `border border-white/10`.
    - Effect: `group-hover:scale-110 duration-500`.
- **Primary Text**: `font-black text-text-main tracking-tight`.
- **Price Text**: `font-bold text-success text-lg drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]`.

### 2.2. Status Badges (Huy hiệu trạng thái)
- **Badge Shape**: `px-3 py-1 rounded-full text-[10px] uppercase font-black`.
- **Stock OK**: `bg-success/20 text-success border border-success/30`.
- **Stock Low**: `bg-warning/20 text-warning border border-warning/30`.
- **Out of Stock**: `bg-danger/20 text-danger border border-danger/30`.

### 2.3. Glass Bento Card (Thẻ Bento Thủy tinh)
- **Classes**: `bg-white/5 backdrop-blur-3xl rounded-[2rem] border border-white/10 shadow-2xl`.
- **Inner Spacing**: `p-8`.

---

## 3. Interaction & UX (🎭 Hiệu ứng tương tác)

1. **Hover-to-Action**: Toàn bộ nút `Sửa/Xóa/Xem` có độ mờ (`opacity-0`) và chỉ hiện thị (`opacity-100`) khi rê chuột vào hàng, kèm hiệu ứng trượt nhẹ (`translate-x-4` -> `0`).
2. **Keyboard Shortcut**: Nhấn `/` đề focus nhanh vào Search Bar.
3. **Skeleton Loading**: Trong khi tải, hiển thị các khối màu `bg-white/5 animate-pulse` với kích thước tương ứng.

---

## 4. Typography (📝 Kiểu chữ)
- **Font-family**: `'Inter', sans-serif`.
- **Title**: `text-3xl font-black uppercase tracking-tight`.
- **Eyebrow**: `text-[11px] font-black uppercase tracking-[0.2em] opacity-80`.

---

## 5. Next Implementation Tasks (/code Ready)
1. Cấu trúc lại trang `resources/views/livewire/backend/products/index-page.blade.php`.
2. Áp dụng bảng màu `success` cho giá và `rounded-2xl` cho ảnh.
3. Đồng bộ hiệu ứng lơ lửng của thanh Bulk Actions.
