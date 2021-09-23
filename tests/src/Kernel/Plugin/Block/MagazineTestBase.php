<?php

namespace Drupal\Tests\engineering_profile\Kernel\Plugin\Block;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

/**
 *
 */
abstract class MagazineTestBase extends KernelTestBase {

  /**
   * @var array $modules
   *  modules to install.
   */
  protected static $modules = [
    'system',
    'engineering_magazine',
    'node',
    'taxonomy',
    'field',
    'user',
    'media',
    'file',
    'block_content',
    'text',
    'image',
  ];

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('media');
    $this->installEntitySchema('block_content');
    $this->installSchema('node', 'node_access');
    $this->installConfig(['taxonomy', 'block_content']);

    NodeType::create(['type' => 'page', 'name' => 'page'])->save();

    Vocabulary::create(['name' => 'Magazine Issues', 'vid' => 'magazine_issues'])->save();

    Vocabulary::create(['name' => 'Magazine Topics', 'vid' => 'magazine_topics'])->save();

    $field_storage = FieldStorageConfig::create([
      'field_name' => 'su_issue_number',
      'entity_type' => 'taxonomy_term',
      'type' => 'integer',
      'settings' => [],
    ])->save();

    $issue_number_field = FieldConfig::create([
      'field_name' => 'su_issue_number',
      'entity_type' => 'taxonomy_term',
      'bundle' => 'magazine_issues',
      'label' => 'magazine issue',
      'settings' => [],
    ])->save();

    // $issue_number_field = $this->
    Term::create([
      'vid' => 'magazine_issues',
      'name' => 'issue 1',
      'su_issue_number' => 1,
    ])->save();

  }

}
