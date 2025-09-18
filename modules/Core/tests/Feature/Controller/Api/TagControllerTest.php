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

namespace Modules\Core\Tests\Feature\Controller\Api;

use Modules\Core\Models\Tag;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_tags_endpoints(): void
    {
        $this->deleteJson('/api/tags/1')->assertUnauthorized();
        $this->postJson('/api/tags/type')->assertUnauthorized();
        $this->putJson('/api/tags/1')->assertUnauthorized();
    }

    public function test_unauthorized_user_cannot_access_tags_endpoints(): void
    {
        $this->asRegularUser()->signIn();

        $tag = Tag::factory()->create();

        $this->deleteJson('/api/tags/'.$tag->id)->assertForbidden();
        $this->postJson('/api/tags/type')->assertForbidden();
        $this->putJson('/api/tags/'.$tag->id)->assertForbidden();
    }

    public function test_tag_can_be_created(): void
    {
        $this->signIn();

        $data = ['name' => 'HOT LEAD', 'swatch_color' => '#333333'];

        $this->postJson('/api/tags/deals', $data)
            ->assertCreated()
            ->assertJson($data);
    }

    public function test_tag_can_be_updated(): void
    {
        $this->signIn();

        $tag = Tag::factory()->create();

        $data = ['name' => 'HOT LEAD', 'swatch_color' => '#333333', 'display_order' => 365];

        $this->putJson('/api/tags/'.$tag->id, $data)
            ->assertOk()
            ->assertJson($data);
    }

    public function test_on_tag_creation_it_sets_display_order_to_high_number_when_not_specified(): void
    {
        $this->signIn();

        $this->postJson('/api/tags/deals', ['name' => 'HOT LEAD', 'swatch_color' => '#333333'])
            ->assertCreated()
            ->assertJson(['display_order' => 1000]);
    }

    public function test_tag_requires_name(): void
    {
        $this->signIn();

        $this->postJson('/api/tags/deals', ['name' => ''])
            ->assertJsonValidationErrorFor('name');

        $tag = Tag::factory()->create();

        $this->putJson('/api/tags/'.$tag->id, ['name' => ''])
            ->assertJsonValidationErrorFor('name');
    }

    public function test_tag_requires_swatch_color(): void
    {
        $this->signIn();

        $this->postJson('/api/tags/deals', ['swatch_color' => ''])
            ->assertJsonValidationErrorFor('swatch_color');

        $tag = Tag::factory()->create();

        $this->putJson('/api/tags/'.$tag->id, ['swatch_color' => ''])
            ->assertJsonValidationErrorFor('swatch_color');
    }

    public function test_tag_can_be_deleted(): void
    {
        $this->signIn();

        $tag = Tag::factory()->create();

        $this->deleteJson('/api/tags/'.$tag->id)->assertNoContent();
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
