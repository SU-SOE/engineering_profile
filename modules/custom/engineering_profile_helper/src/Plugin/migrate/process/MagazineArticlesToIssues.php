<?php

namespace Drupal\engineering_profile_helper\Plugin\migrate\process;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'MagazineArticlesToIssues' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "magazine_articles_to_issues"
 * )
 */
class MagazineArticlesToIssues extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The migration.
   *
   * @var \Drupal\migrate\Plugin\MigrationInterface
   */
  protected $migration;

  /**
   * The selection plugin.
   *
   * @var \Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginManagerInterface
   */
  protected $selectionPluginManager;

  /**
   * The destination type.
   *
   * @var string
   */
  protected $destinationEntityType;

  /**
   * The destination bundle.
   *
   * @var string|bool
   */
  protected $destinationBundleKey;

  /**
   * The lookup value's key.
   *
   * @var string
   */
  protected $lookupValueKey;

  /**
   * The lookup bundle's key.
   *
   * @var string
   */
  protected $lookupBundleKey;

  /**
   * The lookup bundle.
   *
   * @var string
   */
  protected $lookupBundle;

  /**
   * The lookup entity type.
   *
   * @var string
   */
  protected $lookupEntityType;

  /**
   * The destination property or field.
   *
   * @var string
   */
  protected $destinationProperty;

  /**
   * The access check flag.
   *
   * @var string
   */
  protected $accessCheck = TRUE;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition, MigrationInterface $migration = NULL) {
    $instance = new static(
      $configuration,
      $pluginId,
      $pluginDefinition
    );
    $instance->migration = $migration;
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityFieldManager = $container->get('entity_field.manager');
    $instance->selectionPluginManager = $container->get('plugin.manager.entity_reference_selection');
    $pluginIdParts = explode(':', $instance->migration->getDestinationPlugin()->getPluginId());
    $instance->destinationEntityType = empty($pluginIdParts[1]) ? NULL : $pluginIdParts[1];
    $instance->destinationBundleKey = $instance->destinationEntityType ? $instance->entityTypeManager->getDefinition($instance->destinationEntityType)->getKey('bundle') : NULL;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
      'title' => $value,
      'type' => 'stanford_news',
    ]);
    if (count($nodes) == 1) {
      return array_key_first($nodes);
    }
    return NULL;
  }

}
