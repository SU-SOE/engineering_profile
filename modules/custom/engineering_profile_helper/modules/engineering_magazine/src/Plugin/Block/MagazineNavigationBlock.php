<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'Magazine Navigation' Block.
 *
 * @Block(
 *   id = "magazine_navigation_block",
 *   admin_label = @Translation("Magazine Navigation"),
 *   category = @Translation("SoE Magazine"),
 * )
 */
class MagazineNavigationBlock extends BlockBase implements BlockPluginInterface {

  protected $magazine_topics;

  #public static function create()

  /**
   * Get the magazine_topics vocabulary so we can build a dropdown menu for it.
   */
  public function getMagazineTopics(EntityTypeManagerInterface $entityTypeManager) {
    $terms = $entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['vid' => 'magazine_topics']);
    $this->magazine_topics = $terms;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'magazine_navigation_block',
      '#test_var' => 'This is my test variable from the plugin.',
      '#topics' => $this->magazine_topics,
    ];
  }

}
