<?php

namespace Drupal\Tests\engineering_profile\Kernel\Plugin\migrate\process;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Node\entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\engineering_profile_helper\Plugin\migrate\process\MagazineArticlesToIssues;

/**
 * Class MagazineArticlesToIssuesTest.
 *
 * @group engineering_magazine
 * @coversDefaultClass Drupal\engineering_profile_helper\Plugin\migrate\process\MagazineArticlesToIssues
 */
class MagazineArticlesToIssuesTest extends KernelTestBase {

  /**
   * @var Drupal\Node\entity\Node
   */
  protected $node;

  /**
   * @var \Drupal\migrate\Plugin\MigrateProcessInterface
   */
  protected $plugin;

  /**
   * @var \Drupal\migrate\Row
   */
  protected $row;

  /**
   * @var \Drupal\migrate\MigrateExecutable|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $migrateExecutable;


  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'node',
    'migrate',
    'user',
    'field',
    'engineering_profile_helper',
  ];

  /**
   *
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installSchema('node', 'node_access');

    $this->row = $this
      ->getMockBuilder(Row::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->migrateExecutable = $this
      ->getMockBuilder('Drupal\\migrate\\MigrateExecutable')
      ->disableOriginalConstructor()
      ->getMock();

    NodeType::create([
      'type' => 'stanford_news',
      'name' => 'stanford_news',
    ])->save();

    NodeType::create([
      'type' => 'page',
      'name' => 'page',
    ])->save();

    $this->node = Node::create([
      'title' => 'Migration Test Node',
      'type' => 'stanford_news',
    ]);
    $this->node->save();

    $config = [
      'id' => 'magazine_articles_to_issues',
    ];

    $this->plugin = new MagazineArticlesToIssues($config, 'magazine_articles_to_issues', []);
  }

  /**
   * @covers \Drupal\engineering_profile_helper\Plugin\migrate\process\MagazineArticlesToIssues
   * @covers ::transform
   */
  public function testTransform() {
    $definition = $this->getDefinition();
    $migration = \Drupal::service('plugin.manager.migration')->createStubMigration($definition);

    $executable = new MigrateExecutable($migration);
    $result = $executable->import();
    $this->assertSame(MigrationInterface::RESULT_COMPLETED, $result);
  }

  /**
   * Returns test migration definition.
   *
   * @return array
   */
  public function getDefinition() {
    return [
      'source' => [
        'plugin' => 'embedded_data',
        'data_rows' => [
            [
              'nid' => '55',
              'title' => 'Migration Test Node',
            ],
        ],
        'ids' => [
          'nid' => ['type' => 'string'],
        ],
      ],
      'process' => [
        'first' => [
          'plugin' => 'magazine_articles_to_issues',
          'source' => 'title',
        ],
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'page',
      ],
    ];
  }

}
