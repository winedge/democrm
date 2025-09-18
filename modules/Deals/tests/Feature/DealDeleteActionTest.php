<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

namespace Modules\Deals\Tests\Feature;

use Modules\Core\Tests\ResourceTestCase;
use Modules\Users\Models\User;

class DealDeleteActionTest extends ResourceTestCase
{
    protected $resourceName = 'deals';

    public function test_deal_delete_action(): void
    {
        $this->signIn();

        $deals = $this->factory()->count(2)->create();

        $this->runAction('delete', $deals)->assertActionOk();
        $this->assertSoftDeleted('deals', ['id' => $deals->modelKeys()]);
    }

    public function test_unauthorized_user_cant_run_deal_delete_action(): void
    {
        $this->asRegularUser()->signIn();

        $deals = $this->factory()->for(User::factory())->count(2)->create();

        $this->runAction('delete', $deals)->assertActionUnauthorized();
        $this->assertDatabaseHas('deals', ['id' => $deals->modelKeys()]);
    }

    public function test_authorized_user_can_run_deal_delete_action(): void
    {
        $this->asRegularUser()->withPermissionsTo('delete any deal')->signIn();

        $deal = $this->factory()->for(User::factory())->create();

        $this->runAction('delete', $deal)->assertActionOk();
        $this->assertSoftDeleted('deals', ['id' => $deal->id]);
    }

    public function test_authorized_user_can_run_deal_delete_action_only_on_own_deals(): void
    {
        $signedInUser = $this->asRegularUser()->withPermissionsTo('delete own deals')->signIn();

        $dealForSignedIn = $this->factory()->for($signedInUser)->create();
        $otherdeal = $this->factory()->create();

        $this->runAction('delete', $otherdeal)->assertActionUnauthorized();
        $this->assertDatabaseHas('deals', ['id' => $otherdeal->id]);

        $this->runAction('delete', $dealForSignedIn);
        $this->assertSoftDeleted('deals', ['id' => $dealForSignedIn->id]);
    }

    public function test_authorized_user_can_bulk_delete_deals(): void
    {
        $this->asRegularUser()->withPermissionsTo([
            'delete any deal', 'bulk delete deals',
        ])->signIn();

        $deals = $this->factory()->for(User::factory())->count(2)->create();

        $this->runAction('delete', $deals);
        $this->assertSoftDeleted('deals', ['id' => $deals->modelKeys()]);
    }

    public function test_authorized_user_can_bulk_delete_only_own_deals(): void
    {
        $signedInUser = $this->asRegularUser()->withPermissionsTo([
            'delete own deals',
            'bulk delete deals',
        ])->signIn();

        $dealsForSignedInUser = $this->factory()->count(2)->for($signedInUser)->create();
        $otherdeal = $this->factory()->create();

        $this->runAction('delete', $dealsForSignedInUser->push($otherdeal))->assertActionOk();
        $this->assertDatabaseHas('deals', ['id' => $otherdeal->id]);
        $this->assertSoftDeleted('deals', ['id' => $dealsForSignedInUser->modelKeys()]);
    }

    public function test_unauthorized_user_cant_bulk_delete_deals(): void
    {
        $this->asRegularUser()->signIn();

        $deals = $this->factory()->count(2)->create();

        $this->runAction('delete', $deals)->assertActionUnauthorized();
        $this->assertDatabaseHas('deals', ['id' => $deals->modelKeys()]);
    }

    public function test_user_without_bulk_delete_permission_cannot_bulk_delete_deals(): void
    {
        $this->asRegularUser()->withPermissionsTo([
            'delete any deal',
            'delete own deals',
            'delete team deals',
        ])->signIn();

        $deals = $this->factory()->for(User::factory())->count(2)->create();

        $this->runAction('delete', $deals)->assertActionUnauthorized();
        $this->assertDatabaseHas('deals', ['id' => $deals->modelKeys()]);
    }
}
