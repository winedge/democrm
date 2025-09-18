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

use Modules\Core\Models\ModelVisibilityGroup;
use Modules\Core\Tests\ResourceTestCase;
use Modules\Deals\Models\Pipeline;
use Modules\Deals\Models\Stage;
use Modules\Users\Models\Team;
use Modules\Users\Models\User;

class PipelineResourceTest extends ResourceTestCase
{
    protected $resourceName = 'pipelines';

    public function test_user_can_create_resource_record(): void
    {
        $this->signIn();

        $this->postJson($this->createEndpoint(), [
            'name' => 'Dummy Pipeline',
            'stages' => [
                [
                    'name' => 'Stage Name',
                    'win_probability' => 100,
                ],
            ],
        ])
            ->assertCreated()
            ->assertJson(['name' => 'Dummy Pipeline'])
            ->assertJsonCount(1, 'stages');
    }

    public function test_user_can_create_resource_record_without_providing_stages(): void
    {
        $this->signIn();

        $this->postJson($this->createEndpoint(), [
            'name' => 'Dummy Pipeline',
        ])
            ->assertCreated()
            ->assertJson(['name' => 'Dummy Pipeline'])
            ->assertJsonCount(0, 'stages');
    }

    public function test_unauthorized_user_cannot_create_resource_record(): void
    {
        $this->asRegularUser()->signIn();

        $this->postJson($this->createEndpoint(), ['name' => 'Dummy Pipeline'])->assertForbidden();
    }

    public function test_user_can_update_resource_record(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();
        $stage = Stage::factory()->for($pipeline)->create(['win_probability' => 50]);

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Changed',
            'stages' => [
                [
                    'name' => 'Stage Name',
                    'win_probability' => 100,
                    'display_order' => 1,
                ],
                [
                    'id' => $stage->id,
                    'name' => 'Changed Name',
                    'win_probability' => 25,
                    'display_order' => 2,
                ],
            ],
        ])->assertOk()
            ->assertJsonCount(2, 'stages')
            ->assertJson([
                'stages' => [
                    [
                        'name' => 'Stage Name',
                        'win_probability' => 100,
                        'display_order' => 1,
                    ],
                    [
                        'name' => 'Changed Name',
                        'win_probability' => 25,
                        'display_order' => 2,
                    ],
                ],
            ])
            ->assertJson(['name' => 'Changed']);
    }

    public function test_unauthorized_user_cannot_update_resource_record(): void
    {
        $this->asRegularUser()->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Changed',
        ])->assertForbidden();
    }

    public function test_user_can_retrieve_resource_records(): void
    {
        $this->signIn();

        $this->factory()->count(5)->create();

        $this->getJson($this->indexEndpoint())->assertJsonCount(5, 'data');
    }

    public function test_user_can_retrieve_resource_record(): void
    {
        $this->signIn();

        $record = $this->factory()->create();

        $this->getJson($this->showEndpoint($record))->assertOk();
    }

    public function test_user_can_delete_resource_record(): void
    {
        $this->signIn();

        $record = $this->factory()->create();

        $this->deleteJson($this->deleteEndpoint($record))->assertNoContent();
    }

    public function test_unauthorized_user_cannot_delete_resource_record(): void
    {
        $this->asRegularUser()->signIn();

        $pipeline = $this->factory()->create();

        $this->deleteJson($this->deleteEndpoint($pipeline))->assertForbidden();
    }

    public function test_pipeline_requires_name(): void
    {
        $this->signIn();

        $this->postJson($this->createEndpoint(), ['name' => ''])->assertJsonValidationErrors(['name']);

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline))->assertJsonValidationErrors(['name']);
    }

    public function test_pipeline_update_requires_not_empty_stages(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), ['name' => 'Pipeline', 'stages' => []])
            ->assertJsonValidationErrorFor('stages');
    }

    public function test_pipeline_name_must_be_unique(): void
    {
        $this->signIn();

        $pipelines = $this->factory()->count(2)->create();

        $this->postJson(
            $this->createEndpoint(),
            ['name' => $pipelines->first()->name,
            ]
        )->assertJsonValidationErrors(['name']);

        $this->putJson(
            $this->updateEndpoint($pipelines->get(1)),
            ['name' => $pipelines->first()->name]
        )->assertJsonValidationErrors(['name']);
    }

    public function test_pipeline_update_requires_stages_name_to_be_distinct(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Pipeline',
            'stages' => [
                [
                    'name' => 'Duplicate Stage',
                ],
                [
                    'name' => 'Duplicate Stage',
                ],
                [
                    'name' => 'Unique Stage',
                ],
            ],
        ])
            ->assertJsonValidationErrorFor('stages.0.name')
            ->assertJsonValidationErrorFor('stages.1.name')
            ->assertJsonMissingValidationErrors('stages.2.name');
    }

    public function test_pipeline_update_requires_all_stages_to_have_a_name(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Pipeline',
            'stages' => [
                [
                    'name' => '',
                ],
                [
                    'name' => 'Sample Stage',
                ],
            ],
        ])
            ->assertJsonValidationErrorFor('stages.0.name');
    }

    public function test_pipeline_update_requires_all_stages_to_have_win_probability_defined(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Pipeline',
            'stages' => [
                [
                    'name' => 'Sample Stage',
                    'win_probability' => 50,
                ],
                [
                    'name' => 'Sample Stage',
                ],
            ],
        ])
            ->assertJsonValidationErrorFor('stages.1.win_probability');
    }

    public function test_pipeline_update_requires_stages_win_probability_to_be_max_100(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Pipeline',
            'stages' => [
                [
                    'name' => 'Sample Stage',
                    'win_probability' => 125,
                ],
                [
                    'name' => 'Sample Stage',
                    'win_probability' => 30,
                ],
            ],
        ])
            ->assertJsonValidationErrorFor('stages.0.win_probability')
            ->assertJsonMissingValidationErrors('stages.1.win_probability');
    }

    public function test_pipeline_update_requires_stages_win_probability_to_be_min_0(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => 'Pipeline',
            'stages' => [
                [
                    'name' => 'Sample Stage',
                    'win_probability' => -5,
                ],
                [
                    'name' => 'Sample Stage',
                    'win_probability' => 30,
                ],
            ],
        ])
            ->assertJsonValidationErrorFor('stages.0.win_probability')
            ->assertJsonMissingValidationErrors('stages.1.win_probability');
    }

    public function test_admin_user_can_see_all_pipelines(): void
    {
        $user = $this->signIn();
        $pipeline = $this->newPipelineFactoryWithVisibilityGroup('teams', Team::factory())->create();

        $this->assertTrue($pipeline->isVisible($user));
        $this->getJson($this->indexEndpoint())->assertJsonCount(1, 'data');
    }

    public function test_stages_are_created_when_creating_new_pipeline(): void
    {
        $this->signIn();

        $this->postJson($this->createEndpoint(), [
            'name' => 'Pipeline',
            'stages' => [
                ['name' => 'Stage 1', 'win_probability' => 20, 'display_order' => 1],
                ['name' => 'Stage 2', 'win_probability' => 100, 'display_order' => 2],
            ],
        ]);

        $pipeline = Pipeline::first();

        $this->assertCount(2, $pipeline->stages);
        $this->assertEquals('Stage 1', $pipeline->stages[0]->name);
        $this->assertEquals('Stage 2', $pipeline->stages[1]->name);
    }

    public function test_it_uses_index_as_display_order_when_display_order_is_not_provided(): void
    {
        $this->signIn();

        // create
        $this->postJson($this->createEndpoint(), [
            'name' => 'Pipeline',
            'stages' => [
                ['name' => 'Stage 1', 'win_probability' => 20, 'display_order' => 1],
                ['name' => 'Stage 2', 'win_probability' => 100],
            ],
        ]);

        $pipeline = Pipeline::first();

        $this->assertEquals(2, $pipeline->stages[1]->display_order);

        // update
        $pipeline = $this->factory()->withStages([
            ['name' => 'Stage 1', 'win_probability' => 20, 'display_order' => 2],
        ])->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'stages' => [
                [
                    'id' => $pipeline->stages[0]->id,
                    'name' => 'Stage 1',
                    'win_probability' => 20,
                ],
            ],
        ]);

        $pipeline->load('stages');

        $this->assertEquals(1, $pipeline->stages[0]->display_order);
    }

    public function test_stages_can_be_updated_when_updating_pipeline(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->withStages([
            ['name' => 'Stage 1', 'win_probability' => 20, 'display_order' => 1],
            ['name' => 'Stage 2', 'win_probability' => 100, 'display_order' => 2],
        ])->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'stages' => [
                [
                    'id' => $pipeline->stages[0]->id,
                    'name' => 'Changed name 1',
                    'win_probability' => 40,
                    'display_order' => 1,
                ],
                [
                    'id' => $pipeline->stages[1]->id,
                    'name' => 'Changed name 2',
                    'win_probability' => 80,
                    'display_order' => 2,
                ],
            ],
        ]);

        $pipeline->load('stages');

        $this->assertEquals(40, $pipeline->stages[0]->win_probability);
        $this->assertEquals(80, $pipeline->stages[1]->win_probability);

        $this->assertEquals('Changed name 1', $pipeline->stages[0]->name);
        $this->assertEquals('Changed name 2', $pipeline->stages[1]->name);
    }

    public function test_new_stage_is_created_when_id_is_not_provided(): void
    {
        $this->signIn();

        $pipeline = $this->factory()->withStages([
            ['name' => 'Stage 1', 'win_probability' => 20, 'display_order' => 1],
        ])->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'stages' => [
                [
                    'id' => $pipeline->stages[0]->id,
                    'name' => 'Stage 1',
                    'win_probability' => 20,
                    'display_order' => 1,
                ],
                [
                    'name' => 'Stage 2',
                    'win_probability' => 80,
                    'display_order' => 2,
                ],
            ],
        ]);

        $pipeline->load('stages');

        $this->assertCount(2, $pipeline->stages);
        $this->assertEquals(80, $pipeline->stages[1]->win_probability);
        $this->assertEquals('Stage 2', $pipeline->stages[1]->name);
    }

    public function test_a_pipeline_with_visibility_group_teams_can_be_created(): void
    {
        $this->signIn();

        $attributes = $this->factory()->make()->toArray();
        $team = Team::factory()->create();

        $this->postJson($this->createEndpoint(), array_merge($attributes, [
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeTeams,
                'depends_on' => [$team->id],
            ],
        ]));

        $pipeline = Pipeline::first();

        $this->assertNotNull($pipeline->visibilityGroup);
        $this->assertCount(1, $pipeline->visibilityGroup->teams);
        $this->assertEquals(Pipeline::$visibilityTypeTeams, $pipeline->visibilityGroup->type);
    }

    public function test_a_pipeline_with_visibility_group_users_can_be_created(): void
    {
        $this->signIn();

        $attributes = $this->factory()->make()->toArray();
        $user = User::factory()->create();

        $this->postJson($this->createEndpoint(), array_merge($attributes, [
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeUsers,
                'depends_on' => [$user->id],
            ],
        ]));

        $pipeline = Pipeline::first();

        $this->assertNotNull($pipeline->visibilityGroup);
        $this->assertCount(1, $pipeline->visibilityGroup->users);
        $this->assertEquals(Pipeline::$visibilityTypeUsers, $pipeline->visibilityGroup->type);
    }

    public function test_a_pipeline_with_visibility_group_users_can_be_updated(): void
    {
        $this->signIn();

        $pipeline = $this->factory()
            ->has(
                ModelVisibilityGroup::factory()->users()->hasAttached(User::factory()),
                'visibilityGroup'
            )
            ->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeTeams,
                'depends_on' => [Team::factory()->create()->id],
            ],
        ]);

        $this->assertCount(1, $pipeline->visibilityGroup->teams);
        $this->assertCount(0, $pipeline->visibilityGroup->users);
        $this->assertEquals(Pipeline::$visibilityTypeTeams, $pipeline->visibilityGroup->type);
    }

    public function test_a_pipeline_with_visibility_group_teams_can_be_updated(): void
    {
        $this->signIn();

        $pipeline = $this->factory()
            ->has(
                ModelVisibilityGroup::factory()->teams()->hasAttached(Team::factory()),
                'visibilityGroup'
            )
            ->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeUsers,
                'depends_on' => [User::factory()->create()->id],
            ],
        ]);

        $this->assertCount(0, $pipeline->visibilityGroup->teams);
        $this->assertCount(1, $pipeline->visibilityGroup->users);
        $this->assertEquals(Pipeline::$visibilityTypeUsers, $pipeline->visibilityGroup->type);
    }

    public function test_it_detaches_all_visibility_dependends_when_visibilty_type_is_set_to_all(): void
    {
        $this->signIn();

        $pipeline = $this->factory()
            ->has(
                ModelVisibilityGroup::factory()->teams()->hasAttached(Team::factory()->count(2)),
                'visibilityGroup'
            )
            ->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeAll,
                'depends_on' => [],
            ],
        ]);

        $this->assertCount(0, $pipeline->visibilityGroup->teams);
        $this->assertCount(0, $pipeline->visibilityGroup->users);
        $this->assertEquals(Pipeline::$visibilityTypeAll, $pipeline->visibilityGroup->type);
    }

    public function test_cannot_set_primary_pipeline_visibility(): void
    {
        $pipeline = $this->factory()->primary()->create();

        $this->putJson($this->updateEndpoint($pipeline), [
            'name' => $pipeline->name,
            'visibility_group' => [
                'type' => Pipeline::$visibilityTypeUsers,
                'depends_on' => [],
            ],
        ]);

        $this->assertNull($pipeline->visibilityGroup);
    }

    protected function newPipelineFactoryWithVisibilityGroup($group, $attached)
    {
        return $this->factory()->has(
            ModelVisibilityGroup::factory()->{$group}()->hasAttached($attached),
            'visibilityGroup'
        );
    }
}
