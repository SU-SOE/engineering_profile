<?php

namespace Drupal\Tests\engineering_profile\Kernel\Plugin\Block;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\engineering_magazine\Plugin\Block\MagazineCurtainBlock;
use Drupal\Tests\engineering_profile\Kernel\Plugin\Block\MagazineTestBase;

/**
 * Class MagazineCurtainBlockTest.
 *
 * @group engineering_magazine
 * @coversDefaultClass Drupal\engineering_magazine\Plugin\Block\MagazineCurtainBlock
 */
class MagazineCurtainBlockTest extends MagazineTestBase {


  /**
   * @var \Drupal\stanford_news\Plugin\Block\MagazineCurtainBlock
   */
  protected $blockObject;

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $config = [
      "id" => "magazine_curtain_block",
      "label" => "Magazine Curtain Block",
      "label_display" => "visible",
    ];

    $this->blockObject = new MagazineCurtainBlock($config, '', ["provider" => "engineering_magazine"], \Drupal::entityTypeManager());
  }

  /**
   *
   */
  public function testBuild() {
    $build = $this->blockObject->build();
    // var_dump($build);
    $this->assertEquals([
      '#attached' => [
        'library' => [
          0 => 'engineering_magazine/engineering_magazine',
        ],
      ],
      '#theme' => 'magazine_navigation_block',
      '#theme' => 'magazine_curtain_block',
      '#topics' => [],
      '#curtain_content' => [
        'media_url' => '#',
        'issue_number' => 'Default',
        'issue_url' => '/foo/bar',
      ],
    ], $build);
  }

  /**
   * Check to make sure it's accessible.
   */
  public function testAccess() {
    $account = $this->createMock(AccountInterface::class);
    $this->assertTrue($this->blockObject->access($account));
  }

}
