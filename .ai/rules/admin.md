---
paths:
  - 'resources/css/components/admin/**'
  - 'resources/views/admin/**'
---

# Admin

## Admin listings hide columns by nth-child — keep the ranks in sync
Below `md`, each admin listing drops its secondary columns from `resources/css/components/admin/_table.scss`, targeting `&--users tr > :nth-child(4)` and friends. The rank is positional and the CSS cannot see the `:headers` array, so adding, removing or reordering a column in a listing silently hides the wrong one. Whenever you touch a `:headers` array, check the matching `&--{resource}` block; each selector carries the column name in a trailing comment. The modifier class (`admin-table--users`, `--pets`, `--breeds`, `--vaccines`) is passed on `<x-admin.table class="…">`.

`&__actions` is hidden wholesale below `md`, so a listing whose rows are only reachable through the actions column needs its name cell to be a link (breeds and vaccines link to their edit page, pets and users to their show page).
