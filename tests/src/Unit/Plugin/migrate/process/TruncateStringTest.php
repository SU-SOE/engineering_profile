<?php

namespace Drupal\Tests\stanford_migrate\Unit\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Row;
use Drupal\engineering_profile_helper\Plugin\migrate\process\TruncateString;
use Drupal\Tests\UnitTestCase;

/**
 * Class TruncateStringTest.
 *
 * @group engineering_profile_helper
 * @coversDefaultClass Drupal\engineering_profile_helper\Plugin\migrate\process\TruncateString
 */
class TruncateStringTest extends UnitTestCase {

  /**
   * Test the transform returns an expected hash value.
   */
  public function testTranform() {
    $configuration = [
      'entity_type' => 'type',
      'value_key' => 'key',
      'count' => 5,
    ];
    $definition = [];
    $plugin = new TruncateString($configuration, 'entity_generate', $definition);

    $migrate_executable = $this->createMock(MigrateExecutable::class);
    $row = $this->createMock(Row::class);

    $transformed = $plugin->transform('TestString', $migrate_executable, $row, 'field_stuff');
    $this->assertEquals('TestS', $transformed);


  }

}
