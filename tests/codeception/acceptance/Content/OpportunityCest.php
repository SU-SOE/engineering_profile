<?php

use Faker\Factory;

/**
 * Test opportunity content type.
 *
 * @group opportunity
 */
class OpportunityCest {

  /**
   * Faker.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test Constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

   /**
   * clear the trash directory before each test.
   * @param AcceptanceTester $I
   * @return void
   */
  public function _before(AcceptanceTester $I)
  {
    $trashDir = codecept_root_dir() . 'web/sites/default/files/php/trash';
    if (is_dir($trashDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($trashDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
    }
  }

  public function testContentAccess(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/node/add');
    $I->cantSee('Opportunity');
    $I->amOnPage('/admin/structure/taxonomy');
    $I->cantSee('Opportunity');
    $I->amOnPage('/user/logout');
    $I->click('Log out', 'form');

    $I->logInWithRole('contibutor');
    $I->amOnPage('/node/add');
    $I->cantSee('Opportunity');
    $I->amOnPage('/admin/structure/taxonomy');
    $I->cantSee('Opportunity');
    $I->amOnPage('/user/logout');
    $I->click('Log out', 'form');

    $user = $I->createUserWithRoles(['site_manager', 'opportunity_editor']);
    $I->logInAs($user->getAccountName());
    $I->amOnPage('/node/add');
    $I->canSee('Opportunity');
    $I->amOnPage('/admin/structure/taxonomy');
    $I->canSee('Opportunity');
    $I->amOnPage('/admin/structure/taxonomy/manage/opportunity_tag_filters/add');
    $I->canSeeInField('Name', '');
    $I->amOnPage('/admin/structure/taxonomy/manage/opportunity_sponsor/add');
    $I->canSeeInField('Name', '');
    $I->amOnPage('/admin/structure/taxonomy/manage/opportunity_type/add');
    $I->canSeeInField('Name', '');
  }

}
