<?php

namespace Drupal\engineering_profile_helper\Commands;

use Drush\Commands\DrushCommands;
use Drupal\engineering_profile_helper\PathautoRegenerateService;

/**
 * Drush command file.
 */
class PathautoRegenerateCommands extends DrushCommands {

  /**
   * The pathauto regenerate service.
   *
   * @var \Drupal\engineering_profile_helper\PathautoRegenerateService
   */
  protected $pathautoRegenerateService;

  /**
   * PathautoRegenerateCommands constructor.
   *
   * @param \Drupal\engineering_profile_helper\PathautoRegenerateService $pathauto_regenerate_service
   *   The pathauto regenerate service.
   */
  public function __construct(PathautoRegenerateService $pathauto_regenerate_service) {
    $this->pathautoRegenerateService = $pathauto_regenerate_service;
  }

  /**
   * Regenerates pathauto paths for all nodes.
   *
   * @command pathauto:regenerate
   * @aliases pr
   * @description Regenerates all pathauto paths for nodes.
   */
  public function regenerate() {
    $this->pathautoRegenerateService->regenerateNodePaths();
    $this->logger()->success('Pathauto paths have been regenerated.');
  }
}
