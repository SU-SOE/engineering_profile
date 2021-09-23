<?php

/**
 * Test for custom block types.
 *
 * @group block
 */
class MagazineBlocksCest {

  /**
   * Site managers should be able to edit custom blocks.
   */
  public function testMagazineNavigationBlock(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/magazine');
    $I->canSee('Research & Ideas');
  }

  public function testMagazineCurtainBlock(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/magazine');
    $I->canSee('Stanford Engineering Magazine');
    $I->canSee('Scroll to explore the latest news');
  }



}
