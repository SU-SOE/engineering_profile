<?php

namespace Drupal\Tests\stanford_migrate\Unit\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Row;
use Drupal\engineering_profile_helper\Plugin\migrate\process\UTF8Convert;
use Drupal\Tests\UnitTestCase;

/**
 * Class UTF8ConvertTest.
 *
 * @group engineering_profile_helper
 * @coversDefaultClass Drupal\engineering_profile_helper\Plugin\migrate\process\UTF8Convert
 */
class UTF8ConvertTest extends UnitTestCase {

  /**
   * Test the transform returns an expected hash value.
   */
  public function testTranform() {
    $configuration = [
      'entity_type' => 'type',
      'value_key' => 'key',
    ];
    $definition = [];
    $plugin = new UTF8Convert($configuration, 'entity_generate', $definition);

    $migrate_executable = $this->createMock(MigrateExecutable::class);
    $row = $this->createMock(Row::class);

    $testString = iconv("UTF-8", "ASCII//TRANSLIT", "This is my test string. €");

    $transformed = $plugin->transform($testString, $migrate_executable, $row, 'field_stuff');
    $this->assertEquals("This is my test string. EUR", $transformed);
    $this->assertEquals(TRUE, mb_check_encoding($transformed, "UTF-8"));
  }

}
