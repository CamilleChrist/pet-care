---

paths:

  - '**'

---

# General

## Git workflow

Follow [GitHub Flow](https://docs.github.com/en/get-started/using-github/github-flow).

* `main` is the only permanent branch.
* Never commit directly to `main`.
* Start each task from an up-to-date `main`.
* Create a short-lived branch for each task.
* Keep branches focused on a single purpose.
* Push the branch and open a pull request against `main`.
* Merge through a pull request.
* Delete the branch after it is merged.

### Branch naming

Use:

`<type>/<short-description>`

`<type>` must be a valid [Conventional Commits](https://www.conventionalcommits.org/) type.

Branch names must be lowercase, concise, descriptive, and use hyphens between words.

Examples:

* `feat/user-profile`
* `fix/invalid-login-redirect`
* `refactor/order-service`
* `chore/update-dependencies`
* `docs/installation`

### Before starting work

1. Check the current branch and working tree.
2. Preserve unrelated uncommitted changes.
3. If on `main`, create an appropriate branch before modifying files.
4. Base the branch on the latest available `main`.
5. Do not create a new branch if the user explicitly asks to continue on an existing branch.

Never discard, overwrite, or commit unrelated changes.

## Commits

Follow [Conventional Commits](https://www.conventionalcommits.org/).

Use:

`<type>[optional scope]: <description>`

A commit may include a body and/or footer when useful.

Commit messages should:

* be written in English;
* use the imperative mood;
* be concise and specific;
* describe the change;
* use a scope when it improves clarity;
* avoid unnecessary punctuation or emojis.

Prefer small, logically independent commits.

## Pull requests

Every change intended for `main` must go through a pull request.

Pull requests should:

* clearly describe the change;
* contain only related changes;
* pass the relevant tests and checks;
* receive the required review before merging.

Do not merge unrelated work together.

## History

Do not rewrite published history.

Never force-push `main`.

Preserve existing commits, even if they do not follow the current conventions. Apply the current rules only to new work.
