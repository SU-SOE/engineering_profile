# Upstream Updates

## Overview

This repo (`SU-SWS/ace-soegryphon`) contains the **Engineering Profile** (`engineering_profile`), which is a downstream fork of the [`SU-SOE/soe_profile`](https://github.com/SU-SOE/soe_profile) repo with extensive customizations for the Stanford School of Engineering main site.

Engineering-specific customizations are isolated in a `config_split` named **`engineering`**, located at `config/engineering/`. This split holds custom block placements, content types (e.g. Spotlight), fields (magazine topics, departments, etc.), and patches to upstream config. The upstream profile's config lives in `config/sync/`. This separation makes upstream merges reasonably straightforward — most conflicts will be in the sync directory, not in engineering-specific config.

## Performing an Upstream Update

### 1. Prepare your branch

Pull down a clean copy of this repo and create a new branch from the current base branch:

```bash
git checkout 12.x
git pull origin 12.x
git checkout -b <version>-upstream-updates   # e.g. 4.2.0-upstream-updates
```

### 2. Pull in the upstream changes

Check [SU-SOE/soe_profile releases](https://github.com/SU-SOE/soe_profile/tags) for the most recent tag, then pull it in:

```bash
git pull https://github.com/SU-SOE/soe_profile.git tags/<tag> -X ours --no-edit --no-commit
```

The `-X ours` strategy favors our side on conflicts, and `--no-commit` lets you review everything before committing.

### 3. Review and resolve conflicts

You will very likely have merge conflicts. Use the Source Control view in VS Code to go through changed files. Pay particular attention to:

- **Config files in `config/sync/`** — These are the most common source of conflicts. Make sure upstream config changes don't overwrite engineering-specific patches managed by the `engineering` config_split.
- **`config_split.patch.*` files in `config/engineering/`** — If the upstream changed a config that we patch (e.g. `views.view.stanford_news`, entity form displays, user roles), verify the patch still applies cleanly and makes sense against the new upstream version.
- **Namespaces and module names** — If the upstream renamed or reorganized modules, make sure our references (in `.info.yml`, `.install`, `.module`, etc.) are updated accordingly.
- **`engineering_profile.install`** — Check for new update hooks from upstream that may conflict with our own hook numbering (currently at schema 9106, with D11 updates at 11000+).
- **`composer.json`** — Upstream dependency changes may conflict with our own additions. Ensure any new requirements are compatible and run `composer update` after resolving.

### 4. Commit, push, and open a PR

Once the branch is cleaned up:

```bash
git add .
git commit -m "Upstream update to soe_profile <tag>"
git push origin <branch-name>
```

Open a PR against the `12.x` branch.

### 5. Verify tests pass

This profile has extensive test coverage including:

- **Codeception acceptance tests** (`tests/codeception/acceptance/`) — ~45+ tests covering content types, paragraphs, media, permissions, and contrib modules.
- **Codeception functional tests** (`tests/codeception/functional/`) — Content creation, navigation, and role-based behavior.
- **Kernel tests** (`tests/src/Kernel/`) — Magazine blocks, install tasks, event subscribers, migrations.
- **Unit tests** (`tests/src/Unit/`) — Help plugins, migration processors, config overrides.

If tests fail, investigate and fix the issues. Keep in mind that some tests from `soe_profile` are not relevant to the engineering profile due to our customizations — these can be commented out with a note explaining why.

### 6. Update the stack repo

Update the `ace-soegryphon` stack repo's `composer.json` to reference your branch or the new tagged release of this profile. Deploy to a test environment and verify:

- All custom Engineering functionality works (Spotlights, Magazine features, department taxonomies, etc.)
- Upstream features still function correctly
- No regressions in layout or theming (the profile uses `stanford_basic` / `soe_basic` themes)
