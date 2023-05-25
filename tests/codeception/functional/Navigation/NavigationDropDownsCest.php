<?php

use Faker\Factory;

/**
 * Test for the lockup settings.
 *
 * @group navigation
 * @group testme
 */
class NavigationDropDownsCest {

  /**
   * Faker service.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Cleanup after test.
   */
  public function __after(FunctionalTester $I) {
    \Drupal::entityTypeManager()
      ->getStorage('config_pages')
      ->load('stanford_basic_site_settings')
      ->set('su_site_dropdowns', NULL)
      ->save();
  }

  /**
   * Create some content and test the dropdown menu.
   */
  public function testDropdownMenus(FunctionalTester $I) {
    $I->logInWithRole('administrator');
    $I->wait(1);
    $I->amOnPage('/');
    $I->cantSeeElement('button', ['class' => 'su-nav-toggle']);

    $I->amOnPage('/admin/config/system/basic-site-settings');
    $I->checkOption('Use drop down menus');
    $I->click('Save');

    $node_title = Factory::create()->text(20);

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $node_title);
    $I->click('Menu settings');
    $I->checkOption('Provide a menu link');
    $I->fillField('Menu link title', $node_title);
    // The label on the menu parent changes in D9 vs D8
    $I->selectOption('Parent link', "-- $parent_menu_title");
    $I->waitForText("Change the weight of the links within the $parent_menu_title menu");
    $I->click('Save');
    $I->canSeeLink($node_title);

    $I->amOnPage('/');
    $I->seeElement('button.su-nav-toggle');
  }

}
