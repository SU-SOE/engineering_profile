<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Magazine Curtain' Block.
 *
 * @Block(
 *   id = "magazine_curtain_block",
 *   admin_label = @Translation("Magazine Curtain"),
 *   category = @Translation("SoE Magazine"),
 * )
 */
class MagazineCurtainBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var array
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
   *   Inject the type manager.
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
      $container->get('entity_type.manager'),
      $container->get('path_alias.manager')
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
   * We want the latest Featured Story and issue so we can feed the information into the curtain.
   */
  protected function getNewestFeatured() {
    $entity = $this->entity_type_manager->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query->condition('type', 'stanford_news')
      ->condition('su_magazine_story', TRUE)
      ->condition('su_mag_featured', TRUE)
      ->condition('status', TRUE)
      ->sort('su_news_publishing_date', 'DESC')
      ->pager(1)
      ->execute();
    $nodes = $entity->loadMultiple($ids);
    if (count($nodes) == 1) {
      $node = array_shift($nodes);
      return $this->getImageUrl($node);
    }
    // We need a default image for a fallback.
    throw new \RuntimeException("Could not find a featured node");

  }

  /**
   * Get the newest issue.
   */
  protected function getNewestIssue() {
    $entity = $this->entity_type_manager->getStorage('taxonomy_term');
    $query = $entity->getQuery();
    $tids = $query->condition('vid', 'magazine_issues')
      ->sort('su_issue_number', 'DESC')
      ->pager(1)
      ->execute();
    $terms = $entity->loadMultiple($tids);
    if (count($terms) == 1) {
      $term = array_shift($terms);

      $term_name = $term->getName();
      $term_path = $term->get('path')->alias;
      return [
        'term_name' => $term_name,
        'issue_url' => $term_path,
      ];
    }
    throw new \RuntimeException("Could not find an issue");
  }

  /**
   * Get an image URL for a news node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to get the image from.
   */
  protected function getImageUrl(Node $node) {
    $uri = $node->get('su_news_banner')
      ->entity
      ->field_media_image
      ->entity
      ->getFileUri();
    $media_url = file_create_url($uri);
    return $media_url;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    try {
      $issue = $this->getNewestIssue();
    }
    catch (\Exception $e) {
      $issue = [
        'term_name' => 'Default',
        'issue_url' => '/foo/bar',
      ];
    }

    try {
      $newestFeatured = $this->getNewestFeatured();
    }
    catch (\Exception $e) {
      $newestFeatured = '#';
    }

    return [
      '#theme' => 'magazine_curtain_block',
      '#topics' => $this->getMagazineTopics(),
      '#curtain_content' => [
        'media_url' => $newestFeatured ,
        'issue_number' => $issue['term_name'],
        'issue_url' => $issue['issue_url'],
      ],
      '#attached' => [
        'library' => [
          'engineering_magazine/engineering_magazine',
        ],
      ],
    ];
  }

}
