# Flow Diagram

## Core Flow

```mermaid
flowchart LR

A[Route]
--> B[Livewire]
--> C[Action]
--> D[Service]
--> E[Model]
--> F[Database]

E --> B
B --> G[Blade]
G --> H[Layout]
H --> I[Browser]
```
