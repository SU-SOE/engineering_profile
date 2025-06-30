<?php

namespace Drupal\engineering_profile_helper\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\Routing\RouteCollection;

/**
 * Removes invalid Layout Builder routes for entities without Layout Builder enabled.
 */
class LayoutBuilderRouteSubscriber extends RouteSubscriberBase {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new LayoutBuilderRouteSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Check if Layout Builder is enabled for user entity displays.
    $user_displays = $this->entityTypeManager
      ->getStorage('entity_view_display')
      ->loadByProperties(['targetEntityType' => 'user']);

    $layout_builder_enabled = FALSE;
    foreach ($user_displays as $display) {
      if ($display->isLayoutBuilderEnabled()) {
        $layout_builder_enabled = TRUE;
        break;
      }
    }

    // If Layout Builder is not enabled for any user display, remove the route.
    if (!$layout_builder_enabled) {
      $collection->remove('layout_builder.overrides.user.view');
      $collection->remove('layout_builder.overrides.user.discard_changes');
      $collection->remove('layout_builder.overrides.user.revert');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    // Run after Layout Builder's route subscriber.
    $events = parent::getSubscribedEvents();
    $events[RoutingEvents::ALTER] = ['onAlterRoutes', -115];
    return $events;
  }

}