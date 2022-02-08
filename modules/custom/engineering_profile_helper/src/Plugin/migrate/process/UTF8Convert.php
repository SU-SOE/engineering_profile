<?php

namespace Drupal\engineering_profile_helper\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'UTF8Convert' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "utf8convert"
 * )
 */
class UTF8Convert extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return mb_convert_encoding($value, "UTF-8");
  }

}
