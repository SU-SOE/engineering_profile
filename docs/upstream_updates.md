# Upstream Updates

## Overview

This repo (`SU-SOE/engineering_profile`) is the **Engineering Profile**, a downstream fork of
[`SU-SOE/soe_profile`](https://github.com/SU-SOE/soe_profile) with customizations for the School of Engineering
main site. It is installed from source into the `SU-SWS/ace-soegryphon` stack at
`docroot/profiles/custom/engineering_profile` (see that repo's `docs/upstream_update.md` for the stack side).

- Base (upstream) config lives in `config/sync/`.
- Engineering-only config and `config_split.patch.*` partial overrides live in `config/engineering/`, the
  **`engineering`** config split (`config/sync/config_split.config_split.engineering.yml`).

Keep engineering-specific config in the split, not in `config/sync/`, so upstream merges stay clean.

## Rules

- **Merge, never squash.** Upstream syncs must be real merge commits, and the PR that brings them into the base
  branch must be merged with a merge commit. Squash merges throw away the upstream ancestry and the next merge
  then conflicts on hundreds of files (see [Background](#background-the-1300-12-update)).
- **Don't use `-X ours` / `-X theirs`.** Resolve every conflict by hand.
- **Commit or push the profile before running composer in the stack.** The stack sets `discard-changes: true`
  and installs this profile from source, so `composer install/update` can wipe uncommitted profile changes.
- **Don't push upstream tags.** soe_profile tag names (`12.2.0`, `12.2.1`, ...) collide with this repo's own
  release tags. Fetch only the tag you need and never run `git push --tags`.

## Check the ancestry first

Before starting, confirm git knows which upstream release we are already on:

```bash
git fetch --no-tags https://github.com/SU-SOE/soe_profile.git tag <previous-upstream-tag>
git merge-base HEAD <previous-upstream-tag>
git rev-parse <previous-upstream-tag>^{commit}
```

The two hashes should be the same. After the 13.0.12 update, on the merged branch:

```bash
$ git merge-base HEAD 13.0.12
400161905507548122865174ed83bf79d17fbd9d   # = 13.0.12^{commit}
```

If the merge base is an older upstream commit, the history was squashed somewhere. Add a temporary graft before
you merge (see [Repairing lost ancestry](#repairing-lost-ancestry-graft)).

## Performing an upstream update

### 1. Prepare

1. Create a branch from the current base branch in both repos (the profile and `ace-soegryphon`):

   ```bash
   git checkout <base-branch> && git pull
   git checkout -b <ticket>
   ```

2. Take a DB snapshot of the local site so you can reset and re-run updates (`lando db-export <file>.sql.gz`
   in the stack).
3. Capture BackstopJS reference screenshots from prod (see [Visual regression](#7-visual-regression-backstopjs)).

### 2. Fetch the upstream tag

Check [soe_profile tags](https://github.com/SU-SOE/soe_profile/tags) and fetch only the target tag:

```bash
git ls-remote --tags origin | grep -w '<tag>'   # make sure no engineering tag has the same name
git fetch --no-tags https://github.com/SU-SOE/soe_profile.git tag <tag>
git merge-base HEAD <tag>                       # should be the previous upstream tag
```

### 3. Merge

```bash
git merge <tag>
```

Resolve conflicts, then `git commit`. The result must be a merge commit whose second parent is the upstream tag.
Check this with `git show -s --format='%H %P' HEAD`.

### 4. Resolve conflicts

**Namespace translation.** Upstream code uses soe_profile names (`soe_profile_*` functions,
`Drupal\soe_profile\...` namespaces, `soe_profile.*` service and route names). Take upstream's logic, but keep our
names in:

- `engineering_profile.info.yml`, `engineering_profile.profile`, `engineering_profile.services.yml`
- `engineering_profile.install` and `engineering_profile.post_update.php`
- `src/Hook/InstallHooks.php`, `src/EventSubscriber/EngineeringProfileEventSubscriber.php`,
  `src/Plugin/InstallTask/RouteRebuilder.php`, and the rest of `src/`

After resolving, check that no upstream names leaked in:

```bash
grep -rn "soe_profile" --include='*.php' --include='*.yml' --include='*.profile' --include='*.install' \
  src modules engineering_profile.*
```

**Update hooks.** Keep `engineering_profile_update_last_removed()` and `engineering_profile_removed_post_updates()`
consistent with what has actually run on prod. Don't copy upstream's list as-is. For example,
`header_links_block` and `unpublished_site_banner` never ran here, so listing them would block `updb`. Check
against a prod-synced DB:

```bash
lando drush --uri=https://soegryphon.lndo.site php:eval 'echo \Drupal::service("update.update_hook_registry")->getInstalledVersion("engineering_profile");'
lando drush --uri=https://soegryphon.lndo.site php:eval 'print_r(array_filter(\Drupal::keyValue("post_update")->get("existing_updates", []), fn($n) => str_starts_with($n, "engineering_profile_")));'
```

The installed schema is currently `12000`, so any new `hook_update_N` must be numbered higher than that or it
will never run. Prefer `post_update` functions for config and content changes.

**Config.** See [Config](#5-config).

**`composer.json`.** Take upstream's dependency changes and keep our additions. Composer 2.11 security blocking
needs `config.audit.ignore: ["PKSA-y2cr-5h3j-g3ys"]` (firebase/php-jwt) in both this `composer.json` and the
stack's `composer.json`.

**CI.** Keep `.github/workflows/tests.yml` aligned with upstream (currently PHP 8.4/8.5 and
`--core-version=11.4`). `.github/workflows/trigger_stack_update.yml` is engineering-only. It must watch the
current base branch.

### 5. Config

1. Merge `config/sync/` like code. Upstream's version usually wins for base config.
2. **Patched configs.** When upstream changes a config that `config/engineering/config_split.patch.*` patches,
   regenerate the patch. Don't hand-merge it. Apply the upstream base in `config/sync/`, import, re-apply the
   engineering change on the site, then `drush cex`.
3. **partial_list gotcha.** config_split 2.0.2 applies **every** patch file in `config/engineering/` on import,
   but `drush cex` only regenerates patches for configs listed in the split's `partial_list`. If a patched config
   is missing from `partial_list`, the export writes the engineering version into `config/sync/` and the patch
   goes stale. Add the config to `partial_list` in `config/sync/config_split.config_split.engineering.yml`,
   import, then export again.
4. Remove permissions, dependencies and displays that refer to modules upstream uninstalled. For example, in
   13.x the `site_builder` and `site_developer` roles lost their Display Suite and `ui_patterns_library`
   permissions.
5. Verify:

   ```bash
   lando drush --uri=https://soegryphon.lndo.site cim -y
   lando drush --uri=https://soegryphon.lndo.site cex -y        # inspect git diff in config/sync AND config/engineering
   lando drush --uri=https://soegryphon.lndo.site config:status # "No differences between DB and sync directory."
   lando drush --uri=https://soegryphon.lndo.site cim -y        # a second import must show no changes
   ```

### 6. Point the stack at the branch and deploy locally

1. Push the profile branch.
2. In the stack `composer.json`, set `"su-soe/engineering_profile": "dev-<branch> as <upstream-version>"`
   (for example `dev-SWSDEVOPS-351 as 13.0.12`).
3. Update the profile through the system composer in Lando (see the stack's `docs/upstream_update.md`):

   ```bash
   lando ssh -s appserver -c 'cd /app && /usr/local/bin/composer update su-soe/engineering_profile -W'
   ```

4. Restore the DB snapshot, then deploy:

   ```bash
   lando drush --uri=https://soegryphon.lndo.site php:script hooks/common/scripts/pre-deploy-update-workarounds.php  # only while graphql updates are pending
   lando drush --uri=https://soegryphon.lndo.site deploy -y
   lando drush --uri=https://soegryphon.lndo.site php:eval 'if (node_access_needs_rebuild()) { node_access_rebuild(); }'
   ```

5. Fix the config and templates, then repeat from the snapshot until `deploy` runs cleanly the first time.

### 7. Visual regression (BackstopJS)

The stack's `backstop.config.js` uses prod (`https://engineering.stanford.edu/`) as `referenceUrl` and Lando as
`url`.

```bash
# in the stack root
npx backstop reference --config=backstop.config.js   # before the update, against prod
npx backstop test --config=backstop.config.js        # after the update, against Lando
```

If Chrome runs somewhere that can't resolve `soegryphon.lndo.site` (for example inside a container), add
`--host-resolver-rules=MAP soegryphon.lndo.site <lando-proxy-ip>` to `engineOptions.args` in a local copy of the
config. Find the IP of the Lando proxy container on its `edge` network with `docker inspect`. It was
`172.20.0.2` in the 13.0.12 setup.

Sort the failures before you fix anything. Most diffs are noise:

- **image derivatives**: different crops or styles between prod files and local
- **content**: prod content newer than the local DB
- **random accent colors**: components that pick a random color on each load
- **extlink**: external-link icons

Only what remains is a real regression.

### 8. Tests, PRs and the base branch

1. Run the profile CI (`.github/workflows/tests.yml`) by pushing the branch. Upstream tests that don't apply to
   the engineering customizations can be skipped with a comment explaining why.
2. Open a PR in this repo and a PR in `ace-soegryphon`.
3. Merge the profile PR with a **merge commit**.
4. Create a new long-lived base branch (for example `13.x`) only after the update is verified, and update
   `trigger_stack_update.yml` to watch it.

## Repairing lost ancestry (graft)

If `git merge-base HEAD <new-tag>` returns an upstream commit older than the release we already synced, tell git
the truth with a temporary local graft:

1. Find the engineering commit that last synced upstream (usually an "Upstream updates" squash commit):
   `git log --oneline -i --grep=upstream`.
2. Find its original parent: `git show -s --format='%P' <sync-commit>`.
3. Find the upstream commit it synced to. Use the upstream tag named in the PR or CHANGELOG, fetch it, and run
   `git rev-parse <tag>^{commit}`.
4. Graft, check, merge, and remove the graft:

   ```bash
   git replace --graft <sync-commit> <original-parent> <upstream-commit>
   git merge-base HEAD <new-tag>      # now returns <upstream-commit>
   git merge <new-tag>
   # ...resolve and commit...
   git replace -d <sync-commit>
   git replace -l                     # should print nothing
   ```

Replace refs are local only and never pushed. The resulting merge commit records the new upstream tag as a real
parent, so later merges don't need the graft.

## Background: the 13.0.12 update

SWSDEVOPS-351 moved this profile from 12.2.1 to soe_profile 13.0.12 (Drupal 11.4, stanford_profile 13,
stanford_profile_helper 11).

- Earlier upstream syncs had been squash-merged, so `git merge-base` with soe_profile pointed back at 10.0.0
  and a plain merge produced 263 conflicts.
- `3ba3011e` ("Upstream updates (#207)") had synced soe_profile 12.2.4. The graft used was:

  ```bash
  git replace --graft 3ba3011e 6efa0aa9 6ab7bee25a68a2cfbc8fe5f79fc8dcdc61399479
  ```

  (sync commit, its original parent, soe_profile `12.2.4`). With it, `git merge 13.0.12` had 39 conflicts.
- The result is merge commit `c74d1fab`, whose second parent is `40016190` (tag `13.0.12`).
- **UI Patterns 1 to 2 / SDC.** stanford_profile_helper 11 disables the `ui_patterns_legacy` migrator, so
  engineering-only views and displays that used UIP1 patterns or Display Suite were migrated by hand. The
  spotlights, magazine, magazine_issues, magazine_article_collection and department_terms views now use UIP2
  rows with the SDC `engineering_profile_helper:soe_card`
  (`modules/engineering_profile_helper/components/soe_card`). Engineering theme template overrides keep the prod
  markup. Display Suite and related modules were uninstalled.
