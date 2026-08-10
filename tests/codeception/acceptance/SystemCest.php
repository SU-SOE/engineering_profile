<?php

use Codeception\Attribute as CodeceptionAttribute;

/**
 * System tests.
 */
#[CodeceptionAttribute\Group('system')]
class SystemCest {

  /**
   * Test the site status report.
   */
  public function testSiteStatus(AcceptanceTester $I) {
    $I->runDrush('xmlsitemap:rebuild');
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/reports/status');
    $I->canSee('11.3', '.system-status-general-info');
    if ($I->grabMultiple('.system-status-counter--error')) {
      // Assert the specific error we expect rather than the total count. The
      // count is brittle: unrelated errors from contrib/upstream modules (e.g.
      // a table missing a primary key) would otherwise break this test.
      $I->canSee('Access to update.php ', '.system-status-report__status-icon--error');
    }

    if (\Drupal::moduleHandler()->moduleExists('chosen')) {
      $I->canSee('Chosen Javascript file');
      $I->cantSee('Chosen JavaScript file', '.system-status-report__status-icon--error');
    }
  }

  /**
   * Test the login page.
   */
  #[CodeceptionAttribute\Group('403-redirect')]
  public function testLoginPage(AcceptanceTester $I) {
    $I->amOnPage('/admin/config');
    $I->canSeeInCurrentUrl('/user/login');
    $I->canSeeNumberOfElements('h1', 1);
  }

  /**
   * User json api should not exist.
   */
  #[CodeceptionAttribute\Group('jsonapi')]
  private function testJsonApiUser(AcceptanceTester $I){
    $I->amOnPage('/jsonapi/user/user');
    $I->canSeeResponseCodeIs(404);
  }

}
