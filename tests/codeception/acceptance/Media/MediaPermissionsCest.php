<?php

/**
 * Tests for various media access functionality.
 */
class MediaPermissionsCest {

  /**
   * Test admin perms
   */
  public function testAdminPerms(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage('/media/add/embeddable');
    $I->canSeeResponseCodeIs(200);
    $I->canSee('oEmbed URL');
    $I->canSee('Embed Code');
  }

  /**
   * Test site embedder perms
   */
  public function testSiteEmbedderPerms(AcceptanceTester $I) {
    $user = $I->createUserWithRoles(['soe_site_embedder','site_manager']);
    $I->logInAs($user->getUsername());
    $I->amOnPage('/media/add/embeddable');
    $I->canSeeResponseCodeIs(200);
    $I->canSee('oEmbed URL');
    $I->canSee('Embed Code');
  }

  /**
   * Test site manager perms
   */
  public function testSiteManagerPerms(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/media/add/embeddable');
    $I->canSeeResponseCodeIs(200);
    $I->canSee('oEmbed URL');
    $I->cantSee('Embed Code');
  }

  /**
   * Test site editor perms
   */
  public function testSiteEditorPerms(AcceptanceTester $I) {
    $I->logInWithRole('site_editor');
    $I->amOnPage('/media/add/embeddable');
    $I->canSeeResponseCodeIs(200);
    $I->canSee('oEmbed URL');
    $I->cantSee('Embed Code');
  }

  /**
   * Test contributor perms
   */
  public function testContributorPerms(AcceptanceTester $I) {
    $I->logInWithRole('contributor');
    $I->amOnPage('/media/add/embeddable');
    $I->canSeeResponseCodeIs(200);
    $I->canSee('oEmbed URL');
    $I->cantSee('Embed Code');
  }

}