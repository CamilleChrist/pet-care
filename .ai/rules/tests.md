---
paths:
  - 'tests/**'
---

# Tests

## Pest test(), no it()/describe(), self-contained tests
Write tests with the global `test()` function, never `it()` or `describe()`. Keep each test self-contained — set up its own factories/fixtures inline — rather than sharing state via a file-level `beforeEach()`.

## Test names as plain-English behavior sentences
Name tests as lowercase, plain-English sentences describing behavior from the actor's point of view, e.g. `'a user can create a pet'`, `'a user cannot delete another user pet'`, `'guests cannot access pets'`.

## Cover guest and cross-owner access in every resource test file
For every pet-scoped resource, include one test asserting guests are redirected to login for every route, and one test per view/mutating action asserting another user gets `assertForbidden()` while the record stays unchanged.
