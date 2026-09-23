---
paths:
  - 'resources/css/**'
---

# Css

## CSS: Sass + BEM, no Tailwind
Going forward, style with Sass and BEM naming (`.block`, `.block__element`, `.block__element--modifier`), not Tailwind utility classes. Layers under `resources/css/` (ITCSS order, each entry `@use`s them in this order): `abstracts/` (mixins, Sass breakpoints — no CSS output) → `base/` (reset, `:root` tokens in `_variables.scss`, element styles) → `components/` (shared blocks: `.btn`, `.logo`) → entry-specific layer (`landing-page/**` for `landing.scss`, `app/**` for `app.scss`, one file per block, `app/` mirrors `resources/views/components/layouts/`) → `utilities/` last. Shared partials are `_file.scss`; use `@use … as *` only for `abstracts/mixins`. Use `--space-*` / `--radius-*` tokens instead of raw px and `breakpoint-up/down` instead of raw `@media`; never duplicate a token per entry. Fonts load through `vite.config.js` `fonts` + `@fonts`, not `<link>`. Tailwind is no longer loaded; the utility classes left in unmigrated views (`pets/{create,edit,show}`, `*-records/*`) are dead and get replaced by BEM when each view is migrated — don't mix Tailwind and Sass/BEM within one view.

## The flex() mixin wraps by default — add flex-wrap: nowrap for columns and truncated rows
`@include flex(...)` from `abstracts/mixins` always emits `flex-wrap: wrap` (wanted for rows that must reflow on mobile). In a multi-line flex container each line sizes to its content, so children are never stretched to the container width: `text-overflow: ellipsis` on a child silently does nothing and the text overflows under later siblings. For a column container, or a row whose child must truncate, add `flex-wrap: nowrap` right after the `@include`, plus `min-width: 0` on the shrinking flex item and the nowrap/hidden/ellipsis trio on the element that holds the text (see `.pet-item`, `.vaccine-item`).
