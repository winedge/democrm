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

use App\ToModuleMigrator;
use Modules\Updater\UpdatePatcher;

return new class extends UpdatePatcher
{
    public function run(): void
    {
        ToModuleMigrator::make('calls')
            ->migrateMorphs('App\\Models\\Call', 'Modules\\Calls\\Models\\Call')
            ->migrateWorkflowTriggers([
                'App\\Workflows\\Triggers\\MissedIncomingCall' => 'Modules\Calls\Workflow\Triggers\MissedIncomingCall',
            ])
            ->migrateDbLanguageKeys('call')
            ->migrateLanguageFiles(['call.php'])
            ->deleteConflictedFiles($this->getConflictedFiles());
    }

    public function shouldRun(): bool
    {
        return file_exists(app_path('Models/Call.php'));
    }

    protected function getConflictedFiles(): array
    {
        return [
            app_path('Resources/Call'),
            app_path('Resources/CallOutcome'),
            app_path('Models/Call.php'),
            app_path('Models/CallOutcome.php'),
        ];
    }
};
