<?php

namespace Drupal\engineering_profile_helper\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'TruncateString' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "truncate_string"
 * )
 */
class TruncateString extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($this->configuration['count'])) {
      throw new MigrateException('count value is empty');
    }
    return substr($value, 0, $this->configuration['count']);
  }

}
