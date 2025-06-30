<?php

namespace Drupal\engineering_profile_helper;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\layout_builder\Entity\LayoutEntityDisplayInterface;

/**
 * Provides compatibility layer for Layout Builder during deployments.
 */
class LayoutBuilderCompatibility {

  /**
   * Safely check if a display has Layout Builder enabled.
   *
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The entity view display to check.
   *
   * @return bool
   *   TRUE if Layout Builder is enabled, FALSE otherwise.
   */
  public static function isLayoutBuilderEnabled(EntityViewDisplayInterface $display) {
    // First check if the display implements LayoutEntityDisplayInterface.
    if (!$display instanceof LayoutEntityDisplayInterface) {
      return FALSE;
    }

    // Now we can safely check if Layout Builder is enabled.
    // Use method_exists as an extra safety check.
    if (method_exists($display, 'isLayoutBuilderEnabled')) {
      return $display->isLayoutBuilderEnabled();
    }

    return FALSE;
  }

  /**
   * Get third party settings safely.
   *
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The entity view display.
   * @param string $module
   *   The module name.
   * @param string $key
   *   The setting key.
   * @param mixed $default
   *   The default value.
   *
   * @return mixed
   *   The setting value or default.
   */
  public static function getThirdPartySetting(EntityViewDisplayInterface $display, $module, $key, $default = NULL) {
    if (method_exists($display, 'getThirdPartySetting')) {
      return $display->getThirdPartySetting($module, $key, $default);
    }
    return $default;
  }

}