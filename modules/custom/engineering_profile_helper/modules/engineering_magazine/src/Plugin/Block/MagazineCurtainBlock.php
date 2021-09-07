<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\media\Entity\Media;

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
   * Figure out the vocabulary and term that we are looking at by examining the path.
   */
  public function whatsMyTerm() {
    $current_path = \Drupal::service('path.current')->getPath();
    $path_array = explode('/', $current_path);
    array_shift($path_array);



    switch ($path_array[0]) {
      case 'magazine':
        // we are on the landing page:
        return 'Landing Page Curtain';
      case 'taxonomy':
        // we are on a term page.
        return 'Taxonomy Term Page Curtain';
      default:
        return 'something went wrong, and I got the default.';
    }
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
  public function getNewestFeatured() {
    $newest_featured = [
      'su_mag_featured' => true,
      'su_magazine_story' => true,
    ];
    $entity = $this->entity_type_manager->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query->condition('type', 'stanford_news')
      ->condition('su_magazine_story', true)
      ->condition('su_mag_featured', true)
      ->condition('status', true)
      ->sort('su_news_publishing_date', 'DESC')
      ->pager(1)
      ->execute();
    $nodes = $entity->loadMultiple($ids);
    if (count($nodes) == 1){
      $node = array_shift($nodes);
      return $this->getImageUrl($node);
    }
    // We need a default image for a fallback.
    dpm('Could not find a newest featured node.');

  }

  /**
   * get an image URL for a news node
   */
  protected function getImageUrl($node) {
    $media_field = $node->get('su_news_banner')->target_id;
    $media_entity_load = Media::load($media_field);
    $uri = $media_entity_load->field_media_image->entity->getFileUri();
    $media_url = file_create_url($uri);
    return $media_url;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->getNewestFeatured();
    return [
      '#theme' => 'magazine_curtain_block',
      '#topics' => $this->getMagazineTopics(),
      '#curtain_content' => [
        'text' => $this->whatsMyTerm(),
        'media_url'=> $this->getNewestFeatured(),
      ],
      '#attached' => [
        'library' => [
          'engineering_magazine/engineering_magazine'
        ],
      ],
    ];
  }

}
