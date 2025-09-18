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

namespace Modules\Core\Tests\Feature\Controller\Api\Resource;

use Modules\Contacts\Models\Contact;
use Tests\TestCase;

class EmailSearchControllerTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_the_search_endpoints(): void
    {
        $this->getJson('/api/search/email-address')->assertUnauthorized();
    }

    public function test_it_returns_all_attributes_when_email_searching(): void
    {
        $this->signIn();
        Contact::factory()->create(['email' => 'john@example.com']);

        $this->getJson('/api/search/email-address?q=john@example.com&only=contacts')
            ->assertOk()
            ->assertJsonStructure([
                '0' => [
                    'data' => [
                        '0' => [
                            'id',
                            'address',
                            'name',
                            'path',
                            'resourceName',
                        ],
                    ],
                ],
            ]);
    }

    public function test_own_criteria_is_applied_when_email_searching(): void
    {
        $user = $this->asRegularUser()->withPermissionsTo('view own contacts')->signIn();
        $user1 = $this->createUser();

        Contact::factory()->for($user1)->create(['email' => 'example1@example.com']);
        $record = Contact::factory()->for($user)->create(['email' => 'example2@example.com']);

        $this->getJson('/api/search/email-address?q=example&only=contacts')
            ->assertJsonCount(1, '0.data')
            ->assertJsonPath('0.data.0.id', $record->id);
    }

    public function test_it_does_not_return_any_results_if_search_query_is_empty(): void
    {
        $this->signIn();

        Contact::factory()->create(['email' => 'john@example.com']);

        $this->getJson('/api/search/email-address?q=')
            ->assertJsonCount(0);
    }
}
