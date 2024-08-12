<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
class MagazineNavigationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var array
   *   Holds our list of topics for the menu.
   */
  protected $magazineTopics;

  /**
   * @param array $configuration
   *   The configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Inject a type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entity_type_manager = $entityTypeManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   * @param array $configuration
   *   The configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * Get the magazine_topics vocabulary so we can build a dropdown menu for it.
   *
   * @return array
   *   An array of magazine topics.
   */
  public function getMagazineTopics() : array {
    $terms = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties(['vid' => 'magazine_topics']);
    $this->magazine_topics = $terms;
    $topics_array = [];
    foreach ($terms as $term) {
      $topics_array[] = [
        'name' => $term->getName(),
        'path' => $term->get('path')->alias,
      ];
    }
    return $topics_array;
  }

  /**
   * {@inheritdoc}
   */
  public function build() : array {
    $build = [
      '#theme' => 'magazine_navigation_block',
      '#topics' => $this->getMagazineTopics(),
      '#attached' => [
        'library' => [
          'engineering_magazine/engineering_magazine',
        ],
      ],
    ];

    $build['#cache']['tags'] = ['taxonomy_term_list'];
    $build['#cache']['contexts'] = ['url']; // Ensure cache is per-page.
    return $build;
  }

}
