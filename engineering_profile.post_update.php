<?php

/**
 * @file
 * engineering_profile.install
 */

use Drupal\block\Entity\Block;

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
  ];
}

/**
 * Disable the core search module.
 */
function engineering_profile_post_update_8201_search(){
  \Drupal::service('module_installer')->uninstall(['search']);
}

/**
 * Add the main anchor block to the search page.
 */
function engineering_profile_post_update_8202() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  if (!in_array($theme_name, [
    'soe_basic',
    'stanford_basic',
    'minimally_branded_subtheme',
    'engineering',
  ])) {
    Block::create([
      'id' => "{$theme_name}_main_anchor",
      'theme' => $theme_name,
      'region' => 'content',
      'weight' => -10,
      'plugin' => 'jumpstart_ui_skipnav_main_anchor',
      'settings' => [
        'id' => 'jumpstart_ui_skipnav_main_anchor',
        'label' => 'Main content anchor target',
        'label_display' => 0,
      ],
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'negate' => FALSE,
          'pages' => '/search',
        ],
      ],
    ])->save();
  }
}

/**
 * Update field storage definitions.
 */
function soe_profile_post_update_update_field_defs() {
  $um = \Drupal::entityDefinitionUpdateManager();
  foreach ($um->getChangeList() as $entity_type => $changes) {
    if (isset($changes['field_storage_definitions'])) {
      foreach ($changes['field_storage_definitions'] as $field_name => $status) {
        $um->updateFieldStorageDefinition($um->getFieldStorageDefinition($field_name, $entity_type));
      }
    }
  }
}
