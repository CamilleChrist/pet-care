---
paths:
  - 'resources/views/**'
---

# Views

## Prefer Blade components going forward
New reusable UI — icons, forms, and any other repeated UI element (buttons, cards, badges, etc.) — should be built as dedicated Blade components (`<x-*>`), not `@include` partials.

The existing icon dispatcher (`<x-icon name="...">` that `@include`s `components/icons/{name}.blade.php`) and the form partials (`_partials/form.blade.php`, included with `@include` and explicit variables) still work as-is until migrated — don't mix the two approaches within one view.

## Page layouts are Blade components: base / app / auth
Every page wraps in one of `resources/views/components/layouts/`: `<x-layouts.app title="…">` for logged-in screens (sidebar ≥768px, bottom-nav below — both in `layouts/nav/`), `<x-layouts.auth title="…" headline="…" description="…">` for login/register/password screens (brand panel + form card). Both extend `<x-layouts.base>` (HTML skeleton, loads `resources/css/app.scss`). New views never `@extends('layouts.base')` — that file only survives until `pets/*` and `*-records/*` are migrated, then it goes.
