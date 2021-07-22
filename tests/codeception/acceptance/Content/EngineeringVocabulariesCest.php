<?php

/**
 * Class EngineeringVocabulariesCest.
 *
 * @group content
 */
class EngineeringVocabulariesCest {

    public function testVocabulariesExist(AcceptanceTester $I) {
        $I->logInWithRole('administrator');
        $I->amOnPage('/admin/structure/taxonomy');
        $I->canSee('Department');
        $I->canSee('Magazine Topics');
        $I->canSee('Affiliation');
        $I->amOnPage('/admin/structure/taxonomy/manage/department/overview');
        $deparments_terms = [
            'Aeronautics and Astronautics',
            'Bioengineering',
            'Chemical Engineering',
            'Civil & Environmental Engineering',
            'Computer Science',
            'Electrical Engineering',
            'Institute for Computational & Mathematical Engineering',
            'Management Science and Engineering',
            'Materials Science and Engineering',
            'Mechanical Engineering',
        ];
        foreach ($deparments_terms as $term){
            $I->canSee($term);
        }
        $I->amOnPage('/admin/structure/taxonomy/manage/magazine_topics/overview');
        $magazine_terms = [
            'Artificial Intelligence',
            'Computation & Data',
            'Electronics & Networking',
            'Energy',
            'Environment',
            'Health',
            'Materials',
            'Security',
            'Technology & Society',
            'Transportation & Robotics',
        ];
        foreach ($magazine_terms as $term){
            $I->canSee($term);
        }

        $I->amOnPage('/admin/structure/taxonomy/manage/affiliation/overview');
        $affiliation_terms = [
            'Alumni',
            'Faculty',
            'Giving',
            'Office of the Dean',
            'Staff',
            'Students',
        ];
        foreach ($affiliation_terms as $term){
            $I->canSee($term);
        }
      }

}
