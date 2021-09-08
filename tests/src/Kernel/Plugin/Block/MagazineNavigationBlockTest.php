<?php

namespace Drupal\Tests\engineering_profile\Unit\Plugin\Block;

use Drupal\Core\Form\FormState;
use Drupal\Core\Session\AccountInterface;
use Drupal\engineering_magazine\Plugin\Block\MagazineNavigationBlock;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeManager;

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


    $entityManager = $this->getMockBuilder(EntityTypeManager::class)
        ->disableOriginalConstructor()
        ->getMock();

    $container->set('entity.type_manager', $entityManager);
    $this->blockObject = new MagazineNavigationBlock($config, '', ["provider" => "engineering_magazine"], $entityManager);
  }

  public function testBuild() {
    $build = $this->blockObject->build();
    $this->assertArrayEquals([
      '#theme' => 'signup_block',
    ], $build);
  }

  public function testAccess() {
    $account = $this->createMock(AccountInterface::class);
    $this->assertTrue($this->blockObject->access($account));

    $config = $this->blockObject->getConfiguration();
    $config['form_action'] = NULL;
    $this->blockObject->setConfiguration($config);
    $this->assertFalse($this->blockObject->access($account));
  }



}
