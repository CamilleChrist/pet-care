---
paths:
  - 'resources/css/components/**'
---

# Components

## One Sass file per Blade component, mirroring resources/views/components/
`resources/css/components/` holds one `_block.scss` per Blade component and mirrors the view folder: `_buttons.scss` at the root (no Blade counterpart), then `ui/_card.scss`, `ui/_alert.scss`, `form/{_form,_input,_switch}.scss`, `pet/_avatar.scss`… for `views/components/{ui,form,nav,pet,weight,vaccine}/*`. A new component gets its own file (in the matching subfolder), `@use`d from the entry that needs it — don't grow one file into a grab-bag. Semantic colors (`--color-{success,danger,info}[-soft]`) live in `base/_variables.scss` like the rest of the tokens.
