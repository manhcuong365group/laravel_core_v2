# Audit: Admin Architecture Gap Analysis

## 1. Overview
This audit identifies the gaps between the current Admin implementation and the target architecture defined in `/docs/architecture`.

**Blueprint Module:** Product (to be refactored first).

---

## 2. Gap Analysis: Product Module

| Category | Current State | Target Architecture | Gap |
| :--- | :--- | :--- | :--- |
| **Routing** | Controller-based (`ProductController`) | Livewire Page-based (`IndexPage`, `CreatePage`, `EditPage`) | High |
| **Responsibility** | Logic split between Controller, Livewire Table, and basic Actions | Logic encapsulated in Actions; Livewire only for UI state | Medium |
| **Naming** | `ProductController`, `ProductTable.php` | `IndexPage.php`, `CreatePage.php`, `EditPage.php` | Medium |
| **Data Contract** | Raw arrays from `ProductRequest` | DTO (`ProductData`) as input contract | High |
| **Output Contract** | Blade views / Redirects | Resources (`ProductResource`) for API-readiness | High |
| **Business Logic** | Actions exist but are thin; Media logic still in Controller | Actions handle full use case including Media/Relations | High |
| **Authorization** | Middleware / Permission strings in Route/Controller | Policy-based (`viewAny`, `view`, `create`, `update`, `delete`) | Medium |

---

## 3. Specific Violations (Product Module)

### 3.1. DB Logic in Livewire
File: `app/Livewire/Backend/ProductTable.php`
- `deleteProduct()`: Queries `Product::find()` and calls `delete()` directly.
- `deleteSelected()`: Calls `Product::whereIn(...)->delete()` directly.
- `bulkStatus()`: Calls `Product::whereIn(...)->update()` directly.
- **Recommendation:** Refactor to use `DeleteProductAction`, `BulkDeleteProductAction`, etc.

### 3.2. Thin Actions
File: `app/Actions/Product/CreateProductAction.php`
- Only handles `Product::create($data)`.
- Does NOT handle media (this is currently in `ProductController`).
- **Recommendation:** Action should handle the entire use case (persistence + media + relations).

### 3.3. Legacy Controller Flow
File: `app/Http/Controllers/Admin/ProductController.php`
- Traditional `index()`, `create()`, `store()` methods returning `View` or `RedirectResponse`.
- **Recommendation:** Replace with Livewire Pages and Actions.

---

## 4. Implementation Checklist (Phase 1 & 2)

- [ ] Create `app/DTOs/ProductData.php`.
- [ ] Refactor `CreateProductAction` to accept `ProductData`.
- [ ] Refactor `UpdateProductAction` to accept `ProductData`.
- [ ] Move Media handling from `ProductController` to Actions/Services.
- [ ] Create `app/Livewire/Backend/Products/IndexPage.php`.
- [ ] Create `app/Livewire/Backend/Products/CreatePage.php`.
- [ ] Create `app/Livewire/Backend/Products/EditPage.php`.
- [ ] Update `routes/admin.php` to use the new Livewire Pages.
- [ ] Create `app/Policies/ProductPolicy.php` and enforce it.

---

## 5. Audit Summary
The project has already started moving towards an Action-centric architecture, but it's currently in a "hybrid" state. The Product module serves as a perfect candidate for a full refactor to demonstrate the "Module-first" and "Action-centric" approach.
