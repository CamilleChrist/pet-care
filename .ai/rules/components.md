---
paths:
  - 'resources/css/components/**'
---

# Components

## One Sass file per Blade component, mirroring resources/views/components/
`resources/css/components/` holds one `_block.scss` per Blade component and mirrors the view folder: `_buttons.scss`, `_logo.scss`, `_card.scss`, `_alert.scss`, and `form/{_form,_input,_switch}.scss` for `views/components/form/*`. A new component gets its own file (in the matching subfolder), `@use`d from the entry that needs it — don't grow one file into a grab-bag. Semantic colors (`--color-{success,danger,info}[-soft]`) live in `base/_variables.scss` like the rest of the tokens.
