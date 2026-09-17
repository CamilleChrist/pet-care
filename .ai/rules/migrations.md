---
paths:
  - 'database/migrations/**'
---

# Migrations

## Foreign keys via foreignIdFor
Declare foreign keys with `foreignIdFor(Model::class)`, not `foreignId('x_id')->constrained()` or `foreign()->references()->on()`.

## Fixed-value columns use SQL enum()
Store fixed-value columns with `$table->enum(...)`, not `string()` plus a cast, even when a PHP backed enum exists at the model layer.

## Foreign key on-delete behavior by relationship role
Set the FK delete behavior by what the relationship means: a record owned by a pet (`weight_records.pet_id`, `vaccination_records.pet_id`) uses `cascadeOnDelete()`; an optional reference to lookup data (`pets.breed_id`, `vaccination_records.vaccine_id`) is nullable with `nullOnDelete()`; a required reference to a core entity (`pets.user_id`) is left to the default restrict behavior.

## Event date/time columns default to the current time in the DB
Columns recording when something happened (`weight_records.recorded_at`, `vaccination_records.administered_at`) use `->useCurrent()` for a DB-level default, rather than setting the value from application code.
