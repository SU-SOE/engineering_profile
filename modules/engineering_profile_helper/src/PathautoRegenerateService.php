<?php
namespace Drupal\engineering_profile_helper;

use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class PathautoRegenerateService.
 */
class PathautoRegenerateService {

  /**
   * The path alias manager service.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * PathautoRegenerateService constructor.
   *
   * @param \Drupal\path_alias\AliasManagerInterface $alias_manager
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(AliasManagerInterface $alias_manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->aliasManager = $alias_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Regenerates pathauto paths for all nodes.
   */
  public function regenerateNodePaths() {
    $storage = $this->entityTypeManager->getStorage('node');
    $node_ids = $storage->getQuery()->execute();
    $nodes = $storage->loadMultiple($node_ids);

    foreach ($nodes as $node) {
      // Clear the existing alias.
      $this->aliasManager->deleteEntityAlias($node, 'update');

      // Generate a new alias.
      $this->aliasManager->updateAlias($node, 'update');
    }
  }
}
