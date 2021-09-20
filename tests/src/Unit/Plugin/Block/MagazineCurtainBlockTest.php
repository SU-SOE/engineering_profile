<?php

namespace Drupal\Tests\engineering_profile\Unit\Plugin\Block;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\engineering_magazine\Plugin\Block\MagazineCurtainBlock;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\path\Plugin\Field\FieldType\PathItem;
use Drupal\Core\Entity\Query\Sql\Query;
use Drupal\media\Entity\Media;

/**
 * Class SignupBlockTest.
 *
 * @group stanford_news
 * @coversDefaultClass \Drupal\stanford_news\Plugin\Block\SignupBlock
 */
class MagazineCurtainBlockTest extends MagazineTestBase {


  /**
   * @var \Drupal\stanford_news\Plugin\Block\MagazineCurtainBlock
   */
  protected $blockObject;

  /**
   * @var
   */
  protected $path_field;


  protected $media;

  /**
   * @var
   */
  protected static $modules = [
    'system',
    'engineering_magazine',
    'node',
    'taxonomy',
    'field',
    'user',
    'media',
    'file',
  ];

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->storage->method('loadMultiple')
      ->willReturnOnConsecutiveCalls(
        [0 => $this->node], [0 => $this->taxonomyTerm],
      );

    $config = [
      "id" => "magazine_curtain_block",
      "label" => "Magazine Curtain",
      "label_display" => "visible",
    ];

    $this->media = $this->getMockBuilder(Media::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->media->method('load')
      ->willReturn($this->getMockMediaImage());

    $this->blockObject = new MagazineCurtainBlock($config, '', ["provider" => "engineering_magazine"], $this->entityManager);
  }

  public function loadByPropertiesCallback() {
    $taxonomy_term = $this->getMockBuilder(Term::class)
      ->disableOriginalConstructor()
      ->getMock();
    $taxonomy_term->setName('Mock Term');
    $taxonomy_term->method('get')
      ->with('path')
      ->willReturn($this->path_field);
    return [ 0 => $taxonomy_term];
  }


  /**
   *
   */
  public function getPathCallback() {
    return '/magazine';
  }

  /**
   *
   */

  public function getQueryCallback() {
    $this->path_field = $this->getMockBuilder(PathItem::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->path_field->alias = '/foo/bar';

    $query = $this->getMockBuilder(Query::class)
      ->disableOriginalConstructor()
      ->getMock();
    $query->method('condition')->will($this->returnValue($query));
    $query->method('sort')->will($this->returnValue($query));
    $query->method('pager')->will($this->returnValue($query));
    $query->method('execute')->will($this->returnValue([0 => $this->path_field]));
    return $query;
  }

  /**
   *
   */

  public function getMockMediaImage() {

    $entity = new \stdClass();
    $entity->getFileUri = function () {
      return 'public://xxx/something.jpg';
    };

    $mock_path = new \stdClass();
    $mock_path->alias = '/mock/path';

    $mock_media_entity = new \stdClass();
    $mock_media_entity->entity = $entity;
    $mock_media_entity->path = $mock_path;
    return $mock_media_entity;
  }

  /**
   *
   */
  public function testBuild() {
    $build = $this->blockObject->build();
    var_dump($build);
    $this->assertArrayEquals([
      '#attached' => [
        'library' => [
          0 => 'engineering_magazine/engineering_magazine'
        ],
      ],
      '#theme' => 'magazine_navigation_block',
      '#theme' => 'magazine_curtain_block',
      '#topics' => [
          0 => [
            'name' => 'Mock Term',
            'path' => null,
            ],
          ],
     '#curtain_content' => [
        'media_url' => '#',
        'issue_number' => 'Mock Term',
        'issue_url' => null,
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
