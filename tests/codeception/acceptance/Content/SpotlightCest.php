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
    $I->amOnPage("/spotlight");
    $I->see("Spotlights");
    $I->seeElement('h2.field-content');
    $I->seeElement('.su-card__contents');
    $I->seeElement(['css' => '.soe-link__button .button']);
    $I->click(["css" =>".soe-spotlight--cards--banner a.su-link"]);

  }

  public function testLandingPageForm(AcceptanceTester $I){
    $I->amOnPage("/spotlight");
//    $option = $I->grabTextFrom('#edit-tid option[value="All"]');
    $I->selectOption("#edit-tid" ,'Bioengineering');
    $I->click('Filter');
  }
}
