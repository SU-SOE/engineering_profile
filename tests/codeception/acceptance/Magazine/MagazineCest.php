<?php

/**
 * Test for custom block types.
 *
 * @group block
 */
class MagazineCest {

  /**
   * Test basic magazine functionality
   * Also covers landing pages for /magazine
   * and vocabularies for departments
   * topics, and article collections.
   */
  public function testMagazineStory(AcceptanceTester $I) {

    $magazineStory = $this->createMagazineNode($I, 'Magazine Test');

    $I->logInWithRole('site_manager');

    /**
     * Test the landing page exists.
     */
    $I->amOnPage('/news');
    // This was removed.
    //$I->canSee('Research & Ideas');
    $I->canSee('Topics');
    // This was removed from the layout.
    //$I->canSee('Issues');
    $I->canSee('Test Magazine Topic');
    $I->canSee('Latest News');
    // This was removed from the layout.
    //$I->canSee('What are we working on?');
    $I->canSee($magazineStory->getTitle());

    /**
     * Test the node page exists.
     */
    $I->amOnPage($magazineStory->toUrl()->toString());
    $I->canSeeResponseCodeIs(200);
    /**
     * Test the pathauto generation
     */
    $I->seeCurrentUrlEquals('/news/magazine-test');
    /**
     * Test to make sure there's a related departments field.
     */
    //$I->canSee('Related Departments');
    //$I->canSee('Test Department');

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
    $I->seeCurrentUrlEquals('/news/topic/test-magazine-topic');
    $I->canSee('Test Magazine Topic');
    // $I->canSee($magazineStory->getTitle());
    /**
     * And that it links back to the correct page.
     */
    // $I->click($magazineStory->getTitle());
    // $I->seeCurrentUrlEquals($magazineStory->toUrl()->toString());

    /**
     * Check to make sure our issue path works.
     */
    // We have stopped using Issues.
    //$I->amOnPage('/magazine/issue');
    //$I->canSee('Stanford Engineering Magazine');
    //$I->canSee('Issue #9999');
    //$I->canSee($magazineStory->getTitle());
    /**
     * And that it links back to the correct page.
     */
    //$I->click($magazineStory->getTitle());
    //$I->seeCurrentUrlEquals($magazineStory->toUrl()->toString());

  }

  public function createMagazineNode(AcceptanceTester $I, string $name="Magazine Test Node") {
    $mag_topic = $this->createMagazineTopic($I);
    $mag_issue = $this->createMagazineIssue($I);
    $article_collection = $this->createArticleCollection($I);
    $department = $this->createDepartment($I);
    $node = $I->createEntity([
      'type' => 'stanford_news',
      'title' => $name,
      'su_magazine_story' => TRUE,
      'su_soe_department' => $department->id(),
      'su_magazine_issue' => $mag_issue->id(),
      'su_soe_mag_topics' => $mag_topic->id(),
      'su_soe_mag_collection' => $article_collection->id(),
      'su_mag_featured_value' => TRUE,
      'status' => 1,
    ]);
    $I->runDrush('cr');
    return $node;
  }

  public function createMagazineTopic(AcceptanceTester $I) {
    return $I->createEntity([
        'vid' => 'magazine_topics',
        'name' => 'Test Magazine Topic',
    ], 'taxonomy_term');
  }

  public function createMagazineIssue(AcceptanceTester $I) {
    return $I->createEntity([
        'vid' => 'magazine_issues',
        'name' => 'Issue #9999',
        'su_issue_number' => 9999,
    ], 'taxonomy_term');
  }

  public function createArticleCollection(AcceptanceTester $I) {
    return $I->createEntity([
        'vid' => 'article_collection',
        'name' => 'Test Article Collection',
    ], 'taxonomy_term');
  }

  public function createDepartment(AcceptanceTester $I) {
    return $I->createEntity([
        'vid' => 'department',
        'name' => 'Test Department',
    ], 'taxonomy_term');
  }

}
