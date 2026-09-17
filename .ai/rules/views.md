---
paths:
  - 'resources/views/**'
---

# Views

## Prefer Blade components going forward
New reusable UI — icons, forms, and any other repeated UI element (buttons, cards, badges, etc.) — should be built as dedicated Blade components (`<x-*>`), not `@include` partials.

The existing icon dispatcher (`<x-icon name="...">` that `@include`s `components/icons/{name}.blade.php`) and the form partials (`_partials/form.blade.php`, included with `@include` and explicit variables) still work as-is until migrated — don't mix the two approaches within one view.
