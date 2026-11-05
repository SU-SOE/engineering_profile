<?php

/**
 * @file
 * engineering_profile.post_update.php
 */

/**
 * Implements hook_removed_post_updates().
 */
function engineering_profile_removed_post_updates() {
  return [
    'engineering_profile_post_update_8101' => '8.x-2.0',
    'engineering_profile_post_update_8102' => '8.x-2.0',
    'engineering_profile_post_update_8103' => '8.x-2.0',
    'engineering_profile_post_update_8104' => '8.x-2.0',
    'engineering_profile_post_update_8200_uuids' => '8.x-2.10',
    'engineering_profile_post_update_8201_search' => '12.0.0',
    'engineering_profile_post_update_8202' => '12.0.0',
    'engineering_profile_post_update_update_field_defs' => '12.0.0',
    'engineering_profile_post_update_samlauth' => '12.0.0',
    'engineering_profile_post_update_site_orgs' => '12.0.0',
    'engineering_profile_post_update_rabbit_hole_block' => '13.0.0',
  ];
}
