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
use Illuminate\Support\Facades\File;
use Modules\Core\Facades\Innoclapps;
use Modules\Updater\UpdatePatcher;

return new class extends UpdatePatcher
{
    public function run(): void
    {
        if ($this->missingSettingsSeededFlag()) {
            settings(['_seeded' => true]);
        }

        ToModuleMigrator::make('core')
            ->migrateMorphs('App\\Innoclapps\\Models\\CustomField', 'Modules\\Core\\Models\\CustomField')
            ->migrateMorphs('App\\Innoclapps\\Models\\Filter', 'Modules\\Core\\Models\\Filter')
            ->migrateMorphs('App\\Innoclapps\\Models\\Media', 'Modules\\Core\\Models\\Media')
            ->migrateMorphs('App\\Innoclapps\\Models\\Role', 'Modules\\Core\\Models\\Role')
            ->migrateMorphs('App\\Models\\Synchronization', 'Modules\\Core\\Models\\Synchronization')
            ->migrateWorkflowActions([
                'App\\Workflows\\Actions\\WebhookAction' => 'Modules\\Core\\Workflow\\Actions\\WebhookAction',
            ])
            ->migrateDbLanguageKeys('resource')
            ->migrateDbLanguageKeys('timeline')
            ->migrateDbLanguageKeys('notifications')
            ->migrateLanguageFiles(['actions.php', 'api.php', 'app.php', 'contentbuilder.php', 'country.php', 'dashboard.php', 'dates.php', 'fields.php', 'filters.php', 'import.php', 'mail_template.php', 'media.php', 'notifications.php', 'resource.php', 'role.php', 'settings.php', 'table.php', 'timeline.php', 'update.php', 'workflow.php'])
            ->deleteConflictedFiles($this->getConflictedFiles());

        foreach (Innoclapps::locales() as $locale) {
            if (File::isFile($path = lang_path($locale.DIRECTORY_SEPARATOR.'editor.php'))) {
                File::delete($path);
            }
        }
    }

    public function shouldRun(): bool
    {
        return $this->missingSettingsSeededFlag() || file_exists(app_path('Innoclapps/Models/CustomField.php'));
    }

    protected function missingSettingsSeededFlag(): bool
    {
        return is_null(settings('_seeded'));
    }

    protected function getConflictedFiles(): array
    {
        return [
            app_path('Innoclapps/Models/CustomField.php'),
        ];
    }
};
