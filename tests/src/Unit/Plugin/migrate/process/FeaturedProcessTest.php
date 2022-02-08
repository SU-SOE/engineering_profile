<?php

namespace Drupal\Tests\stanford_migrate\Unit\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Row;
use Drupal\engineering_profile_helper\Plugin\migrate\process\FeaturedProcess;
use Drupal\Tests\UnitTestCase;

/**
 * Class FeaturedProcessTest.
 *
 * @group engineering_profile_helper
 * @coversDefaultClass Drupal\engineering_profile_helper\Plugin\migrate\process\FeaturedProcess
 */
class FeaturedProcessTest extends UnitTestCase {

  /**
   * Test the transform returns an expected hash value.
   */
  public function testTranform() {
    $configuration = [
      'entity_type' => 'type',
      'value_key' => 'key',
    ];
    $definition = [];
    $plugin = new FeaturedProcess($configuration, 'entity_generate', $definition);

    $migrate_executable = $this->createMock(MigrateExecutable::class);
    $row = $this->createMock(Row::class);

    $transformed = $plugin->transform('Featured', $migrate_executable, $row, 'field_stuff');
    $this->assertEquals(TRUE, $transformed);
    $transformed = $plugin->transform('Yes', $migrate_executable, $row, 'field_stuff');
    $this->assertEquals(TRUE, $transformed);
    $transformed = $plugin->transform('Anything Else', $migrate_executable, $row, 'field_stuff');
    $this->assertEquals(FALSE, $transformed);

  }

}
