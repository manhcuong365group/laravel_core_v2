# Module System

## Structure

Each module must follow:

Module/
Actions/
Services/
Livewire/
Views/

---

## Example: Users

Users/
Actions/
CreateUserAction.php
UpdateUserAction.php

Livewire/
IndexPage.php
CreatePage.php
EditPage.php

Services/
UserAvatarService.php

---

## Rules

- One module = one domain
- No cross-module direct logic
- Use Service if sharing logic
