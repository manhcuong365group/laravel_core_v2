# Architecture Overview

## Stack

- Laravel 12
- Livewire
- Blade
- Alpine.js
- Tailwind

---

## Core Principles

1. Separate Frontend / Backend
2. Livewire = UI Layer
3. Actions = Business Logic
4. Services = Shared Logic
5. Models = Data Layer
6. Blade = Presentation only

---

## Standard Flow

Route
→ Livewire Page
→ Action
→ Service (optional)
→ Model
→ DB
→ View
→ Layout
→ Browser
