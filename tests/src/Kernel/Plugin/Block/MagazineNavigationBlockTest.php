<?php

namespace Drupal\Tests\engineering_profile\Unit\Plugin\Block;

use Drupal\Core\Form\FormState;
use Drupal\Core\Session\AccountInterface;
use Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\taxonomy\Tests\TaxonomyTestTrait;
use Drupal\taxonomy\Entity\Term;
use Drupal\path\Plugin\Field\FieldType\PathItem;

/**
 * Class SignupBlockTest.
 *
 * @group stanford_news
 * @coversDefaultClass \Drupal\stanford_news\Plugin\Block\SignupBlock
 */
class MagazineNavigationBlockTest extends UnitTestCase {


  /**
   * @var \Drupal\stanford_news\Plugin\Block\MagazineNavigationBlock
   */
  protected $blockObject;

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
      "label" => "Newsletter Signup",
      "label_display" => "visible",
    ];


    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());
    \Drupal::setContainer($container);

    $field = $this->getMockBuilder(PathItem::class)
        ->disableOriginalConstructor()
        ->getMock();

    $field->expects($this->any())
        ->method('get')
        ->will($this->returnValue('/mock/path'));

    $taxonomy_term = $this->getMockBuilder(\Drupal\taxonomy\Entity\Term::class)
    ->disableOriginalConstructor()
    ->getMock();
    $taxonomy_term->expects($this->any())
        ->method('getName')
        ->will($this->returnValue('Mock Term'));
    $taxonomy_term->expects($this->any())
        ->method('get')
        ->will($this->returnValue($field));



    //'name' => $term->getName(),
    //    'path' => $term->get('path')->alias,

    //$taxonomy_term->alias = '/mock/path';
    //$taxonomy_term->name = 'Mock term';

    $storage = $this->getMockBuilder(\Drupal\Core\Entity\EntityStorageInterface::class)->getMock();
    $storage->expects($this->any())
        ->method('loadByProperties')
        ->will($this->returnValue([ 0 => $taxonomy_term]));

    $entityManager = $this->getMockBuilder(EntityTypeManager::class)
        ->disableOriginalConstructor()
        ->getMock();


    $entityManager->expects($this->any())
        ->method('getStorage')
        ->will($this->returnValue($storage));

    $container->set('entity.type_manager', $entityManager);
    $this->blockObject = new MagazineNavigationBlock($config, '', ["provider" => "engineering_magazine"], $entityManager);
  }

  public function testBuild() {
    $build = $this->blockObject->build();
    $this->assertArrayEquals([
      '#theme' => 'magazine_navigation_block',
      '#attached' => [
          'library' => [
            0 => 'engineering_magazine/engineering_magazine'
          ],
      ],
      '#topics' => [
          [
              'name' => 'Mock Term',
              'path' => null,
          ],
      ],
    ], $build);
  }

  public function testAccess() {
    $account = $this->createMock(AccountInterface::class);
    $this->assertTrue($this->blockObject->access($account));
  }

}
