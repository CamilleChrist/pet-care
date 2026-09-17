---
paths:
  - 'resources/css/**'
---

# Css

## CSS: Sass + BEM, no Tailwind
Going forward, style with Sass and BEM naming (`.block`, `.block__element`, `.block__element--modifier`), not Tailwind utility classes — match the landing page's structure (`resources/css/landing-page/**`): one file per section under `sections/`, shared reset/variables/mixins/buttons in `_partials/`, pulled in via `@use`. Existing app views (`resources/css/app.css`) still use Tailwind until migrated; don't mix Tailwind and Sass/BEM within one view.
