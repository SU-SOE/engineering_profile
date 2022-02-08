<?php

namespace Drupal\engineering_profile_helper\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'FeaturedProcess' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "featured_process"
 * )
 */
class FeaturedProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if ($value == 'Featured' || $value == 'Yes') {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
