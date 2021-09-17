<?php

/**
 *
 */
class SpotlightCest{

  /**
   * Test that the view pages exist.
   */
  public function testLandingPagesExist(AcceptanceTester $I) {
    $I->amOnPage("/spotlights");
    $I->see("Spotlights");
    $I->canSeeNumberOfElements(".su-card", 22);
    $I->seeLink('Load More');
    $I->click("a[href='?tid=All&tid_1=All&page=1']");
    $I->waitForAjaxToFinish();
    $I->canSeeNumberOfElements(".su-card", 43);
  }
}

