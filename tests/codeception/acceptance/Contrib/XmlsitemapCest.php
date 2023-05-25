<?php

/**
 * Test that engineering_profile_xmlsitemap_link_alter is working.
 */
class XmlsitemapCest {

  public function _before(AcceptanceTester $I){
    $drush_response = $I->runDrush('pm-list --filter=name=stanford_ssp --format=json');
    $drush_response = json_decode($drush_response, TRUE);
    $saml_enabled = $drush_response['stanford_ssp']['status'] == 'Enabled';
    if ($saml_enabled) {
      $I->runDrush('pm-uninstall simplesamlphp_auth -y');
    }
  }

  /**
   * Test that xmlsitemap is having 403 and 404 pages removed.
   */
  public function testSoeSitemapLinkAlter(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/config/search/xmlsitemap/rebuild");
    $I->click('Save configuration');

    $I->amOnPage("/sitemap.xml");
    $host = \Drupal::request()->getSchemeAndHttpHost();
    /** @var \Drupal\path_alias\AliasManagerInterface $alias_manager */
    $alias_manager = \Drupal::service('path_alias.manager');

    $engineering_profile_403_page = \Drupal::config('system.site')->get('page.403');
    $alias_403 = $alias_manager->getAliasByPath($engineering_profile_403_page);
    $engineering_profile_404_page = \Drupal::config('system.site')->get('page.404');
    $alias_404 = $alias_manager->getAliasByPath($engineering_profile_404_page);
    $I->dontSeeLink($host . $alias_403);
    $I->dontSeeLink($host . $alias_404);
  }

}
