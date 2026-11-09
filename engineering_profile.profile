<?php

/**
 * @file
 * engineering_profile.profile
 */

use Drupal\engineering_profile\Hook\InstallHooks;

/**
 * Implements hook_install_tasks().
 *
 * This must remain procedural — Drupal core's
 * HookCollectorPass::checkForProceduralOnlyHooks() explicitly denies OOP
 * #[Hook] attribute support for install_tasks. The task callback resolves
 * InstallHooks via the class resolver and calls InstallHooks::finalTask(),
 * which runs the profile's InstallTask plugins.
 */
function engineering_profile_install_tasks(&$install_state) {
  return [
    'engineering_profile_final_task' => [
      'function' => [\Drupal::classResolver(InstallHooks::class), 'finalTask'],
    ],
  ];
}
