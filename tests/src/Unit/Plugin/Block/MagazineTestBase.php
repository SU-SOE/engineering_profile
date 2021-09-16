<?php

namespace Drupal\Tests\engineering_profile\Unit\Plugin\Block;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\taxonomy\Entity\Term;
use Drupal\path\Plugin\Field\FieldType\PathItem;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\node\Entity\Node;

/**
 *
 */
abstract class MagazineTestBase extends UnitTestCase {

  protected $blockObject;

  protected $container;

  protected $storage;

  protected $entityManager;

  protected $pathManager;

  protected $logger;

  protected $entityTypeRepository;

  /**
   * @var Drupal\taxonomy\Entity\Term
   *  Taxonomy Term
   */
  protected $taxonomyTerm;

  /**
   * @var Drupal\path\Plugin\Field\FieldType\PathItem
   *  A path field
   */
  protected $field;

  protected $node;

  /**
   *
   */
  protected function setUp(): void {
    parent::setUp();

    $this->field = $this->getMockBuilder(PathItem::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->field->method('get')
      ->will($this->returnValue('/mock/path'));

    $this->taxonomyTerm = $this->getMockBuilder(Term::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->taxonomyTerm->method('getName')
      ->will($this->returnValue('Mock Term'));
    $this->taxonomyTerm->method('get')
      ->will($this->returnValue($this->field));

    $this->node = $this->getMockBuilder(Node::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->node->method('get')
      ->will($this->returnValue($this->getMockedNode()));

    $this->container = new ContainerBuilder();
    $this->container->set('string_translation', $this->getStringTranslationStub());

    $this->storage = $this->getMockBuilder(EntityStorageInterface::class)
      ->getMock();
    $this->storage->method('loadByProperties')
      ->will($this->returnCallback([$this, 'loadByPropertiesCallback']));
    $this->storage->method('load')
      ->will($this->returnCallback([$this, 'loadCallback']));
    $this->storage->method('getQuery')
      ->will($this->returnCallback([$this, 'getQueryCallback']));
      //->will($this->returnCallback([$this, 'loadMultipleCallback']));

    $this->entityManager = $this->getMockBuilder(EntityTypeManagerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->entityManager->method('getStorage')
      ->will($this->returnValue($this->storage));

    $this->pathManager = $this->getMockBuilder(CurrentPathStack::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->pathManager->method('getPath')
      ->will($this->returnCallback([$this, 'getPathCallback']));

    $this->entityTypeRepository = $this->getMockBuilder(EntityTypeRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->logger = $this->getMockBuilder(LoggerChannelFactory::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->logger->method('get')
      ->will($this->returnCallback([$this, 'getLoggerCallback']));


    $this->container->set('entity.type_manager', $this->entityManager);
    $this->container->set('entity_type.manager', $this->entityManager);
    $this->container->set('path.current', $this->pathManager);
    $this->container->set('entity_type.repository', $this->entityTypeRepository);
    $this->container->set('entity.type_manager', $this->entityManager);
    $this->container->set('logger.factory', $this->logger);

    \Drupal::setContainer($this->container);

    /**
     * we need to mock file_create_url, since we're not fully booted in drupal.
     * see http://www.drupal.org/project/drupal/issues/2669074
     */





  }

  /**
   *
   */
  public function loadByPropertiesCallback() {
    return;
  }

  /**
   *
   */
  public function loadCallback() {
    return;
  }

  /**
   *
   */
  public function getQueryCallback() {
    return;
  }

  /**
   *
   */
  public function loadMultipleCallback() {
    return;
  }

  /**
   *
   */
  public function getPathCallback() {
    return;
  }

  /**
   *
   */

  public function getMockedNode() {

    $field_media_image = new \stdClass();
    $field_media_image->entity = $this->getMockImageEntity();

    $node_field = new \stdClass();
    $node_field->field_media_image = $field_media_image;

    $node = new \stdClass();
    $node->entity = $node_field;

    return $node;
  }

  public function getMockImageEntity() {
    return new class {
      public function getFileUri() {
        return 'public://xxx/something.jpg';
      }
    };
  }

  public function getLoggerCallback() {
    return new class{
      public function error() {
        return true;
      }
    };
  }

}
