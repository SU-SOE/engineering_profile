<?php

namespace Drupal\Tests\engineering_profile_helper\Unit\EventSubscriber;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\engineering_profile_helper\EventSubscriber\LayoutBuilderRouteSubscriber;
use Drupal\layout_builder\Entity\LayoutBuilderEntityViewDisplay;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Tests the LayoutBuilderRouteSubscriber.
 *
 * @coversDefaultClass \Drupal\engineering_profile_helper\EventSubscriber\LayoutBuilderRouteSubscriber
 * @group engineering_profile_helper
 */
class LayoutBuilderRouteSubscriberTest extends UnitTestCase {

  /**
   * The entity type manager mock.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $entityTypeManager;

  /**
   * The route subscriber under test.
   *
   * @var \Drupal\engineering_profile_helper\EventSubscriber\LayoutBuilderRouteSubscriber
   */
  protected $routeSubscriber;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $this->routeSubscriber = new LayoutBuilderRouteSubscriber($this->entityTypeManager);
  }

  /**
   * Tests that routes are removed when Layout Builder is not enabled.
   *
   * @covers ::alterRoutes
   */
  public function testRoutesRemovedWhenLayoutBuilderDisabled() {
    // Create a mock entity view display that does not have Layout Builder enabled.
    $display = $this->createMock(LayoutBuilderEntityViewDisplay::class);
    $display->expects($this->once())
      ->method('isLayoutBuilderEnabled')
      ->willReturn(FALSE);

    // Create a mock storage that returns our display.
    $storage = $this->createMock(EntityStorageInterface::class);
    $storage->expects($this->once())
      ->method('loadByProperties')
      ->with(['targetEntityType' => 'user'])
      ->willReturn([$display]);

    $this->entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('entity_view_display')
      ->willReturn($storage);

    // Create a route collection with the Layout Builder routes.
    $collection = new RouteCollection();
    $collection->add('layout_builder.overrides.user.view', new Route('/user/{user}/layout'));
    $collection->add('layout_builder.overrides.user.discard_changes', new Route('/user/{user}/layout/discard-changes'));
    $collection->add('layout_builder.overrides.user.revert', new Route('/user/{user}/layout/revert'));
    $collection->add('some.other.route', new Route('/some/other/path'));

    // Call the protected alterRoutes method using reflection.
    $method = new \ReflectionMethod($this->routeSubscriber, 'alterRoutes');
    $method->setAccessible(TRUE);
    $method->invoke($this->routeSubscriber, $collection);

    // Assert that Layout Builder routes were removed.
    $this->assertNull($collection->get('layout_builder.overrides.user.view'));
    $this->assertNull($collection->get('layout_builder.overrides.user.discard_changes'));
    $this->assertNull($collection->get('layout_builder.overrides.user.revert'));
    
    // Assert that other routes remain.
    $this->assertNotNull($collection->get('some.other.route'));
  }

  /**
   * Tests that routes remain when Layout Builder is enabled.
   *
   * @covers ::alterRoutes
   */
  public function testRoutesRemainWhenLayoutBuilderEnabled() {
    // Create a mock entity view display that has Layout Builder enabled.
    $display = $this->createMock(LayoutBuilderEntityViewDisplay::class);
    $display->expects($this->once())
      ->method('isLayoutBuilderEnabled')
      ->willReturn(TRUE);

    // Create a mock storage that returns our display.
    $storage = $this->createMock(EntityStorageInterface::class);
    $storage->expects($this->once())
      ->method('loadByProperties')
      ->with(['targetEntityType' => 'user'])
      ->willReturn([$display]);

    $this->entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('entity_view_display')
      ->willReturn($storage);

    // Create a route collection with the Layout Builder routes.
    $collection = new RouteCollection();
    $collection->add('layout_builder.overrides.user.view', new Route('/user/{user}/layout'));
    $collection->add('layout_builder.overrides.user.discard_changes', new Route('/user/{user}/layout/discard-changes'));
    $collection->add('layout_builder.overrides.user.revert', new Route('/user/{user}/layout/revert'));

    // Call the protected alterRoutes method using reflection.
    $method = new \ReflectionMethod($this->routeSubscriber, 'alterRoutes');
    $method->setAccessible(TRUE);
    $method->invoke($this->routeSubscriber, $collection);

    // Assert that all routes remain.
    $this->assertNotNull($collection->get('layout_builder.overrides.user.view'));
    $this->assertNotNull($collection->get('layout_builder.overrides.user.discard_changes'));
    $this->assertNotNull($collection->get('layout_builder.overrides.user.revert'));
  }

  /**
   * Tests that routes remain when at least one display has Layout Builder enabled.
   *
   * @covers ::alterRoutes
   */
  public function testRoutesRemainWhenOneDisplayHasLayoutBuilder() {
    // Create mock displays - one with Layout Builder disabled, one enabled.
    $display1 = $this->createMock(LayoutBuilderEntityViewDisplay::class);
    $display1->expects($this->once())
      ->method('isLayoutBuilderEnabled')
      ->willReturn(FALSE);

    $display2 = $this->createMock(LayoutBuilderEntityViewDisplay::class);
    $display2->expects($this->once())
      ->method('isLayoutBuilderEnabled')
      ->willReturn(TRUE);

    // Create a mock storage that returns both displays.
    $storage = $this->createMock(EntityStorageInterface::class);
    $storage->expects($this->once())
      ->method('loadByProperties')
      ->with(['targetEntityType' => 'user'])
      ->willReturn([$display1, $display2]);

    $this->entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('entity_view_display')
      ->willReturn($storage);

    // Create a route collection with the Layout Builder routes.
    $collection = new RouteCollection();
    $collection->add('layout_builder.overrides.user.view', new Route('/user/{user}/layout'));
    $collection->add('layout_builder.overrides.user.discard_changes', new Route('/user/{user}/layout/discard-changes'));
    $collection->add('layout_builder.overrides.user.revert', new Route('/user/{user}/layout/revert'));

    // Call the protected alterRoutes method using reflection.
    $method = new \ReflectionMethod($this->routeSubscriber, 'alterRoutes');
    $method->setAccessible(TRUE);
    $method->invoke($this->routeSubscriber, $collection);

    // Assert that all routes remain since one display has Layout Builder enabled.
    $this->assertNotNull($collection->get('layout_builder.overrides.user.view'));
    $this->assertNotNull($collection->get('layout_builder.overrides.user.discard_changes'));
    $this->assertNotNull($collection->get('layout_builder.overrides.user.revert'));
  }

  /**
   * Tests the event subscriber priority.
   *
   * @covers ::getSubscribedEvents
   */
  public function testGetSubscribedEvents() {
    $events = LayoutBuilderRouteSubscriber::getSubscribedEvents();
    
    // Assert that the subscriber listens to the correct event.
    $this->assertArrayHasKey('routing.route_alter', $events);
    
    // Assert that the priority is set to run after Layout Builder's route subscriber (-110).
    $this->assertEquals(['onAlterRoutes', -115], $events['routing.route_alter']);
  }

  /**
   * Tests behavior when no user displays exist.
   *
   * @covers ::alterRoutes
   */
  public function testNoUserDisplays() {
    // Create a mock storage that returns no displays.
    $storage = $this->createMock(EntityStorageInterface::class);
    $storage->expects($this->once())
      ->method('loadByProperties')
      ->with(['targetEntityType' => 'user'])
      ->willReturn([]);

    $this->entityTypeManager->expects($this->once())
      ->method('getStorage')
      ->with('entity_view_display')
      ->willReturn($storage);

    // Create a route collection with the Layout Builder routes.
    $collection = new RouteCollection();
    $collection->add('layout_builder.overrides.user.view', new Route('/user/{user}/layout'));
    $collection->add('layout_builder.overrides.user.discard_changes', new Route('/user/{user}/layout/discard-changes'));
    $collection->add('layout_builder.overrides.user.revert', new Route('/user/{user}/layout/revert'));

    // Call the protected alterRoutes method using reflection.
    $method = new \ReflectionMethod($this->routeSubscriber, 'alterRoutes');
    $method->setAccessible(TRUE);
    $method->invoke($this->routeSubscriber, $collection);

    // Assert that routes were removed when no displays exist.
    $this->assertNull($collection->get('layout_builder.overrides.user.view'));
    $this->assertNull($collection->get('layout_builder.overrides.user.discard_changes'));
    $this->assertNull($collection->get('layout_builder.overrides.user.revert'));
  }

}