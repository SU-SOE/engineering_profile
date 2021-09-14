<?php

namespace Drupal\Tests\engineering_profile\Unit\Plugin\Block;


use Drupal\Core\Session\AccountInterface;
use Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock;


/**
 * Class MagazineNavigationBlockTest.
 *
 * @group engineering_magazine
 * @coversDefaultClass Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock
 */
class MagazineNavigationBlockTest extends MagazineTestBase {


  /**
   * @var \Drupal\stanford_news\Plugin\Block\MagazineNavigationBlock
   *  The block to test
   */
  protected $blockObject;


  /**
   * @var array
   *  Array of modules
   */
  protected static $modules = [
    'system',
    'engineering_profile_helper',
    'engineering_magazine',
    'node',
    'taxonomy',
    'field',
    'user',
    'text',
    'config_pages',
  ];

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $config = [
      "id" => "magazine_navigation_block",
      "label" => "Magazine Navigation Block",
      "label_display" => "visible",
    ];




    $this->blockObject = new MagazineNavigationBlock($config, '', ["provider" => "engineering_magazine"], $this->entityManager);
  }

  /**
   *
   */
  public function loadByPropertiesCallback() {
    return [0 => $this->taxonomyTerm];
  }

  /**
   *
   */
  public function testBuild() {
    $build = $this->blockObject->build();
    $this->assertArrayEquals([
      '#theme' => 'magazine_navigation_block',
      '#attached' => [
        'library' => [
          0 => 'engineering_magazine/engineering_magazine',
        ],
      ],
      '#topics' => [
          [
            'name' => 'Mock Term',
            'path' => NULL,
          ],
      ],
    ], $build);
  }

  /**
   *
   */
  public function testAccess() {
    $account = $this->createMock(AccountInterface::class);
    $this->assertTrue($this->blockObject->access($account));
  }

}
