<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

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
   * @var \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   */
  protected $entity_type_manager;

  /**
   * @var array $magazine_topics
   */
  protected $magazine_topics;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entity_type_manager = $entity_type_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
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
   */
  public function getMagazineTopics() {
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
  public function build() {
    $this->getMagazineTopics();
    return [
      '#theme' => 'magazine_navigation_block',
      '#topics' => $this->getMagazineTopics(),
      '#attached' => [
        'library' => [
          'engineering_magazine/engineering_magazine'
        ],
      ],
    ];
  }

}
