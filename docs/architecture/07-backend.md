# Backend Architecture

## Flow

Route
→ Middleware (auth, permission)
→ Livewire Backend Page
→ Action
→ Model
→ View

---

## Layout

- sidebar
- topbar
- content

---

## Modules

Backend/
Dashboard/
Users/
Roles/
Settings/

---

## Rules

- All backend must use auth
- Use Action for CRUD
- Use components for UI reuse
