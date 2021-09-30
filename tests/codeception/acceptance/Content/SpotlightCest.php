<?php

class SpotlightCest
{
  /*    public function _before(AcceptanceTester $I)
      {
      }*/

  /**
   * Test that the view pages elements exists.
   */
  public function testLandingPagesElements(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 4',
    ]);
    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 5',
    ]);
    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 6',
    ]);

    $I->amOnPage("/spotlight");
    $I->see("Spotlights");
//    $I->canSeeNumberOfElements(".su-card", 3);
//    $I->seeElement('.su-card__contents');
//    $I->click(["css" =>".soe-spotlight--cards--banner a.su-link"]);
  }

  public function testLandingPageForm(AcceptanceTester $I){
    $I->amOnPage("/spotlight");
    $option = $I->grabTextFrom('#edit-tid option[value="All"]');
    $I->selectOption("#edit-tid" ,'Bioengineering');
    $I->click('Apply');
  }

  /**
   * Test that the vocabulary and terms exist.
   */
  public function testVocabularyTermsExists(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/taxonomy/manage/department/overview");
    $I->canSeeNumberOfElements(".term-id", 10);
  }

  /**
   * Test that only two of three new news nodes show up in the more news view
   * on the node page.
   */
  public function testRelatedSpotlight(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 1',
    ]);
    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 2',
    ]);
    $I->createEntity([
      'type' => 'spotlight',
      'title' => 'Test spotlight 3',
    ]);

    $I->amOnPage("/test-spotlight-2");
    $I->see("Related spotlights");
    $I->see("Test spotlight 1");
    $I->see("Test spotlight 3");
  }
}
