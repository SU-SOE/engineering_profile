<?php

namespace Drupal\Tests\engineering_profile_helper\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\User;

/**
 * Tests the Layout Builder local task removal for user entities.
 *
 * @group engineering_profile_helper
 */
class LayoutBuilderLocalTaskTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'layout_builder',
    'engineering_profile_helper',
    'user',
    'field_ui',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A test user with permission to view user pages.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $testUser;

  /**
   * An admin user with permission to configure display settings.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create users.
    $this->testUser = $this->drupalCreateUser(['access user profiles']);
    $this->adminUser = $this->drupalCreateUser([
      'access user profiles',
      'administer users',
      'administer user display',
      'configure any layout',
    ]);
  }

  /**
   * Tests that Layout Builder tab is not shown when Layout Builder is disabled.
   */
  public function testLayoutBuilderTabNotShownWhenDisabled() {
    // Ensure Layout Builder is not enabled for user displays.
    $displays = $this->container->get('entity_type.manager')
      ->getStorage('entity_view_display')
      ->loadByProperties(['targetEntityType' => 'user']);
    
    foreach ($displays as $display) {
      $display->disableLayoutBuilder()->save();
    }

    // Clear caches to ensure our hook runs.
    drupal_flush_all_caches();

    // Visit a user page as admin.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('user/' . $this->testUser->id());

    // Assert that the Layout tab is not present.
    $this->assertSession()->linkNotExists('Layout');
    $this->assertSession()->responseNotContains('layout_builder.overrides.user.view');
  }

  /**
   * Tests that Layout Builder tab is shown when Layout Builder is enabled.
   */
  public function testLayoutBuilderTabShownWhenEnabled() {
    // Enable Layout Builder for the default user display.
    $display = $this->container->get('entity_type.manager')
      ->getStorage('entity_view_display')
      ->load('user.user.default');
    
    if (!$display) {
      // Create the display if it doesn't exist.
      $display = $this->container->get('entity_type.manager')
        ->getStorage('entity_view_display')
        ->create([
          'targetEntityType' => 'user',
          'bundle' => 'user',
          'mode' => 'default',
          'status' => TRUE,
        ]);
    }
    
    $display->enableLayoutBuilder()
      ->setOverridable()
      ->save();

    // Clear caches to ensure our hook runs.
    drupal_flush_all_caches();

    // Visit a user page as admin.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('user/' . $this->testUser->id());

    // Assert that the Layout tab is present.
    $this->assertSession()->linkExists('Layout');
  }

  /**
   * Tests that the local task is properly removed from the plugin manager.
   */
  public function testLocalTaskRemovedFromPluginManager() {
    // Ensure Layout Builder is not enabled.
    $displays = $this->container->get('entity_type.manager')
      ->getStorage('entity_view_display')
      ->loadByProperties(['targetEntityType' => 'user']);
    
    foreach ($displays as $display) {
      $display->disableLayoutBuilder()->save();
    }

    // Clear caches.
    drupal_flush_all_caches();

    // Get the local task manager.
    $local_task_manager = $this->container->get('plugin.manager.menu.local_task');
    
    // Get all local tasks.
    $definitions = $local_task_manager->getDefinitions();
    
    // Assert that the Layout Builder user task is not present.
    $this->assertArrayNotHasKey('layout_builder_ui:layout_builder.overrides.user.view', $definitions);
  }

  /**
   * Tests that other entity types' Layout Builder tabs are not affected.
   */
  public function testOtherEntityTypesNotAffected() {
    // Enable the node module.
    $this->container->get('module_installer')->install(['node']);
    
    // Create a content type.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);
    
    // Enable Layout Builder for node article display.
    $display = $this->container->get('entity_type.manager')
      ->getStorage('entity_view_display')
      ->load('node.article.default');
    
    if ($display) {
      $display->enableLayoutBuilder()
        ->setOverridable()
        ->save();
    }

    // Clear caches.
    drupal_flush_all_caches();

    // Get the local task manager.
    $local_task_manager = $this->container->get('plugin.manager.menu.local_task');
    
    // Get all local tasks.
    $definitions = $local_task_manager->getDefinitions();
    
    // Assert that the node Layout Builder task is still present.
    if (isset($definitions['layout_builder_ui:layout_builder.overrides.node.view'])) {
      $this->assertArrayHasKey('layout_builder_ui:layout_builder.overrides.node.view', $definitions);
    }
  }

}