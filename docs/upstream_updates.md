# Performing Upstream Updates for the engineering_profile

The `engineering_profile` is forked from `su-soe/soe_profile`. Engineering-specific customizations are managed through a config-split (`config/engineering/`), which keeps most of our overrides separate from the upstream configs in `config/sync/`. This makes it easier to merge upstream changes without losing our customizations.

## Prerequisites

- A clean clone of this repo (or a clean working tree on an existing clone)
- Familiarity with the [config split structure](#understanding-the-config-split) below
- The latest tag from [soe_profile releases](https://github.com/SU-SOE/soe_profile/tags)

## Understanding the Config Split

Before merging, it helps to understand what lives where:

- **`config/sync/`** — Standard configuration shared with upstream. This is what gets overwritten during merges.
- **`config/engineering/`** — Engineering-specific overrides (114+ files). These contain:
  - **Full replacements**: Spotlight content type, magazine taxonomies/fields, department configs, engineering-specific blocks
  - **Patch files** (`config_split.patch.*`): Delta overrides applied on top of sync configs. These are critical — they add engineering-specific fields and display settings to shared content types without fully replacing the upstream config.

Key patch files to be aware of:
- `config_split.patch.core.entity_form_display.node.stanford_news.default.yml` — Adds magazine fields to the news edit form
- `config_split.patch.core.entity_view_display.node.stanford_news.default.yml` — Adds layout builder sections for magazine fields
- `config_split.patch.core.entity_view_display.node.stanford_news.stanford_h3_card.yml` — Card view customization
- `config_split.patch.views.view.stanford_news.yml` — Adds `topics_list` display for magazine topics

The split is configured in `config/sync/config_split.config_split.engineering.yml`.

## Merge Process

### 1. Set Up the Update Branch

```bash
git checkout 12.x
git pull origin 12.x
git checkout -b <update-branch-name>   # e.g., feb_upstream_updates
```

### 2. Pull the Upstream Tag

```bash
git pull https://github.com/SU-SOE/soe_profile.git tags/<latest-tag> -X ours --no-edit --no-commit
```

Replace `<latest-tag>` with the most recent tag from soe_profile (e.g., `12.2.1`).

The `-X ours` flag favors our version in conflicts, and `--no-commit` lets you review everything before committing.

### 3. Review the Changes

You will very likely have merge conflicts. Use the Source Control view in VS Code to go through changed files and make sure the updates make sense.

**Areas of particular interest:**

#### Configs and Namespaces
Pay close attention to any config files that reference `soe_profile` namespaces — these may need to be updated to `engineering_profile` equivalents.

#### Stanford News Content Type
The `stanford_news` content type is significantly different from the standard `soe_profile` version due to our magazine functionality. Make sure the merge does NOT overwrite:
- `core.entity_form_display.node.stanford_news.default.yml`
- `core.entity_view_display.node.stanford_news.default.yml`
- `core.entity_view_display.node.stanford_news.stanford_h3_card.yml`

If upstream changes these files, you'll need to compare carefully and only bring in the upstream changes that don't conflict with the engineering-specific fields (magazine story, magazine issue, department, external source, etc.). The config split patches will apply on top of whatever lands in `config/sync/`, so make sure the base configs are compatible.

#### Views
Check that updates to views (especially `views.view.stanford_news`) don't overwrite customizations. Our config split patches add the `topics_list` display to the stanford_news view — make sure this isn't disrupted.

#### Renamed Profile Files
These files have been renamed from their `soe_profile` counterparts. The merge won't automatically carry over changes from the upstream versions, so you need to manually compare and port any relevant updates:
- `engineering_profile.info.yml`
- `engineering_profile.install`
- `engineering_profile.post_update.php`
- `engineering_profile.profile`
- `engineering_profile.services.yml`

Compare each one against its `soe_profile` equivalent to see if any new hooks, updates, or dependency changes need to be reflected.

### 4. Commit, Push, and Open a PR

Once the branch is cleaned up:
```bash
git add .
git commit -m "Merge upstream tag <latest-tag>"
git push origin <update-branch-name>
```

Open a PR against the `12.x` branch.

### 5. Fix Test Failures

Track whether the GitHub Actions tests pass. Common reasons for test failures after an upstream merge:

- **New or changed permissions** — Kernel tests that check permissions may need updating
- **Config schema changes** — New fields or changed config structures can break kernel tests that install specific configs
- **Renamed or removed modules** — If upstream adds/removes a module dependency, tests that rely on it may break
- **Changed test expectations** — If upstream content or default values changed, acceptance tests may need updated assertions

Look at the test output carefully and trace failures back to specific merge changes.

## Local Validation

Once tests pass, install the updated profile locally and verify it works against an existing database:

```bash
lando drush @soegryphon.lando deploy
```

This runs config import and database updates against the existing local site. If it fails, check:
- Whether new config entities conflict with existing ones
- Whether update hooks run in the correct order
- Whether any removed configs are still referenced elsewhere

## Deployment to DEV

If everything passes tests and works locally:

1. Create a branch in the **stack repo** (`su-soe/gryphon`) that points to your update branch of `engineering_profile` (update the `composer.json` to reference your branch).
2. Deploy that stack branch to the **DEV environment**.
3. Verify the deployment succeeds and the site functions correctly.

If it installs correctly on DEV, passes tests, and works locally, you're good to merge into the `12.x` branch and continue with the normal development workflow.
