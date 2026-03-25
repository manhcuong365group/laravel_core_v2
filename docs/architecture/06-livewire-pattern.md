# Livewire Pattern

## Page Pattern

Each page should be:

- IndexPage
- CreatePage
- EditPage

---

## Example

Users/
IndexPage.php
CreatePage.php
EditPage.php

---

## Flow

```mermaid
flowchart TD

A[User Action]
--> B[Livewire Component]
--> C[Validate]
--> D[Action]
--> E[Model]
--> F[DB]
--> G[Re-render UI]
```
