---
paths:
  - 'resources/views/**'
---

# Views

## Prefer Blade components going forward
New reusable UI — icons, forms, and any other repeated UI element (buttons, cards, badges, etc.) — should be built as dedicated Blade components (`<x-*>`), not `@include` partials.

The existing icon dispatcher (`<x-icon name="...">` that `@include`s `components/icons/{name}.blade.php`) and the form partials (`_partials/form.blade.php`, included with `@include` and explicit variables) still work as-is until migrated — don't mix the two approaches within one view.

## Page layouts are Blade components: base / app / auth
Every page wraps in one of `resources/views/components/layouts/`: `<x-layouts.app title="…">` for logged-in screens (sidebar ≥768px, bottom-nav below — both in `layouts/nav/`), `<x-layouts.auth title="…" headline="…" description="…">` for login/register/password screens (brand panel + centered `<main>`; the view supplies its own `<x-card>`). Both extend `<x-layouts.base>` (HTML skeleton, loads `resources/css/app.scss`). New views never `@extends('layouts.base')` — that file only survives until `pets/*` and `*-records/*` are migrated, then it goes.

## Auth views bring their own card; reuse the existing form/feedback components
`<x-layouts.auth>` only provides the brand panel and a centered `<main>` (props `title`, `headline`, `description`, `step` 1–3 for the password-reset steps). Each auth view wraps its own content in `<x-card tagTitle="h1" title description>` — the layout does not wrap the slot, and `session('status')` is rendered by the view (as `<x-alert tone="success">`), not the layout.

Existing components to reuse before writing new ones: `<x-card title description tagTitle>`, `<x-form.input name label type icon hint required>` (reads `old()` and `$errors` itself → per-field `.field__error`; no separate `$errors->any()` list), `<x-form.switch name label checked>`, `<x-alert tone="info|success|danger">` (icon chosen by tone). Forms keep native HTML validation (`required`, `type="email"`) — no `novalidate`; server errors only appear for what the browser can't check.

## Confirmations use the native <x-dialog>, never confirm() or a modal lib
`<x-dialog id title description>` wraps a native `<dialog>` (focus trap, Escape, `::backdrop` for free); the slot is the actions row (`.btn--ghost` cancel with `[data-dialog-close]`, then the real `<form method="post">` + `@csrf` around the confirming `.btn--primary|--danger`). Any `[data-dialog-open="id"]` element opens it — `resources/js/components/dialog.js` handles open/close/backdrop click. Place the dialog in the page or layout, not inside `<x-layouts.nav.sidebar>` (hidden below `md`). Bottom sheet on mobile, centered box from `md` — styles in `resources/css/components/_dialog.scss`.
