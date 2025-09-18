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

namespace Modules\Users\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\Team;
use Modules\Users\Models\User;
use Modules\Users\Models\UserInvitation;
use Tests\TestCase;

class UserInvitationAcceptControllerTest extends TestCase
{
    public function test_invitation_can_be_viewed(): void
    {
        $this->signIn();

        $invitation = UserInvitation::factory()->create();

        $this->get($invitation->link)->assertOk();
    }

    public function test_invitation_cannot_be_viewed_when_invalid_token_is_provided(): void
    {
        $this->signIn();

        UserInvitation::factory()->create();

        $this->get(route('invitation.show', ['token' => 'dummy']))->assertNotFound();
    }

    public function test_invitation_can_expire(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->travel(config('users.invitation.expires_after') + 1)->days();

        $this->get($invitation->link)->assertNotFound();
        $this->postJson($invitation->link)->assertNotFound();
    }

    public function test_invitation_can_be_accepted(): void
    {
        $role = $this->createRole();
        $teams = Team::factory(2)->create();

        $invitation = UserInvitation::factory()->create([
            'roles' => [$role->id],
            'teams' => $teams->modelKeys(),
        ]);

        $this->postJson($invitation->link, [
            'name' => 'Dummy Name',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => 'Europe/Berlin',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
        ])->assertSuccessful();

        $user = User::orderByDesc('id')->first();
        $this->assertNotNull($user);
        $this->assertEquals($user->email, $invitation->email);
        $this->assertEquals($user->super_admin, $invitation->super_admin);
        $this->assertEquals($user->access_api, $invitation->access_api);
        $this->assertEquals($user->roles->modelKeys(), $invitation->roles);
        $this->assertEquals($user->teams->modelKeys(), $invitation->teams);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseMissing('user_invitations', [
            'id' => $invitation->id,
        ]);
    }

    public function test_it_does_not_throw_errors_when_team_is_deleted_before_invitation_is_accepted(): void
    {
        $teams = Team::factory(2)->create();

        $invitation = UserInvitation::factory()->create([
            'teams' => $teams->modelKeys(),
        ]);

        $teams->first()->delete();

        $this->postJson($invitation->link, [
            'name' => 'Dummy Name',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => 'Europe/Berlin',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
        ])->assertSuccessful();

        $user = User::orderByDesc('id')->first();

        $this->assertDatabaseHas('team_user', [
            'user_id' => $user->id,
            'team_id' => $teams[1]->id,
        ]);

        $this->assertCount(1, $user->teams);
    }

    public function test_invitation_requires_name(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'name' => '',
        ])->assertJsonValidationErrors('name');
    }

    public function test_invitation_requires_unique_email(): void
    {
        $invitation = UserInvitation::factory()->create();
        $user = $this->createUser();

        $this->postJson($invitation->link, [
            'email' => $user->email,
        ])->assertJsonValidationErrors('email');
    }

    public function test_invitation_requires_password(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'password' => '',
        ])->assertJsonValidationErrors('password');
    }

    public function test_invitation_requires_confirmed_password(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'password' => 'password',
        ])->assertJsonValidationErrors('password');
    }

    public function test_invitation_requires_timezone(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'timezone' => '',
        ])->assertJsonValidationErrors('timezone');

        $this->postJson($invitation->link, [
            'timezone' => 'invalid-timezone',
        ])->assertJsonValidationErrors('timezone');
    }

    public function test_invitation_requires_date_format(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'date_format' => '',
        ])->assertJsonValidationErrors('date_format');

        $this->postJson($invitation->link, [
            'date_format' => 'invalid-date-format',
        ])->assertJsonValidationErrors('date_format');
    }

    public function test_invitation_requires_time_format(): void
    {
        $invitation = UserInvitation::factory()->create();

        $this->postJson($invitation->link, [
            'time_format' => '',
        ])->assertJsonValidationErrors('time_format');

        $this->postJson($invitation->link, [
            'time_format' => 'invalid-time-format',
        ])->assertJsonValidationErrors('time_format');
    }
}
