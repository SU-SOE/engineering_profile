<?php

namespace Drupal\Tests\engineering_profile\Kernel\Plugin\InstallTask;

use Drupal\KernelTests\KernelTestBase;
use Drupal\engineering_profile\Plugin\InstallTask\Users;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Class UsersTest.
 *
 * @coversDefaultClass \Drupal\engineering_profile\Plugin\InstallTask\Users
 */
class UsersTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'user',
  ];

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setInstallProfile('engineering_profile');

    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installSchema('system', ['key_value_expire', 'sequences']);
    Role::create(['label' => 'Owner', 'id' => "site_manager"])->save();
  }

  /**
   * The correct number of users should be created.
   */
  public function testUsers() {
    $user_plugin = Users::create($this->container, [], '', []);
    $install_state = [];
    $user_plugin->runTask($install_state);
    $this->assertCount(5, User::loadMultiple());
  }

}
