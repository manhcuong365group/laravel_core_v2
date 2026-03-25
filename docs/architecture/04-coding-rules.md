# Coding Rules

## 1. Do NOT

- Put business logic in Blade
- Put heavy logic in Livewire
- Query DB directly in Blade
- Mix frontend and backend

## 2. MUST

- Use Action for business logic
- Use Livewire for UI state
- Use Blade for rendering
- Use components for reusable UI

## 3. Structure

Route → Livewire → Action → Model → View

## 4. Keep code

- Small
- Clear
- Single responsibility
