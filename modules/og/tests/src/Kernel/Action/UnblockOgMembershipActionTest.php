<?php

namespace Drupal\Tests\og\Kernel\Action;

use Drupal\og\OgMembershipInterface;

/**
 * Tests the UnblockOgMembership action plugin.
 *
 * @group og
 * @coversDefaultClass \Drupal\og\Plugin\Action\UnblockOgMembership
 */
class UnblockOgMembershipActionTest extends ActionTestBase {

  /**
   * {@inheritdoc}
   */
  protected $pluginId = 'og_membership_unblock_action';

  /**
   * Checks if the action can be performed correctly.
   *
   * @param string $membership
   *   The membership on which to perform the action.
   *
   * @covers ::execute
   * @dataProvider executeProvider
   */
  public function testExecute($membership = NULL) {
    $membership = $this->memberships[$membership];
    /** @var \Drupal\og\Plugin\Action\AddSingleOgMembershipRole $plugin */
    $plugin = $this->getPlugin();
    $plugin->execute($membership);

    $this->assertEquals(OgMembershipInterface::STATE_ACTIVE, $membership->getState());
  }

  /**
   * {@inheritdoc}
   */
  public function executeProvider() {
    return [
      ['blocked'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function accessProvider() {
    return [
      // Access should be provided if the membership is blocked and the user
      // executing the action is a privileged user.
      ['uid1', 'blocked'],
      ['administrator', 'blocked'],
      ['group_administrator', 'blocked'],
      ['group_moderator', 'blocked'],
      ['group_owner', 'blocked', TRUE],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function noAccessProvider() {
    return [
      // Access is denied to users that are not privileged, and if the
      // membership is not blocked.
      ['uid1', 'member'],
      ['uid1', 'pending'],
      ['uid1', 'group_administrator'],
      ['uid1', 'group_moderator'],
      ['administrator', 'member'],
      ['administrator', 'pending'],
      ['administrator', 'group_administrator'],
      ['administrator', 'group_moderator'],
      ['group_administrator', 'member'],
      ['group_administrator', 'pending'],
      ['group_administrator', 'group_administrator'],
      ['group_administrator', 'group_moderator'],
      ['group_moderator', 'member'],
      ['group_moderator', 'pending'],
      ['group_moderator', 'group_administrator'],
      ['group_moderator', 'group_moderator'],
      ['group_owner', 'member', TRUE],
      ['group_owner', 'pending', TRUE],
      ['group_owner', 'group_administrator', TRUE],
      ['group_owner', 'group_moderator', TRUE],
      ['anonymous', 'member'],
      ['anonymous', 'pending'],
      ['anonymous', 'blocked'],
      ['anonymous', 'group_administrator'],
      ['anonymous', 'group_moderator'],
      ['authenticated', 'member'],
      ['authenticated', 'pending'],
      ['authenticated', 'blocked'],
      ['authenticated', 'group_administrator'],
      ['authenticated', 'group_moderator'],
      ['member', 'member'],
      ['member', 'pending'],
      ['member', 'blocked'],
      ['member', 'group_administrator'],
      ['member', 'group_moderator'],
      ['pending', 'member'],
      ['pending', 'pending'],
      ['pending', 'blocked'],
      ['pending', 'group_administrator'],
      ['pending', 'group_moderator'],
      ['blocked', 'member'],
      ['blocked', 'pending'],
      ['blocked', 'blocked'],
      ['blocked', 'group_administrator'],
      ['blocked', 'group_moderator'],
      ['group_owner', 'member', FALSE],
      ['group_owner', 'pending', FALSE],
      ['group_owner', 'blocked', FALSE],
      ['group_owner', 'group_administrator', FALSE],
      ['group_owner', 'group_moderator', FALSE],
    ];
  }

}
