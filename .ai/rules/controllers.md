---
paths:
  - 'app/Http/Controllers/**'
---

# Controllers

## No service/action layer
Controllers query and mutate Eloquent models directly (e.g. `Model::create()`, `$pet->weightRecords()->create()`). Do not introduce Action, Service, Repository, or Query-object classes.

## Validation via Form Request classes
Validate incoming requests via dedicated Form Request classes (e.g. `Store*Request`, `Update*Request`), not inline `$request->validate()`.
