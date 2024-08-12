<?php

namespace Drupal\Tests\engineering_profile\Kernel\Plugin\Block;


use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock;
use Drupal\Tests\engineering_profile\Kernel\Plugin\Block\MagazineTestBase;


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
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $config = [
      "id" => "magazine_navigation_block",
      "label" => "Magazine Navigation Block",
      "label_display" => "visible",
    ];

    $this->blockObject = new MagazineNavigationBlock($config, '', ["provider" => "engineering_magazine"], \Drupal::entityTypeManager());
  }

  /**
   *
   * @covers \Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock
   * @covers ::create
   */
  public function testBuild() {
    $build = $this->blockObject->build();
    $this->assertEquals([
      '#theme' => 'magazine_navigation_block',
      '#attached' => [
        'library' => [
          0 => 'engineering_magazine/engineering_magazine',
        ],
      ],
      '#cache' => [
        'contexts' => [
          0 => 'url',
        ],
        'tags' => [
          0 => 'taxonomy_term_list',
        ],
      ],
      '#topics' => [],
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
