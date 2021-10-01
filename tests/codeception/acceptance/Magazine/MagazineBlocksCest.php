<?php

/**
 * Test for custom block types.
 *
 * @group block
 */
class MagazineBlocksCest {

  use MagazineTestTrait;
  /**
   * Site managers should be able to edit custom blocks.
   */
  public function testMagazineStory(AcceptanceTester $I) {

    $magazineStory = $this->createMagazineNode($I, 'Magazine Test');

    $I->logInWithRole('site_manager');
    /**
     * Test the landing page exists.
     */
    $I->amOnPage('/magazine');
    $I->canSee('Research & Ideas');
    $I->canSee('Test Magazine Topic');
    $I->canSee('Latest News');
    $I->canSee('What are we working on?');
    $I->canSee($magazineStory->getTitle());
    /**
     * Test the node page exists.
     */
    $I->amOnPage('/magazine/article/magazine-test');
    $I->canSeeResponseCodeIs(200);
    /**
     * Test to make sure there's a related departments field.
     */
    $I->canSee('Related Departments');
    $I->canSee('Test Department');
    /**
     * Test to make sure the department landing page exists.
     */
    $I->click('Test Department');
    $I->seeCurrentUrlEquals('/department/test-department');
    $I->canSee('Test Department');
    $I->canSee($magazineStory->getTitle());
    /**
     * Test to make sure the magazine topic exists.
     */
    $I->canSee('Test Magazine Topic');
    $I->click('Test Magazine Topic');
    /**
     * Test to ensure the topic landing page exists.
     */
    $I->seeCurrentUrlEquals('/magazine/test-magazine/topic');
    $I->canSee('Test Magazine Topic');
    $I->canSee($magazineStory->getTitle());
    /**
     * And that it links back to the correct page.
     */
    $I->click($magazineStory->getTitle());
    $I->seeCurrentUrlEquals('/magazine/article/magazine-test');

  }

  public function testMagazineCurtainBlock(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/magazine');
    $I->canSee('Stanford Engineering Magazine');
    $I->canSee('Explore the latest news');
  }

}
