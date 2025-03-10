<?php

namespace Drupal\engineering_profile_helper\Commands;

use Drush\Commands\DrushCommands;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\pathauto\PathautoState;

/**
 * A Drush command file.
 */
class EngineeringProfileHelperCommands extends DrushCommands {

  /**
   * Updates URL alias settings for specific nodes and taxonomy terms.
   *
   * @command engineering-profile-helper:update-url-aliases
   * @aliases eph-update-aliases
   * @description Updates URL alias settings for stanford_page, spotlight, and stanford_news nodes that were not set to generate automatically.
   */
  public function updateUrlAliases() {
    $entity_type = 'node';
    $entity_storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $nodes = $entity_storage->loadMultiple();
    foreach ($nodes as $node) {
      $bundles = ['stanford_page', 'stanford_news', 'spotlight'];
      if (in_array($node->bundle(), $bundles) && !$node->path->pathauto) {
        $node->path->pathauto = PathautoState::CREATE;
        $node->save();
      }
    }

    $entity_type = 'taxonomy_term';
    $entity_storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $terms = $entity_storage->loadMultiple();
    foreach ($terms as $term) {
      if (!$term->path->pathauto) {
        $term->path->pathauto = PathautoState::CREATE;
        $term->save();
      }
    }

    $this->logger()->success('Updated URL alias settings for stanford_page, spotlight, and stanford_news nodes and all taxonomy terms that were not set to generate automatically.');
  }

}
