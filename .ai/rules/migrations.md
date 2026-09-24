---
paths:
  - 'database/migrations/**'
---

# Migrations

## Foreign keys via foreignIdFor
Declare foreign keys with `foreignIdFor(Model::class)`, not `foreignId('x_id')->constrained()` or `foreign()->references()->on()`.

## Fixed-value columns use SQL enum()
Store fixed-value columns with `$table->enum(...)`, not `string()` plus a cast, even when a PHP backed enum exists at the model layer.

## Event date/time columns default to the current time in the DB
Columns recording when something happened (`weight_records.recorded_at`, `vaccination_records.administered_at`) use `->useCurrent()` for a DB-level default, rather than setting the value from application code.

## Foreign key on-delete behavior by relationship role
Set the FK delete behavior by what the relationship means: a record owned by its parent (`weight_records.pet_id`, `vaccination_records.pet_id`, `pets.user_id`) uses `cascadeOnDelete()` so deleting the owner takes its whole history with it; an optional reference to lookup data (`pets.breed_id`, `vaccination_records.vaccine_id`) is nullable with `nullOnDelete()`.

`pets.user_id` was created with the default restrict behavior and switched to cascade in `2026_09_24_110726_cascade_on_delete_for_user_id_on_pets_table` — account deletion relies on it, so don't revert it. Files on the `public` disk are not cascaded: delete pet photos from application code before deleting the owner.
