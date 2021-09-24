<?php

class SpotlightFunctionsCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
  public function testLandingPageForm(AcceptanceTester $I){
    $I->amOnPage("/spotlight");
//    $I->selectOption("#edit-tid" ,'Bioengineering');
//    $I->click('Filter');
  }
}
