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

namespace Modules\Deals\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Deals\Database\Factories\DealFactory;
use Modules\Deals\Database\Factories\PipelineFactory;
use Modules\Deals\Enums\DealStatus;
use Modules\Deals\Models\Deal;
use Modules\Deals\Models\LostReason;
use Modules\Deals\Models\Pipeline;
use Modules\Deals\Models\Stage;
use Modules\Users\Models\User;

class DealsSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create lost reasons
        $this->createLostReasons();

        // Create pipelines with stages
        $this->createPipelinesWithStages();

        // Create sample deals
        $this->createSampleDeals();
    }

    /**
     * Create lost reasons for deals
     */
    protected function createLostReasons(): void
    {
        $reasons = [
            'Client went silent',
            'Not responsive',
            'Doesn\'t pick up the phone, doesn\'t respond',
            'They couldn\'t afford our services',
            'Didn\'t have the budget',
            'Went with our competitor',
            'Lack of expertise',
            'Timing was not right',
            'Changed requirements',
            'Internal decision maker left',
        ];

        foreach ($reasons as $reason) {
            LostReason::firstOrCreate(['name' => $reason]);
        }
    }

    /**
     * Create pipelines with their stages
     */
    protected function createPipelinesWithStages(): void
    {
        // Sales Pipeline
        $salesPipeline = Pipeline::firstOrCreate(
            ['name' => 'Sales Pipeline'],
            ['flag' => 'primary']
        );

        $salesStages = [
            ['name' => 'Lead', 'win_probability' => 10, 'display_order' => 1],
            ['name' => 'Qualified', 'win_probability' => 25, 'display_order' => 2],
            ['name' => 'Proposal', 'win_probability' => 50, 'display_order' => 3],
            ['name' => 'Negotiation', 'win_probability' => 75, 'display_order' => 4],
            ['name' => 'Closed Won', 'win_probability' => 100, 'display_order' => 5],
        ];

        foreach ($salesStages as $stageData) {
            Stage::firstOrCreate(
                [
                    'name' => $stageData['name'],
                    'pipeline_id' => $salesPipeline->id,
                ],
                [
                    'win_probability' => $stageData['win_probability'],
                    'display_order' => $stageData['display_order'],
                ]
            );
        }

        // Marketing Pipeline
        $marketingPipeline = Pipeline::firstOrCreate(
            ['name' => 'Marketing Pipeline']
        );

        $marketingStages = [
            ['name' => 'Awareness', 'win_probability' => 5, 'display_order' => 1],
            ['name' => 'Interest', 'win_probability' => 20, 'display_order' => 2],
            ['name' => 'Consideration', 'win_probability' => 40, 'display_order' => 3],
            ['name' => 'Intent', 'win_probability' => 70, 'display_order' => 4],
            ['name' => 'Evaluation', 'win_probability' => 90, 'display_order' => 5],
        ];

        foreach ($marketingStages as $stageData) {
            Stage::firstOrCreate(
                [
                    'name' => $stageData['name'],
                    'pipeline_id' => $marketingPipeline->id,
                ],
                [
                    'win_probability' => $stageData['win_probability'],
                    'display_order' => $stageData['display_order'],
                ]
            );
        }

        // Partnership Pipeline
        $partnershipPipeline = Pipeline::firstOrCreate(
            ['name' => 'Partnership Pipeline']
        );

        $partnershipStages = [
            ['name' => 'Initial Contact', 'win_probability' => 15, 'display_order' => 1],
            ['name' => 'Discussion', 'win_probability' => 35, 'display_order' => 2],
            ['name' => 'Agreement Draft', 'win_probability' => 60, 'display_order' => 3],
            ['name' => 'Legal Review', 'win_probability' => 85, 'display_order' => 4],
            ['name' => 'Signed', 'win_probability' => 100, 'display_order' => 5],
        ];

        foreach ($partnershipStages as $stageData) {
            Stage::firstOrCreate(
                [
                    'name' => $stageData['name'],
                    'pipeline_id' => $partnershipPipeline->id,
                ],
                [
                    'win_probability' => $stageData['win_probability'],
                    'display_order' => $stageData['display_order'],
                ]
            );
        }
    }

    /**
     * Create sample deals
     */
    protected function createSampleDeals(): void
    {
        $pipelines = Pipeline::with('stages')->get();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create users first before seeding deals.');
            return;
        }

        $sampleDeals = [
            // Sales Pipeline Deals
            [
                'name' => 'Enterprise Software License - TechCorp Inc.',
                'amount' => 50000.00,
                'expected_close_date' => now()->addDays(30)->format('Y-m-d'),
                'pipeline_name' => 'Sales Pipeline',
                'stage_name' => 'Proposal',
                'status' => DealStatus::open,
                'swatch_color' => '#3B82F6',
            ],
            [
                'name' => 'Cloud Migration Services - GlobalTech Solutions',
                'amount' => 75000.00,
                'expected_close_date' => now()->addDays(45)->format('Y-m-d'),
                'pipeline_name' => 'Sales Pipeline',
                'stage_name' => 'Negotiation',
                'status' => DealStatus::open,
                'swatch_color' => '#10B981',
            ],
            [
                'name' => 'CRM Implementation - StartupXYZ',
                'amount' => 25000.00,
                'expected_close_date' => now()->addDays(15)->format('Y-m-d'),
                'pipeline_name' => 'Sales Pipeline',
                'stage_name' => 'Qualified',
                'status' => DealStatus::open,
                'swatch_color' => '#F59E0B',
            ],
            [
                'name' => 'Data Analytics Platform - DataCorp',
                'amount' => 100000.00,
                'expected_close_date' => now()->addDays(60)->format('Y-m-d'),
                'pipeline_name' => 'Sales Pipeline',
                'stage_name' => 'Closed Won',
                'status' => DealStatus::won,
                'won_date' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'swatch_color' => '#8B5CF6',
            ],
            [
                'name' => 'Mobile App Development - AppStart',
                'amount' => 30000.00,
                'expected_close_date' => now()->subDays(10)->format('Y-m-d'),
                'pipeline_name' => 'Sales Pipeline',
                'stage_name' => 'Lead',
                'status' => DealStatus::lost,
                'lost_date' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'lost_reason' => 'Client went silent',
                'swatch_color' => '#EF4444',
            ],

            // Marketing Pipeline Deals
            [
                'name' => 'Content Marketing Campaign - BrandCo',
                'amount' => 15000.00,
                'expected_close_date' => now()->addDays(20)->format('Y-m-d'),
                'pipeline_name' => 'Marketing Pipeline',
                'stage_name' => 'Consideration',
                'status' => DealStatus::open,
                'swatch_color' => '#06B6D4',
            ],
            [
                'name' => 'SEO Services - LocalBusiness',
                'amount' => 8000.00,
                'expected_close_date' => now()->addDays(10)->format('Y-m-d'),
                'pipeline_name' => 'Marketing Pipeline',
                'stage_name' => 'Intent',
                'status' => DealStatus::open,
                'swatch_color' => '#84CC16',
            ],
            [
                'name' => 'Social Media Management - RetailChain',
                'amount' => 12000.00,
                'expected_close_date' => now()->addDays(25)->format('Y-m-d'),
                'pipeline_name' => 'Marketing Pipeline',
                'stage_name' => 'Evaluation',
                'status' => DealStatus::open,
                'swatch_color' => '#F97316',
            ],

            // Partnership Pipeline Deals
            [
                'name' => 'Strategic Partnership - TechGiant',
                'amount' => 200000.00,
                'expected_close_date' => now()->addDays(90)->format('Y-m-d'),
                'pipeline_name' => 'Partnership Pipeline',
                'stage_name' => 'Agreement Draft',
                'status' => DealStatus::open,
                'swatch_color' => '#EC4899',
            ],
            [
                'name' => 'Integration Partnership - SoftwareVendor',
                'amount' => 50000.00,
                'expected_close_date' => now()->addDays(30)->format('Y-m-d'),
                'pipeline_name' => 'Partnership Pipeline',
                'stage_name' => 'Legal Review',
                'status' => DealStatus::open,
                'swatch_color' => '#6366F1',
            ],
        ];

        foreach ($sampleDeals as $dealData) {
            $pipeline = $pipelines->where('name', $dealData['pipeline_name'])->first();
            $stage = $pipeline->stages->where('name', $dealData['stage_name'])->first();
            $user = $users->random();

            Deal::create([
                'name' => $dealData['name'],
                'uuid' => \Illuminate\Support\Str::uuid(),
                'pipeline_id' => $pipeline->id,
                'stage_id' => $stage->id,
                'status' => $dealData['status'],
                'amount' => $dealData['amount'],
                'expected_close_date' => $dealData['expected_close_date'],
                'swatch_color' => $dealData['swatch_color'],
                'user_id' => $user->id,
                'created_by' => $user->id,
                'owner_assigned_date' => now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
                'won_date' => $dealData['won_date'] ?? null,
                'lost_date' => $dealData['lost_date'] ?? null,
                'lost_reason' => $dealData['lost_reason'] ?? null,
                'created_at' => now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        // Create additional random deals using the factory
        DealFactory::new()
            ->count(15)
            ->randomColor()
            ->create();

        $this->command->info('Sample deals data has been created successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . Pipeline::count() . ' pipelines');
        $this->command->info('- ' . Stage::count() . ' stages');
        $this->command->info('- ' . Deal::count() . ' deals');
        $this->command->info('- ' . LostReason::count() . ' lost reasons');
    }
}
